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

if (($_SESSION['role'] == "Admin" || $_SESSION['role'] == "Responsable") && $_SESSION['select_shop'] != "") {

    $shop = $_SESSION['select_shop'];
}

$id = $_GET['id'];

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

if (isset($_GET['status'])) {

    if ($_GET['status'] == "all") {

        $select = $pdo->prepare("SELECT * FROM tbl_shop_item WHERE shop_code= '$shop'");
    } else if ($_GET['status'] == "ok") {

        $select = $pdo->prepare("SELECT * FROM tbl_shop_item WHERE stock > min_stock AND shop_code= '$shop'");
        $statusName = " en stock";
    } else if ($_GET['status'] == "alert") {

        $select = $pdo->prepare("SELECT * FROM tbl_shop_item WHERE stock <= min_stock AND stock <> 0 AND shop_code= '$shop'");
        $statusName = " stock alerte";
    } else if ($_GET['status'] == "null") {

        $select = $pdo->prepare("SELECT * FROM tbl_shop_item WHERE stock = 0 AND shop_code= '$shop'");
        $statusName = " stock null";
    } else {

        $select = $pdo->prepare("SELECT * FROM tbl_shop_item AND shop_code=" . $shop);
    }
} else {

    $select = $pdo->prepare("SELECT * FROM tbl_shop_item WHERE shop_code= '$shop' ");
}





?>
<html>

<head>
    <!--<meta http-equiv="refresh" content="60">-->
</head>

</html>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">

            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"> | Boutique : <?php echo $_SESSION['magasin']; ?></h3>

                    <a href="product_shop_item.php?status=ok" class="btn btn-primary btn-sm">PRODUITS - STOCK OK </a>
                    <!--<a href="product.php?status=delivered" class="btn btn-success btn-sm">Commandes Livrees</a>-->
                    <a href="product_shop_item.php?status=alert" class="btn btn-warning btn-sm">PRODUITS - STOCK ALERTE</a>

                    <a href="product_shop_item.php?status=null" class="btn btn-danger btn-sm">PRODUITS - STOCK NULL</a>

                    <a href="product_shop_item.php?status=all" class="badge badge-info bg-dark btn-sm">TOUS LES PRODUITS</a>

                    <?php if ($_SESSION["role"] == "Admin") { ?>

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
                    <!--<a href="add_product_shop.php" class="btn btn-success btn-sm pull-right">Nouveau Produit</a>-->
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
                                    <th>Fournisseur</th>
                                    <th>Actions</th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                //$select = $pdo->prepare("SELECT * FROM tbl_shop_item");
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
                                        <td><?php echo $row->supplier; ?></td>
                                        <td>

                                            <?php
                                            if ($_SESSION['role'] == "Responsable") {
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
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    $(document).ready(function() {
        $('#myProduct').DataTable();
    });
</script>

<?php
include_once 'inc/footer_all.php';
?>