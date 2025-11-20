<?php
    include_once'db/connect_db.php';
    session_start();
    if($_SESSION['user_name']==""){
        header('location:index.php');
    }else{
        if($_SESSION['role']=="Admin"){
          include_once'inc/header_all.php';
        }else{
            include_once'inc/header_all_operator.php';
        }
    }

    error_reporting(0);

    $id_agence = $_GET['id_agence'];
    $id_produit = $_GET['id_produit'];

    $delete = $pdo->prepare("DELETE FROM tbl_shop_product WHERE code_agence=".$id_agence." AND code_produit=".$id_produit);

    if($delete->execute()){
        echo'<script type="text/javascript">
            jQuery(function validation(){
            swal("Info", "Product Has Been Deleted", "info", {
            button: "Continue",
                });
            });
            </script>';
    }



    if(isset($_GET['status'])){

            if($_GET['status']=="all"){

                $select = $pdo->prepare("SELECT * FROM tbl_shop_product ORDER BY code_agence, code_produit");
            }


            else if($_GET['status']=="ok"){

                $select = $pdo->prepare("SELECT * FROM tbl_shop_product WHERE stock > stock_min");
            }


            else if($_GET['status']=="alert"){

                $select = $pdo->prepare("SELECT * FROM tbl_shop_product WHERE stock <= stock_min AND stock <> 0 ");
            }


            else if($_GET['status']=="null"){

                $select = $pdo->prepare("SELECT * FROM tbl_shop_product WHERE stock = 0 ");
            }


            else {

                $select = $pdo->prepare("SELECT * FROM tbl_shop_product");
            }


        }

        else {

            $select = $pdo->prepare("SELECT * FROM tbl_shop_product");
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
                <h3 class="box-title">Liste Commandes</h3>
                         
                <a href="product_shop.php?status=ok" class="btn btn-primary btn-sm">PRODUITS - STOCK OK </a>
                <!--<a href="product.php?status=delivered" class="btn btn-success btn-sm">Commandes Livrees</a>-->
                <a href="product_shop.php?status=alert" class="btn btn-warning btn-sm">PRODUITS - STOCK ALERTE</a>
                
                <a href="product_shop.php?status=null" class="btn btn-danger btn-sm">PRODUITS - STOCK NULL</a>

                <a href="product_shop.php?status=all" class="badge badge-info bg-dark btn-sm">TOUS LES PRODUITS</a>
            </div>


            <div class="box-header with-border">
                <h3 class="box-title">Liste Produits</h3>
                <a href="add_product.php" class="btn btn-success btn-sm pull-right">Nouveau Produit</a>
            </div>
            <div class="box-body">
                <div style="overflow-x:auto;">
                    <table class="table table-striped" id="myProduct">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Agence</th>
                                <th>Code Produit</th>
                                <th>Description Produuit</th>                                
                                <th>Stock</th>
                                <th>Stock Min</th>
                                <th>Prix de vente</th>
                                <th>Actions</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            //$select = $pdo->prepare("SELECT * FROM tbl_product");
                            $select->execute();
                            while($row=$select->fetch(PDO::FETCH_OBJ)){
                            ?>
                                <tr>
                                <td><?php echo $no++ ;?></td>
                                <td><?php echo $row->code_agence; ?></td>
                                <td><?php echo $row->code_produit; ?></td>
                                <td>


                                        <?php $cd = $row->code_produit;


                                            $select_prod = $pdo->prepare("SELECT * FROM tbl_product WHERE product_code='$cd'");
                                    $select_prod->execute();
                                    while($item = $select_prod->fetch(PDO::FETCH_OBJ)){ 

                                            echo $item->product_name;

                                        }

                                            ?>   
                                                                             

                                </td>
                                <td> <?php if($row->stock=="0"){ ?>
                                <span class="label label-danger"><?php echo $row->stock; ?></span>
                                <?php }elseif($row->stock<=$row->stock_min){ ?>
                                <span class="label label-warning"><?php echo $row->stock; ?></span>
                                <?php }else{ ?>
                                <span class="label label-primary"><?php echo $row->stock; ?></span>
                                <?php } ?>
                                <span class="label label-default"><?php //echo $row->product_satuan; ?></span>
                                </td>
                                <td><?php echo $row->stock_min; ?></td>
                                <td> <?php echo number_format($row->prix_vente,0,null," ")." "; ?> FCFA</td>
                                
                                <td>
                                    <?php if($_SESSION['role']=="Admin"){ ?>
                                    <a href="product.php?id=<?php echo $row->product_id; ?>"
                                    class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    <a href="edit_product.php?id=<?php echo $row->product_id; ?>" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
                                    <?php
                                    }
                                    ?>
                                    <a href="view_product.php?id=<?php echo $row->product_id; ?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
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
  $(document).ready( function () {
      $('#myProduct').DataTable();
  } );
  </script>

 <?php
    include_once'inc/footer_all.php';
 ?>