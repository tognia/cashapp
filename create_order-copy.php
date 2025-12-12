<?php
// create_order.php
include_once 'db/connect_db.php';

// Vérification session
if (!isset($_SESSION['user_name'])) {
  header('location:index.php');
  exit;
}

// Header
if ($_SESSION['role'] == "Admin") {
  include_once 'inc/header_all.php';
} else {
  include_once 'inc/header_all_operator.php';
}

error_reporting(0);
date_default_timezone_set('Africa/Douala');

// --- 1. MODE ÉDITION : Si on vient de pending_orders.php ---
$edit_mode = false;
$edit_id = "";
$existing_items = [];
$client_preselected = "common";

if (isset($_GET['edit_id'])) {
  $edit_mode = true;
  $edit_id = $_GET['edit_id'];

  // Récupérer infos facture
  $stmt_inv = $pdo->prepare("SELECT * FROM tbl_invoice WHERE invoice_id = :id AND status = 'pending'");
  $stmt_inv->execute([':id' => $edit_id]);
  $invoice_data = $stmt_inv->fetch(PDO::FETCH_ASSOC);

  if ($invoice_data) {
    $client_preselected = $invoice_data['id_client'];

    // Récupérer les produits et joindre avec le stock actuel pour vérification
    $stmt_details = $pdo->prepare("SELECT d.*, s.stock as current_stock, s.min_stock, s.min_price 
                                       FROM tbl_invoice_detail d 
                                       JOIN tbl_shop_item s ON d.product_id = s.product_id 
                                       WHERE d.invoice_id = :id");
    $stmt_details->execute([':id' => $edit_id]);
    $existing_items = $stmt_details->fetchAll(PDO::FETCH_ASSOC);
  }
}

// --- 2. TRAITEMENT DU FORMULAIRE (Save ou Hold) ---
if (isset($_POST['save_order']) || isset($_POST['hold_order'])) {

  $status = isset($_POST['hold_order']) ? 'pending' : 'saved';
  $invoice_id_process = $_POST['invoice_id_hidden']; // ID si modification
  $is_updating = !empty($invoice_id_process);

  // Données formulaire
  $cashier_name = $_POST['cashier_name'];
  $id_client = $_POST['client'];
  $order_date = date('Y-m-d');
  $order_time = date('H:i:s');

  // Totaux & Paiement
  $total = $_POST['total'];
  $paid = $_POST['paid'];
  $due = $_POST['due'];
  $remise = $_POST['remise'];
  $tva = $_POST['tva'];
  $payment_mode = $_POST['payment_mode'];

  // Tableaux produits
  $arr_product_id = $_POST['productid'];
  // ... On récupère les autres tableaux via POST indexé

  if (empty($arr_product_id)) {
    echo '<script>swal("Attention", "Le panier est vide.", "warning");</script>';
  } else {
    try {
      $pdo->beginTransaction();

      if ($is_updating) {
        // MISE A JOUR (Update status + infos)
        $sql = "UPDATE tbl_invoice SET id_client=:client, total=:total, paid=:paid, due=:due, 
                        remise=:remise, tva=:tva, payment_mode=:pay, status=:status, order_date=:odate, time_order=:otime 
                        WHERE invoice_id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
          ':client' => $id_client,
          ':total' => $total,
          ':paid' => $paid,
          ':due' => $due,
          ':remise' => $remise,
          ':tva' => $tva,
          ':pay' => $payment_mode,
          ':status' => $status,
          ':odate' => $order_date,
          ':otime' => $order_time,
          ':id' => $invoice_id_process
        ]);

        // On supprime les anciens détails pour réinsérer les nouveaux (plus simple que update ligne par ligne)
        $pdo->exec("DELETE FROM tbl_invoice_detail WHERE invoice_id = $invoice_id_process");
        $new_invoice_id = $invoice_id_process;
      } else {
        // INSERTION NOUVELLE
        $sql = "INSERT INTO tbl_invoice(cashier_name, user, id_client, order_date, time_order, total, paid, due, remise, tva, payment_mode, status)
                        VALUES(:name, :user, :client, :odate, :otime, :total, :paid, :due, :remise, :tva, :pay, :status)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
          ':name' => $cashier_name,
          ':user' => $_SESSION['user_name'],
          ':client' => $id_client,
          ':odate' => $order_date,
          ':otime' => $order_time,
          ':total' => $total,
          ':paid' => $paid,
          ':due' => $due,
          ':remise' => $remise,
          ':tva' => $tva,
          ':pay' => $payment_mode,
          ':status' => $status
        ]);
        $new_invoice_id = $pdo->lastInsertId();
      }

      // Boucle Produits
      for ($i = 0; $i < count($arr_product_id); $i++) {
        $rem_qty = $_POST['productstock'][$i] - $_POST['quantity'][$i];

        // Si statut = saved, on décrémente le stock réel
        if ($status == 'saved') {
          $upd = $pdo->prepare("UPDATE tbl_shop_item SET stock = :new WHERE product_id = :id AND shop_code = :shop");
          $upd->execute([':new' => $rem_qty, ':id' => $arr_product_id[$i], ':shop' => $_SESSION['magasin']]);
        }

        // Insertion détail
        $ins_det = $pdo->prepare("INSERT INTO tbl_invoice_detail(invoice_id, product_id, product_code, product_name, qty, product_satuan, price, total, order_date, remise)
                                          VALUES(:inv, :pid, :pcode, :pname, :qty, :unit, :price, :tot, :date, :rem)");
        $ins_det->execute([
          ':inv' => $new_invoice_id,
          ':pid' => $arr_product_id[$i],
          ':pcode' => $_POST['productcode'][$i],
          ':pname' => $_POST['productname'][$i],
          ':qty' => $_POST['quantity'][$i],
          ':unit' => $_POST['productsatuan'][$i],
          ':price' => $_POST['productprice'][$i],
          ':tot' => $_POST['producttotal'][$i],
          ':date' => $order_date,
          ':rem' => $_POST['productremise'][$i]
        ]);
      }

      $pdo->commit();

      if ($status == 'saved') {
        echo '<script>swal("Succès", "Facture validée !", "success").then(()=>{ window.location.href="print_receipt.php?id=' . $new_invoice_id . '"; });</script>';
      } else {
        echo '<script>swal("Suspendu", "Commande mise en attente.", "info").then(()=>{ window.location.href="create_order.php"; });</script>';
      }
    } catch (Exception $e) {
      $pdo->rollBack();
      echo '<script>swal("Erreur", "' . $e->getMessage() . '", "error");</script>';
    }
  }
}
?>

<style>
  /* Styles CSS simplifiés */
  .scanner-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    color: white;
  }

  #barcodeScanner {
    width: 100%;
    padding: 15px;
    font-size: 18px;
    border-radius: 5px;
    border: none;
    color: #333;
  }

  .alert-pending {
    background: #fff3cd;
    color: #856404;
    padding: 10px;
    border: 1px solid #ffeeba;
    border-radius: 4px;
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <div style="display:flex; justify-content:space-between;">
      <h1>🛒 Caisse</h1>
      <a href="pending_orders.php" class="btn btn-warning"><i class="fa fa-clock-o"></i> Voir les commandes en attente</a>
    </div>
  </section>

  <section class="content">

    <?php if ($edit_mode): ?>
      <div class="alert-pending">
        <span><i class="fa fa-pencil"></i> Vous modifiez le brouillon <b>#<?php echo $edit_id; ?></b></span>
        <a href="create_order.php" class="btn btn-sm btn-outline-warning" style="border:1px solid #856404; color:#856404;">Annuler / Nouvelle vente</a>
      </div>
    <?php endif; ?>

    <div class="scanner-section">
      <div class="row">
        <div class="col-md-6">
          <label><i class="fa fa-barcode"></i> Scanner</label>
          <input type="text" id="barcodeScanner" placeholder="Code barre..." autocomplete="off" autofocus>
        </div>
        <div class="col-md-6">
          <label><i class="fa fa-search"></i> Recherche Manuelle</label>
          <select class="form-control select2" id="manualSelect" style="width: 100%;">
            <option value="">-- Choisir un produit --</option>
          </select>
        </div>
      </div>
    </div>

    <form action="" method="POST" id="orderForm">
      <input type="hidden" name="invoice_id_hidden" value="<?php echo $edit_id; ?>">

      <div class="box box-success">
        <div class="box-header">
          <div class="row">
            <div class="col-md-4">
              <label>Client</label>
              <select class="form-control select2" name="client" id="clientSelect">
                <option value="common">Client Régulier</option>
                <?php
                $u = $pdo->prepare("SELECT * FROM users ORDER BY firstname");
                $u->execute();
                while ($r = $u->fetch(PDO::FETCH_ASSOC)) {
                  $selected = ($r['username'] == $client_preselected) ? 'selected' : '';
                  echo "<option value='" . $r['username'] . "' $selected>" . $r['firstname'] . " " . $r['lastname'] . "</option>";
                }
                ?>
              </select>
            </div>
            <div class="col-md-4"><label>Date</label><input type="text" class="form-control" value="<?php echo date('d-m-Y'); ?>" readonly></div>
            <div class="col-md-4"><label>Caissier</label><input type="text" class="form-control" name="cashier_name" value="<?php echo $_SESSION['fullname']; ?>" readonly></div>
          </div>
        </div>

        <div class="box-body table-responsive">
          <table class="table table-bordered" id="myOrder">
            <thead>
              <tr style="background:#f4f4f4;">
                <th>Code</th>
                <th>Produit</th>
                <th>Stock</th>
                <th>Prix</th>
                <th>Remise %</th>
                <th>Qté</th>
                <th>Total</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php
              // GÉNÉRATION PHP DES LIGNES SI MODE ÉDITION
              if ($edit_mode && !empty($existing_items)) {
                foreach ($existing_items as $item) {
                  $remise_percent = ($item['qty'] > 0 && $item['price'] > 0) ? round(($item['remise'] / ($item['price'] * $item['qty'])) * 100) : 0;
                  echo '<tr>
                                        <input type="hidden" class="productid" name="productid[]" value="' . $item['product_id'] . '">
                                        <input type="hidden" class="productstock" name="productstock[]" value="' . $item['current_stock'] . '">
                                        <input type="hidden" class="minstock" name="minstock[]" value="' . $item['min_stock'] . '">
                                        <input type="hidden" class="productmin" name="productmin[]" value="' . $item['min_price'] . '">
                                        <input type="hidden" class="productsatuan" name="productsatuan[]" value="' . $item['product_satuan'] . '">
                                        <input type="hidden" class="productremise" name="productremise[]" value="' . $item['remise'] . '"> <td><input type="text" class="form-control" name="productcode[]" value="' . $item['product_code'] . '" readonly></td>
                                        <td><input type="text" class="form-control" name="productname[]" value="' . $item['product_name'] . '" readonly></td>
                                        <td><span class="badge badge-info">' . $item['current_stock'] . '</span></td>
                                        <td><input type="number" class="form-control productprice" name="productprice[]" value="' . $item['price'] . '"></td>
                                        <td><input type="number" class="form-control discount" name="discount[]" value="' . $remise_percent . '"></td>
                                        <td><input type="number" class="form-control quantity_product" name="quantity[]" value="' . $item['qty'] . '"></td>
                                        <td><input type="text" class="form-control producttotal" name="producttotal[]" value="' . $item['total'] . '" readonly></td>
                                        <td><button type="button" class="btn btn-danger btn-sm btn-remove"><i class="fa fa-times"></i></button></td>
                                    </tr>';
                }
              }
              ?>
            </tbody>
          </table>
        </div>

        <div class="box-footer">
          <div class="row">
            <div class="col-md-3">
              <label>Total TTC</label>
              <input type="text" class="form-control input-lg" name="total" id="total" readonly style="background:#e8f5e9; font-weight:bold;">
            </div>
            <div class="col-md-3">
              <label>Reçu</label>
              <input type="number" class="form-control input-lg" name="paid" id="paid" value="0">
            </div>
            <div class="col-md-3">
              <label>Rendu</label>
              <input type="text" class="form-control input-lg" name="due" id="due" readonly style="background:#fff3cd;">
            </div>
            <div class="col-md-3">
              <label>Paiement</label>
              <select class="form-control input-lg" name="payment_mode" id="paymentMode">
                <option value="especes">Espèces</option>
                <option value="orange_money">Orange Money</option>
                <option value="mtn_money">MTN Money</option>
                <option value="carte_visa">Carte Visa</option>
              </select>
            </div>
          </div>

          <input type="hidden" name="tva" id="tva">
          <input type="hidden" name="remise" id="remise">

          <div class="text-center" style="margin-top:20px;">
            <button type="submit" name="hold_order" class="btn btn-info btn-lg">⏸️ Mettre en Attente (F7)</button>
            <button type="submit" name="save_order" id="saveOrderBtn" class="btn btn-success btn-lg" onclick="return confirm('Confirmer la vente ?')">💾 Encaisser (F9)</button>
          </div>
        </div>
      </div>
    </form>
  </section>
</div>

<script>
  $(document).ready(function() {

    // --- 1. CHARGEMENT LISTE PRODUITS (Pour recherche manuelle) ---
    $.ajax({
      url: "get_all_products.php",
      method: "GET",
      dataType: "json",
      success: function(data) {
        data.forEach(function(p) {
          $("#manualSelect").append(`<option value="${p.product_code}">${p.product_code} – ${p.product_name}</option>`);
        });
      }
    });

    $("#manualSelect").change(function() {
      let code = $(this).val();
      if (code) {
        searchProduct(code);
        $(this).val('');
      }
    });

    // --- 2. SCANNER ---
    $('#barcodeScanner').keypress(function(e) {
      if (e.which === 13) {
        e.preventDefault();
        let code = $(this).val();
        if (code) searchProduct(code);
        $(this).val('');
      }
    });

    // --- 3. FONCTIONS LOGIQUES ---
    function searchProduct(code) {
      $.ajax({
        url: 'get_product_by_code.php',
        method: 'POST',
        dataType: 'json',
        data: {
          code: code
        },
        success: function(data) {
          if (data && data.product_id) {
            addRow(data);
          } else {
            alert('Produit non trouvé');
          }
        }
      });
    }

    function addRow(data) {
      // Vérifier si existe déjà
      let exists = false;
      $('.productid').each(function() {
        if ($(this).val() == data.product_id) {
          let row = $(this).closest('tr');
          let qtyInput = row.find('.quantity_product');
          let currentQty = parseInt(qtyInput.val());
          let stock = parseInt(row.find('.productstock').val());

          if (currentQty < stock) {
            qtyInput.val(currentQty + 1).trigger('change');
          } else {
            alert("Stock insuffisant !");
          }
          exists = true;
        }
      });

      if (!exists) {
        let html = `<tr>
                <input type="hidden" class="productid" name="productid[]" value="${data.product_id}">
                <input type="hidden" class="productstock" name="productstock[]" value="${data.stock}">
                <input type="hidden" class="minstock" name="minstock[]" value="${data.min_stock}">
                <input type="hidden" class="productmin" name="productmin[]" value="${data.min_price}">
                <input type="hidden" class="productsatuan" name="productsatuan[]" value="${data.product_satuan}">
                <input type="hidden" class="productremise" name="productremise[]" value="0">
                
                <td><input type="text" class="form-control" name="productcode[]" value="${data.product_code}" readonly></td>
                <td><input type="text" class="form-control" name="productname[]" value="${data.product_name}" readonly></td>
                <td><span class="badge badge-info">${data.stock}</span></td>
                <td><input type="number" class="form-control productprice" name="productprice[]" value="${data.sell_price}"></td>
                <td><input type="number" class="form-control discount" name="discount[]" value="${data.discount}"></td>
                <td><input type="number" class="form-control quantity_product" name="quantity[]" value="1"></td>
                <td><input type="text" class="form-control producttotal" name="producttotal[]" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm btn-remove"><i class="fa fa-times"></i></button></td>
            </tr>`;
        $('#myOrder tbody').append(html);
        $('#myOrder tbody tr:last .quantity_product').trigger('change'); // Pour calculer le premier total
      }
    }

    // --- 4. CALCULS AUTOMATIQUES ---
    $(document).on('keyup change', '.quantity_product, .productprice, .discount', function() {
      let row = $(this).closest('tr');
      let qty = parseInt(row.find('.quantity_product').val()) || 0;
      let price = parseFloat(row.find('.productprice').val()) || 0;
      let discPercent = parseFloat(row.find('.discount').val()) || 0;
      let stock = parseInt(row.find('.productstock').val());

      // Controle stock
      if (qty > stock) {
        row.find('.quantity_product').val(stock);
        qty = stock;
        alert('Stock Max atteint');
      }
      if (qty < 1) {
        row.find('.quantity_product').val(1);
        qty = 1;
      }

      // Calculs ligne
      let discValue = (price * qty * discPercent) / 100;
      let total = (price * qty) - discValue;

      row.find('.productremise').val(discValue.toFixed(2));
      row.find('.producttotal').val(total.toFixed(2));

      calculateGlobal();
    });

    $(document).on('click', '.btn-remove', function() {
      $(this).closest('tr').remove();
      calculateGlobal();
    });

    $('#paid').keyup(function() {
      calculateGlobal();
    });

    function calculateGlobal() {
      let net = 0;
      let totalRemise = 0;

      $('.producttotal').each(function() {
        net += parseFloat($(this).val()) || 0;
      });
      $('.productremise').each(function() {
        totalRemise += parseFloat($(this).val()) || 0;
      });

      let paid = parseFloat($('#paid').val()) || 0;
      let due = paid - net;

      $('#total').val(net.toFixed(2));
      $('#remise').val(totalRemise.toFixed(2));
      $('#tva').val((net * 0.1925).toFixed(2)); // Juste informatif
      $('#due').val(due.toFixed(2));

      if (paid < net) {
        $('#saveOrderBtn').prop('disabled', true);
        $('#paid').css('border', '2px solid red');
      } else {
        $('#saveOrderBtn').prop('disabled', false);
        $('#paid').css('border', '1px solid #ccc');
      }
    }

    // --- 5. INITIALISATION AU CHARGEMENT (Important pour le mode édition) ---
    // On force le recalcul de chaque ligne pour mettre à jour les totaux si on vient de charger une commande
    $('.quantity_product').trigger('change');

    // Raccourcis Clavier
    $(document).keydown(function(e) {
      if (e.key == "F7") {
        e.preventDefault();
        $("button[name='hold_order']").click();
      }
      if (e.key == "F9") {
        e.preventDefault();
        $("#saveOrderBtn").click();
      }
    });

  });
</script>

<?php include_once 'inc/footer_all.php'; ?>