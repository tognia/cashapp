<?php
    include_once'db/connect_db.php';
    session_start();
    if($_SESSION['role']=="Admin"){
      //include_once'header_login.php';
      include_once'inc/header_all.php';
    }
    else if ($_SESSION['role']=="Operator") {
      include_once'inc/header_all_operator.php';
    }

    else {

      header('refresh:2;index.php');
        
    }


    require_once("include/functions_panier.php");

    error_reporting(0);

    if(isset($_GET['id'])){

    $id = $_GET['id'];


    $servername = 'localhost';
            $username = 'root';
            $password = '';

        //On établit la connexion
            $conn = new mysqli($servername, $username, $password,"bevilec");
            
            //On vérifie la connexion
            if($conn->connect_error){
                die('Erreur : ' .$conn->connect_error);
            }
                                                        /*UPDATE students
                                            SET city = 'Birmingham',
                                                student_rep = 15
                                            WHERE student_id > 20;*/

            $delete_query1 ="UPDATE tbl_invoice_detail_client  SET Status = 'deleted' WHERE invoice_id = '$id' ";
            $delete_query ="UPDATE tbl_invoice_client  SET Status = 'deleted' WHERE invoice_id = '$id' ";

    //$delete_query = "DELETE tbl_invoice , tbl_invoice_detail FROM tbl_invoice INNER JOIN tbl_invoice_detail ON tbl_invoice.invoice_id =    tbl_invoice_detail.invoice_id WHERE tbl_invoice.invoice_id=$id";
    //$delete = $pdo->prepare($delete_query);
    //if($delete->execute()){
    if(mysqli_query($conn, $delete_query) && mysqli_query($conn, $delete_query1)){
        echo'<script type="text/javascript">
            jQuery(function validation(){
            swal("Info", "Commande supprimee avec success", "info", {
            button: "Continue",
                });
            });
            </script>';
    }

    else { echo " ECHEC ANNULATION";}


    }
?>

<html>
<head>
<meta http-equiv="refresh" content="60">
</head>
</html>



<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">

            <div class="box-header with-border">
                <h3 class="box-title">Transactions</h3>
                
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $letotal=0;
                            //$u = $_SESSION['username'];
                            $select = $pdo->prepare("SELECT * FROM tbl_invoice_client WHERE status <> 'deleted'  ORDER BY invoice_id DESC");
                            $select->execute();
                            while($row=$select->fetch(PDO::FETCH_OBJ)){
                            ?>
                                <tr>
                                <td><?php echo $no++ ; ?></td>
                                <td ><?php echo $row->invoice_id; ?></td>
                                <td ><?php echo $row->id_client; ?></td>
                                <td class="text-uppercase"><?php echo $row->name_client; ?></td>
                                <td><?php echo $row->order_date; ?></td>
                                <td><?php echo number_format($row->total,0,null," ")." FCFA"; ?></td>
                                <td>
                                    <?php if($_SESSION['role']=="Admin"){ ?>

                                    <a href="order.php?id=<?php echo $row->invoice_id; ?>" onclick="return confirm('Confirmer Annualtion Commande ?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    <?php } ?>

                                    <a href="misc/notaclient.php?id=<?php echo $row->invoice_id; ?>" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-print"></i></a>
                                </td>
                                </tr>
                            <?php

                                    $letotal = $letotal + $row->total;
                            }
                            ?>
                            <tr>
                                <td colspan="4" align="right"><h3>TOTAL :</h3></td>
                                <td colspan="3"><h3><?php echo number_format($letotal,0,null," ")." FCFA"; ?></h3></td>
                            </tr>

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
  $(document).ready( function () {
      $('#myProduct').DataTable();
  } );
  </script>

 <?php
    include_once'inc/footer_all.php';
 ?>