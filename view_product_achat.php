<?php
    include_once'db/connect_db.php';
    session_start();
    if($_SESSION['user_name']==""){
      header('location:index.php');
    }else{
      if($_SESSION['role']=="Admin"){
        //include_once'inc/header_all.php';
        require_once("include/header.php");
      }else{
          //include_once'inc/header_all_operator.php';
        require_once("include/header.php");
      }
    }
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Produit
      </h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-body">
              <?php
                $id = $_GET['id'];

                $select = $pdo->prepare("SELECT * FROM tbl_product WHERE product_id=$id");
                $select->execute();
                while($row = $select->fetch(PDO::FETCH_OBJ)){ ?>


  <section class="u-align-center u-clearfix u-grey-10 u-section-3" id="sec-19ca">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1"><!--products--><!--products_options_json--><!--{"type":"Recent","source":"","tags":"","count":""}--><!--/products_options_json-->
        <div class="u-expanded-width u-products u-products-1">

          


                  <div class="col-md-6">
                  <ul class="list-group">
                    <center><p class="list-group-item list-group-item-success"></p></center>
                    <img src="upload/<?php echo $row->img?>" alt="Product Image" class="img-responsive">
                  </ul>
                </div>

                <div class="col-md-6">
                  <ul class="list-group">

                    <center><p class="list-group-item list-group-item-success">Details Matériel</p></center>
                    <li class="list-group-item"> <b>Code Produit</b>     :<span class="label badge pull-right"><?php echo $row->product_code; ?></span></li>
                    <li class="list-group-item"><b>SKU</b>        :<span class="label label-primary pull-right"><?php echo $row->product_sku; ?></span></li>
                    <li class="list-group-item"><b>Libelle Produit</b>    :<span class="label label-info pull-right"><?php echo $row->product_name; ?></span></li>
                    <li class="list-group-item"><b>Categorie Produit</b>        :<span class="label label-primary pull-right"><?php echo $row->product_category; ?></span></li>
                    <li class="list-group-item"><b>Marque</b>        :<span class="label label-primary pull-right"><?php echo $row->product_brand; ?></span></li>
                    <li class="list-group-item"><b>QTE</b>        :<span class="label label-primary pull-right"><?php echo $_REQUEST['quantity']; ?></span></li>
                    
                                        
                  <center>
                  <div class="u-price-wrapper u-spacing-10"><!--product_old_price-->
                    <div class="u-hide-price u-old-price"></div><!--/product_old_price--><!--product_regular_price-->
                    <div class="u-price u-text-palette-1-base" style="font-size: 1.5rem; font-weight: 700;">PRIX : <?php echo number_format($row->sell_price); ?>&nbsp;FCFA<!--/product_regular_price_content--></div><!--/product_regular_price-->
                  </div>
                  </center>
                    
                    <li class="list-group-item"><b>Quantite en stock </b>          :<span class="label label-default pull-right"><?php echo $row->stock; ?></span></li>
                    
                    <li class="list-group-item"><b>Unite</b>               :<span class="label label-default pull-right"><?php echo $row->product_satuan; ?></span></li>
                    <li class="list-group-item"><b>Description</b>    :</li>
                    <li class="list-group-item col-md-12"><span class="text-muted"><?php echo $row->description ?></span></li>
                  </ul>
                </div>


           
          </div>
        </div>
      </section>
                
              <?php
                }
              ?>
            </div>
            <div class="box-footer">
                <a href="boutique.php" class="btn btn-warning">Retour A La Boutique</a>
            </div>

        </div>


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php
    include_once'inc/footer_all.php';
 ?>