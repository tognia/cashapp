<?php
    include_once'db/connect_db.php';
   if($_SESSION['user_name']==""){
      header('location:index.php');
  }else{
      if($_SESSION['role']=="Admin"){
        include_once'inc/header_all.php';
      }else{
          include_once'inc/header_all_operator.php';
      }
  }

    
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
      <div class="row">
        <!-- get alert stock -->
        <?php
        $select = $pdo->prepare("SELECT count(product_code) as total FROM tbl_product WHERE stock <= min_stock");
        $select->execute();
        $row=$select->fetch(PDO::FETCH_OBJ);
        $total1 = $row->total;
        ?>
        <!-- get alert notification -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-danger alert alert-danger"><i class="fa fa-archive"></i></span>

            <div class="info-box-content">
              <a href="product.php?status=alert"><span class="info-box-text">Produits | Stock Alerte</span>
              <?php if($total1==true){ ?>
              <span class="badge badge-danger alert alert-danger"><small><?php echo $row->total;?></small></span>
              <?php }else{?>
              <span class="info-box-text"><strong>RIEN A SIGNALER</strong></span>
              <?php }?>
            </a>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>


        <!-- get total products-->
        <?php
        $select = $pdo->prepare("SELECT count(product_code) as t FROM tbl_product");
        $select->execute();
        $row=$select->fetch(PDO::FETCH_OBJ);
        $total = $row->t;
        ?>

        <!-- get total products notification -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-cubes"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Produits</span>
              <span class="badge badge-primary bg-aqua alert alert-primary"><small><?php echo $row->t ?></small></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        <!-- get today transactions -->
        <?php
        $select = $pdo->prepare("SELECT count(invoice_id) as i FROM tbl_invoice WHERE order_date = CURDATE()");
        $select->execute();
        $row=$select->fetch(PDO::FETCH_OBJ);
        $invoice = $row->i ;
        ?>
         <!-- get today transactions notification -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TRANSACTIONS DU JOUR</span>
              <span class="badge badge-success bg-green alert alert-success"><small><?php echo $row->i ?></small></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>


        <!-- get today income -->
        <?php
        $select = $pdo->prepare("SELECT sum(total) as total FROM tbl_invoice WHERE order_date = CURDATE()");
        $select->execute();
        $row=$select->fetch(PDO::FETCH_OBJ);
        $total = $row->total ;
        ?>
         <!-- get today income -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-money"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">REVENU DU JOUR</span>
              <span class="badge badge-warning bg-yellow alert alert-warning"><small> <?php echo number_format($total,0,null," "); ?> FCFA</small></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

      </div>
      <br><br><br>

      <div class="col-md-offset-1 col-md-10">

      <form action="" method="POST">

        <input type="submit" name="signal" value="SIGNALER STOCK ALERTE !!!" class="btn btn-danger">

        
      </div><br><br><br><br>

      </form>



      <div class="col-md-offset-1 col-md-10">
        <div class="box box-success">
          <div class="box-header with-border">
              <h3 class="box-title">Liste des produits vendus</h3>
          </div>
          <div class="box-body">
            <div class="col-md-offset-1 col-md-10">
              <div style="overflow-x:auto;">
                  <table class="table table-striped" id="myBestProduct">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Produit</th>
                              <th>Code</th>
                              <th>Vendu</th>
                              <th>Prix</th>
                              <th>Revenu</th>
                          </tr>

                      </thead>
                      <tbody>
                          <?php
                          $no = 1;

                          $magasin = $_SESSION['magasin'];

                          echo $magasin;

                          if($_SESSION['role']=="Admin"){
                          $select = $pdo->prepare("SELECT product_code,product_name,price,product_satuan,sum(qty) as q, sum(qty*price) as total FROM
                          tbl_invoice_detail GROUP BY product_id ORDER BY sum(qty) DESC LIMIT 30");
                           }
                           else{
                           $select = $pdo->prepare("SELECT product_code,product_name,price,product_satuan,sum(qty) as q, sum(qty*price) as total FROM
                          tbl_invoice_detail WHERE invoice_id IN (SELECT invoice_id FROM tbl_invoice WHERE cashier_name IN (SELECT username FROM tbl_user WHERE magasin = '$magasin')  ) GROUP BY product_id ORDER BY sum(qty) DESC LIMIT 30");
         
                           }


                          $select->execute();
                          while($row=$select->fetch(PDO::FETCH_OBJ)){
                          ?>
                              <tr>
                              <td><?php echo $no++ ;?></td>
                              <td><?php echo $row->product_name; ?></td>
                              <td><?php echo $row->product_code; ?></td>
                              <td><?php echo $row->q; ?>
                              <span><?php echo $row->product_satuan; ?></span>
                              </td>
                              <td><?php echo number_format($row->price,0,null," ");?> FCFA</td>
                              <td><?php echo number_format($row->total,0,null," "); ?> FCFA</td>
                              </tr>

                        <?php
                          }
                        ?>
                      </tbody>
                  </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script>
  $(document).ready( function () {
      $('#myBestProduct').DataTable();
  } );
  </script>


 <?php
    include_once'inc/footer_all.php';
	//include_once'inc/footer.php';
 ?>