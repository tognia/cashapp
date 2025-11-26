<?php
include_once 'db/connect_db.php';
if ($_SESSION['role'] == "Admin") {
    include_once 'inc/header_all.php';
} else if ($_SESSION['role'] == "Operator") {
    include_once 'inc/header_all_operator.php';
} else {

    header('refresh:2;index.php');
}


require_once("include/functions_panier.php");

error_reporting(0);

if (isset($_GET['id'])) {


    include("include/connect_db.php");

    //On vérifie la connexion
    if ($conn->connect_error) {
        die('Erreur : ' . $conn->connect_error);
    }

    //// DELETE ORDER ///////////////////////////

    if (isset($_GET["delete"])) {


        $id = $_GET['id'];


        $delete_query1 = "UPDATE tbl_invoice_detail_client  SET Status = 'deleted' WHERE invoice_id = '$id' ";
        $delete_query = "UPDATE tbl_invoice_client  SET Status = 'deleted' WHERE invoice_id = '$id' ";

        if (mysqli_query($conn, $delete_query) && mysqli_query($conn, $delete_query1)) {
            echo '<script type="text/javascript">
            jQuery(function validation(){
            swal("Info", "Commande supprimee avec success", "info", {
            button: "Continue",
                });
            });
            </script>';
        } else {
            echo " ECHEC ANNULATION";
        }
    }

    //// END DELETE ORDER ///////////////////////////


    /////////////////// *********  DELIVER THE ORDER ************ \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ 


    if (isset($_GET["delivered"])) {


        $id = $_GET['id'];

        $delivered_query1 = "UPDATE tbl_invoice_detail_client  SET Status = 'delivered' WHERE invoice_id = '$id' ";
        $delivered_query = "UPDATE tbl_invoice_client  SET Status = 'delivered' WHERE invoice_id = '$id' ";


        if (mysqli_query($conn, $delivered_query) && mysqli_query($conn, $delivered_query1)) {
            echo '<script type="text/javascript">
                        jQuery(function validation(){
                        swal("Info", "Commande Livree avec success", "info", {
                        button: "Continue",
                            });
                        });
                        </script>';


            ////////////////////////  MISE A JOUR STOCK \\\\\\\\\\\\\\\\\\\\\\\\\\\


            $s_tb_dtail = $pdo->prepare("SELECT * FROM tbl_invoice_detail_client WHERE invoice_id='$id'");

            //$select = $pdo->prepare("SELECT * FROM tbl_invoice_client WHERE Status <> 'deleted'  ORDER BY invoice_id DESC");

            $s_tb_dtail->execute();
            while ($row_tb = $s_tb_dtail->fetch(PDO::FETCH_OBJ)) {

                $n = $row_tb->product_name;
                $q = $row_tb->qty;

                $sel = $pdo->prepare("select * from tbl_product where product_name='$n'");
                $sel->execute();
                $row1 = $sel->fetch(PDO::FETCH_ASSOC);

                $stock = $row1['stock'];

                $prod = $row1['product_id'];

                $rem_qty = $stock - $q;


                if ($rem_qty < 0) {
                    echo '<script type="text/javascript">
                                    jQuery(function validation(){
                                    swal("Warning", "Stock Insuffisant!!!!!", "warning", {
                                    button: "Continue",
                                        });
                                    });
                                    </script>';
                } else {
                    $update = $pdo->prepare("UPDATE tbl_product SET stock = '$rem_qty' WHERE product_id='" . $prod . "'");
                    $update->execute();
                }
            }



            ////////////////////////  FIN MISE A JOUR STOCK \\\\\\\\\\\\\\\\\\\\\\\\\\\



            $client = $_GET["id_client"];
            $sel = $pdo->prepare("select * from users where username='$client'");
            $sel->execute();
            $row1 = $sel->fetch(PDO::FETCH_ASSOC);

            //echo $row->id_client."_".$row1['firstname']." ".$row1['middlename']." ".$row1['lastname'];
            $_SESSION["email_client"] = $row1['email'];
            $_SESSION["id_client"] = $row1['username'];
            $_SESSION["name_client"] = $row1['firstname'] . " " . $row1['middlename'] . " " . $row1['lastname'];

            $_SESSION["delivered_invoice"] = $id;


            echo "<script type=\"text/javascript\">
                                        window.open('misc/nota_delivered.php?id=" . $id . "', '_blank')
                                    </script>";

            sleep(10);


            include("include/notif_PDF_orderdelivered.php");

            $_SESSION['delivered_order'] == 1;
            echo '<script>location.href="orderclient.php";</script>';
        } else {
            echo " ECHEC LIVRAISON";
        }
    }


    ///////////////////***  END DELIVER THE ORDER ****** \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\  






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
            <div class="box-header with-border">
                <h3 class="box-title">Liste Commandes</h3>


                <a href="orderclient.php?status=delivered" class="btn btn-success btn-sm">Commandes Livrees</a>
                <a href="orderclient.php?status=paid" class="btn btn-warning btn-sm">Commandes Payees</a>
                <a href="orderclient.php?status=ordered" class="btn btn-primary btn-sm">Commandes En Cours</a>

                <a href="orderclient.php?status=deleted" class="btn btn-danger btn-sm">Commandes Supprimees</a>

                <a href="orderclient.php?status=all" class="btn btn-secondary btn-sm">Toutes les Commandes</a>
            </div>
            <div class="box-body">
                <div style="overflow-x:auto;">
                    <table class="table table-striped" id="myProduct">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Num Invoice</th>
                                <th>ID Client</th>
                                <th>NOM Client</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $letotal = 0;
                            //$u = $_SESSION['username'];

                            if ($_GET['status'] == "ordered") {
                                $select = $pdo->prepare("SELECT * FROM tbl_invoice_client WHERE Status = 'ordered' ORDER BY invoice_id DESC");
                            } else if ($_GET['status'] == "paid") {
                                $select = $pdo->prepare("SELECT * FROM tbl_invoice_client WHERE Status = 'paid' ORDER BY invoice_id DESC");
                            } else if ($_GET['status'] == "deleted") {
                                $select = $pdo->prepare("SELECT * FROM tbl_invoice_client WHERE Status = 'deleted' ORDER BY invoice_id DESC");
                            } else if ($_GET['status'] == "delivered") {
                                $select = $pdo->prepare("SELECT * FROM tbl_invoice_client WHERE Status = 'delivered' ORDER BY invoice_id DESC");
                            } else {
                                $select = $pdo->prepare("SELECT * FROM tbl_invoice_client ORDER BY invoice_id DESC");
                            }

                            //$select = $pdo->prepare("SELECT * FROM tbl_invoice_client WHERE Status <> 'deleted'  ORDER BY invoice_id DESC");

                            $select->execute();
                            while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row->invoice_id; ?></td>
                                    <td><?php echo $row->id_client; ?></td>
                                    <td class="text-uppercase"><?php echo $row->name_client; ?></td>
                                    <td><?php echo $row->order_date; ?></td>
                                    <td><?php echo number_format($row->total, 0, null, " ") . " FCFA"; ?></td>

                                    <td> <?php if ($row->Status == "deleted") { ?>
                                            <span class="label label-danger"><?php echo $row->Status; ?></span>
                                        <?php } elseif ($row->Status == "ordered") { ?>
                                            <span class="label label-primary"><?php echo $row->Status; ?></span>
                                        <?php } elseif ($row->Status == "paid") { ?>
                                            <span class="label label-warning"><?php echo $row->Status; ?></span>
                                        <?php } else { ?>
                                            <span class="label label-success"><?php echo $row->Status; ?></span>
                                        <?php } ?>

                                    </td>
                                    <td>
                                        <?php if ($_SESSION['role'] == "Admin") {

                                            if ($row->Status == "ordered") {

                                        ?>

                                                <a href="pay_order.php?id=<?php echo $row->invoice_id; ?>&pay=pay&orderdate=<?php echo date("d-m-Y"); ?>&timeorder=<?php echo date('H:i') ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Valider Commande">

                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cash-coin" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0z" />
                                                        <path d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1h-.003zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195l.054.012z" />
                                                        <path d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083c.058-.344.145-.678.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1H1z" />
                                                        <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 5.982 5.982 0 0 1 3.13-1.567z" />
                                                    </svg>

                                                </a>

                                                <a href="orderclient.php?id=<?php echo $row->invoice_id; ?>&delete=del" onclick="return confirm('Confirmer Annualtion Commande ?')" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Supprimer commande"><i class="fa fa-trash"></i></a>

                                            <?php

                                            }

                                            if ($row->Status == "paid") {

                                                $_SESSION['delivered_order'] = 0;

                                            ?>


                                                <a href="orderclient.php?id=<?php echo $row->invoice_id; ?>&delivered=delivered&id_client=<?php echo $row->id_client; ?>" onclick="return confirm('Confirmer Livraison Commande ?')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Livrer commande">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-all" viewBox="0 0 16 16">
                                                        <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z" />
                                                        <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z" />
                                                    </svg>
                                                </a>


                                        <?php

                                            }
                                        }

                                        ?>

                                        <a href="misc/notaclient.php?id=<?php echo $row->invoice_id; ?>" target="_blank" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimer Facture"><i class="fa fa-print"></i></a>
                                    </td>
                                </tr>
                            <?php

                                $letotal = $letotal + $row->total;
                            }
                            ?>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <table align="center">
            <tr align="center">
                <td colspan="4" align="right">
                    <h3>TOTAL :</h3>
                </td>
                <td colspan="3">
                    <h3><?php echo number_format($letotal, 0, null, " ") . " FCFA"; ?></h3>
                </td>
            </tr>
        </table>

    </section>
    <!-- /.content -->
</div>
<div>


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