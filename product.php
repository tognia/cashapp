<?php
//product.php
include_once 'db/connect_db.php';

// --- Session and Access Control ---
if (empty($_SESSION['user_name'])) {
    header('location:index.php');
    exit();
} else {
    // Determine header based on role
    if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "Responsable" || $_SESSION['role'] == "storekeeper") {
        include_once 'inc/header_all.php';
    } else {
        include_once 'inc/header_all_operator.php';
    }
}

// Disable PHP error display (Good practice in production)
error_reporting(0);

// --- Product Deletion Logic (IMPROVED: Use Prepared Statements) ---
// ... (Your existing deletion logic remains unchanged) ...
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

// --- NEW LOGIC: Mass Stock Update (Transaction) ---
if (isset($_POST['mass_update_stock'])) {
    $updates = $_POST['updates'] ?? [];
    $receipt_date = $_POST['mass_receipt_date'] ?? date('Y-m-d');
    $default_supplier = $_POST['mass_supplier_name'] ?? 'Non spécifié';
    $current_user_id = $_SESSION['user_id'] ?? null;
    $success_count = 0;
    $error_occurred = false;

    if (!$current_user_id) {
        $error_occurred = true;
        $global_message = "Erreur: Identifiant utilisateur non disponible. Opération annulée.";
    } elseif (empty($updates)) {
        $error_occurred = true;
        $global_message = "Aucun produit à mettre à jour n'a été trouvé.";
    } else {
        try {
            $pdo->beginTransaction();

            $update_product_stmt = $pdo->prepare("UPDATE tbl_product SET stock = stock + :quantity WHERE product_id = :id");
            $insert_receipt_stmt = $pdo->prepare("
                INSERT INTO tbl_product_receipt (
                    receipt_date, product_id, received_quantity, supplier_name, 
                    receipt_price, user_id, product_code, product_sku, product_name
                )
                VALUES (
                    :date, :product_id, :quantity, :supplier, 
                    :price, :user_id, :product_code, :product_sku, :product_name
                )
            ");

            foreach ($updates as $update) {
                $id = filter_var($update['product_id'] ?? null, FILTER_SANITIZE_NUMBER_INT);
                $quantity = filter_var($update['quantity'] ?? null, FILTER_SANITIZE_NUMBER_INT);
                $price = filter_var($update['price'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $supplier = filter_var($update['supplier'] ?? $default_supplier, FILTER_SANITIZE_STRING);

                if ($id && is_numeric($quantity) && $quantity > 0) {

                    // 1. Récupérer les données actuelles pour l'historique
                    $select_product = $pdo->prepare("SELECT product_code, product_sku, product_name FROM tbl_product WHERE product_id = :id");
                    $select_product->bindParam(':id', $id, PDO::PARAM_INT);
                    $select_product->execute();
                    $product_info = $select_product->fetch(PDO::FETCH_ASSOC);

                    if ($product_info) {
                        // 2. Mise à jour du stock
                        $update_product_stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                        $update_product_stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $update_product_stmt->execute();

                        // 3. Insertion de la réception
                        $insert_receipt_stmt->bindParam(':date', $receipt_date);
                        $insert_receipt_stmt->bindParam(':product_id', $id, PDO::PARAM_INT);
                        $insert_receipt_stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                        $insert_receipt_stmt->bindParam(':supplier', $supplier);
                        $insert_receipt_stmt->bindParam(':price', $price);
                        $insert_receipt_stmt->bindParam(':user_id', $current_user_id, PDO::PARAM_INT);
                        $insert_receipt_stmt->bindParam(':product_code', $product_info['product_code']);
                        $insert_receipt_stmt->bindParam(':product_sku', $product_info['product_sku']);
                        $insert_receipt_stmt->bindParam(':product_name', $product_info['product_name']);
                        $insert_receipt_stmt->execute();

                        $success_count++;
                    } else {
                        // Product ID not found, but continue with others
                        error_log("Mass Stock Update: Product ID $id not found.");
                    }
                }
            }

            if ($success_count > 0) {
                $pdo->commit();
                $global_message = "$success_count produit(s) mis à jour avec succès.";
                $global_alert_type = "success";
            } else {
                $pdo->rollBack();
                $global_message = "Aucun produit valide trouvé pour la mise à jour du stock. Transaction annulée.";
                $global_alert_type = "warning";
            }
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Mass Stock Update Error: " . $e->getMessage());
            $global_message = "Erreur fatale de base de données. L'opération a été annulée.";
            $global_alert_type = "error";
            $error_occurred = true;
        }

        // Display SweetAlert for mass update result
        if (isset($global_message)) {
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                    swal("' . ucfirst($global_alert_type) . '", "' . $global_message . '", "' . $global_alert_type . '", {
                    button: "Continue",
                        }).then(() => {
                            window.location.href = "product.php";
                        });
                    });
                    </script>';
        }
    }
}

// --- Product Listing Query Logic ---
$status = $_GET['status'] ?? 'all';
$statusName = "";
$select_query = "";

switch ($status) {
    // ... (Your existing status query logic remains unchanged) ...
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
// ... (Your existing product insertion logic remains unchanged) ...
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
    $shop_success = true; // Initialize to true

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

                    <button type="button" onclick="openPrintWindow('<?php echo htmlspecialchars($status); ?>')" class="btn btn-info btn-sm" style="margin-left: 10px;" title="Imprimer la liste actuelle">
                        <i class="fa fa-print"></i> Imprimer la Liste
                    </button>
                </div>
            </div>

            <div class="box-header with-border">
                <h3 class="box-title">Liste Produits <?php echo $statusName; ?></h3>

                <button type="button" class="btn btn-info btn-sm pull-right" data-toggle="modal" data-target="#massStockModal" style="margin-right: 10px;" title="Ajouter du stock en masse">
                    <i class="fa fa-upload"></i> Ajouter Stock (En Masse)
                </button>

                <button type="button" class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#addNewProductModal" title="Créer un Nouveau Produit" style="margin-right: 10px;">
                    <i class="fa fa-plus"></i> Nouveau Produit
                </button>
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
                                            <a href="edit_stock.php?id=<?php echo $row->product_id; ?>" class="btn btn-success btn-sm" title="Ajouter Stock (Individuel)"><i class="fa fa-plus"></i></a>
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

<div class="modal fade" id="massStockModal" tabindex="-1" role="dialog" aria-labelledby="massStockModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="massStockModalLabel"><i class="fa fa-cubes"></i> Ajout de Stock en Masse</h4>
            </div>
            <form action="product.php" method="POST" name="form_mass_stock" onsubmit="return confirmMassAddition();" autocomplete="off">
                <div class="modal-body">
                    <div class="row" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mass_receipt_date">Date de Réception Globale <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="mass_receipt_date" id="mass_receipt_date" required value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mass_supplier_name">Fournisseur (Par défaut si non spécifié par ligne)</label>
                                <select class="form-control" name="mass_supplier_name" id="mass_supplier_name" style="width: 100%;">
                                    <option value="">-- Aucun / Non spécifié --</option>
                                    <?php
                                    $select_sup_mass = $pdo->prepare("SELECT suplier_name FROM supliers ORDER BY suplier_name ASC");
                                    $select_sup_mass->execute();
                                    while ($row_sup_mass = $select_sup_mass->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option>' . htmlspecialchars($row_sup_mass['suplier_name']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="product_code_input">Saisir/Scanner Code Produit</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="product_code_input" placeholder="Entrez le code produit ou SKU">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="button" id="add_product_line_btn"><i class="fa fa-search"></i> Ajouter Ligne</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="mass_stock_table">
                            <thead>
                                <tr class="bg-primary">
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 25%;">Produit (Code)</th>
                                    <th style="width: 15%;">Stock Actuel</th>
                                    <th style="width: 15%;">Quantité Reçue <span class="text-danger">*</span></th>
                                    <th style="width: 20%;">Prix Achat Unitaire</th>
                                    <th style="width: 15%;">Fournisseur</th>
                                    <th style="width: 5%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="stock_lines_container">
                                <tr id="no-item-row">
                                    <td colspan="7" class="text-center text-muted">Utilisez le champ ci-dessus pour ajouter des produits par code.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-info" name="mass_update_stock" id="submit_mass_stock" disabled>
                        <i class="fa fa-check-circle"></i> Enregistrer Toutes les Réceptions
                    </button>
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

    // NEW FUNCTION: Open Print Window as a pseudo-desktop app
    function openPrintWindow(status) {
        var url = 'print_product_list.php?status=' + status;
        var windowName = 'PrintProductList';

        // Define window features to remove browser chrome
        var features = 'width=800,height=600,scrollbars=yes,resizable=yes,location=no,menubar=no,toolbar=no,status=no';

        // Open the window
        window.open(url, windowName, features);
    }

    // NEW FUNCTION: Check if mass stock table has items
    function checkMassStockTable() {
        const rowCount = $('#stock_lines_container tr').not('#no-item-row').length;
        $('#submit_mass_stock').prop('disabled', rowCount === 0);
        $('#no-item-row').toggle(rowCount === 0);
    }

    /**
     * Confirme la mise à jour de stock en masse avant la soumission.
     * @returns {boolean} True si l'utilisateur confirme, false sinon.
     */
    function confirmMassAddition() {
        const rowCount = $('#stock_lines_container tr').not('#no-item-row').length;
        if (rowCount === 0) {
            swal("Attention", "Veuillez ajouter au moins un produit pour la mise à jour.", "warning");
            return false;
        }

        const confirmationMessage = `Êtes-vous sûr de vouloir enregistrer les réceptions pour **${rowCount}** produit(s) et mettre à jour le stock principal?`;

        return confirm(confirmationMessage);
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

        // Clear modal content/form on close (for new product modal)
        $('#addNewProductModal').on('hidden.bs.modal', function() {
            $(this).find('form').trigger('reset'); // Reset form
            $('#modal_img_preview').attr('src', 'upload/default.png').width(150).height('auto'); // Reset image preview
        });

        // Clear modal content/form on close (for mass stock modal)
        $('#massStockModal').on('hidden.bs.modal', function() {
            $('#stock_lines_container').empty().html('<tr id="no-item-row"><td colspan="7" class="text-center text-muted">Utilisez le champ ci-dessus pour ajouter des produits par code.</td></tr>'); // Clear dynamic rows
            $(this).find('form').trigger('reset'); // Reset form elements outside the table
            $('#mass_receipt_date').val('<?php echo date('Y-m-d'); ?>'); // Reset date
            checkMassStockTable();
        });

        // --- Mass Stock Modal Logic ---

        // Dynamic Row Counter
        let line_counter = 0;
        const suppliers_options = $('#mass_supplier_name').html(); // Reuse the supplier options

        $('#add_product_line_btn').on('click', function() {
            const product_code = $('#product_code_input').val().trim();

            if (product_code === '') {
                swal("Attention", "Veuillez entrer un code produit ou SKU.", "warning");
                return;
            }

            // Check if product already exists in the list
            if ($(`#code_input_${product_code.replace(/[^a-zA-Z0-9]/g, '')}`).length > 0) {
                swal("Attention", `Le produit avec le code ${product_code} est déjà dans la liste.`, "warning");
                $('#product_code_input').val('');
                return;
            }

            // AJAX to fetch product data
            $.ajax({
                url: 'fetch_product_data.php', // This is a NEW file you need to create
                method: 'GET',
                dataType: 'json',
                data: {
                    code: product_code
                },
                beforeSend: function() {
                    $('#add_product_line_btn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Recherche...');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        line_counter++;
                        const data = response.data;
                        const unique_id_part = data.product_code.replace(/[^a-zA-Z0-9]/g, '');

                        // Create the new table row
                        const newRow = `
                            <tr id="row_${unique_id_part}">
                                <td>${line_counter}</td>
                                <td>
                                    ${data.product_name} (${data.product_code})
                                    <input type="hidden" name="updates[${line_counter}][product_id]" value="${data.product_id}" />
                                    <input type="hidden" id="code_input_${unique_id_part}" value="${data.product_code}" />
                                </td>
                                <td>
                                    <span class="label label-primary">${data.stock} ${data.product_satuan}</span>
                                </td>
                                <td>
                                    <input type="number" min="1" step="1" class="form-control" 
                                        name="updates[${line_counter}][quantity]" required placeholder="Qté" 
                                        style="max-width: 100px;" value="1" />
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" class="form-control" 
                                        name="updates[${line_counter}][price]" required 
                                        placeholder="${data.purchase_price}" value="${data.purchase_price}" />
                                </td>
                                <td>
                                    <select class="form-control" name="updates[${line_counter}][supplier]">
                                        ${suppliers_options.replace(new RegExp('value=""'), 'value="" selected')}
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-xs remove-line" data-unique-id="${unique_id_part}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        `;

                        $('#stock_lines_container').append(newRow);
                        $('#product_code_input').val(''); // Clear input
                        checkMassStockTable();

                        // Select the supplier that matches the product's default supplier
                        $(`#row_${unique_id_part} select[name="updates[${line_counter}][supplier]"]`).val(data.supplier);


                    } else {
                        swal("Erreur", response.message, "error");
                    }
                },
                error: function() {
                    swal("Erreur", "Problème de communication avec le serveur.", "error");
                },
                complete: function() {
                    $('#add_product_line_btn').prop('disabled', false).html('<i class="fa fa-search"></i> Ajouter Ligne');
                }
            });
        });

        // Remove Line Button Handler
        $('#stock_lines_container').on('click', '.remove-line', function() {
            const uniqueId = $(this).data('unique-id');
            $(`#row_${unique_id}`).remove();
            checkMassStockTable();
            // Re-index visually if desired, but array index is fine for PHP
        });
    });
</script>

<?php
// Retrieve supplier list for the new modal (already done above, but kept here for clarity if moving blocks)
$select_sup = $pdo->prepare("SELECT suplier_name FROM supliers");
$select_sup->execute();
$suppliers_list = $select_sup->fetchAll(PDO::FETCH_COLUMN);
// The list is used in the modal HTML

include_once 'inc/footer_all.php';
?>