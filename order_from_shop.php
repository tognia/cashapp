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

$id = $_GET['id'];

$today = date("j, n, Y");
$today = date("Y-m-d");
$magasin = $_SESSION['magasin'];

$delete_query = "DELETE tbl_commandes_magasin , tbl_commandes_magasin_details FROM tbl_commandes_magasin INNER JOIN tbl_commandes_magasin_details ON tbl_invoice.invoice_id =
    tbl_commandes_magasin_details.invoice_id WHERE tbl_commandes_magasin.invoice_id=$id";
$delete = $pdo->prepare($delete_query);
if ($delete->execute()) {
    echo '<script type="text/javascript">
            jQuery(function validation(){
            swal("Info", "La transaction supprimee", "info", {
            button: "Continue",
                });
            });
            </script>';
}
?>

<html>

<head>

</head>

</html>


<?php

include("include/stat_commandes.php");

?>

<!-- Main content -->
<section class="content container-fluid">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Liste des transactions</h3>

            <?php
            if ($_SESSION['count_alert'] > 0 && isset($_POST['save_order'])) {

                echo "PRODUITS STOCK ALERT : " . $_SESSION['count_alert'];

                for ($j = 0; $j < $_SESSION['count_alert']; $j++) {

                    echo $j . "__";

                    echo $_SESSION['tab_alert']['id'][$j];
                    echo $_SESSION['tab_alert']['code'][$j];
                    echo $_SESSION['tab_alert']['name'][$j];
                    echo $_SESSION['tab_alert']['stock'][$j];
                    echo $_SESSION['tab_alert']['stock_min'][$j];

                    echo "__" . $_SESSION['em'];
                }

                include("include/notif_PDF_email_stock_alert.php");
            }


           // if ($_SESSION['role'] != "Admin" && $_SESSION['role'] != "Responsable") {

            ?>

                <a href="commande_magasin.php" class="btn btn-success btn-sm pull-right">Nouvelle Commande</a>

            <?php //} ?>

        </div>
        <div class="box-body">
            <div style="overflow-x:auto;">
                <table class="table table-striped" id="myOrder">
                    <thead>
                        <tr>
                            <th style="width:20px;">No</th>
                            <th style="width:100px;">Operateur</th>
                            <th style="width:100px;">Date</th>
                            <th style="width:100px;">Montant</th>
                            <th style="width:50px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        //$select = $pdo->prepare("SELECT * FROM tbl_invoice ORDER BY invoice_id DESC");

                        if (isset($_POST['date_filter'])) {

                            $leshop = $_POST['shop'];



                            if ($_SESSION['role'] == "Admin" && $leshop != "all") {
                                $select = $pdo->prepare("SELECT * FROM tbl_commandes_magasin WHERE cashier_name IN (SELECT username FROM tbl_user WHERE magasin = '$leshop' ) AND  order_date BETWEEN :fromdate AND :todate ORDER BY invoice_id");
                            } elseif ($_SESSION['role'] == "Admin" && $leshop == "all") {

                                $select = $pdo->prepare("SELECT * FROM tbl_commandes_magasin WHERE order_date BETWEEN :fromdate AND :todate ORDER BY invoice_id");
                            } else {
                                $select = $pdo->prepare("SELECT * FROM tbl_commandes_magasin WHERE (order_date BETWEEN :fromdate AND :todate) AND cashier_name IN (SELECT username FROM tbl_user WHERE magasin = '$magasin' )  ORDER BY invoice_id");
                            }

                            $select->bindParam(':fromdate', $_POST['date_1']);
                            $select->bindParam(':todate', $_POST['date_2']);


                        ?>


                            <div><?php echo $leshop; ?></div>


                        <?php



                        } else {


                            if ($_SESSION['role'] == "Admin") {
                                $select = $pdo->prepare("SELECT * FROM tbl_commandes_magasin ORDER BY invoice_id DESC");
                            } else {


                                $select = $pdo->prepare("SELECT * FROM tbl_commandes_magasin WHERE cashier_name IN (SELECT username FROM tbl_user WHERE magasin = '$magasin') ORDER BY invoice_id DESC");
                            }
                        }

                        $select->execute();
                        while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td class="text-uppercase"><?php echo $row->cashier_name; ?></td>
                                <td><?php echo $row->order_date; ?></td>
                                <td><?php echo number_format($row->total); ?>&nbsp; FCFA</td>
                                <td>
                                    <?php if ($_SESSION['role'] == "Admin" || ($_SESSION['role'] == "Responsable" && $row->order_date == $today)) { ?>
                                        <a href="order_from_shop.php" onclick="return confirm('Supprimer la transaction?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    <?php } ?>
                                    <a href="misc/print_command.php?id=<?php echo $row->invoice_id; ?>" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-print"></i></a>
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
<!-- /.content-wrapper -->

<script>
    $(document).ready(function() {
        $('#myOrder').DataTable();
    });
</script>


<script>
    //Date picker
    $('#datepicker_1').datepicker({
        autoclose: true
    });
    //Date picker
    $('#datepicker_2').datepicker({
        autoclose: true
    });

    $(document).ready(function() {
        $('#mySalesReport').DataTable();
    });
</script>

<script>
    var ctx = document.getElementById('myChart');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($date); ?>,
            datasets: [{
                label: 'Total Pendapatan',
                data: <?php echo json_encode($total); ?>,
                backgroundColor: 'rgb(13, 192, 58)',
                borderColor: 'rgb(32, 204, 75)',
                borderWidth: 1
            }]
        },
        options: {}
    });
</script>

<style>
    .color {
        backgroundColor: rgb(120, 102, 102);
    }
</style>


<script>
    var ctx = document.getElementById('myBestSellItem');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($pname); ?>,
            datasets: [{
                label: 'Total Produit Best Sellers',
                data: <?php echo json_encode($qty); ?>,
                backgroundColor: 'rgb(120,112,175)',
                borderColor: 'rgb(255,255,255)',
                borderWidth: 1
            }]
        },
        options: {}
    });
</script>

<?php
include_once 'inc/footer_all.php';
?>