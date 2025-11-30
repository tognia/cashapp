<?php
include_once 'db/connect_db.php';
if ($_SESSION['user_name'] == "") {
    header('location:index.php');
} else {
    if ($_SESSION['role'] == "Admin") {
        include_once 'inc/header_all.php';
    } else {
        include_once 'inc/header_all_operator.php';
    }
}
error_reporting(0);
if ($_SESSION['role'] != "Admin") {
    $shop = $_SESSION['magasin'];
}

if (isset($_POST['select_shop'])) {

    $_SESSION['select_shop'] = $_POST['shop'];
}

if (($_SESSION['role'] == "Admin" || $_SESSION['role'] == "storekeeper" || $_SESSION['role'] == "Responsable") && $_SESSION['select_shop'] != "") {

    $shop = $_SESSION['select_shop'];
}

$id = $_GET['id'];

// NOTE: This DELETE query is vulnerable to SQL injection as $shop is not properly sanitized/prepared.
$delete = $pdo->prepare("DELETE FROM tbl_shop_item WHERE shop_code= '$shop' AND product_id=" . $id);

if ($delete->execute()) {
    echo '<script type="text/javascript">
            jQuery(function validation(){
            swal("Info", "Product Has Been Deleted", "info", {
            button: "Continue",
                });
            });
            </script>';
}

$statusName = "";

// --- Start of Database Query Modification ---

// SQL Subquery to calculate the total delivered quantity for the current shop ($shop)
$delivered_quantity_subquery = "
    (
        SELECT COALESCE(SUM(tps.shipped_quantity), 0)
        FROM tbl_product_shipment tps
        WHERE tps.product_code = tsi.product_code
        AND tps.delivery_status = 'Delivered'
        AND tps.code_agence = '$shop'
    ) AS total_delivered
";

// Base SELECT statement structure
$base_select_columns = "tsi.*, $delivered_quantity_subquery";
$base_from_table = "tbl_shop_item tsi";
$base_where_clause = "tsi.shop_code = '$shop'";


if (isset($_GET['status'])) {

    if ($_GET['status'] == "all") {

        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE $base_where_clause");
    } else if ($_GET['status'] == "ok") {

        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE tsi.stock > tsi.min_stock AND $base_where_clause");
        $statusName = " en stock";
    } else if ($_GET['status'] == "alert") {

        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE tsi.stock <= tsi.min_stock AND tsi.stock <> 0 AND $base_where_clause");
        $statusName = " stock alerte";
    } else if ($_GET['status'] == "null") {

        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE tsi.stock = 0 AND $base_where_clause");
        $statusName = " stock null";
    } else {

        // Corrected logic for an unknown status parameter
        $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE $base_where_clause");
    }
} else {

    $select = $pdo->prepare("SELECT $base_select_columns FROM $base_from_table WHERE $base_where_clause ");
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
                    <h3 class="box-title"> | Boutique : <?php echo $_SESSION['magasin']; ?></h3>

                    <a href="product_shop_item.php?status=ok" class="btn btn-primary btn-sm">PRODUITS - STOCK OK </a>
                    <a href="product_shop_item.php?status=alert" class="btn btn-warning btn-sm">PRODUITS - STOCK ALERTE</a>

                    <a href="product_shop_item.php?status=null" class="btn btn-danger btn-sm">PRODUITS - STOCK NULL</a>

                    <a href="product_shop_item.php?status=all" class="badge badge-info bg-dark btn-sm">TOUS LES PRODUITS</a>

                    <?php if ($_SESSION["role"] == "Admin" || $_SESSION["role"] == "storekeeper") { ?>

                        <form action="" method="POST">

                            <label for="">Magasin</label>
                            <select class="form-control" name="shop" required>
                                <?php
                                $select1 = $pdo->prepare("SELECT * FROM agence");
                                $select1->execute();
                                while ($row = $select1->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row)
                                ?>
                                    <option value="<?php echo $row['code_agence']; ?>"><?php echo $row['code_agence'] . " " . $row['libelle_agence']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <input type="submit" name="select_shop" value="SELECTIONNER UN MAGASIN">


                        </form>

                    <?php } ?>


                </div>


                <div class="box-header with-border">
                    <h3 class="box-title">Liste Produits <?php echo $statusName; ?> Magasin : <?php echo $shop; ?></h3>
                    <?php
                    if ($_SESSION['role'] == "Responsable") {
                    ?>
                        <button type="button" class="btn btn-primary btn-lg pull-right" data-toggle="modal" data-target="#receiveStockModal">
                            <i class="fa fa-cubes"></i> <a href="edit_stock_shop_validation.php" class="btn btn-success btn-sm pull-right">Réceptionner Stocks En Attente</a>
                        </button>
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
                                    <th>Categorie</th>
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
                                        <td><?php echo $row->product_name; ?></td>
                                        <td><?php echo $row->product_category; ?></td>
                                        <td><?php echo $row->product_code; ?></td>
                                        <td><?php echo $row->product_sku; ?></td>
                                        <td><?php echo $row->description; ?></td>
                                        <td><img src="upload/<?php echo $row->img; ?>" width="50px" height="50" /></td>
                                        <td> <?php echo number_format($row->purchase_price, 0, null, " ") . " "; ?> FCFA</td>
                                        <td> <?php echo number_format($row->sell_price, 0, null, " ") . " "; ?> FCFA</td>
                                        <td> <?php if ($row->stock == "0") { ?>
                                                <span class="label label-danger"><?php echo $row->stock; ?></span>
                                            <?php } elseif ($row->stock <= $row->min_stock) { ?>
                                                <span class="label label-warning"><?php echo $row->stock; ?></span>
                                            <?php } else { ?>
                                                <span class="label label-primary"><?php echo $row->stock; ?></span>
                                            <?php } ?>
                                            <span class="label label-default"><?php echo $row->product_satuan; ?></span>
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
                                        <td><?php echo $row->supplier; ?></td>
                                        <td>

                                            <?php
                                            if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "storekeeper") {
                                            ?>
                                                <a href="edit_stock_shop.php?id=<?php echo $row->product_id; ?>" class="btn btn-info btn-sm"><i class="fa fa-plus"></i></a>
                                            <?php
                                            }
                                            ?>

                                            <a href="view_product_shop.php?id=<?php echo $row->product_id; ?>&shop=<?php echo $row->shop_code; ?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
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
<script>
    $(document).ready(function() {
        $('#myProduct').DataTable();
    });
</script>

<?php
include_once 'inc/footer_all.php';
?>