<?php
//product.php
include_once 'db/connect_db.php';

if (empty($_SESSION['user_name'])) {
    header('location:index.php');
    exit();
} else {
    if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "Responsable" || $_SESSION['role'] == "storekeeper") {
        include_once 'inc/header_all.php';
    } else {
        include_once 'inc/header_all_operator.php';
    }
}

error_reporting(0);

$id = $_GET['id'] ?? null;
$code_to_delete = $_GET['code'] ?? null;

if ($id && $code_to_delete) {
    $delete_product = $pdo->prepare("DELETE FROM tbl_product WHERE product_id = :id");
    $delete_product->bindParam(':id', $id, PDO::PARAM_INT);
    $delete_shop_items = $pdo->prepare("DELETE FROM tbl_shop_item WHERE product_code = :code");
    $delete_shop_items->bindParam(':code', $code_to_delete);

    try {
        $pdo->beginTransaction();
        $delete_product->execute();
        $delete_shop_items->execute();
        $pdo->commit();
        echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Success", "Le produit a été supprimé avec succès.", "success", {
                button: "Continue",
                    }).then(() => {
                        window.location.href = "product.php";
                    });
                });
                </script>';
    } catch (Exception $e) {
        $pdo->rollBack();
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

                    $select_product = $pdo->prepare("SELECT product_code, product_sku, product_name FROM tbl_product WHERE product_id = :id");
                    $select_product->bindParam(':id', $id, PDO::PARAM_INT);
                    $select_product->execute();
                    $product_info = $select_product->fetch(PDO::FETCH_ASSOC);

                    if ($product_info) {
                        $update_product_stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                        $update_product_stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $update_product_stmt->execute();

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

if (isset($_POST['add_product'])) {
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

    $select_code = $pdo->prepare("SELECT product_code FROM tbl_product WHERE product_code = :code");
    $select_code->bindParam(':code', $code);
    $select_code->execute();

    $insert_success = false;
    $shop_success = true;

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
                                ':stock_init' => 0,
                                ':min_stock_init' => 0,
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

    if ($insert_success) {
        $alert_message = $shop_success ? "Produit enregistré avec succès et initialisé dans les magasins." : "Produit enregistré, mais échec de l\'initialisation dans *certains* magasins. Veuillez vérifier.";
        $alert_type = $shop_success ? "success" : "warning";

        echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Success", "' . $alert_message . '", "' . $alert_type . '", {
                button: "Continue",
                    }).then(() => {
                        window.location.href = "product.php";
                    });
                });
                </script>';
    } elseif (isset($_POST['product_code']) && !$select_code->rowCount() > 0 && strlen($code) <= 50) {
        echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Error", "Erreur d\'enregistrement du produit.", "error", {
                button: "Continue",
                    });
                });
                </script>';
    }
}

$select_all_products = $pdo->prepare("SELECT product_id, product_code, product_name FROM tbl_product ORDER BY product_name ASC");
$select_all_products->execute();
$all_products = $select_all_products->fetchAll(PDO::FETCH_ASSOC);
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

<div class="modal fade" id="massStockModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-cubes"></i> Ajout de Stock en Masse</h4>
            </div>
            <form action="product.php" method="POST" onsubmit="return confirmMassAddition();">
                <div class="modal-body">
                    <!-- Global Settings -->
                    <div class="row" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background-color: #f9f9f9;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date de Réception Globale <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="mass_receipt_date" id="mass_receipt_date" required value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fournisseur (Par défaut)</label>
                                <select class="form-control" name="mass_supplier_name" id="mass_supplier_name">
                                    <option value="">-- Non spécifié --</option>
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

                    <!-- DUAL SELECTION METHOD (NEW FEATURE) -->
                    <div class="row" style="margin-bottom: 15px; padding: 15px; border: 2px solid #5bc0de; border-radius: 6px; background-color: #f0f8ff;">
                        <div class="col-xs-12">
                            <h5 style="margin-top: 0; color: #31708f; font-weight: bold;">
                                <i class="fa fa-search-plus"></i> Rechercher et Ajouter un Produit
                            </h5>
                        </div>

                        <!-- Method 1: Code Input -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fa fa-barcode"></i> Méthode 1: Saisir/Scanner Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="product_code_input" placeholder="Code ou SKU">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="button" id="add_by_code_btn">
                                            <i class="fa fa-search"></i> Ajouter
                                        </button>
                                    </span>
                                </div>
                                <span class="text-muted small"><i class="fa fa-info-circle"></i> Scannez ou tapez</span>
                            </div>
                        </div>

                        <!-- Method 2: Dropdown Selection (NEW) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fa fa-list-ul"></i> Méthode 2: Sélectionner de la Liste</label>
                                <div class="input-group">
                                    <select class="form-control select2" id="product_select_dropdown" style="width: 100%;">
                                        <option value="">-- Rechercher --</option>
                                        <?php foreach ($all_products as $prod): ?>
                                            <option value="<?php echo htmlspecialchars($prod['product_code']); ?>">
                                                <?php echo htmlspecialchars($prod['product_code'] . ' - ' . $prod['product_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="input-group-btn">
                                        <button class="btn btn-success" type="button" id="add_by_dropdown_btn">
                                            <i class="fa fa-plus-circle"></i> Ajouter
                                        </button>
                                    </span>
                                </div>
                                <span class="text-muted small"><i class="fa fa-info-circle"></i> Tapez pour filtrer</span>
                            </div>
                        </div>
                    </div>

                    <!-- Product Lines Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-primary">
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 25%;">Produit</th>
                                    <th style="width: 15%;">Stock Actuel</th>
                                    <th style="width: 15%;">Quantité <span class="text-danger">*</span></th>
                                    <th style="width: 20%;">Prix Achat</th>
                                    <th style="width: 15%;">Fournisseur</th>
                                    <th style="width: 5%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="stock_lines_container">
                                <tr id="no-item-row">
                                    <td colspan="7" class="text-center text-muted" style="padding: 30px;">
                                        <i class="fa fa-inbox fa-3x" style="color: #ccc;"></i>
                                        <p style="margin-top: 10px;">Aucun produit ajouté. Utilisez les méthodes ci-dessus.</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row" style="margin-top: 15px;">
                        <div class="col-xs-12">
                            <div class="alert alert-info" style="margin-bottom: 0;">
                                <strong><i class="fa fa-info-circle"></i> Information:</strong>
                                <span id="product_count_display">0 produit(s)</span> en attente.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i> Fermer
                    </button>
                    <button type="submit" class="btn btn-info" name="mass_update_stock" id="submit_mass_stock" disabled>
                        <i class="fa fa-check-circle"></i> Enregistrer Toutes les Réceptions
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#modal_img_preview').attr('src', e.target.result).width(150).height('auto');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openPrintWindow(status) {
        var url = 'print_product_list.php?status=' + status;
        var features = 'width=800,height=600,scrollbars=yes,resizable=yes';
        window.open(url, 'PrintProductList', features);
    }

    function checkMassStockTable() {
        const rowCount = $('#stock_lines_container tr').not('#no-item-row').length;
        $('#submit_mass_stock').prop('disabled', rowCount === 0);
        $('#no-item-row').toggle(rowCount === 0);
        $('#product_count_display').text(rowCount + ' produit(s)');
    }

    function confirmMassAddition() {
        const rowCount = $('#stock_lines_container tr').not('#no-item-row').length;
        if (rowCount === 0) {
            swal("Attention", "Veuillez ajouter au moins un produit.", "warning");
            return false;
        }
        return confirm(`Êtes-vous sûr de vouloir enregistrer ${rowCount} produit(s)?`);
    }

    $(document).ready(function() {
        if ($.fn.DataTable) {
            $('#myProduct').DataTable({
                "order": [
                    [0, "asc"]
                ]
            });
        }

        if ($.fn.select2) {
            $('#product_select_dropdown').select2({
                placeholder: '-- Rechercher --',
                allowClear: true,
                width: '100%'
            });
        }

        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            var productId = $(this).data('id');
            var productCode = $(this).data('code');
            var deleteUrl = 'product.php?id=' + productId + '&code=' + productCode;
            swal({
                title: "Êtes-vous sûr(e)?",
                text: "La suppression est irréversible!",
                icon: "warning",
                buttons: ["Annuler", "Supprimer"],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) window.location.href = deleteUrl;
            });
        });

        $('#addNewProductModal').on('hidden.bs.modal', function() {
            $(this).find('form').trigger('reset');
            $('#modal_img_preview').attr('src', 'upload/default.png');
        });

        // MASS STOCK MODAL LOGIC
        let line_counter = 0;
        const suppliers_options = $('#mass_supplier_name').html();

        function addProductLine(product_code) {
            if (product_code === '') {
                swal("Attention", "Veuillez entrer ou sélectionner un code.", "warning");
                return;
            }

            const sanitized_code = product_code.replace(/[^a-zA-Z0-9]/g, '');
            if ($(`#code_input_${sanitized_code}`).length > 0) {
                swal("Attention", `Produit ${product_code} déjà dans la liste.`, "warning");
                clearInputs();
                return;
            }

            $.ajax({
                url: 'fetch_product_data.php',
                method: 'GET',
                dataType: 'json',
                data: {
                    code: product_code
                },
                beforeSend: function() {
                    $('#add_by_code_btn, #add_by_dropdown_btn').prop('disabled', true)
                        .html('<i class="fa fa-spinner fa-spin"></i> Recherche...');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        line_counter++;
                        const data = response.data;
                        const unique_id = data.product_code.replace(/[^a-zA-Z0-9]/g, '');

                        const newRow = `
                        <tr id="row_${unique_id}">
                            <td class="text-center">${line_counter}</td>
                            <td>
                                <strong>${data.product_name}</strong><br>
                                <small class="text-muted">(${data.product_code})</small>
                                <input type="hidden" name="updates[${line_counter}][product_id]" value="${data.product_id}" />
                                <input type="hidden" id="code_input_${unique_id}" value="${data.product_code}" />
                            </td>
                            <td class="text-center">
                                <span class="label label-primary">${data.stock} ${data.product_satuan}</span>
                            </td>
                            <td>
                                <input type="number" min="1" step="1" class="form-control input-sm" 
                                    name="updates[${line_counter}][quantity]" required value="1" style="text-align: center;" />
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" min="0" class="form-control" 
                                        name="updates[${line_counter}][price]" required value="${data.purchase_price}" />
                                    <span class="input-group-addon">FCFA</span>
                                </div>
                            </td>
                            <td>
                                <select class="form-control input-sm" name="updates[${line_counter}][supplier]">
                                    ${suppliers_options}
                                </select>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-xs remove-line" data-unique-id="${unique_id}">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    `;

                        $('#stock_lines_container').append(newRow);
                        $(`#row_${unique_id} select`).val(data.supplier);
                        clearInputs();
                        checkMassStockTable();
                        $(`#row_${unique_id} input[name="updates[${line_counter}][quantity]"]`).focus().select();
                    } else {
                        swal("Produit Introuvable", response.message || "Code inexistant.", "error");
                    }
                },
                error: function() {
                    swal("Erreur", "Problème de communication serveur.", "error");
                },
                complete: function() {
                    $('#add_by_code_btn').prop('disabled', false).html('<i class="fa fa-search"></i> Ajouter');
                    $('#add_by_dropdown_btn').prop('disabled', false).html('<i class="fa fa-plus-circle"></i> Ajouter');
                }
            });
        }

        function clearInputs() {
            $('#product_code_input').val('');
            $('#product_select_dropdown').val('').trigger('change');
        }

        $('#add_by_code_btn').on('click', function() {
            addProductLine($('#product_code_input').val().trim());
        });

        $('#add_by_dropdown_btn').on('click', function() {
            addProductLine($('#product_select_dropdown').val());
        });

        $('#product_code_input').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#add_by_code_btn').click();
            }
        });

        $('#stock_lines_container').on('click', '.remove-line', function() {
            const uniqueId = $(this).data('unique-id');
            $(`#row_${uniqueId}`).fadeOut(300, function() {
                $(this).remove();
                checkMassStockTable();
            });
        });

        $('#massStockModal').on('hidden.bs.modal', function() {
            $('#stock_lines_container').empty().html(
                '<tr id="no-item-row"><td colspan="7" class="text-center text-muted" style="padding: 30px;">' +
                '<i class="fa fa-inbox fa-3x" style="color: #ccc;"></i>' +
                '<p style="margin-top: 10px;">Aucun produit ajouté.</p></td></tr>'
            );
            $(this).find('form').trigger('reset');
            $('#mass_receipt_date').val('<?php echo date('Y-m-d'); ?>');
            line_counter = 0;
            checkMassStockTable();
        });

        checkMassStockTable();
    });
</script>

<?php include_once 'inc/footer_all.php'; ?>