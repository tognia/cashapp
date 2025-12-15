<?php
//product_shop_item.php
include_once 'db/connect_db.php';

// --- Session and Access Control ---
if ($_SESSION['user_name'] == "") {
    header('location:index.php');
    exit();
} else {
    if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "storekeeper" || $_SESSION['role'] == "Responsable") {
        include_once 'inc/header_all.php';
    } else {
        include_once 'inc/header_all_operator.php';
    }
}
error_reporting(0);

// --- 1. RIGOROUS SHOP SELECTION LOGIC ---

// 1.1. If a form was submitted (by Admin/Storekeeper), update the session preference
if (isset($_POST['select_shop'])) {
    $_SESSION['select_shop'] = $_POST['shop'];
}

// 1.2. Define $shop based strictly on Role
$shop = '';
if ($_SESSION['role'] == "Responsable") {
    // STRICT RULE: Responsable MUST use their assigned shop.
    $shop = $_SESSION['magasin'] ?? '';
} elseif ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "storekeeper") {
    // Admin/Storekeeper use the selected shop from session
    $shop = $_SESSION['select_shop'] ?? '';
} else {
    // Fallback for any other roles (Operators, etc.) - Default to assigned magasin
    $shop = $_SESSION['magasin'] ?? '';
}

if (!$shop) {
    // Si aucun magasin n'est défini pour les rôles restreints, forcez une sélection ou une redirection
    if ($_SESSION['role'] == "Responsable" || $_SESSION['role'] == "Operator") {
        echo '<script>swal("Attention", "Aucun magasin attribué ou sélectionné.", "warning").then(() => { window.location.href = "index.php"; });</script>';
        exit();
    }
}

$id = $_GET['id'] ?? null;

// --- 2. DELETE Logic (Shop Item) ---
if ($id && $shop) {
    // Vérifier si l'utilisateur a le droit de supprimer
    if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "storekeeper") {
        $delete = $pdo->prepare("DELETE FROM tbl_shop_item WHERE shop_code = :shop AND product_id = :id");
        $delete->bindParam(':shop', $shop);
        $delete->bindParam(':id', $id, PDO::PARAM_INT);

        if ($delete->execute()) {
            echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Info", "Produit Supprimé du Magasin: ' . htmlspecialchars($shop) . '", "info", {
                button: "Continue",
                    }).then(() => {
                        window.location.href = "product_shop_item.php";
                    });
                });
                </script>';
        } else {
            echo '<script type="text/javascript">
                jQuery(function validation(){
                swal("Erreur", "Échec de la suppression.", "error", {
                button: "Continue",
                    });
                });
                </script>';
        }
    }
}

// --- 3. NEW LOGIC: Mass Stock Transfer (Transaction) ---
if (isset($_POST['mass_transfer_stock'])) {
    $transfers = $_POST['transfers'] ?? [];
    $shipment_date = $_POST['mass_shipment_date'] ?? date('Y-m-d');
    $notes = $_POST['mass_notes'] ?? '';
    $shop_code_target = $_POST['shop_code_target'] ?? ''; // Should be $shop from above logic
    $current_user_id = $_SESSION['user_id'] ?? null;
    $success_count = 0;
    $error_occurred = false;

    if (!$current_user_id || !$shop_code_target) {
        $error_occurred = true;
        $global_message = "Erreur: Magasin ou Identifiant utilisateur non disponible. Opération annulée.";
    } elseif (empty($transfers)) {
        $error_occurred = true;
        $global_message = "Aucun produit à transférer n'a été trouvé.";
    } else {
        try {
            $pdo->beginTransaction();

            $update_main_stock_stmt = $pdo->prepare("UPDATE tbl_product SET stock = stock - :quantity WHERE product_code = :product_code");
            // $update_shop_stock_stmt = $pdo->prepare("UPDATE tbl_shop_item SET stock = stock + :quantity WHERE product_id = :product_id AND shop_code = :shop_code");
            $insert_shipment_stmt = $pdo->prepare("
                INSERT INTO tbl_product_shipment (
                    shipment_date, product_id, shipped_quantity, code_agence, 
                    user_id, delivery_status, notes, product_code, product_sku, product_name
                )
                VALUES (
                    :date, :product_id, :quantity, :code_agence, 
                    :user_id, 'Delivered', :notes, :product_code, :product_sku, :product_name
                )
            ");

            foreach ($transfers as $transfer) {
                $product_id = filter_var($transfer['product_id'] ?? null, FILTER_SANITIZE_NUMBER_INT);
                $quantity = filter_var($transfer['quantity'] ?? null, FILTER_SANITIZE_NUMBER_INT);
                $product_code_line = filter_var($transfer['product_code'] ?? '', FILTER_SANITIZE_STRING);

                if ($product_id && $product_code_line && is_numeric($quantity) && $quantity > 0) {

                    // 1. Fetch current main stock
                    $select_stock = $pdo->prepare("SELECT stock, product_sku, product_name FROM tbl_product WHERE product_code = :code");
                    $select_stock->bindParam(':code', $product_code_line);
                    $select_stock->execute();
                    $main_product_info = $select_stock->fetch(PDO::FETCH_ASSOC);

                    $current_main_stock = $main_product_info['stock'] ?? 0;

                    if ($current_main_stock < $quantity) {
                        // Stock principal insuffisant. Annuler la transaction et alerter.
                        $pdo->rollBack();
                        $global_message = "Échec du transfert: Stock insuffisant dans l'entrepôt pour le produit **" . htmlspecialchars($main_product_info['product_name'] ?? $product_code_line) . "**. Transaction annulée.";
                        $global_alert_type = "error";
                        $error_occurred = true;
                        break;
                    }

                    // 2. Déduction du stock principal
                    $update_main_stock_stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                    $update_main_stock_stmt->bindParam(':product_code', $product_code_line);
                    $update_main_stock_stmt->execute();

                    // 3. Ajout au stock boutique
                    // $update_shop_stock_stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                    // $update_shop_stock_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                    // $update_shop_stock_stmt->bindParam(':shop_code', $shop_code_target);
                    // $update_shop_stock_stmt->execute();

                    // 4. Enregistrement de l'expédition
                    $insert_shipment_stmt->bindParam(':date', $shipment_date);
                    $insert_shipment_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                    $insert_shipment_stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                    $insert_shipment_stmt->bindParam(':code_agence', $shop_code_target);
                    $insert_shipment_stmt->bindParam(':user_id', $current_user_id, PDO::PARAM_INT);
                    $insert_shipment_stmt->bindParam(':notes', $notes);
                    $insert_shipment_stmt->bindParam(':product_code', $product_code_line);
                    $insert_shipment_stmt->bindParam(':product_sku', $main_product_info['product_sku']);
                    $insert_shipment_stmt->bindParam(':product_name', $main_product_info['product_name']);
                    $insert_shipment_stmt->execute();

                    $success_count++;
                }
            }

            if (!$error_occurred) {
                if ($success_count > 0) {
                    $pdo->commit();
                    $global_message = "$success_count transfert(s) de stock effectué(s) vers **" . htmlspecialchars($shop_code_target) . "** avec succès.";
                    $global_alert_type = "success";
                } else {
                    // Si on a break à cause d'un manque de stock, $error_occurred est déjà true
                    if (!$error_occurred) {
                        $pdo->rollBack();
                        $global_message = "Aucun transfert valide trouvé. Transaction annulée.";
                        $global_alert_type = "warning";
                    }
                }
            }
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Mass Transfer Error: " . $e->getMessage());
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
                            window.location.href = "product_shop_item.php";
                        });
                    });
                    </script>';
        }
    }
}


// --- 4. Database Query for Listing ---
$statusName = "";

// SQL Subquery to calculate the total delivered quantity for the current shop ($shop)
$delivered_quantity_subquery = "
    (
        SELECT COALESCE(SUM(tps.shipped_quantity), 0)
        FROM tbl_product_shipment tps
        WHERE tps.product_code = tsi.product_code
        AND tps.delivery_status = 'Delivered'
        AND tps.code_agence = :shop_param
    ) AS total_delivered
";

// Base SELECT statement structure
$base_select_columns = "tsi.*, $delivered_quantity_subquery";
$base_from_table = "tbl_shop_item tsi";
$base_where_clause = "tsi.shop_code = :shop_where";

$status = $_GET['status'] ?? 'all';

// Préparation de la requête SELECT
switch ($status) {
    case 'all':
        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE $base_where_clause ORDER BY tsi.product_name ASC");
        break;
    case 'ok':
        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE tsi.stock > tsi.min_stock AND $base_where_clause ORDER BY tsi.product_name ASC");
        $statusName = " en stock";
        break;
    case 'alert':
        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE tsi.stock <= tsi.min_stock AND tsi.stock <> 0 AND $base_where_clause ORDER BY tsi.product_name ASC");
        $statusName = " stock alerte";
        break;
    case 'null':
        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE tsi.stock = 0 AND $base_where_clause ORDER BY tsi.product_name ASC");
        $statusName = " stock null";
        break;
    default:
        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE $base_where_clause ORDER BY tsi.product_name ASC");
        break;
}

// BINDING DES PARAMÈTRES (Sécurité accrue)
if ($shop && isset($select)) {
    $select->bindParam(':shop_param', $shop);
    $select->bindParam(':shop_where', $shop);
}

// Fetch the list of all product codes/IDs from the main warehouse for the new modal
$product_codes_in_main = [];
try {
    $stmt_main_products = $pdo->query("SELECT product_code FROM tbl_product WHERE stock > 0 ORDER BY product_code ASC");
    $product_codes_in_main = $stmt_main_products->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    error_log("Failed to fetch main product codes: " . $e->getMessage());
}


// Fetch agency list for the select box (if role allows)
$agencies_list = [];
if ($_SESSION["role"] == "Admin" || $_SESSION["role"] == "storekeeper") {
    try {
        $select1 = $pdo->prepare("SELECT code_agence, libelle_agence FROM agence");
        $select1->execute();
        $agencies_list = $select1->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Failed to fetch agencies: " . $e->getMessage());
    }
}
?>

<div class="content-wrapper">
    <section class="content container-fluid">
        <div class="box box-success">

            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"> | Boutique : <?php echo htmlspecialchars($shop); ?></h3>

                    <a href="product_shop_item.php?status=ok" class="btn btn-primary btn-sm">PRODUITS - STOCK OK </a>
                    <a href="product_shop_item.php?status=alert" class="btn btn-warning btn-sm">PRODUITS - STOCK ALERTE</a>

                    <a href="product_shop_item.php?status=null" class="btn btn-danger btn-sm">PRODUITS - STOCK NULL</a>

                    <a href="product_shop_item.php?status=all" class="badge badge-info bg-dark btn-sm">TOUS LES PRODUITS</a>

                    <button type="button" onclick="openPrintWindow('<?php echo htmlspecialchars($status); ?>')" class="btn btn-info btn-sm" style="margin-left: 10px;" title="Imprimer la liste actuelle">
                        <i class="fa fa-print"></i> Imprimer la Liste
                    </button>
                    <?php if ($_SESSION["role"] == "Admin" || $_SESSION["role"] == "storekeeper") { ?>

                        <form action="" method="POST" class="pull-right" style="margin-right: 20px;">
                            <div class="input-group input-group-sm">
                                <label for="shop_select" style="margin-right: 5px; line-height: 30px;">Magasin</label>
                                <select class="form-control" name="shop" id="shop_select" required style="width: auto; margin-right: 5px;">
                                    <?php
                                    foreach ($agencies_list as $row) {
                                        $selected = ($row['code_agence'] == $shop) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($row['code_agence']) . '" ' . $selected . '>' . htmlspecialchars($row['code_agence'] . " " . $row['libelle_agence']) . '</option>';
                                    }
                                    ?>
                                </select>
                                <span class="input-group-btn">
                                    <button type="submit" name="select_shop" class="btn btn-default btn-flat">SELECTIONNER</button>
                                </span>
                            </div>
                        </form>

                    <?php } ?>


                </div>


                <div class="box-header with-border">
                    <h3 class="box-title">Liste Produits <?php echo $statusName; ?> Magasin : **<?php echo htmlspecialchars($shop); ?>**</h3>
                    <?php
                    if ($_SESSION['role'] == "Responsable" || $_SESSION['role'] == "Admin" || $_SESSION['role'] == "storekeeper") {
                    ?>
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#massTransferModal" title="Transférer stock en masse">
                                <i class="fa fa-truck"></i> Transfert Stock Entrepôt
                            </button>
                            <a href="edit_stock_shop_validation.php" class="btn btn-success btn-sm" style="margin-left: 10px;"><i class="fa fa-cubes"></i> Réceptionner Stocks En Attente</a>
                        </div>
                    <?php
                    } ?>

                </div>
                <div class="box-body">
                    <?php if (!$shop) : ?>
                        <div class="alert alert-warning text-center">
                            **Veuillez sélectionner un magasin** pour afficher l'inventaire.
                        </div>
                    <?php elseif (!isset($select)) : ?>
                        <div class="alert alert-danger text-center">
                            Erreur de préparation de la requête.
                        </div>
                    <?php else : ?>
                        <div style="overflow-x:auto;">
                            <table class="table table-striped" id="myProduct">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Description Produit</th>
                                        <th>Catégorie</th>
                                        <th>Code</th>
                                        <th>SKU</th>
                                        <th>Details</th>
                                        <th>IMG</th>
                                        <th>Prix Achat</th>
                                        <th>Prix de vente</th>
                                        <th>Stock</th>
                                        <th>Expédié Non Validé</th>
                                        <th>Fournisseur</th>
                                        <th>Actions</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $select->execute();
                                    while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($row->product_name); ?></td>
                                            <td><?php echo htmlspecialchars($row->product_category); ?></td>
                                            <td><?php echo htmlspecialchars($row->product_code); ?></td>
                                            <td><?php echo htmlspecialchars($row->product_sku); ?></td>
                                            <td><?php echo htmlspecialchars($row->description); ?></td>
                                            <td><img src="upload/<?php echo htmlspecialchars($row->img); ?>" width="50px" height="50" style="object-fit: cover; border-radius: 4px;" /></td>
                                            <td> <?php echo number_format($row->purchase_price, 0, null, " "); ?> FCFA</td>
                                            <td> <?php echo number_format($row->sell_price, 0, null, " "); ?> FCFA</td>
                                            <td> <?php if ($row->stock == "0") { ?>
                                                    <span class="label label-danger"><?php echo $row->stock; ?></span>
                                                <?php } elseif ($row->stock <= $row->min_stock) { ?>
                                                    <span class="label label-warning"><?php echo $row->stock; ?></span>
                                                <?php } else { ?>
                                                    <span class="label label-primary"><?php echo $row->stock; ?></span>
                                                <?php } ?>
                                                <span class="label label-default"><?php echo htmlspecialchars($row->product_satuan); ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                $delivered_qty = $row->total_delivered ?? 0;
                                                if ($delivered_qty > 0) {
                                                ?>
                                                    <span class="label label-danger"><?php echo $delivered_qty; ?></span>
                                                <?php } else { ?>
                                                    <span class="label label-default">0</span>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($row->supplier); ?></td>
                                            <td>

                                                <?php
                                                if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "storekeeper") {
                                                ?>
                                                    <a href="edit_stock_shop.php?id=<?php echo $row->product_id; ?>" class="btn btn-info btn-sm" title="Transférer/Ajuster Stock"><i class="fa fa-plus"></i></a>
                                                    <button type="button" class="btn btn-danger btn-sm delete-shop-item-btn"
                                                        data-id="<?php echo $row->product_id; ?>"
                                                        data-name="<?php echo htmlspecialchars($row->product_name); ?>"
                                                        data-shop="<?php echo htmlspecialchars($shop); ?>"
                                                        title="Supprimer du Magasin"><i class="fa fa-trash"></i></button>
                                                <?php
                                                }
                                                ?>

                                                <a href="view_product_shop.php?id=<?php echo $row->product_id; ?>&shop=<?php echo htmlspecialchars($row->shop_code); ?>" class="btn btn-default btn-sm" title="Voir Détails"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
    </section>
</div>

<div class="modal fade" id="massTransferModal" tabindex="-1" role="dialog" aria-labelledby="massTransferModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="massTransferModalLabel"><i class="fa fa-truck"></i> Transfert de Stock Entrepôt vers Boutique **<?php echo htmlspecialchars($shop); ?>**</h4>
            </div>
            <form action="product_shop_item.php" method="POST" name="form_mass_transfer" onsubmit="return confirmMassTransfer();" autocomplete="off">
                <div class="modal-body">
                    <input type="hidden" name="shop_code_target" value="<?php echo htmlspecialchars($shop); ?>">
                    <div class="row" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mass_shipment_date">Date d'Expédition <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="mass_shipment_date" id="mass_shipment_date" required value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mass_notes">Notes d'Expédition (Optionnel)</label>
                                <textarea class="form-control" name="mass_notes" id="mass_notes" rows="3" placeholder="Réf. Bon de sortie, etc."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="product_code_input_transfer">Saisir/Scanner Code Produit de l'Entrepôt</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="product_code_input_transfer" placeholder="Entrez le code produit ou SKU">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="button" id="add_transfer_line_btn"><i class="fa fa-search"></i> Ajouter Ligne</button>
                                    </span>
                                </div>
                                <span class="help-block text-muted">Affiche uniquement les produits existant dans le magasin actuel (pour la mise à jour du stock boutique) et ayant du stock à l'entrepôt principal.</span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="mass_transfer_table">
                            <thead>
                                <tr class="bg-primary">
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 25%;">Produit (Code)</th>
                                    <th style="width: 15%;">Stock Entrepôt</th>
                                    <th style="width: 15%;">Stock Boutique</th>
                                    <th style="width: 15%;">Qté à Transférer <span class="text-danger">*</span></th>
                                    <th style="width: 5%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="transfer_lines_container">
                                <tr id="no-transfer-item-row">
                                    <td colspan="6" class="text-center text-muted">Utilisez le champ ci-dessus pour ajouter des produits par code.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary" name="mass_transfer_stock" id="submit_mass_transfer" disabled>
                        <i class="fa fa-share-square-o"></i> Confirmer Transferts
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // NEW FUNCTION: Open Print Window as a pseudo-desktop app
    function openPrintWindow(status) {
        var url = 'print_shop_item_list.php?status=' + status;
        var windowName = 'PrintProductList';
        var features = 'width=800,height=600,scrollbars=yes,resizable=yes,location=no,menubar=no,toolbar=no,status=no';
        window.open(url, windowName, features);
    }

    // NEW FUNCTION: Check if mass transfer table has items
    function checkMassTransferTable() {
        const rowCount = $('#transfer_lines_container tr').not('#no-transfer-item-row').length;
        $('#submit_mass_transfer').prop('disabled', rowCount === 0);
        $('#no-transfer-item-row').toggle(rowCount === 0);
    }

    /**
     * Confirms the mass stock transfer before submission.
     * @returns {boolean} True if the user confirms, false otherwise.
     */
    function confirmMassTransfer() {
        const rowCount = $('#transfer_lines_container tr').not('#no-transfer-item-row').length;
        if (rowCount === 0) {
            swal("Attention", "Veuillez ajouter au moins un produit pour le transfert.", "warning");
            return false;
        }

        let totalQuantity = 0;
        let valid = true;

        $('#transfer_lines_container input[name$="[quantity]"]').each(function() {
            const qty = parseInt($(this).val());
            const max_qty = parseInt($(this).attr('max'));
            if (isNaN(qty) || qty < 1) {
                valid = false;
            }
            if (qty > max_qty) {
                valid = false;
                alert(`Erreur: La quantité de ${qty} pour le produit est supérieure au stock entrepôt disponible (${max_qty}).`);
                return false; // Stop iteration
            }
            totalQuantity += qty;
        });

        if (!valid) {
            swal("Erreur", "Veuillez vérifier les quantités saisies. Elles doivent être valides et ne pas dépasser le stock entrepôt.", "error");
            return false;
        }

        const confirmationMessage = `CONFIRMER LE TRANSFERT :\n\nÊtes-vous sûr de vouloir transférer **${totalQuantity}** unité(s) pour **${rowCount}** produit(s) de l'entrepôt principal à la boutique **<?php echo htmlspecialchars($shop); ?>**?`;

        return confirm(confirmationMessage);
    }

    $(document).ready(function() {
        // Initialisation de DataTables et SweetAlert pour la suppression (inchangé)
        if ($.fn.DataTable) {
            $('#myProduct').DataTable({
                "order": [
                    [0, "asc"]
                ]
            });
        }

        $('.delete-shop-item-btn').on('click', function(e) {
            e.preventDefault();
            var productId = $(this).data('id');
            var productName = $(this).data('name');
            var shopCode = $(this).data('shop');
            var deleteUrl = 'product_shop_item.php?id=' + productId;

            swal({
                    title: "Êtes-vous sûr(e)?",
                    text: "Voulez-vous supprimer le produit " + productName + " du magasin " + shopCode + " ? Cette action retire l'article de la liste des produits disponibles pour cette boutique.",
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

        // Clear modal content/form on close (for mass transfer modal)
        $('#massTransferModal').on('hidden.bs.modal', function() {
            $('#transfer_lines_container').empty().html('<tr id="no-transfer-item-row"><td colspan="6" class="text-center text-muted">Utilisez le champ ci-dessus pour ajouter des produits par code.</td></tr>');
            $(this).find('form').trigger('reset');
            $('#mass_shipment_date').val('<?php echo date('Y-m-d'); ?>');
            checkMassTransferTable();
        });


        // --- Mass Transfer Modal Logic ---

        let transfer_line_counter = 0;
        const targetShopCode = '<?php echo $shop; ?>';

        $('#add_transfer_line_btn').on('click', function() {
            const product_code = $('#product_code_input_transfer').val().trim();

            if (product_code === '') {
                swal("Attention", "Veuillez entrer un code produit ou SKU.", "warning");
                return;
            }

            // Check if product already exists in the list
            if ($(`#code_transfer_input_${product_code.replace(/[^a-zA-Z0-9]/g, '')}`).length > 0) {
                swal("Attention", `Le produit avec le code ${product_code} est déjà dans la liste de transfert.`, "warning");
                $('#product_code_input_transfer').val('');
                return;
            }

            // AJAX to fetch product data from main warehouse AND shop item
            $.ajax({
                url: 'fetch_product_main_and_shop.php', // NEW file to create
                method: 'GET',
                dataType: 'json',
                data: {
                    code: product_code,
                    shop: targetShopCode
                },
                beforeSend: function() {
                    $('#add_transfer_line_btn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Recherche...');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        transfer_line_counter++;
                        const data = response.data;
                        const unique_id_part = data.product_code.replace(/[^a-zA-Z0-9]/g, '');

                        // Determine the maximum stock that can be transferred
                        const maxTransfer = data.main_stock > 0 ? data.main_stock : 0;
                        const stockMainLabel = maxTransfer > 0 ? `<span class="label label-success">${maxTransfer}</span>` : `<span class="label label-danger">0</span>`;
                        const stockShopLabel = data.shop_stock >= data.shop_min_stock ? `<span class="label label-primary">${data.shop_stock}</span>` : `<span class="label label-warning">${data.shop_stock}</span>`;

                        // Create the new table row
                        const newRow = `
                            <tr id="row_transfer_${unique_id_part}">
                                <td>${transfer_line_counter}</td>
                                <td>
                                    ${data.product_name} (${data.product_code})
                                    <input type="hidden" name="transfers[${transfer_line_counter}][product_id]" value="${data.product_id}" />
                                    <input type="hidden" name="transfers[${transfer_line_counter}][product_code]" value="${data.product_code}" />
                                    <input type="hidden" id="code_transfer_input_${unique_id_part}" value="${data.product_code}" />
                                </td>
                                <td>${stockMainLabel}</td>
                                <td>${stockShopLabel}</td>
                                <td>
                                    <input type="number" min="1" step="1" class="form-control" 
                                        name="transfers[${transfer_line_counter}][quantity]" required placeholder="Qté" 
                                        max="${maxTransfer}"
                                        style="max-width: 120px;" value="1" />
                                    <span class="text-danger small" id="error_${unique_id_part}"></span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-xs remove-transfer-line" data-unique-id="${unique_id_part}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        `;

                        $('#transfer_lines_container').append(newRow);
                        $('#product_code_input_transfer').val(''); // Clear input
                        checkMassTransferTable();

                    } else {
                        swal("Erreur", response.message, "error");
                    }
                },
                error: function(jqXHR) {
                    const error = jqXHR.responseJSON || {
                        message: "Problème de communication avec le serveur."
                    };
                    swal("Erreur", error.message, "error");
                },
                complete: function() {
                    $('#add_transfer_line_btn').prop('disabled', false).html('<i class="fa fa-search"></i> Ajouter Ligne');
                }
            });
        });

        // Remove Line Button Handler
        $('#transfer_lines_container').on('click', '.remove-transfer-line', function() {
            const uniqueId = $(this).data('unique-id');
            $(`#row_transfer_${uniqueId}`).remove();
            checkMassTransferTable();
        });

        // Live validation for input quantity
        $('#transfer_lines_container').on('input', 'input[name$="[quantity]"]', function() {
            const qty = parseInt($(this).val());
            const maxQty = parseInt($(this).attr('max'));
            const errorId = `error_${$(this).closest('tr').find('.remove-transfer-line').data('unique-id')}`;

            if (isNaN(qty) || qty < 1) {
                $(`#${errorId}`).text("Qté min 1");
            } else if (qty > maxQty) {
                $(`#${errorId}`).text(`Max: ${maxQty}`);
            } else {
                $(`#${errorId}`).text("");
            }
        });
    });
</script>

<?php
include_once 'inc/footer_all.php';
?>