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

// --- Fonctions utilitaires (peuvent être conservées, mais non utilisées directement dans la nouvelle logique JS) ---

function fill_product($pdo)
{
  $output = '';
  $s = $_SESSION['magasin'];
  $req = "SELECT * FROM tbl_shop_item WHERE shop_code = ?";
  $select = $pdo->prepare($req);
  $select->execute([$s]);
  $result = $select->fetchAll();

  foreach ($result as $row) {
    $output .= '<option value="' . $row['product_id'] . '">' . $row["product_name"] . '_' . $row["product_code"] . '</option>';
  }
  return $output;
}

function fill_client($pdo)
{
  $output = '';
  $select = $pdo->prepare("SELECT * FROM users ORDER BY username");
  $select->execute();
  $result = $select->fetchAll();

  foreach ($result as $row) {
    $output .= '<option value="' . $row['username'] . '">' . $row["firstname"] . ' ' . $row["middlename"] . ' ' . $row["lastname"] . '</option>';
  }
  return $output;
}

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

  // Récupération des données du formulaire
  $cashier_name = $_POST['cashier_name'];
  $id_client = $_POST['client'];
  $order_date = date("Y-m-d", strtotime($_POST['orderdate']));
  $order_time = date("H:i:s", strtotime($_POST['timeorder'])); // Ajout des secondes pour précision

  // Totaux calculés
  $total = $_POST['total'];      // Total TTC
  $paid = $_POST['paid'];
  $due = $_POST['due'];
  $remise = $_POST['remise'];
  $tva = $_POST['tva'];
  $payment_mode = $_POST['payment_mode'];

  // Détails des produits
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

  // Vérification minimale
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


      // Début de la transaction
      $pdo->beginTransaction();

      // 1. Insertion de la facture principale
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
        $alert_products = []; // Pour stocker les produits en alerte

        // 2. Traitement des détails de la facture et mise à jour du stock
        for ($i = 0; $i < count($arr_product_id); $i++) {

          $product_id = $arr_product_id[$i];
          $qty_sold = $arr_product_qty[$i];
          $current_stock = $arr_product_stock[$i];
          $min_stock = $arr_product_stockmin[$i];
          $price_sold = $arr_product_price[$i];
          $min_price = $arr_product_min[$i];

          // Calculs
          $rem_qty = $current_stock - $qty_sold; // Nouveau stock restant
          $diff_price = $price_sold - $min_price; // Différence prix vendu et prix min
          $reste_stock_min = $rem_qty - $min_stock; // Écart avec le stock min

          // --- Vérifications (Ces alertes devraient idéalement être gérées en JS avant soumission) ---
          // if ($qty_sold > $current_stock) {
          //   $has_error = true;
          //   // On ne devrait pas arriver ici si le JS fonctionne
          //   throw new Exception("Stock insuffisant pour le produit " . $arr_product_code[$i]);
          // }
          if ($diff_price < 0) {
            $has_error = true;
            // On ne devrait pas arriver ici si le JS fonctionne
            throw new Exception("Prix de vente inférieur au prix minimum pour " . $arr_product_code[$i]);
          }
          // -----------------------------------------------------------------------------------------

          // Mise à jour du stock
          $update_stock = $pdo->prepare("UPDATE tbl_shop_item SET stock = :new_stock WHERE shop_code = :shop AND product_id = :id");
          $update_stock->bindParam(':new_stock', $rem_qty);
          $update_stock->bindParam(':shop', $shop);
          $update_stock->bindParam(':id', $product_id);
          $update_stock->execute();

          // Insertion du détail de la facture
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

          // Gestion de l'alerte stock (si le stock atteint ou passe sous le seuil min)
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

        // Mettre à jour la session d'alerte après le succès de la transaction
        foreach ($alert_products as $prod) {
          array_push($_SESSION['tab_alert']['id'], $prod['id']);
          array_push($_SESSION['tab_alert']['code'], $prod['code']);
          array_push($_SESSION['tab_alert']['name'], $prod['name']);
          array_push($_SESSION['tab_alert']['stock'], $prod['stock']);
          array_push($_SESSION['tab_alert']['stock_min'], $prod['stock_min']);
        }
        $_SESSION['count_alert'] = count($_SESSION['tab_alert']['id']);


        // Valider la transaction
        $pdo->commit();

        // Redirection après succès (peut-être vers la page d'impression de reçu)
        // Note: La redirection en JS permet de ne pas resoumettre le formulaire
        $_SESSION['invoice_id_to_print'] = $invoice_id; // Stocker l'ID pour l'impression

        echo '<script>
    swal("Success", "Opération enregistrée avec succès. Facture #' . $invoice_id . '", "success").then(() => {
        window.location.href="print_receipt.php?id=' . $invoice_id . '"; // REDIRECTION VERS L\'IMPRESSION
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
          <input type="submit" name="save_order" value="Enregistrer Opération" id="saveOrderBtn" class="btn btn-success" onclick="return confirm('Êtes-vous sûr de vouloir enregistrer cette transaction ?')">
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
    html += '<td><input type="text" class="form-control productname" style="width:220px;" name="productname[]" readonly></td>';

    html += '<td><input type="text" class="form-control productstock" style="width:50px;" name="productstock[]" required readonly><input type="hidden" class="form-control minstock" style="width:50px;" name="minstock[]"></td>';

    html += '<td><input type="text" class="form-control productprice" style="width:90px;" name="productprice[]" value="0"></td>';

    html += '<td><input type="text" class="form-control productmin btn btn-outline-dark" style="width:90px;" name="productmin[]" readonly></td>';

    html += '<td><input type="text" class="form-control discount" style="width:40px;" name="discount[]" value="0"></td>';

    html += '<td><input type="text" class="form-control remise" style="width:60px;" name="productremise[]" readonly value="0"></td>';

    html += '<td><input type="number" min="1" class="form-control quantity_product" style="width:70px;" name="quantity[]" required value="0" readonly></td>';

    html += '<td><input type="text" class="form-control productsatuan" style="width:40px;" name="productsatuan[]" readonly></td>';

    html += '<td><input type="text" class="form-control producttotal" style="width:130px;" name="producttotal[]" readonly value="0.00"></td>';

    html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm btn-remove" disabled><i class="fa fa-remove"></i></button></td>'
    html += '</tr>';

    $('#myOrder tbody').append(html);
    $('.input-row:last .productSearch').focus(); // Focus sur le nouveau champ
  }

  // --- Fonction pour incrémenter la quantité d'une ligne existante ---
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
    tr.find(".quantity_product").val(1).prop('readonly', false); // Initialiser à 1 et rendre éditable
    tr.find(".btn-remove").prop('disabled', false);

    tr.find(".quantity_product").trigger('change');

    tr.removeClass('input-row');
    add_new_row(); // Ajouter automatiquement une nouvelle ligne de saisie
  }


  $(document).ready(function() {

    // ✅ CORRECTION 1 : Le bouton d'ajout manuel fonctionne
    $(document).on('click', '.btn_addOrder', function() {
      add_new_row();
    });

    // 1. Gestion de la recherche (Code ou Libellé) avec autocomplétion
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

    // 2. Gestion de la sélection dans le dropdown (Scan/Sélection)
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

                // ✅ CORRECTION 2 : Ajouter la nouvelle ligne de saisie automatique
                add_new_row();

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

    // 3. Suppression d'une ligne
    $(document).on('click', '.btn-remove', function() {
      $(this).closest('tr').remove();
      calculate(parseFloat($("#paid").val()));
    })

    // 4. Gestion de la quantité, prix et remise par ligne
    $("#myOrder").delegate(".quantity_product, .productprice, .discount", "keyup change", function() {
      var tr = $(this).closest('tr');
      var quantity = parseInt(tr.find(".quantity_product").val()) || 0;
      var price = parseFloat(tr.find(".productprice").val()) || 0;
      var discount_rate = parseFloat(tr.find(".discount").val()) / 100 || 0;
      var max_stock = parseInt(tr.find(".productstock").val()) || 0;

      if (quantity > max_stock) {
        swal("Warning", "Stock Insuffisant pour ce produit. Max: " + max_stock, "warning");
        quantity = max_stock;
        tr.find(".quantity_product").val(quantity);
      }
      if (quantity < 0) {
        quantity = 1;
        tr.find(".quantity_product").val(quantity);
      }

      var total_net_produit = (1 - discount_rate) * quantity * price;
      var remise_val_produit = discount_rate * quantity * price;

      tr.find(".producttotal").val(total_net_produit.toFixed(2));
      tr.find(".remise").val(remise_val_produit.toFixed(2));

      calculate(parseFloat($("#paid").val()));

      if ($(this).hasClass('productprice')) {
        var min_price = parseFloat(tr.find(".productmin").val()) || 0;
        if (price < min_price) {
          swal("Warning", "Erreur : Le prix de vente doit être supérieur ou égal au prix minimum (" + min_price + ").", "warning");
        }
      }
    });

    // 5. Fonction de calcul globale
    /**
     * Calcule les totaux de la commande, y compris la TVA (Taxe sur la valeur ajoutée) et la remise.
     * * Hypothèses de l'utilisateur:
     * 1. La valeur dans .producttotal est le prix net final APRES remise.
     * 2. La TVA est incluse AVANT la remise (ce qui suggère qu'elle fait partie du prix unitaire de base).
     * 3. Nous allons DÉDUIRE la TVA pour trouver le HT à partir du TTC total si nécessaire.
     */
    function calculate(paid) {
      // 1. Initialiser les totaux
      var total_net_apres_remise = 0; // Somme des .producttotal (Montant total TTC dû APRES remise)
      var total_remise_valeur = 0; // Somme des .remise (Montant total de la remise)
      var tva_rate = 0.1925; // Taux de TVA (19.25%)

      // 2. Calculer le total net TTC après remise
      $(".producttotal").each(function() {
        // total_net_apres_remise est le total TTC DÛ (après remise)
        total_net_apres_remise += (parseFloat($(this).val()) || 0);
      });

      // 3. Calculer le total de la remise
      $(".remise").each(function() {
        total_remise_valeur += (parseFloat($(this).val()) || 0);
      });

      // 4. Calculer le Total TTC AVANT remise (cela inclut la TVA)
      // C'est le montant qui aurait été dû sans la remise.
      var total_ttc_avant_remise = total_net_apres_remise + total_remise_valeur;

      // 5. Calculer le Total HT AVANT TVA (Total Hors Taxe, avant remise)
      // Nous déduisons la TVA du total TTC avant remise pour trouver le HT.
      // TTC = HT * (1 + tva_rate)  =>  HT = TTC / (1 + tva_rate)
      var total_ht_avant_tva = total_ttc_avant_remise / (1 + tva_rate);

      // 6. Calculer le Montant de la TVA (sur le total TTC avant remise)
      // TVA = TTC - HT
      var tva = total_ttc_avant_remise - total_ht_avant_tva;

      // Le montant final à payer (TTC) est déjà total_net_apres_remise
      var total_ttc_a_payer = total_net_apres_remise;
      var total_ttc_a_payer_fixe = total_ttc_a_payer.toFixed(2);


      // 7. Calculer le 'Reste à payer' (Due)
      // Assurez-vous que 'paid' est bien un nombre.
      var due = (parseFloat(paid) || 0) - total_ttc_a_payer;

      // 8. Mettre à jour les champs
      // Note: Utiliser le total HT (avant TVA et remise) pour #thetotal
      $("#thetotal").val(total_ht_avant_tva.toFixed(2));
      $("#remise").val(total_remise_valeur.toFixed(2));
      $("#tva").val(tva.toFixed(2));
      $("#total").val(total_ttc_a_payer_fixe); // Total TTC Final (APRES remise)
      $("#due").val(due.toFixed(2));

      // 9. 🛑 Vérification de l'argent reçu (logique inchangée)
      if ((parseFloat(paid) || 0) < parseFloat(total_ttc_a_payer_fixe)) {
        $("#paid").css('border-color', 'red');
        $("#saveOrderBtn").prop('disabled', true);
      } else {
        $("#paid").css('border-color', ''); // Réinitialiser la couleur
        $("#saveOrderBtn").prop('disabled', false);
      }
    }

    // 6. Gestion du paiement (Argent reçu)
    $("#paid").keyup(function() {
      var paid = parseFloat($(this).val()) || 0;
      calculate(paid);
    });

    // 7. 🛑 NOUVEAUTÉ : Validation finale lors de la soumission du formulaire
    $('#orderForm').on('submit', function(e) {
      var totalTTC = parseFloat($("#total").val()) || 0;
      var paidAmount = parseFloat($("#paid").val()) || 0;

      // S'assurer que le calcul est à jour avant la soumission
      calculate(paidAmount);

      if (paidAmount < totalTTC) {
        e.preventDefault(); // Empêcher l'envoi du formulaire
        swal("Erreur de Paiement", "Le montant d'argent reçu (" + paidAmount.toFixed(2) + " FCFA) est inférieur au total TTC à payer (" + totalTTC.toFixed(2) + " FCFA). Veuillez ajuster le montant reçu.", "error");
        $("#paid").focus();
        return false;
      }
      return true; // Continuer la soumission si la vérification est OK
    });


    // Initialisation au chargement de la page
    if ($('#myOrder tbody tr').length === 0) {
      add_new_row();
    }
    // Appel initial pour s'assurer que le bouton est désactivé si le total est > 0 et paid = 0
    calculate(parseFloat($("#paid").val()));

  });
</script>

<?php
include_once 'inc/footer_all.php';
?>