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
?>

<div class="content-wrapper">
  <section class="content container-fluid">
    <div class="row">
      <?php
      $select = $pdo->prepare("SELECT count(product_code) as total FROM tbl_product WHERE stock <= min_stock");
      $select->execute();
      $row = $select->fetch(PDO::FETCH_OBJ);
      $total1 = $row->total;
      ?>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-danger alert alert-danger"><i class="fa fa-archive"></i></span>

          <div class="info-box-content">
            <a href="product.php?status=alert"><span class="info-box-text">Produits | Stock Alerte</span>
              <?php if ($total1 == true) { ?>
                <span class="badge badge-danger alert alert-danger"><small><?php echo $row->total; ?></small></span>
              <?php } else { ?>
                <span class="info-box-text"><strong>RIEN A SIGNALER</strong></span>
              <?php } ?>
            </a>
          </div>
        </div>
      </div>


      <?php
      $select = $pdo->prepare("SELECT count(product_code) as t FROM tbl_product");
      $select->execute();
      $row = $select->fetch(PDO::FETCH_OBJ);
      $total = $row->t;
      ?>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-aqua"><i class="fa fa-cubes"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Total Produits</span>
            <span class="badge badge-primary bg-aqua alert alert-primary"><small><?php echo $row->t ?></small></span>
          </div>
        </div>
      </div>

      <?php
      // UPDATE: Only count transactions where status is 'saved'
      $select = $pdo->prepare("SELECT count(invoice_id) as i FROM tbl_invoice WHERE order_date = CURDATE() AND status = 'saved'");
      $select->execute();
      $row = $select->fetch(PDO::FETCH_OBJ);
      $invoice = $row->i;
      ?>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">TRANSACTIONS DU JOUR</span>
            <span class="badge badge-success bg-green alert alert-success"><small><?php echo $row->i ?></small></span>
          </div>
        </div>
      </div>


      <?php
      // UPDATE: Only sum total where status is 'saved'
      $select = $pdo->prepare("SELECT sum(total) as total FROM tbl_invoice WHERE order_date = CURDATE() AND status = 'saved'");
      $select->execute();
      $row = $select->fetch(PDO::FETCH_OBJ);
      $total = $row->total;
      ?>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-yellow"><i class="fa fa-money"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">REVENU DU JOUR</span>
            <span class="badge badge-warning bg-yellow alert alert-warning"><small> <?php echo number_format($total, 0, null, " "); ?> FCFA</small></span>
          </div>
        </div>
      </div>

    </div>
    <br><br><br>

    <div class="col-md-offset-1 col-md-10">

      <form action="" method="POST">

        <!-- <input type="submit" name="signal" value="SIGNALER STOCK ALERTE !!!" class="btn btn-danger"> -->


    </div><br><br><br><br>

    </form>




  </section>
</div>
<script>
  $(document).ready(function() {
    $('#myBestProduct').DataTable();
  });
</script>


<?php
include_once 'inc/footer_all.php';
//include_once'inc/footer.php';
?>