<?php
include_once 'db/connect_db.php';
if ($_SESSION['user_name'] == "") {
  header('location:index.php');
} else {
  if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "Responsable") {
    include_once 'inc/header_all.php';
  } else {
    include_once 'inc/header_all_operator.php';
  }
}
echo $_SESSION['shop_code'];
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

        $select = $pdo->prepare("SELECT * FROM tbl_shop_item WHERE product_id=$id");
        $select->execute();
        while ($row = $select->fetch(PDO::FETCH_OBJ)) { ?>

          <div class="col-md-6">
            <ul class="list-group">
              <center>
                <p class="list-group-item"><?php echo $row->shop_code; ?></p>
              </center>
              <center>
                <p class="list-group-item list-group-item-success">Detail Produit</p>
              </center>
              <li class="list-group-item"> <b>Code Produit</b> :<span class="label badge pull-right"><?php echo $row->product_code; ?></span></li>
              <li class="list-group-item"><b>SKU</b> :<span class="label label-primary pull-right"><?php echo $row->product_sku; ?></span></li>
              <li class="list-group-item"><b>Libelle Produit</b> :<span class="label label-info pull-right"><?php echo $row->product_name; ?></span></li>
              <li class="list-group-item"><b>Categorie Produit</b> :<span class="label label-primary pull-right"><?php echo $row->product_category; ?></span></li>
              <li class="list-group-item"><b>Emplacement</b> :<span class="label label-primary pull-right"><?php echo $row->place_in_store; ?></span></li>
              <li class="list-group-item"><b>Marque</b> :<span class="label label-primary pull-right"><?php echo $row->product_brand; ?></span></li>
              <li class="list-group-item"><b>Prix Achat</b> :<span class="label label-warning pull-right"> FCFA &nbsp; <?php echo number_format($row->purchase_price); ?></span></li>
              <li class="list-group-item"><b>Prix de Vente</b> :<span class="label label-warning pull-right"> FCFA &nbsp; <?php echo number_format($row->sell_price); ?></span></li>

              <li class="list-group-item"><b>Prix Minimum</b> :<span class="label label-warning pull-right"> FCFA &nbsp; <?php echo number_format($row->min_price); ?></span></li>
              <li class="list-group-item"><b>Discount</b> :<span class="label label-warning pull-right"> <?php echo number_format($row->discount); ?> &nbsp; %</span></li>

              <li class="list-group-item"><b>Plus Value</b> :<span class="label label-success pull-right"> FCFA &nbsp; <?php echo number_format(($row->sell_price - $row->purchase_price)); ?></span></li>
              <li class="list-group-item"><b>Quantite en stock </b> :<span class="label label-default pull-right"><?php echo $row->stock; ?></span></li>
              <li class="list-group-item"><b>Stock Minimal </b> :<span class="label label-default pull-right"><?php echo $row->min_stock; ?></span></li>
              <li class="list-group-item"><b>Unite</b> :<span class="label label-default pull-right"><?php echo $row->product_satuan; ?></span></li>
              <li class="list-group-item"><b>Fournisseur</b> :<span class="label label-default pull-right"><?php echo $row->supplier; ?></span></li>
              <li class="list-group-item"><b>Description</b> :</li>
              <li class="list-group-item col-md-12"><span class="text-muted"><?php echo $row->description ?></span></li>
            </ul>
          </div>
          <div class="col-md-6">
            <ul class="list-group">
              <center>
                <p class="list-group-item list-group-item-success">Image</p>
              </center>
              <img src="upload/<?php echo $row->img ?>" alt="Product Image" class="img-responsive">
            </ul>
          </div>
        <?php
        }
        ?>
      </div>
      <div class="box-footer">
        <a href="product_shop_item.php" class="btn btn-warning">Retour</a>
      </div>

    </div>


  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include_once 'inc/footer_all.php';
?>