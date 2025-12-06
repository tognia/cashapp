<?php
include_once 'db/connect_db.php';
if ($_SESSION['user_name'] == "") {
    header('location:index.php');
    exit(); // Ajouté exit() après la redirection
} else {
    if ($_SESSION['role'] == "Admin") {
        include_once 'inc/header_all.php';
    } else {
        include_once 'inc/header_all_operator.php';
    }
}
error_reporting(0);

// Initialisation de $shop
if ($_SESSION['role'] != "Admin") {
    $shop = $_SESSION['magasin'];
} else {
    // Si Admin, initialiser avec la session si elle existe, sinon laisser vide (ou utiliser une valeur par défaut si possible)
    $shop = $_SESSION['select_shop'] ?? '';
}

if (isset($_POST['select_shop'])) {
    $_SESSION['select_shop'] = $_POST['shop'];
    $shop = $_POST['shop']; // Mise à jour immédiate de $shop
}

// Mise à jour de $shop pour Admin/Storekeeper/Responsable après la sélection
if (($_SESSION['role'] == "Admin" || $_SESSION['role'] == "storekeeper" || $_SESSION['role'] == "Responsable") && isset($_SESSION['select_shop'])) {
    $shop = $_SESSION['select_shop'];
}

$id = $_GET['id'] ?? null;

// --- CORRECTION DE SÉCURITÉ : Utilisation de Prepared Statement pour DELETE ---
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

$statusName = "";

// --- Start of Database Query Modification (Préparez la requête pour les variables dans WHERE) ---

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

switch ($status) {
    case 'all':
        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE $base_where_clause");
        break;
    case 'ok':
        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE tsi.stock > tsi.min_stock AND $base_where_clause");
        $statusName = " en stock";
        break;
    case 'alert':
        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE tsi.stock <= tsi.min_stock AND tsi.stock <> 0 AND $base_where_clause");
        $statusName = " stock alerte";
        break;
    case 'null':
        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE tsi.stock = 0 AND $base_where_clause");
        $statusName = " stock null";
        break;
    default:
        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE $base_where_clause");
        break;
}

// BINDING DES PARAMÈTRES (Sécurité accrue en utilisant des déclarations préparées même pour SELECT)
// Si $shop est défini, nous bindons les paramètres
if ($shop && isset($select)) {
    // Le paramètre :shop_param est utilisé dans la sous-requête, :shop_where dans la clause WHERE principale
    $select->bindParam(':shop_param', $shop);
    $select->bindParam(':shop_where', $shop);
}


// --- End of Database Query Modification ---
?>
<html>

<head>
</head>

</html>

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
                                <label for="shop" style="margin-right: 5px; line-height: 30px;">Magasin</label>
                                <select class="form-control" name="shop" required style="width: auto; margin-right: 5px;">
                                    <?php
                                    $select1 = $pdo->prepare("SELECT code_agence, libelle_agence FROM agence");
                                    $select1->execute();
                                    while ($row = $select1->fetch(PDO::FETCH_ASSOC)) {
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
                    <h3 class="box-title">Liste Produits <?php echo $statusName; ?> Magasin : <?php echo htmlspecialchars($shop); ?></h3>
                    <?php
                    if ($_SESSION['role'] == "Responsable") {
                    ?>
                        <div class="pull-right">
                            <a href="edit_stock_shop_validation.php" class="btn btn-success btn-sm"><i class="fa fa-cubes"></i> Réceptionner Stocks En Attente</a>
                        </div>
                    <?php
                    } ?>

                </div>
                <div class="box-body">
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
                                // Exécuter la requête UNIQUEMENT si $shop est défini et la préparation a réussi
                                if ($shop && isset($select)) {
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
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </section>
</div>
<script>
    // NEW FUNCTION: Open Print Window as a pseudo-desktop app
    function openPrintWindow(status) {
        var url = 'print_shop_item_list.php?status=' + status;
        var windowName = 'PrintProductList';

        // Define window features to remove browser chrome
        var features = 'width=800,height=600,scrollbars=yes,resizable=yes,location=no,menubar=no,toolbar=no,status=no';

        // Open the window
        window.open(url, windowName, features);
    }

    $(document).ready(function() {
        // Initialiser DataTables
        if ($.fn.DataTable) {
            $('#myProduct').DataTable({
                "order": [
                    [0, "asc"]
                ]
            });
        }

        // SweetAlert pour la confirmation de suppression
        $('.delete-shop-item-btn').on('click', function(e) {
            e.preventDefault();
            var productId = $(this).data('id');
            var productName = $(this).data('name');
            var shopCode = $(this).data('shop');
            var deleteUrl = 'product_shop_item.php?id=' + productId; // $shop est déjà dans la session ou défini

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
    });
</script>

<?php
include_once 'inc/footer_all.php';
?>