<?php
// Inclure le fichier de connexion et de sécurité
include_once 'db/connect_db.php';

// Vérification de la session utilisateur
if (!isset($_SESSION['user_name']) || $_SESSION['user_name'] == "") {
  include_once 'inc/404.php';
  exit(); // Arrêter l'exécution si non connecté
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
// (Conservée pour la gestion des alertes après une vente réussie)
$_SESSION['tab_alert'] = array();
$_SESSION['tab_alert']['id'] = array();
$_SESSION['tab_alert']['code'] = array();
$_SESSION['tab_alert']['name'] = array();
$_SESSION['tab_alert']['stock'] = array();
$_SESSION['tab_alert']['stock_min'] = array();
$_SESSION['count_alert'] = 0;


// --- Logique de Sauvegarde de la Commande (Validation Critique Côté Serveur) ---
if (isset($_POST['save_order'])) {

  // Récupération des données du formulaire
  $cashier_name = $_POST['cashier_name'];
  $id_client = $_POST['client'];
  $order_date = date("Y-m-d", strtotime($_POST['orderdate']));
  $order_time = date("H:i:s", strtotime($_POST['timeorder']));

  // Totaux calculés
  $total = $_POST['total'];      // Total TTC
  $paid = $_POST['paid'];
  $due = $_POST['due'];
  $remise = $_POST['remise'];
  $tva = $_POST['tva'];
  $payment_mode = $_POST['payment_mode'];

  // Détails des produits commandés
  $arr_product_id = $_POST['productid'];
  $arr_product_code = $_POST['productcode'];
  $arr_product_name = $_POST['productname'];
  $arr_product_stock = $_POST['productstock']; // NOTE: Stock affiché (non fiable pour la validation)
  $arr_product_stockmin = $_POST['minstock'];
  $arr_product_qty = $_POST['quantity'];
  $arr_product_satuan = $_POST['productsatuan'];
  $arr_product_price = $_POST['productprice'];
  $arr_product_min = $_POST['productmin'];
  $arr_product_remise = $_POST['productremise'];
  $arr_product_total = $_POST['producttotal'];

  // Filtrer les lignes vides (où la quantité est 0 ou non définie)
  $valid_products = [];
  $has_product = false;
  for ($i = 0; $i < count($arr_product_id); $i++) {
    $qty = intval($arr_product_qty[$i]);
    if ($qty > 0) {
      $has_product = true;
      $valid_products[] = [
        'id' => $arr_product_id[$i],
        'code' => $arr_product_code[$i],
        'name' => $arr_product_name[$i],
        'stock' => $arr_product_stock[$i],
        'minstock' => $arr_product_stockmin[$i],
        'qty' => $qty,
        'satuan' => $arr_product_satuan[$i],
        'price' => $arr_product_price[$i],
        'min_price' => $arr_product_min[$i],
        'remise' => $arr_product_remise[$i],
        'total' => $arr_product_total[$i],
      ];
    }
  }

  if (!$has_product) {
    echo '<script type="text/javascript">
                jQuery(function validation(){
                    swal("Warning", "Veuillez ajouter des produits à la transaction.", "warning", {
                        button: "Continue",
                    });
                });
                </script>';
    return; // Sortir si aucun produit valide
  }

  try {
    // --- Étape 1 : Début de la transaction ---
    $pdo->beginTransaction();

    // --- Étape 2 : Vérification du stock réel et du prix minimum (Côté Serveur) ---
    $alert_products = [];
    foreach ($valid_products as $item) {
      $product_id = $item['id'];
      $requested_qty = $item['qty'];
      $min_price = $item['min_price'];
      $sold_price = $item['price'];

      // a) Récupération du stock réel actuel depuis la DB
      $stmt_stock = $pdo->prepare("SELECT stock FROM tbl_shop_item WHERE shop_code = :shop AND product_id = :id FOR UPDATE"); // FOR UPDATE verrouille la ligne
      $stmt_stock->bindParam(':shop', $shop);
      $stmt_stock->bindParam(':id', $product_id);
      $stmt_stock->execute();
      $current_stock_db = $stmt_stock->fetchColumn();

      if ($current_stock_db === false) {
        throw new Exception("Produit ID $product_id introuvable dans ce magasin.");
      }

      // b) Vérification de survente
      if ($requested_qty > $current_stock_db) {
        throw new Exception("Stock Insuffisant pour le produit " . $item['name'] . " (Demandé: $requested_qty, Disponible: $current_stock_db).");
      }

      // c) Vérification du prix minimum
      if ($sold_price < $min_price) {
        throw new Exception("Prix de vente inférieur au prix minimum pour " . $item['name'] . " (Min: $min_price, Vendu: $sold_price).");
      }
    }

    // --- Étape 3 : Insertion de la facture principale (après validation) ---
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

    if (!$invoice_id) {
      throw new Exception("Échec de l'insertion de la facture principale.");
    }

    // --- Étape 4 : Insertion des détails et Mise à jour du stock ---
    foreach ($valid_products as $item) {
      $product_id = $item['id'];
      $qty_sold = $item['qty'];
      $min_stock = $item['minstock'];

      // Mise à jour du stock (Décrémentation)
      // Utiliser une décrémentation directe pour plus de fiabilité
      $update_stock = $pdo->prepare("UPDATE tbl_shop_item SET stock = stock - :qty WHERE shop_code = :shop AND product_id = :id");
      $update_stock->bindParam(':qty', $qty_sold);
      $update_stock->bindParam(':shop', $shop);
      $update_stock->bindParam(':id', $product_id);
      $update_stock->execute();

      // Re-vérifier le stock après décrémentation pour l'alerte (moins critique ici)
      $new_stock_query = $pdo->prepare("SELECT stock FROM tbl_shop_item WHERE product_id = :id");
      $new_stock_query->bindParam(':id', $product_id);
      $new_stock_query->execute();
      $new_stock = $new_stock_query->fetchColumn();

      // Insertion du détail de la facture
      $insert_detail = $pdo->prepare("INSERT INTO tbl_invoice_detail(invoice_id, product_id, product_code, product_name, qty, product_satuan, price, total, order_date, remise)
                                            VALUES(:invid, :productid, :productcode, :productname, :qty, :productsatuan, :price, :total, :orderdate, :remise)");

      $insert_detail->bindParam(':invid', $invoice_id);
      $insert_detail->bindParam(':productid', $product_id);
      $insert_detail->bindParam(':productcode', $item['code']);
      $insert_detail->bindParam(':productname', $item['name']);
      $insert_detail->bindParam(':qty', $qty_sold);
      $insert_detail->bindParam(':productsatuan', $item['satuan']);
      $insert_detail->bindParam(':price', $item['price']);
      $insert_detail->bindParam(':total', $item['total']);
      $insert_detail->bindParam(':orderdate', $order_date);
      $insert_detail->bindParam(':remise', $item['remise']);
      $insert_detail->execute();

      // Gestion de l'alerte stock (si le stock atteint ou passe sous le seuil min)
      if ($new_stock <= $min_stock) {
        $alert_products[] = [
          'id' => $product_id,
          'code' => $item['code'],
          'name' => $item['name'],
          'stock' => $new_stock,
          'stock_min' => $min_stock
        ];
      }
    }

    // Mettre à jour la session d'alerte
    foreach ($alert_products as $prod) {
      array_push($_SESSION['tab_alert']['id'], $prod['id']);
      array_push($_SESSION['tab_alert']['code'], $prod['code']);
      array_push($_SESSION['tab_alert']['name'], $prod['name']);
      array_push($_SESSION['tab_alert']['stock'], $prod['stock']);
      array_push($_SESSION['tab_alert']['stock_min'], $prod['stock_min']);
    }
    $_SESSION['count_alert'] = count($_SESSION['tab_alert']['id']);


    // --- Étape 5 : Validation de la transaction ---
    $pdo->commit();

    // Succès et redirection
    $_SESSION['invoice_id_to_print'] = $invoice_id;
    echo '<script>
            swal("Success", "Opération enregistrée avec succès. Facture #' . $invoice_id . '", "success").then(() => {
                window.location.href="order.php";
            });
        </script>';
  } catch (Exception $e) {
    $pdo->rollBack();
    echo '<script>swal("Error", "Erreur lors de l\'enregistrement : ' . $e->getMessage() . '", "error");</script>';
  }
}
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      🛒 Transaction Caisse
    </h1>
    <hr>
  </section>

  <section class="content container-fluid">
    <div class="box box-success">
      <form action="" method="POST">
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
              <select class="form-control select2" name="client" required>
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
            <table class="table table-border" id="myOrder">
              <thead>
                <tr>
                  <th>Code/Libellé Search</th>
                  <th>Code Produit</th>
                  <th>Libellé</th>
                  <th>Stock</th>
                  <th>Prix</th>
                  <th class="badge badge-warning">Prix Min</th>
                  <th>Rem %</th>
                  <th>Rem Val</th>
                  <th>Quantité</th>
                  <th>Unité</th>
                  <th>Total</th>
                  <th>
                    <button type="button" name="addOrder" class="btn btn-success btn-sm btn_addOrder"><span><i class="fa fa-plus"></i></span></button>
                  </th>
                </tr>
              </thead>
              <tbody>
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
                <input type="text" class="form-control pull-right" name="total" id="total" required readonly value="0.00">
                <div class="input-group-addon"><span>FCFA</span></div>
              </div>
            </div>


            <div class="form-group">
              <label>Argent reçu</label>
              <div class="input-group">
                <input type="text" class="form-control pull-right" name="paid" id="paid" required value="0">
                <div class="input-group-addon"><span>FCFA</span></div>
              </div>
            </div>

            <div class="form-group">
              <label>Remboursement (Monnaie)</label>
              <div class="input-group">
                <input type="text" class="form-control pull-right" name="due" id="due" required readonly value="0.00">
                <div class="input-group-addon"><span>FCFA</span></div>
              </div>
            </div>

            <div class="form-group">
              <label>Mode Paiement</label>
              <select class="form-control" name="payment_mode" required>
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
          <input type="submit" name="save_order" value="Enregistrer Opération" class="btn btn-success" onclick="return confirm('Êtes-vous sûr de vouloir enregistrer cette transaction ?')">
          <a href="order.php" class="btn btn-warning">Annuler</a>
        </div>
      </form>
    </div>
  </section>
</div>
<script>
  // --- Fonction pour ajouter une nouvelle ligne de saisie (celle qui est toujours vide à la fin) ---
  function add_new_row() {
    var html = '';
    html += '<tr class="input-row">';
    html += '<td><input type="text" class="form-control productSearch" name="productSearch[]" style="width:250px;"><div class="productDropdown" style="position: absolute; z-index: 1000; display: none; background-color: #fff; border: 1px solid #ccc; max-height: 200px; overflow-y: auto;"></div><input type="hidden" class="form-control productid" name="productid[]" value=""></td>';

    html += '<td><input type="text" class="form-control productcode" style="width:100px;" name="productcode[]" readonly></td>';
    html += '<td><input type="text" class="form-control productname" style="width:200px;" name="productname[]" readonly></td>';

    // Masqué sur petit écran (col-hide-sm) - NOTE: Stock affiché, non utilisé pour la validation JS
    html += '<td class="col-hide-sm"><input type="text" class="form-control productstock" style="width:50px;" name="productstock[]" required readonly><input type="hidden" class="form-control minstock" style="width:50px;" name="minstock[]"></td>';

    html += '<td><input type="text" class="form-control productprice" style="width:90px;" name="productprice[]" value="0"></td>';

    // Masqué sur petit écran (col-hide-sm)
    html += '<td class="col-hide-sm"><input type="text" class="form-control productmin btn btn-outline-dark" style="width:90px;" name="productmin[]" readonly></td>';

    html += '<td><input type="text" class="form-control discount" style="width:40px;" name="discount[]" value="0"></td>';

    // Masqué sur petit écran (col-hide-sm)
    html += '<td class="col-hide-sm"><input type="text" class="form-control remise" style="width:60px;" name="productremise[]" readonly value="0"></td>';

    html += '<td><input type="number" min="1" class="form-control quantity_product" style="width:70px;" name="quantity[]" required value="0" readonly></td>';

    // Masqué sur petit écran (col-hide-sm)
    html += '<td class="col-hide-sm"><input type="text" class="form-control productsatuan" style="width:40px;" name="productsatuan[]" readonly></td>';

    html += '<td><input type="text" class="form-control producttotal" style="width:130px;" name="producttotal[]" readonly value="0.00"></td>';

    html += '<td style="width: 50px;"><button type="button" name="remove" class="btn btn-danger btn-sm btn-remove" disabled><i class="fa fa-remove"></i></button></td>'
    html += '</tr>';

    $('#myOrder tbody').append(html);
    $('.input-row:last .productSearch').focus();
  }

  // --- Fonction pour incrémenter la quantité d'une ligne existante (CHECK DE STOCK RETIRÉ) ---
  // function update_existing_row(tr_existante, data) {
  //   var current_qty = parseInt(tr_existante.find(".quantity_product").val());
  //   var new_qty = current_qty + 1;

  //   // La vérification de stock est maintenant faite côté serveur

  //   tr_existante.find(".quantity_product").val(new_qty).trigger('change');
  //   tr_existante.find(".quantity_product").prop('readonly', false);
  //   tr_existante.find(".btn-remove").prop('disabled', false);
  // }
  function update_existing_row(tr_existante, data) {
    var current_qty = parseInt(tr_existante.find(".quantity_product").val());
    var max_stock = parseInt(tr_existante.find(".productstock").val());
    var new_qty = current_qty + 1;
    var product_name = data['product_name'];

    // Si la nouvelle quantité dépasse le stock
    if (new_qty > max_stock) {
      // Option 1 : Bloquer l'incrémentation (Comportement actuel)
      // swal("Warning", "Stock Insuffisant. Maximum atteint pour " + product_name + " (Max: " + max_stock + ").", "warning");
      // return;


      // Option 2 : Forcer la quantité à être égale au stock (Vente du stock restant)
      if (current_qty < max_stock) {
        new_qty = max_stock;
        swal("Info", "Quantité ajustée au stock maximum (" + max_stock + ") pour " + product_name, "info");
      } else {
        swal("Warning", "Stock Insuffisant. Maximum atteint pour " + product_name + " (Max: " + max_stock + ").", "warning");
        return;
      }

    }

    // Mettre à jour la quantité (si non bloquée par le 'return')
    tr_existante.find(".quantity_product").val(new_qty).trigger('change');
    tr_existante.find(".quantity_product").prop('readonly', false);
    tr_existante.find(".btn-remove").prop('disabled', false);
  }

  // --- Fonction pour populer les détails du produit sur une nouvelle ligne ---
  function populate_new_row(tr, data) {
    tr.find(".productid").val(data["product_id"]);
    tr.find(".productcode").val(data["product_code"]);
    tr.find(".productname").val(data["product_name"]);
    tr.find(".productstock").val(data["stock"]);
    tr.find(".minstock").val(data["min_stock"]);
    tr.find(".productsatuan").val(data["product_satuan"]);
    tr.find(".productprice").val(data["sell_price"]);
    tr.find(".productmin").val(data["min_price"]);
    tr.find(".discount").val(data["discount"]);
    tr.find(".quantity_product").val(1).prop('readonly', false);
    tr.find(".btn-remove").prop('disabled', false);

    tr.find(".quantity_product").trigger('change');

    tr.removeClass('input-row');
    add_new_row();
  }


  $(document).ready(function() {

    // Ajout du CSS Responsive (à mettre dans votre feuille de style ou <head>)
    $('head').append('<style>@media screen and (max-width: 767px) {.col-hide-sm, #myOrder thead th.col-hide-sm {display: none !important;} #myOrder {width: 100%; min-width: 600px;} #myOrder input[type="text"], #myOrder input[type="number"] {width: 100% !important; box-sizing: border-box;}}</style>');


    // 1. Bouton d'ajout manuel
    $(document).on('click', '.btn_addOrder', function() {
      add_new_row();
    });

    // 2. Gestion de la recherche (Code ou Libellé) avec autocomplétion
    $(document).on('keyup', '.productSearch', function() {
      var query = $(this).val();
      var tr = $(this).closest('tr');
      var dropdown = tr.find('.productDropdown');

      if (query.length < 2) {
        dropdown.hide().html('');
        return;
      }

      $.ajax({
        url: 'get_products.php',
        method: 'POST',
        data: {
          query: query
        },
        success: function(data) {
          if (data.trim() !== "") {
            dropdown.html(data);
            dropdown.show();
          } else {
            dropdown.hide().html('');
          }
        }
      });
    });

    // 3. Gestion de la sélection dans le dropdown
    $(document).on('click', '.productDropdown li', function() {
      var selected_li = $(this);
      var tr = selected_li.closest('tr');
      var productId = selected_li.data('product-id');

      tr.find('.productDropdown').hide();

      $.ajax({
        url: "get_product.php",
        method: "get",
        dataType: "json",
        data: {
          id: productId
        },
        success: function(data) {
          if (data && data["product_id"]) {

            var already_added = false;
            $('#myOrder tbody tr:not(.input-row)').each(function() {
              if ($(this).find('.productid').val() == productId) {
                update_existing_row($(this), data);
                already_added = true;
                tr.remove();
                add_new_row(); // Rajoute la ligne de saisie vide
                return false;
              }
            });

            if (!already_added) {
              tr.find('.productSearch').val(data["product_code"] + ' - ' + data["product_name"]);
              populate_new_row(tr, data);
            }
          } else {
            swal("Erreur", "Produit introuvable.", "error");
          }
        },
        error: function() {
          swal("Erreur", "Erreur de communication avec le serveur pour les détails du produit.", "error");
        }
      })
    });

    // 4. Suppression d'une ligne
    $(document).on('click', '.btn-remove', function() {
      $(this).closest('tr').remove();
      calculate(parseFloat($("#paid").val()));
    })

    // 5. Gestion de la quantité, prix et remise par ligne
    $("#myOrder").delegate(".quantity_product, .productprice, .discount", "keyup change", function() {
      var tr = $(this).closest('tr');
      var quantity = parseInt(tr.find(".quantity_product").val()) || 0;
      var price = parseFloat(tr.find(".productprice").val()) || 0;
      var discount_rate = parseFloat(tr.find(".discount").val()) / 100 || 0;

      // NOTE : Le contrôle de la quantité vs stock max est fait ici uniquement si le max HTML est défini
      if (quantity < 0) {
        quantity = 1;
        tr.find(".quantity_product").val(quantity);
      }

      var total_net_produit = (1 - discount_rate) * quantity * price;
      var remise_val_produit = discount_rate * quantity * price;

      tr.find(".producttotal").val(total_net_produit.toFixed(2));
      tr.find(".remise").val(remise_val_produit.toFixed(2));

      calculate(parseFloat($("#paid").val()));

      // Vérification du prix minimum (peut rester en client-side pour feedback immédiat)
      if ($(this).hasClass('productprice')) {
        var min_price = parseFloat(tr.find(".productmin").val()) || 0;
        if (price < min_price) {
          // C'est un simple avertissement/feedback. La VRAIE validation est côté serveur.
          // swal("Warning", "Avertissement : Le prix de vente est inférieur au prix minimum (" + min_price + ").", "warning");
        }
      }
    });

    // 6. Fonction de calcul globale
    function calculate(paid) {
      var total_net_apres_remise = 0;
      var total_remise_valeur = 0;

      $(".producttotal").each(function() {
        total_net_apres_remise += (parseFloat($(this).val()) || 0);
      });

      $(".remise").each(function() {
        total_remise_valeur += (parseFloat($(this).val()) || 0);
      });

      var total_ht_avant_tva = total_net_apres_remise + total_remise_valeur;

      var tva_rate = 0.1925;
      var tva = total_net_apres_remise * tva_rate;

      var total_ttc_a_payer = total_net_apres_remise + tva;

      var due = (paid || 0) - total_ttc_a_payer;

      $("#thetotal").val(total_ht_avant_tva.toFixed(2));
      $("#remise").val(total_remise_valeur.toFixed(2));
      $("#tva").val(tva.toFixed(2));
      $("#total").val(total_ttc_a_payer.toFixed(2));
      $("#due").val(due.toFixed(2));
    }

    // 7. Gestion du paiement (Argent reçu)
    $("#paid").keyup(function() {
      var paid = parseFloat($(this).val()) || 0;
      calculate(paid);
    });

    // 8. Initialisation
    if ($('#myOrder tbody tr').length === 0) {
      add_new_row();
    }

  });
</script>

<?php
include_once 'inc/footer_all.php';
?>