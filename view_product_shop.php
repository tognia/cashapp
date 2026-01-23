<?php
// view_product_shop.php
include_once 'db/connect_db.php';

if (empty($_SESSION['user_name'])) {
  header('location:index.php');
  exit();
} else {
  if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "Responsable") {
    include_once 'inc/header_all.php';
  } else {
    include_once 'inc/header_all_operator.php';
  }
}

$shop = '';
if ($_SESSION['role'] == "Responsable") {
  $shop = $_SESSION['magasin'] ?? '';
} elseif ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "storekeeper") {
  $shop = $_SESSION['select_shop'] ?? '';
} else {
  $shop = $_SESSION['magasin'] ?? '';
}

// --- LOGIC TO UPDATE SHIPMENT QUANTITY ---
if (isset($_POST['btn_update_transit']) && ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "storekeeper")) {
  $shipment_id = $_POST['shipment_id'];
  $new_qty = (int)$_POST['new_qty'];
  $p_code = $_POST['product_code'];

  try {
    $pdo->beginTransaction();

    // 3. Adjust Main Warehouse Stock (tbl_product)
    $update_main = $pdo->prepare("UPDATE tbl_shop_item SET stock = :new_qty WHERE product_code = :pcode AND shop_code = :shop");
    $update_main->execute([':new_qty' => $new_qty, ':pcode' => $p_code, ':shop' => $shop]);

    $pdo->commit();
    echo '<script>jQuery(function(){ swal("Succès", "Quantité  mise à jour avec succès.", "success"); });</script>';
    // }
  } catch (Exception $e) {
    $pdo->rollBack();
    echo '<script>jQuery(function(){ swal("Erreur", "Impossible de mettre à jour le stock.", "error"); });</script>';
  }
}
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Produit <small>Détails et Gestion Transit</small></h1>
  </section>

  <section class="content container-fluid">
    <div class="box box-success">
      <div class="box-body">
        <?php
        $id = $_GET['id'];

        // Subquery to get Total Delivered and the latest shipment_id for editing
        $select = $pdo->prepare("
                    SELECT 
                        tsi.*,
                        (
                            SELECT COALESCE(SUM(tps.shipped_quantity), 0)
                            FROM tbl_product_shipment tps
                            WHERE tps.product_code = tsi.product_code
                            AND tps.code_agence = tsi.shop_code
                            AND tps.delivery_status = 'Delivered'
                        ) AS total_delivered,
                        (
                            SELECT tps.shipment_id 
                            FROM tbl_product_shipment tps 
                            WHERE tps.product_code = tsi.product_code 
                            AND tps.code_agence = tsi.shop_code 
                            AND tps.delivery_status = 'Delivered' 
                            ORDER BY tps.shipment_id DESC LIMIT 1
                        ) AS last_shipment_id
                    FROM tbl_shop_item tsi 
                    WHERE tsi.product_id=:id
                ");

        $select->bindParam(':id', $id, PDO::PARAM_INT);
        $select->execute();

        while ($row = $select->fetch(PDO::FETCH_OBJ)) { ?>

          <div class="col-md-6">
            <ul class="list-group">
              <li class="list-group-item active text-center"><b>BOUTIQUE: <?php echo $row->shop_code; ?></b></li>
              <li class="list-group-item"><b>Code Produit</b> :<span class="label badge pull-right"><?php echo $row->product_code; ?></span></li>
              <li class="list-group-item"><b>SKU</b> :<span class="label label-primary pull-right"><?php echo $row->product_sku; ?></span></li>
              <li class="list-group-item"><b>Libelle Produit</b> :<span class="label label-info pull-right"><?php echo $row->product_name; ?></span></li>
              <li class="list-group-item"><b>Prix de Vente</b> :<span class="label label-warning pull-right"> FCFA &nbsp; <?php echo number_format($row->sell_price); ?></span></li>
              <li class="list-group-item"><b>Quantite en stock Boutique</b> :<span class="label label-default pull-right"><?php echo $row->stock; ?></span></li>
              <li class="list-group-item"><b>Expédié Non Validé (Transit)</b> :<span class="label label-danger pull-right"><?php echo $row->total_delivered; ?></span></li>
            </ul>

            <?php if ($_SESSION['role'] == "Admin") { ?>
              <div class="well" style="background-color: #fcf8e3; border: 1px solid #faebcc;">
                <h4><i class="fa fa-edit text-warning"></i> Modifier la quantité</h4>
                <form action="" method="POST" class="form-inline">
                  <input type="hidden" name="shipment_id" value="<?php echo $row->last_shipment_id; ?>">
                  <input type="hidden" name="product_code" value="<?php echo $row->product_code; ?>">
                  <div class="form-group">
                    <label for="new_qty">Nouvelle Qté:</label>
                    <input type="number" name="new_qty" class="form-control" value="<?php echo $row->total_delivered; ?>" min="0" required>
                  </div>
                  <button type="submit" name="btn_update_transit" class="btn btn-warning" onclick="return confirm('Voulez-vous modifier la quantité en transit ? Cela impactera le stock de l\'entrepôt.')">Mettre à jour</button>
                </form>
                <!-- <p class="text-muted" style="margin-top:10px;"><small>* Cette modification ajuste automatiquement le stock de l'entrepôt principal.</small></p> -->
              </div>
            <?php } ?>

            <ul class="list-group">
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
</div>

<?php
include_once 'inc/footer_all.php';
?>