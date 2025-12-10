<?php
// create_order.php - Version améliorée avec double mode de recherche
include_once 'db/connect_db.php';

// Vérification de la session utilisateur
if (!isset($_SESSION['user_name']) || $_SESSION['user_name'] == "") {
  include_once 'inc/404.php';
  exit();
}

// Inclure l'en-tête en fonction du rôle
if ($_SESSION['role'] == "Admin") {
  include_once 'inc/header_all.php';
} else {
  include_once 'inc/header_all_operator.php';
}

error_reporting(0);
date_default_timezone_set('Africa/Douala');
$shop = $_SESSION['magasin'];

// --- Initialisation des tableaux d'alerte (Stock Min) ---
$_SESSION['tab_alert'] = array();
$_SESSION['tab_alert']['id'] = array();
$_SESSION['tab_alert']['code'] = array();
$_SESSION['tab_alert']['name'] = array();
$_SESSION['tab_alert']['stock'] = array();
$_SESSION['tab_alert']['stock_min'] = array();
$j = 0;
$_SESSION['count_alert'] = 0;

// --- Logique de Sauvegarde de la Commande ---
if (isset($_POST['save_order'])) {

  $cashier_name = $_POST['cashier_name'];
  $id_client = $_POST['client'];
  $order_date = date("Y-m-d", strtotime($_POST['orderdate']));
  $order_time = date("H:i:s", strtotime($_POST['timeorder']));

  $total = $_POST['total'];
  $paid = $_POST['paid'];
  $due = $_POST['due'];
  $remise = $_POST['remise'];
  $tva = $_POST['tva'];
  $payment_mode = $_POST['payment_mode'];

  $arr_product_id = $_POST['productid'];
  $arr_product_code = $_POST['productcode'];
  $arr_product_name = $_POST['productname'];
  $arr_product_stock = $_POST['productstock'];
  $arr_product_stockmin = $_POST['minstock'];
  $arr_product_qty = $_POST['quantity'];
  $arr_product_satuan = $_POST['productsatuan'];
  $arr_product_price = $_POST['productprice'];
  $arr_product_min = $_POST['productmin'];
  $arr_product_remise = $_POST['productremise'];
  $arr_product_total = $_POST['producttotal'];

  if (empty($arr_product_id) || array_sum($arr_product_qty) == 0) {
    echo '<script type="text/javascript">
                jQuery(function validation(){
                    swal("Warning", "Veuillez ajouter des produits à la transaction.", "warning", {
                        button: "Continue",
                    });
                });
                </script>';
  } else {
    try {
      $pdo->beginTransaction();

      $insert_invoice = $pdo->prepare("INSERT INTO tbl_invoice(cashier_name, id_client, order_date, time_order, total, paid, due, remise, tva, payment_mode)
                                             VALUES(:name, :id_client, :orderdate, :timeorder, :total, :paid, :due, :remise, :tva, :payment_mode)");

      $insert_invoice->bindParam(':name', $cashier_name);
      $insert_invoice->bindParam(':id_client', $id_client);
      $insert_invoice->bindParam(':orderdate', $order_date);
      $insert_invoice->bindParam(':timeorder', $order_time);
      $insert_invoice->bindParam(':total', $total);
      $insert_invoice->bindParam(':paid', $paid);
      $insert_invoice->bindParam(':due', $due);
      $insert_invoice->bindParam(':remise', $remise);
      $insert_invoice->bindParam(':tva', $tva);
      $insert_invoice->bindParam(':payment_mode', $payment_mode);
      $insert_invoice->execute();

      $invoice_id = $pdo->lastInsertId();

      if ($invoice_id) {
        $has_error = false;
        $alert_products = [];

        for ($i = 0; $i < count($arr_product_id); $i++) {

          $product_id = $arr_product_id[$i];
          $qty_sold = $arr_product_qty[$i];
          $current_stock = $arr_product_stock[$i];
          $min_stock = $arr_product_stockmin[$i];
          $price_sold = $arr_product_price[$i];
          $min_price = $arr_product_min[$i];

          $rem_qty = $current_stock - $qty_sold;
          $diff_price = $price_sold - $min_price;
          $reste_stock_min = $rem_qty - $min_stock;

          if ($diff_price < 0) {
            $has_error = true;
            throw new Exception("Prix de vente inférieur au prix minimum pour " . $arr_product_code[$i]);
          }

          $update_stock = $pdo->prepare("UPDATE tbl_shop_item SET stock = :new_stock WHERE shop_code = :shop AND product_id = :id");
          $update_stock->bindParam(':new_stock', $rem_qty);
          $update_stock->bindParam(':shop', $shop);
          $update_stock->bindParam(':id', $product_id);
          $update_stock->execute();

          $insert_detail = $pdo->prepare("INSERT INTO tbl_invoice_detail(invoice_id, product_id, product_code, product_name, qty, product_satuan, price, total, order_date, remise)
                                                    VALUES(:invid, :productid, :productcode, :productname, :qty, :productsatuan, :price, :total, :orderdate, :remise)");

          $insert_detail->bindParam(':invid', $invoice_id);
          $insert_detail->bindParam(':productid', $product_id);
          $insert_detail->bindParam(':productcode', $arr_product_code[$i]);
          $insert_detail->bindParam(':productname', $arr_product_name[$i]);
          $insert_detail->bindParam(':qty', $qty_sold);
          $insert_detail->bindParam(':productsatuan', $arr_product_satuan[$i]);
          $insert_detail->bindParam(':price', $price_sold);
          $insert_detail->bindParam(':total', $arr_product_total[$i]);
          $insert_detail->bindParam(':orderdate', $order_date);
          $insert_detail->bindParam(':remise', $arr_product_remise[$i]);
          $insert_detail->execute();

          if ($reste_stock_min <= 0) {
            $alert_products[] = [
              'id' => $product_id,
              'code' => $arr_product_code[$i],
              'name' => $arr_product_name[$i],
              'stock' => $rem_qty,
              'stock_min' => $min_stock
            ];
          }
        }

        foreach ($alert_products as $prod) {
          array_push($_SESSION['tab_alert']['id'], $prod['id']);
          array_push($_SESSION['tab_alert']['code'], $prod['code']);
          array_push($_SESSION['tab_alert']['name'], $prod['name']);
          array_push($_SESSION['tab_alert']['stock'], $prod['stock']);
          array_push($_SESSION['tab_alert']['stock_min'], $prod['stock_min']);
        }
        $_SESSION['count_alert'] = count($_SESSION['tab_alert']['id']);

        $pdo->commit();

        $_SESSION['invoice_id_to_print'] = $invoice_id;

        echo '<script>
    swal("Success", "Opération enregistrée avec succès. Facture #' . $invoice_id . '", "success").then(() => {
        window.location.href="print_receipt.php?id=' . $invoice_id . '";
    });
    </script>';
      } else {
        $pdo->rollBack();
        echo '<script>swal("Error", "Échec de l\'insertion de la facture.", "error");</script>';
      }
    } catch (Exception $e) {
      $pdo->rollBack();
      echo '<script>swal("Error", "Erreur lors de l\'enregistrement : ' . $e->getMessage() . '", "error");</script>';
    }
  }
}
?>

<style>
  .scanner-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .search-methods-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
  }

  .search-method {
    background: rgba(255, 255, 255, 0.95);
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .search-method-title {
    color: #667eea;
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .scanner-input-wrapper {
    position: relative;
  }

  #barcodeScanner {
    width: 100%;
    padding: 12px 40px 12px 12px;
    font-size: 16px;
    border: 2px solid #667eea;
    border-radius: 6px;
    transition: all 0.3s ease;
  }

  #barcodeScanner:focus {
    outline: none;
    border-color: #ffd700;
    box-shadow: 0 0 15px rgba(255, 215, 0, 0.4);
    transform: scale(1.01);
  }

  .scanner-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
    color: #667eea;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {

    0%,
    100% {
      opacity: 1;
    }

    50% {
      opacity: 0.5;
    }
  }

  .select2-container--default .select2-selection--single {
    height: 42px !important;
    border: 2px solid #667eea !important;
    border-radius: 6px !important;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px !important;
    padding-left: 12px !important;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
  }

  .product-row-highlight {
    background-color: #d4edda !important;
    animation: fadeIn 0.3s ease;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .stats-badge {
    display: inline-block;
    padding: 5px 15px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    color: white;
    margin: 0 5px;
    font-weight: bold;
  }

  .mode-indicator {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #28a745;
    color: white;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: bold;
  }

  @media (max-width: 768px) {
    .search-methods-container {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      🛒 Transaction Caisse - Mode Hybride
    </h1>
    <hr>
  </section>

  <section class="content container-fluid">

    <!-- SECTION DOUBLE RECHERCHE -->
    <div class="scanner-section">
      <div class="search-methods-container">

        <!-- MÉTHODE 1: Scanner Code-Barre -->
        <div class="search-method">
          <div class="search-method-title">
            <i class="fa fa-barcode"></i>
            SCANNER CODE-BARRE (Mode Rapide)
          </div>
          <div class="scanner-input-wrapper">
            <input
              type="text"
              id="barcodeScanner"
              placeholder="Scannez ou tapez le code exact..."
              autocomplete="off"
              autofocus>
            <i class="fa fa-barcode scanner-icon"></i>
            <span class="mode-indicator">F2</span>
          </div>
        </div>

        <!-- MÉTHODE 2: Recherche Manuelle -->
        <div class="search-method">
          <div class="search-method-title">
            <i class="fa fa-search"></i>
            RECHERCHE MANUELLE (Nom ou Code)
          </div>
          <select class="form-control select2" id="productSearchSelect" style="width: 100%;">
            <option value="">-- Rechercher un produit --</option>
          </select>
        </div>

      </div>

      <div style="text-align: center; margin-top: 15px;">
        <span class="stats-badge" id="itemCount">0 articles</span>
        <span class="stats-badge" id="totalItems">0 unités</span>
      </div>
    </div>

    <div class="box box-success">
      <form action="" method="POST" id="orderForm">
        <div class="box-body">

          <div class="col-md-4">
            <div class="form-group">
              <label>Nom Opérateur</label>
              <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-user"></i></div>
                <input type="text" class="form-control pull-right" name="cashier_name" value="<?php echo $_SESSION['user_name']; ?>" readonly>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Date de la transaction</label>
              <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <input type="text" class="form-control pull-right" name="orderdate" value="<?php echo date("d-m-Y"); ?>" readonly data-date-format="dd-mm-yyyy">
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Heure de transaction</label>
              <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                <input type="text" class="form-control pull-right" name="timeorder" value="<?php echo date('H:i') ?>" readonly>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label for="">Client</label>
              <select class="form-control select2" name="client" id="clientSelect" required>
                <option value="common">Client Régulier (common)</option>
                <?php
                $select_client = $pdo->prepare("SELECT * FROM users ORDER BY firstname");
                $select_client->execute();
                while ($row = $select_client->fetch(PDO::FETCH_ASSOC)) {
                ?>
                  <option value="<?php echo $row['username']; ?>"><?php echo $row['firstname'] . " " . $row['middlename'] . " " . $row['lastname']; ?></option>
                <?php
                }
                ?>
              </select>
            </div>
          </div>
        </div>

        <div class="box-body">
          <div class="col-md-12" style="overflow-x:auto;">
            <table class="table table-bordered table-hover" id="myOrder">
              <thead style="background-color: #f4f4f4;">
                <tr>
                  <th style="width: 100px;">Code</th>
                  <th style="width: 250px;">Libellé</th>
                  <th style="width: 80px;">Stock</th>
                  <th style="width: 100px;">Prix</th>
                  <th style="width: 100px;" class="badge badge-warning">Prix Min</th>
                  <th style="width: 70px;">Rem %</th>
                  <th style="width: 90px;">Rem Val</th>
                  <th style="width: 90px;">Quantité</th>
                  <th style="width: 60px;">Unité</th>
                  <th style="width: 120px;">Total</th>
                  <th style="width: 60px;">
                    <button type="button" class="btn btn-danger btn-sm" id="clearAllBtn" title="Vider le panier">
                      <i class="fa fa-trash"></i>
                    </button>
                  </th>
                </tr>
              </thead>
              <tbody>
                <!-- Les produits seront ajoutés ici dynamiquement -->
              </tbody>
            </table>
          </div>
        </div>

        <div class="box-body">
          <div class="col-md-offset-1 col-md-10">
            <div class="form-group">
              <label>Total HT (A Payer) </label>
              <div class="input-group">
                <input type="text" class="form-control pull-right" name="thetotal" id="thetotal" required readonly value="0.00">
                <div class="input-group-addon"><span>FCFA</span></div>
              </div>
            </div>

            <div class="form-group">
              <label>TVA (19.25%) </label>
              <div class="input-group">
                <input type="text" class="form-control pull-right" name="tva" id="tva" required readonly value="0.00">
                <div class="input-group-addon"><span>FCFA</span></div>
              </div>
            </div>

            <div class="form-group">
              <label>Total Remise (valeur)</label>
              <div class="input-group">
                <input type="text" class="form-control pull-right" name="remise" id="remise" required readonly value="0.00">
                <div class="input-group-addon"><span>FCFA</span></div>
              </div>
            </div>

            <div class="form-group">
              <label>Total TTC A Payer</label>
              <div class="input-group">
                <input type="text" class="form-control pull-right" name="total" id="total" required readonly value="0.00" style="font-size: 20px; font-weight: bold; background: #e8f5e9;">
                <div class="input-group-addon"><span>FCFA</span></div>
              </div>
            </div>

            <div class="form-group">
              <label>Argent reçu</label>
              <div class="input-group">
                <input type="text" class="form-control pull-right" name="paid" id="paid" required value="0" style="font-size: 18px;">
                <div class="input-group-addon"><span>FCFA</span></div>
              </div>
            </div>

            <div class="form-group">
              <label>Remboursement (Monnaie)</label>
              <div class="input-group">
                <input type="text" class="form-control pull-right" name="due" id="due" required readonly value="0.00" style="font-size: 18px; background: #fff3cd;">
                <div class="input-group-addon"><span>FCFA</span></div>
              </div>
            </div>

            <div class="form-group">
              <label>Mode Paiement</label>
              <select class="form-control" name="payment_mode" id="paymentMode" required>
                <option value="especes">Espèces</option>
                <option value="orange_money">Orange Money</option>
                <option value="mtn_money">MTN Money</option>
                <option value="carte_visa">Carte Visa</option>
                <option value="virement">Virement</option>
              </select>
            </div>
          </div>
        </div>

        <div class="box-footer" align="center">
          <input type="submit" name="save_order" value="💾 Enregistrer Opération (F9)" id="saveOrderBtn" class="btn btn-success btn-lg" onclick="return confirm('Êtes-vous sûr de vouloir enregistrer cette transaction ?')">
          <a href="order.php" class="btn btn-warning btn-lg">Annuler</a>
        </div>
      </form>
    </div>
  </section>
</div>

<script>
  $(document).ready(function() {

    let scanTimeout;
    let currentEditingRow = null;

    // ========================================
    // INITIALISATION SELECT2 POUR RECHERCHE PRODUIT
    // ========================================
    $('#productSearchSelect').select2({
      placeholder: 'Tapez le nom ou code du produit...',
      allowClear: true,
      minimumInputLength: 2,
      ajax: {
        url: 'search_products.php',
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            search: params.term
          };
        },
        processResults: function(data) {
          return {
            results: data
          };
        },
        cache: true
      },
      templateResult: formatProductOption,
      templateSelection: formatProductSelection
    });

    // Format d'affichage des options dans le dropdown
    function formatProductOption(product) {
      if (product.loading) return product.text;

      return $('<div class="select2-product-option">' +
        '<strong>' + product.product_code + '</strong> - ' + product.product_name +
        '<br><small style="color: #666;">Stock: ' + product.stock + ' | Prix: ' + product.sell_price + ' FCFA</small>' +
        '</div>');
    }

    // Format d'affichage de la sélection
    function formatProductSelection(product) {
      return product.product_code || product.text;
    }

    // ========================================
    // GESTION SÉLECTION PRODUIT MANUEL
    // ========================================
    $('#productSearchSelect').on('select2:select', function(e) {
      const data = e.params.data;

      if (data.product_id) {
        addOrUpdateProduct(data);

        // Réinitialiser le select
        $(this).val(null).trigger('change');

        // Retour au scanner
        setTimeout(function() {
          $('#barcodeScanner').focus();
        }, 100);
      }
    });

    // ========================================
    // FONCTION : Scanner de code-barre
    // ========================================
    $('#barcodeScanner').on('keypress', function(e) {
      if (e.which === 13) {
        e.preventDefault();

        const barcode = $(this).val().trim();

        if (barcode === '') {
          return;
        }

        searchProductByCode(barcode);
        $(this).val('');
      }
    });

    // ========================================
    // FONCTION : Recherche produit par code
    // ========================================
    function searchProductByCode(code) {
      $.ajax({
        url: 'get_product_by_code.php',
        method: 'POST',
        dataType: 'json',
        data: {
          code: code
        },
        success: function(data) {
          if (data && data.product_id) {
            addOrUpdateProduct(data);
          } else {
            playErrorSound();
            showNotification('❌ Produit non trouvé: ' + code, 'error');
          }
        },
        error: function() {
          playErrorSound();
          showNotification('⚠️ Erreur de communication avec le serveur', 'error');
        }
      });
    }

    // ========================================
    // FONCTION : Ajouter ou mettre à jour produit
    // ========================================
    function addOrUpdateProduct(data) {
      const productId = data.product_id;
      let existingRow = null;

      $('#myOrder tbody tr').each(function() {
        if ($(this).find('.productid').val() == productId) {
          existingRow = $(this);
          return false;
        }
      });

      if (existingRow) {
        updateExistingProduct(existingRow, data);
      } else {
        addNewProduct(data);
      }

      playSuccessSound();

      setTimeout(function() {
        $('#barcodeScanner').focus();
      }, 100);
    }

    // ========================================
    // FONCTION : Mettre à jour produit existant
    // ========================================
    function updateExistingProduct(row, data) {
      const currentQty = parseInt(row.find('.quantity_product').val()) || 0;
      const maxStock = parseInt(row.find('.productstock').val()) || 0;
      let newQty = currentQty + 1;

      if (newQty > maxStock) {
        if (currentQty < maxStock) {
          newQty = maxStock;
          showNotification('⚠️ Quantité ajustée au stock max (' + maxStock + ')', 'warning');
        } else {
          playErrorSound();
          showNotification('❌ Stock insuffisant pour ' + data.product_name, 'error');
          return;
        }
      }

      row.addClass('product-row-highlight');
      setTimeout(function() {
        row.removeClass('product-row-highlight');
      }, 800);

      row.find('.quantity_product').val(newQty).trigger('change');
      showNotification('✅ Quantité mise à jour: ' + data.product_name + ' (x' + newQty + ')', 'success');
    }

    // ========================================
    // FONCTION : Ajouter nouveau produit
    // ========================================
    function addNewProduct(data) {
      const html = `
      <tr class="product-row-highlight">
        <input type="hidden" class="productid" name="productid[]" value="${data.product_id}">
        <input type="hidden" class="productstock" name="productstock[]" value="${data.stock}">
        <input type="hidden" class="minstock" name="minstock[]" value="${data.min_stock}">
        
        <td><input type="text" class="form-control productcode" name="productcode[]" value="${data.product_code}" readonly></td>
        <td><input type="text" class="form-control productname" name="productname[]" value="${data.product_name}" readonly></td>
        <td><span class="badge badge-info">${data.stock}</span></td>
        <td><input type="text" class="form-control productprice" name="productprice[]" value="${data.sell_price}" style="width:100px;"></td>
        <td><input type="text" class="form-control productmin" name="productmin[]" value="${data.min_price}" readonly style="width:100px;"></td>
        <td><input type="text" class="form-control discount" name="discount[]" value="${data.discount}" style="width:70px;"></td>
        <td><input type="text" class="form-control remise" name="productremise[]" value="0" readonly style="width:90px;"></td>
        <td><input type="number" min="1" class="form-control quantity_product" name="quantity[]" value="1" style="width:90px;"></td>
        <td><input type="text" class="form-control productsatuan" name="productsatuan[]" value="${data.product_satuan}" readonly style="width:60px;"></td>
        <td><input type="text" class="form-control producttotal" name="producttotal[]" value="0" readonly style="width:120px;"></td>
        <td><button type="button" class="btn btn-danger btn-sm btn-remove"><i class="fa fa-times"></i></button></td>
      </tr>
    `;

      $('#myOrder tbody').append(html);

      const newRow = $('#myOrder tbody tr:last');
      newRow.find('.quantity_product').trigger('change');

      setTimeout(function() {
        newRow.removeClass('product-row-highlight');
      }, 800);

      showNotification('✅ Produit ajouté: ' + data.product_name, 'success');
      updateStats();
    }

    // ========================================
    // FONCTION : Calculs et gestion quantité/prix
    // ========================================
    $(document).on('keyup change', '.quantity_product, .productprice, .discount', function() {
      const tr = $(this).closest('tr');
      let quantity = parseInt(tr.find('.quantity_product').val()) || 0;
      const price = parseFloat(tr.find('.productprice').val()) || 0;
      const discount_rate = parseFloat(tr.find('.discount').val()) / 100 || 0;
      const max_stock = parseInt(tr.find('.productstock').val()) || 0;
      const min_price = parseFloat(tr.find('.productmin').val()) || 0;

      if (quantity > max_stock) {
        quantity = max_stock;
        tr.find('.quantity_product').val(quantity);
        showNotification('⚠️ Quantité limitée au stock disponible', 'warning');
      }

      if (quantity < 1) {
        quantity = 1;
        tr.find('.quantity_product').val(quantity);
      }

      if (price < min_price) {
        showNotification('❌ Prix inférieur au prix minimum (' + min_price + ')', 'error');
        tr.find('.productprice').css('border-color', 'red');
      } else {
        tr.find('.productprice').css('border-color', '');
      }

      const total_net = (1 - discount_rate) * quantity * price;
      const remise_val = discount_rate * quantity * price;

      tr.find('.producttotal').val(total_net.toFixed(2));
      tr.find('.remise').val(remise_val.toFixed(2));

      calculate(parseFloat($('#paid').val()));
      updateStats();
    });

    // ========================================
    // FONCTION : Suppression produit
    // ========================================
    $(document).on('click', '.btn-remove', function() {
      $(this).closest('tr').remove();
      calculate(parseFloat($('#paid').val()));
      updateStats();
      showNotification('🗑️ Produit retiré du panier', 'info');
    });

    // ========================================
    // FONCTION : Vider tout le panier
    // ========================================
    $('#clearAllBtn').on('click', function() {
      if (confirm('Voulez-vous vraiment vider tout le panier ?')) {
        $('#myOrder tbody').empty();
        calculate(0);
        updateStats();
        $('#barcodeScanner').focus();
        showNotification('🗑️ Panier vidé', 'info');
      }
    });

    // ========================================
    // FONCTION : Calcul des totaux
    // ========================================
    function calculate(paid) {
      let total_net_apres_remise = 0;
      let total_remise_valeur = 0;
      const tva_rate = 0.1925;

      $('.producttotal').each(function() {
        total_net_apres_remise += (parseFloat($(this).val()) || 0);
      });

      $('.remise').each(function() {
        total_remise_valeur += (parseFloat($(this).val()) || 0);
      });

      const total_ttc_avant_remise = total_net_apres_remise + total_remise_valeur;
      const total_ht_avant_tva = total_ttc_avant_remise / (1 + tva_rate);
      const tva = total_ttc_avant_remise - total_ht_avant_tva;
      const total_ttc_a_payer = total_net_apres_remise;
      const due = (parseFloat(paid) || 0) - total_ttc_a_payer;

      $('#thetotal').val(total_ht_avant_tva.toFixed(2));
      $('#remise').val(total_remise_valeur.toFixed(2));
      $('#tva').val(tva.toFixed(2));
      $('#total').val(total_ttc_a_payer.toFixed(2));
      $('#due').val(due.toFixed(2));

      if ((parseFloat(paid) || 0) < parseFloat(total_ttc_a_payer.toFixed(2))) {
        $('#paid').css('border-color', 'red');
        $('#saveOrderBtn').prop('disabled', true);
      } else {
        $('#paid').css('border-color', '');
        $('#saveOrderBtn').prop('disabled', false);
      }
    }

    // ========================================
    // FONCTION : Mise à jour statistiques
    // ========================================
    function updateStats() {
      const itemCount = $('#myOrder tbody tr').length;
      let totalUnits = 0;

      $('.quantity_product').each(function() {
        totalUnits += parseInt($(this).val()) || 0;
      });

      $('#itemCount').text(itemCount + ' article' + (itemCount > 1 ? 's' : ''));
      $('#totalItems').text(totalUnits + ' unité' + (totalUnits > 1 ? 's' : ''));
    }

    // ========================================
    // FONCTION : Notifications
    // ========================================
    function showNotification(message, type) {
      const bgColor = {
        'success': '#28a745',
        'error': '#dc3545',
        'warning': '#ffc107',
        'info': '#17a2b8'
      };

      const notification = $('<div>')
        .css({
          position: 'fixed',
          top: '20px',
          right: '20px',
          padding: '15px 25px',
          backgroundColor: bgColor[type] || '#333',
          color: 'white',
          borderRadius: '5px',
          zIndex: 9999,
          boxShadow: '0 4px 6px rgba(0,0,0,0.3)',
          fontWeight: 'bold',
          minWidth: '300px'
        })
        .text(message)
        .appendTo('body')
        .fadeIn(300);

      setTimeout(function() {
        notification.fadeOut(300, function() {
          $(this).remove();
        });
      }, 3000);
    }

    // ========================================
    // FONCTION : Sons
    // ========================================
    function playSuccessSound() {
      try {
        const audioContext = new(window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        oscillator.frequency.value = 800;
        oscillator.type = 'sine';
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.2);
      } catch (e) {
        console.log('Audio play failed');
      }
    }

    function playErrorSound() {
      try {
        const audioContext = new(window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        oscillator.frequency.value = 400;
        oscillator.type = 'sine';
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.3);
      } catch (e) {
        console.log('Audio play failed');
      }
    }

    // ========================================
    // GESTION PAIEMENT
    // ========================================
    $('#paid').on('keyup change', function() {
      const paid = parseFloat($(this).val()) || 0;
      calculate(paid);
    });

    // ========================================
    // RACCOURCIS CLAVIER
    // ========================================
    $(document).on('keydown', function(e) {
      if (e.key === 'F2') {
        e.preventDefault();
        $('#barcodeScanner').focus();
      }

      if (e.key === 'F3') {
        e.preventDefault();
        $('#productSearchSelect').select2('open');
      }

      if (e.key === 'F9') {
        e.preventDefault();
        if (!$('#saveOrderBtn').prop('disabled')) {
          if (confirm('Êtes-vous sûr de vouloir enregistrer cette transaction ?')) {
            $('#orderForm').submit();
          }
        }
      }

      if (e.key === 'Escape') {
        $('#barcodeScanner').focus();
      }
    });

    // ========================================
    // VALIDATION FORMULAIRE
    // ========================================
    $('#orderForm').on('submit', function(e) {
      const totalTTC = parseFloat($('#total').val()) || 0;
      const paidAmount = parseFloat($('#paid').val()) || 0;
      const itemCount = $('#myOrder tbody tr').length;

      if (itemCount === 0) {
        e.preventDefault();
        swal('Erreur', 'Aucun produit dans le panier.', 'error');
        return false;
      }

      calculate(paidAmount);

      if (paidAmount < totalTTC) {
        e.preventDefault();
        swal('Erreur de Paiement', 'Le montant d\'argent reçu (' + paidAmount.toFixed(2) + ' FCFA) est inférieur au total TTC à payer (' + totalTTC.toFixed(2) + ' FCFA). Veuillez ajuster le montant reçu.', 'error');
        $('#paid').focus();
        return false;
      }

      return true;
    });

    // ========================================
    // INITIALISATION
    // ========================================
    calculate(0);
    updateStats();
    $('#barcodeScanner').focus();

    setInterval(function() {
      if (!$(':focus').is('input[type="number"], #paid, #paymentMode, #clientSelect, .select2-search__field')) {
        $('#barcodeScanner').focus();
      }
    }, 2000);

  });
</script>

<?php
include_once 'inc/footer_all.php';
?>