<?php
include_once 'db/connect_db.php';

// --- Session and Access Control ---
if (empty($_SESSION['user_name'])) {
    header('location:index.php');
    exit();
} else {
    if ($_SESSION['role'] == "Admin") {
        include_once 'inc/header_all.php';
    } else {
        include_once 'inc/header_all_operator.php';
    }
}

// Disable PHP error display (Good practice in production)
error_reporting(0);

// --- Product Deletion Logic (IMPROVED: Use Prepared Statements) ---
$id = $_GET['id'] ?? null;
$code_to_delete = $_GET['code'] ?? null;

if ($id && $code_to_delete) {
    // SECURITY IMPROVEMENT: Use prepared statement for DELETE
    $delete_product = $pdo->prepare("DELETE FROM tbl_product WHERE product_id = :id");
    $delete_product->bindParam(':id', $id, PDO::PARAM_INT);

    // Deleting associated shop items
    $delete_shop_items = $pdo->prepare("DELETE FROM tbl_shop_item WHERE product_code = :code");
    $delete_shop_items->bindParam(':code', $code_to_delete);

    try {
        $pdo->beginTransaction(); // Start transaction for safety
        $delete_product->execute();
        $delete_shop_items->execute();
        $pdo->commit(); // Commit if both deletions succeed

        echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Success", "Le produit a été supprimé avec succès.", "success", {
                button: "Continue",
                    }).then(() => {
                        window.location.href = "product.php"; // Reload list after successful deletion
                    });
                });
                </script>';
    } catch (Exception $e) {
        $pdo->rollBack(); // Rollback on error
        error_log("Product Deletion Error: " . $e->getMessage());
        echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Error", "Échec de la suppression du produit.", "error", {
                button: "Continue",
                    });
                });
                </script>';
    }
}

// --- Product Listing Query Logic ---
$status = $_GET['status'] ?? 'all';
$statusName = "";
$select_query = "";

switch ($status) {
    case 'ok':
        $select_query = "SELECT * FROM tbl_product WHERE stock > min_stock ORDER BY product_id DESC";
        $statusName = " en stock";
        break;
    case 'alert':
        $select_query = "SELECT * FROM tbl_product WHERE stock <= min_stock AND stock <> 0 ORDER BY product_id DESC";
        $statusName = " stock alerte";
        break;
    case 'null':
        $select_query = "SELECT * FROM tbl_product WHERE stock = 0 ORDER BY product_id DESC";
        $statusName = " stock null";
        break;
    case 'all':
    default:
        $select_query = "SELECT * FROM tbl_product ORDER BY product_id DESC";
        $statusName = " complet";
        break;
}

$select = $pdo->prepare($select_query);

// --- New Product Insertion Logic (From Modal Submission) ---
if (isset($_POST['add_product'])) {

    // 1. Sanitize/Extract Data
    $code = str_replace(' ', '', $_POST['product_code']);
    $sku = str_replace(' ', '', $_POST['product_sku']);
    $product = $_POST['product_name'];
    $category = (!empty($_POST['category_new'])) ? $_POST['category_new'] : $_POST['category'];
    $brand = $_POST['product_brand'];
    $purchase = $_POST['purchase_price'];
    $sell = $_POST['sell_price'];
    $min = $_POST['min_price'];
    $discount = $_POST['discount'];
    $stock = $_POST['stock'];
    $min_stock = $_POST['min_stock'];
    $satuan = $_POST['satuan'];
    $supplier = (!empty($_POST['supplier'])) ? $_POST['supplier'] : $brand;
    $desc = $_POST['description'];
    $place_in_store = $_POST['place_in_store'];
    $place_in_storeroom = $_POST['place_in_storeroom'];

    // 2. Validation Check (Product Code Exists)
    $select_code = $pdo->prepare("SELECT product_code FROM tbl_product WHERE product_code = :code");
    $select_code->bindParam(':code', $code);
    $select_code->execute();

    $insert_success = false;

    if ($select_code->rowCount() > 0) {
        echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Warning", "Produit existant | Erreur de code", "warning", {
                button: "Continue",
                    });
                });
                </script>';
    } elseif (strlen($code) > 50) {
        echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Warning", "Le code doit avoir au plus 50 caractères", "warning", {
                button: "Continue",
                    });
                });
                </script>';
    } else {
        // 3. Image Handling
        $img = $_FILES['product_img']['name'];
        $img_tmp = $_FILES['product_img']['tmp_name'];
        $img_size = $_FILES['product_img']['size'];
        $img_ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

        $img_new = uniqid() . '.' . $img_ext;
        $store = "upload/" . $img_new;

        if ($img_ext == 'jpg' || $img_ext == 'jpeg' || $img_ext == 'png' || $img_ext == 'gif') {
            if ($img_size >= 1000000) {
                echo '<script type="text/javascript">
                        jQuery(function validation(){
                        swal("Error", "Taille Max du fichier 1 Mo", "error", {
                        button: "Continue",
                            });
                        });
                        </script>';
            } else {
                if (move_uploaded_file($img_tmp, $store)) {
                    $product_img = $img_new;

                    // 4. Database Insertion (tbl_product)
                    $insert_product = $pdo->prepare("INSERT INTO tbl_product
                        (product_code, product_sku, product_name, product_category, product_brand, purchase_price,
                         sell_price, min_price, discount, stock, min_stock, product_satuan, supplier,
                         description, img, place_in_storeroom, place_in_store)
                        VALUES(:product_code, :product_sku, :product_name, :product_category, :product_brand, :purchase_price, :sell_price,
                        :min_price, :discount, :stock, :min_stock, :satuan, :supplier, :desc1, :img, :place_in_storeroom, :place_in_store)");

                    $params = [
                        ':product_code' => $code,
                        ':product_sku' => $sku,
                        ':product_name' => $product,
                        ':product_category' => $category,
                        ':product_brand' => $brand,
                        ':purchase_price' => $purchase,
                        ':sell_price' => $sell,
                        ':min_price' => $min,
                        ':discount' => $discount,
                        ':stock' => $stock,
                        ':min_stock' => $min_stock,
                        ':satuan' => $satuan,
                        ':supplier' => $supplier,
                        ':desc1' => $desc,
                        ':img' => $product_img,
                        ':place_in_storeroom' => $place_in_storeroom,
                        ':place_in_store' => $place_in_store
                    ];

                    if ($insert_product->execute($params)) {
                        $insert_success = true;
                        // 5. Database Insertion (tbl_shop_item) - Initialize stock to 0 in all branches
                        $select_agences = $pdo->prepare("SELECT code_agence FROM agence");
                        $select_agences->execute();

                        $shop_success = true;
                        while ($row_agence = $select_agences->fetch(PDO::FETCH_ASSOC)) {
                            $shop = $row_agence['code_agence'];

                            $insert_item = $pdo->prepare("INSERT INTO tbl_shop_item(shop_code, product_code, product_sku, product_name,
                                product_category, product_brand, purchase_price, sell_price, min_price, discount, stock,
                                min_stock, product_satuan, supplier, description, place_in_store, img)
                                VALUES(:shop, :product_code, :product_sku, :product_name, :product_category,
                                :product_brand, :purchase_price, :sell_price, :min_price, :discount, :stock_init,
                                :min_stock_init, :satuan, :supplier, :desc1, :place_in_store, :img)");

                            $shop_params = [
                                ':shop' => $shop,
                                ':product_code' => $code,
                                ':product_sku' => $sku,
                                ':product_name' => $product,
                                ':product_category' => $category,
                                ':product_brand' => $brand,
                                ':purchase_price' => $purchase,
                                ':sell_price' => $sell,
                                ':min_price' => $min,
                                ':discount' => $discount,
                                ':stock_init' => 0, // Initial stock in shop is 0
                                ':min_stock_init' => 0, // Initial min_stock in shop is 0
                                ':satuan' => $satuan,
                                ':supplier' => $supplier,
                                ':desc1' => $desc,
                                ':place_in_store' => $place_in_store,
                                ':img' => $product_img
                            ];

                            if (!$insert_item->execute($shop_params)) {
                                $shop_success = false;
                                error_log("Shop Item Insertion Error for shop $shop: " . implode(", ", $insert_item->errorInfo()));
                            }
                        }
                    } else {
                        // Main product insert failed
                        $insert_success = false;
                    }
                }
            }
        } else {
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("Error", "Format image requis : jpg, jpeg, png, gif", "error", {
                    button: "Continue",
                        });
                    });
                    </script>';
        }
    }

    // Final SweetAlert based on overall result
    if ($insert_success) {
        $alert_message = $shop_success ? "Produit enregistré avec succès et initialisé dans les magasins." : "Produit enregistré, mais échec de l\'initialisation dans *certains* magasins. Veuillez vérifier.";
        $alert_type = $shop_success ? "success" : "warning";

        echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Success", "' . $alert_message . '", "' . $alert_type . '", {
                button: "Continue",
                    }).then(() => {
                        window.location.href = "product.php"; // Reload page
                    });
                });
                </script>';
    } elseif (isset($_POST['product_code']) && !$select_code->rowCount() > 0 && strlen($code) <= 50) {
        // Only show generic error if product wasn't already registered or had a code length error
        echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Error", "Erreur d\'enregistrement du produit.", "error", {
                button: "Continue",
                    });
                });
                </script>';
    }
}
?>

<div class="content-wrapper">
    <section class="content container-fluid">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Gestion des Produits</h3>

                <div class="pull-right" style="margin-left: 10px;">
                    <a href="product.php?status=ok" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i> STOCK OK </a>
                    <a href="product.php?status=alert" class="btn btn-warning btn-sm"><i class="fa fa-exclamation-triangle"></i> STOCK ALERTE</a>
                    <a href="product.php?status=null" class="btn btn-danger btn-sm"><i class="fa fa-times-circle"></i> STOCK NULL</a>
                    <a href="product.php?status=all" class="btn btn-default btn-sm"><i class="fa fa-list"></i> TOUS</a>
                </div>
            </div>

            <div class="box-header with-border">
                <h3 class="box-title">Liste Produits <?php echo $statusName; ?></h3>

                <button type="button" class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#addNewProductModal" title="Créer un Nouveau Produit">
                    <i class="fa fa-plus"></i> Nouveau Produit
                </button>
                <a href="add_stock_many_products.php" class="btn btn-info btn-sm pull-right" style="margin-right: 10px;" title="Ajouter du stock en masse par code-barre">
                    <i class="fa fa-barcode"></i> Ajouter Stock (Lecteur)
                </a>
            </div>

            <div class="box-body">
                <div style="overflow-x:auto;">
                    <table class="table table-bordered table-hover" id="myProduct">
                        <thead>
                            <tr class="bg-primary">
                                <th>No</th>
                                <th>IMG</th>
                                <th>Description Produit</th>
                                <th>Code</th>
                                <th>Stock</th>
                                <th>Prix Vente</th>
                                <th>Categorie</th>
                                <th>Fournisseur</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $select->execute();
                            while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                                // Determine stock label class
                                $stock_label = '';
                                if ($row->stock == 0) {
                                    $stock_label = 'label-danger';
                                } elseif ($row->stock <= $row->min_stock) {
                                    $stock_label = 'label-warning';
                                } else {
                                    $stock_label = 'label-primary';
                                }
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td>
                                        <img src="upload/<?php echo htmlspecialchars($row->img); ?>" width="50px" height="50px" style="object-fit: cover; border-radius: 4px;" />
                                    </td>
                                    <td><?php echo htmlspecialchars($row->product_name); ?></td>
                                    <td><?php echo htmlspecialchars($row->product_code); ?></td>
                                    <td>
                                        <span class="label <?php echo $stock_label; ?>"><?php echo $row->stock; ?></span>
                                        <span class="label label-default"><?php echo htmlspecialchars($row->product_satuan); ?></span>
                                    </td>
                                    <td><?php echo number_format($row->sell_price, 0, null, " "); ?> FCFA</td>
                                    <td><?php echo htmlspecialchars($row->product_category); ?></td>
                                    <td><?php echo htmlspecialchars($row->supplier); ?></td>
                                    <td>
                                        <?php if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "storekeeper") { ?>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="<?php echo $row->product_id; ?>"
                                                data-code="<?php echo htmlspecialchars($row->product_code); ?>"
                                                title="Supprimer">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <a href="edit_product.php?id=<?php echo $row->product_id; ?>" class="btn btn-info btn-sm" title="Modifier Produit"><i class="fa fa-pencil"></i></a>
                                            <a href="edit_stock.php?id=<?php echo $row->product_id; ?>" class="btn btn-success btn-sm" title="Ajouter Stock"><i class="fa fa-plus"></i></a>
                                        <?php } ?>
                                        <a href="view_product.php?id=<?php echo $row->product_id; ?>" class="btn btn-default btn-sm" title="Voir Détails"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="addNewProductModal" tabindex="-1" role="dialog" aria-labelledby="addNewProductModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addNewProductModalLabel"><i class="fa fa-cube"></i> Enregistrer un Nouveau Produit</h4>
            </div>
            <form action="product.php" method="POST" name="form_product" enctype="multipart/form-data" autocomplete="off">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="product_code">Code produit</label>
                                <input type="text" class="form-control" name="product_code" required>
                                <span class="text-muted small">*Assurez-vous que le code est unique.</span>
                            </div>
                            <div class="form-group">
                                <label for="product_sku">SKU</label>
                                <input type="text" class="form-control" name="product_sku">
                            </div>
                            <div class="form-group">
                                <label for="product_name">Libellé Produit</label>
                                <input type="text" class="form-control" name="product_name" required>
                            </div>
                            <div class="form-group">
                                <label for="category">Catégorie</label>
                                <select class="form-control" name="category" required>
                                    <?php
                                    $select_cat = $pdo->prepare("SELECT cat_name FROM tbl_category WHERE cat_level = 3");
                                    $select_cat->execute();
                                    while ($row_cat = $select_cat->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option>' . htmlspecialchars($row_cat['cat_name']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="place_in_storeroom">Emplacement au Dépôt</label>
                                <input type="text" class="form-control" name="place_in_storeroom" required>
                            </div>
                            <div class="form-group">
                                <label for="place_in_store">Emplacement au Magasin</label>
                                <input type="text" class="form-control" name="place_in_store" required>
                            </div>
                            <div class="form-group">
                                <label for="product_brand">Marque</label>
                                <input type="text" class="form-control" name="product_brand">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="purchase_price">Prix Achat (FCFA)</label>
                                <input type="number" min="10" class="form-control" name="purchase_price" required>
                            </div>
                            <div class="form-group">
                                <label for="sell_price">Prix Vente (FCFA)</label>
                                <input type="number" class="form-control" name="sell_price" required>
                            </div>
                            <div class="form-group">
                                <label for="min_price">Prix Min (FCFA)</label>
                                <input type="number" class="form-control" name="min_price" required>
                            </div>
                            <div class="form-group">
                                <label for="discount">Discount (%)</label>
                                <input type="number" min="0" max="100" class="form-control" name="discount" value="0" required>
                            </div>
                            <div class="form-group">
                                <label for="stock">Stock Initial (Total Dépôt)</label>
                                <input type="number" min="1" step="1" class="form-control" name="stock" required>
                                <span class="text-muted small">*Unité selon produit</span>
                            </div>
                            <div class="form-group">
                                <label for="min_stock">Stock minimal (Alerte Dépôt)</label>
                                <input type="number" min="1" step="1" class="form-control" name="min_stock" required>
                            </div>
                            <div class="form-group">
                                <label for="satuan">Unité de Mesure</label>
                                <select class="form-control" name="satuan" required>
                                    <?php
                                    $select_sat = $pdo->prepare("SELECT nm_satuan FROM tbl_satuan");
                                    $select_sat->execute();
                                    while ($row_sat = $select_sat->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option>' . htmlspecialchars($row_sat['nm_satuan']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="supplier">Fournisseur</label>
                                <select class="form-control" name="supplier">
                                    <option value=""></option>
                                    <?php
                                    $select_sup = $pdo->prepare("SELECT suplier_name FROM supliers");
                                    $select_sup->execute();
                                    while ($row_sup = $select_sup->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option>' . htmlspecialchars($row_sup['suplier_name']) . '</option>';
                                    }
                                    ?>
                                </select>
                                <span class="text-muted small">Par défaut: Marque</span>
                            </div>
                            <div class="form-group">
                                <label for="description">Description Produit</label>
                                <textarea name="description" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="product_img">Image Produit</label>
                                <input type="file" class="input-group" name="product_img" onchange="readURL(this);" required>
                                <br>
                                <img id="modal_img_preview" src="upload/default.png" alt="Preview" class="img-responsive" style="max-width: 150px; height: auto; border: 1px solid #ccc; padding: 5px;" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-success" name="add_product">Enregistrer Produit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Image Preview Function (Updated to use modal ID)
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#modal_img_preview').attr('src', e.target.result)
                    .width(150)
                    .height('auto');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function() {
        // Initialize DataTables
        if ($.fn.DataTable) {
            $('#myProduct').DataTable({
                "order": [
                    [0, "asc"]
                ]
            });
        }

        // SweetAlert for Deletion Confirmation
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            var productId = $(this).data('id');
            var productCode = $(this).data('code');
            var deleteUrl = 'product.php?id=' + productId + '&code=' + productCode;

            swal({
                    title: "Êtes-vous sûr(e)?",
                    text: "La suppression de ce produit est irréversible et supprime les données associées dans les magasins!",
                    icon: "warning",
                    buttons: ["Annuler", "Supprimer"],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location.href = deleteUrl;
                    }
                });
        });

        // Clear modal content/form on close
        $('#addNewProductModal').on('hidden.bs.modal', function() {
            $(this).find('form').trigger('reset'); // Reset form
            $('#modal_img_preview').attr('src', 'upload/default.png').width(150).height('auto'); // Reset image preview
        });
    });
</script>

<?php
include_once 'inc/footer_all.php';
?>