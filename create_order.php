<?php
// Utilisation d'un fichier unique pour la démonstration, les endpoints AJAX doivent être implémentés séparément.
include_once 'db/connect_db.php';

// Vérification de l'authentification et du rôle (logique originale conservée)
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['user_name']) || $_SESSION['user_name'] == "") {
  include_once 'inc/404.php';
  exit(); // Important pour arrêter l'exécution si non authentifié
} else {
  if ($_SESSION['role'] == "Admin") {
    include_once 'inc/header_all.php';
  } else {
    include_once 'inc/header_all_operator.php';
  }
}

// Configuration
error_reporting(0);
date_default_timezone_set('Africa/Douala');
$shop = isset($_SESSION['magasin']) ? $_SESSION['magasin'] : 'default_shop';
$tva_rate = 0.1925; // 19.25%

// Initialisation des tableaux d'alerte de stock
$_SESSION['tab_alert'] = array(
  'id' => array(),
  'code' => array(),
  'name' => array(),
  'stock' => array(),
  'stock_min' => array()
);
$_SESSION['count_alert'] = 0;

if (isset($_POST['save_order'])) {
  $cashier_name = $_POST['cashier_name'];
  $id_client = $_POST['client'];
  $order_date = date("Y-m-d", strtotime($_POST['orderdate']));
  $order_time = date("H:i:s", strtotime($_POST['timeorder'])); // Ajout des secondes pour plus de précision
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
  $arr_discount = $_POST['discount'];
  $arr_product_total = $_POST['producttotal'];
  $arr_product_remise = $_POST['productremise'];

  if (empty($arr_product_code) || count($arr_product_code) < 1) {
    echo '<script type="text/javascript">
            jQuery(function validation(){
                swal("Warning", "Veuillez ajouter des articles pour la transaction.", "warning", {
                    button: "Continuer",
                });
            });
            </script>';
  } else {
    try {
      // 1. Insertion dans tbl_invoice
      $insert = $pdo->prepare("INSERT INTO 
                tbl_invoice(cashier_name, id_client, order_date, time_order, total, paid, due, remise, tva, payment_mode)
                values(:name, :id_client, :orderdate, :timeorder, :total, :paid, :due, :remise, :tva, :payment_mode)");

      $insert->bindParam(':name', $cashier_name);
      $insert->bindParam(':id_client', $id_client);
      $insert->bindParam(':orderdate', $order_date);
      $insert->bindParam(':timeorder', $order_time);
      $insert->bindParam(':total', $total);
      $insert->bindParam(':paid', $paid);
      $insert->bindParam(':due', $due);
      $insert->bindParam(':remise', $remise);
      $insert->bindParam(':tva', $tva);
      $insert->bindParam(':payment_mode', $payment_mode);
      $insert->execute();

      $invoice_id = $pdo->lastInsertId();

      if ($invoice_id != null) {
        // 2. Traitement des détails de la facture et mise à jour du stock
        for ($i = 0; $i < count($arr_product_id); $i++) {

          $rem_qty = $arr_product_stock[$i] - $arr_product_qty[$i];
          $diff = $arr_product_price[$i] - $arr_product_min[$i];
          $reste = $rem_qty - $arr_product_stockmin[$i];

          if ($arr_product_qty[$i] <= 0) continue; // Ignorer les quantités nulles

          // Vérification Stock et Prix Min
          if ($rem_qty < 0) {
            // Stock insuffisant - ceci devrait normalement être géré côté client, mais on vérifie en dernier recours
            echo '<script type="text/javascript">
                            jQuery(function validation(){
                                swal("Erreur", "Stock Insuffisant pour ' . $arr_product_name[$i] . ' !", "error", {
                                    button: "Continue",
                                });
                            });
                            </script>';
            // On pourrait ajouter un break ou un rollback ici si la transaction était cruciale.
            continue;
          }

          if ($diff < 0) {
            // Prix de vente inférieur au prix minimum
            echo '<script type="text/javascript">
                            jQuery(function validation(){
                                swal("Attention", "Le prix de vente (' . $arr_product_price[$i] . ') est inférieur au prix minimum (' . $arr_product_min[$i] . ') pour ' . $arr_product_name[$i] . '. Contactez un Admin.", "warning", {
                                    button: "Continue",
                                });
                            });
                            </script>';
            continue; // On continue mais avec un avertissement
          }

          // Mise à jour du stock (Requête préparée pour la sécurité)
          $update = $pdo->prepare("UPDATE tbl_shop_item SET stock = :stock WHERE shop_code = :shop_code AND product_id = :product_id");
          $update->bindParam(':stock', $rem_qty);
          $update->bindParam(':shop_code', $shop);
          $update->bindParam(':product_id', $arr_product_id[$i]);
          $update->execute();

          // Insertion dans tbl_invoice_detail (Requête préparée)
          $insert_detail = $pdo->prepare("INSERT INTO tbl_invoice_detail(invoice_id, product_id, product_code, product_name, qty, product_satuan, price, total, order_date, remise)
                        values(:invid, :productid, :productcode, :productname, :qty, :productsatuan, :price, :total, :orderdate, :remise)");

          $insert_detail->bindParam(':invid', $invoice_id);
          $insert_detail->bindParam(':productid', $arr_product_id[$i]);
          $insert_detail->bindParam(':productcode', $arr_product_code[$i]);
          $insert_detail->bindParam(':productname', $arr_product_name[$i]);
          $insert_detail->bindParam(':qty', $arr_product_qty[$i]);
          $insert_detail->bindParam(':productsatuan', $arr_product_satuan[$i]);
          $insert_detail->bindParam(':price', $arr_product_price[$i]);
          $insert_detail->bindParam(':total', $arr_product_total[$i]);
          $insert_detail->bindParam(':orderdate', $order_date);
          $insert_detail->bindParam(':remise', $arr_product_remise[$i]);
          $insert_detail->execute();

          // Gestion des alertes de stock minimum
          if ($reste <= 0) {
            array_push($_SESSION['tab_alert']['id'], $arr_product_id[$i]);
            array_push($_SESSION['tab_alert']['code'], $arr_product_code[$i]);
            array_push($_SESSION['tab_alert']['name'], $arr_product_name[$i]);
            array_push($_SESSION['tab_alert']['stock'], $rem_qty);
            array_push($_SESSION['tab_alert']['stock_min'], $arr_product_stockmin[$i]);
            $_SESSION['count_alert'] = count($_SESSION['tab_alert']['id']);
          }
        }

        // Redirection après succès
        echo '<script>location.href="order.php?invoice_id=' . $invoice_id . '";</script>';
      }
    } catch (PDOException $e) {
      // Gérer l'erreur de base de données
      error_log("DB Error: " . $e->getMessage());
      echo '<script type="text/javascript">
                jQuery(function validation(){
                    swal("Erreur", "Erreur lors de l\'enregistrement de la commande: ' . $e->getMessage() . '", "error");
                });
                </script>';
    }
  }
}

// Fonction pour récupérer les produits par code pour l'AJAX (doit être dans un fichier séparé 'get_product_by_code.php' dans un environnement réel)
// ** NOTE: Dans un environnement réel, ce bloc devrait être dans un fichier PHP séparé appelé par AJAX,
// et sécurisé (ex: `get_product_by_code.php`). Ici, on simule l'appel AJAX.**
/*
if (isset($_GET['action']) && $_GET['action'] == 'getProductByCode' && isset($_GET['code'])) {
    $product_code = $_GET['code'];
    $req = "SELECT * FROM tbl_shop_item WHERE shop_code = ? AND (product_code = ? OR product_name LIKE ?)";
    $select = $pdo->prepare($req);
    // Utiliser LIKE pour simuler une recherche par partie du nom/code si la correspondance exacte n'est pas trouvée
    $select->execute([$shop, $product_code, '%' . $product_code . '%']);
    $product = $select->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($product ? $product : ['error' => 'Product not found']);
    exit();
}
*/
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Nouvelle Transaction Caisse
      <small><?php echo $_SESSION['magasin']; ?></small>
    </h1>
    <hr>
  </section>

  <!-- Main content -->
  <section class="content container-fluid">
    <div class="box box-success">
      <form action="" method="POST" id="orderForm">
        <div class="box-body">

          <!-- LIGNE 1: Infos Transaction -->
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Nom Opérateur</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-user"></i></span>
                  <input type="text" class="form-control" name="cashier_name" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" readonly>
                </div>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label>Date</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  <input type="text" class="form-control" name="orderdate" value="<?php echo date("d-m-Y"); ?>" readonly>
                </div>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label>Heure</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                  <input type="text" class="form-control" name="timeorder" value="<?php echo date('H:i'); ?>" readonly>
                </div>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="client">Client</label>
                <select class="form-control select2" name="client" id="client" required>
                  <option value="common" selected>Client Commun</option>
                  <?php
                  $select = $pdo->prepare("SELECT username, firstname, middlename, lastname FROM users ORDER BY firstname");
                  $select->execute();
                  while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                    $fullName = htmlspecialchars($row['firstname'] . " " . $row['middlename'] . " " . $row['lastname']);
                  ?>
                    <option value="<?php echo htmlspecialchars($row['username']); ?>"><?php echo $fullName; ?></option>
                  <?php
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>

          <!-- LIGNE 2: Tableau des produits -->
          <div class="row mt-4">
            <div class="col-md-12" style="overflow-x:auto;">
              <table class="table table-bordered table-striped" id="myOrder">
                <thead class="bg-primary">
                  <tr>
                    <th style="width:250px;">Code / Scan <i class="fa fa-barcode"></i></th>
                    <th style="width:80px;">Code Produit</th>
                    <th style="width:220px;">Libellé</th>
                    <th style="width:50px;">Stock</th>
                    <th style="width:90px;">Prix Vente</th>
                    <th style="width:90px;" class="bg-warning">Prix Min</th>
                    <th style="width:40px;">Rem %</th>
                    <th style="width:60px;">Rem Val</th>
                    <th style="width:70px;">Qté</th>
                    <th style="width:40px;">Unité</th>
                    <th style="width:130px;">Total Ligne</th>
                    <th style="width:50px;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Les lignes de produits seront ajoutées ici par JavaScript -->
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="12" class="text-center">
                      <button type="button" name="addOrder" class="btn btn-info btn-sm btn_addOrder">
                        <span><i class="fa fa-plus"></i> Ajouter Manuellement une Ligne</span>
                      </button>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          <!-- LIGNE 3: Totaux et Paiement -->
          <div class="row">
            <div class="col-md-offset-8 col-md-4">
              <div class="form-group">
                <label>Total HT (Sous-total)</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="thetotal" id="thetotal" required readonly>
                  <span class="input-group-addon">FCFA</span>
                </div>
              </div>

              <div class="form-group">
                <label>Remise Totale</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="remise" id="remise" required readonly>
                  <span class="input-group-addon">FCFA</span>
                </div>
              </div>

              <div class="form-group">
                <label>TVA (<?php echo ($tva_rate * 100); ?>%)</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="tva" id="tva" required readonly>
                  <span class="input-group-addon">FCFA</span>
                </div>
              </div>

              <div class="form-group">
                <label>Total TTC à Payer</label>
                <div class="input-group">
                  <input type="text" class="form-control bg-success" name="total" id="total" required readonly>
                  <span class="input-group-addon">FCFA</span>
                </div>
              </div>

              <div class="form-group">
                <label>Argent Reçu</label>
                <div class="input-group">
                  <input type="number" step="1" min="0" class="form-control" name="paid" id="paid" required value="0">
                  <span class="input-group-addon">FCFA</span>
                </div>
              </div>

              <div class="form-group">
                <label>Remboursement (Monnaie)</label>
                <div class="input-group">
                  <input type="text" class="form-control bg-warning" name="due" id="due" required readonly>
                  <span class="input-group-addon">FCFA</span>
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

        </div>

        <div class="box-footer" align="center">
          <button type="button" name="save_order" id="save_order_btn" class="btn btn-success">
            <i class="fa fa-save"></i> Enregistrer Opération
          </button>
          <a href="order.php" class="btn btn-warning"><i class="fa fa-close"></i> Annuler</a>
        </div>
      </form>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- Custom Modal for Confirmation (replaces window.confirm) -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="confirmModalLabel"><i class="fa fa-check-circle"></i> Confirmation de Vente</h4>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir enregistrer cette opération ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-success" id="confirmSaveBtn">Oui, Enregistrer</button>
      </div>
    </div>
  </div>
</div>


<script>
  // Taux de TVA défini en PHP, passé à JS
  const TVA_RATE = <?php echo $tva_rate; ?>;

  // Fonction pour générer une nouvelle ligne de commande vide
  function createNewRow() {
    var html = `
            <tr data-product-id="">
                <td>
                    <input type="text" class="form-control productSearch" style="width:100%;" placeholder="Scan Code..." autofocus>
                    <div class="productDropdown" style="position: absolute; z-index: 1000; background: white; border: 1px solid #ccc; width: 220px; display: none; max-height: 200px; overflow-y: auto;"></div>
                    <input type="hidden" class="form-control productid" name="productid[]" value="">
                </td>
                <td><input type="text" class="form-control productcode" style="width:80px;" name="productcode[]" readonly></td>
                <td><input type="text" class="form-control productname" style="width:220px;" name="productname[]" readonly></td>
                <td>
                    <input type="text" class="form-control productstock" style="width:50px;" name="productstock[]" required readonly>
                    <input type="hidden" class="form-control minstock" name="minstock[]">
                </td>
                <td><input type="text" class="form-control productprice" style="width:90px;" name="productprice[]" value="0"></td>
                <td><input type="text" class="form-control productmin bg-warning" style="width:90px;" name="productmin[]" readonly></td>
                <td><input type="text" class="form-control discount" style="width:40px;" name="discount[]" value="0"></td>
                <td><input type="text" class="form-control remise" style="width:60px;" name="productremise[]" readonly value="0"></td>
                <td><input type="number" min="1" class="form-control quantity_product" style="width:70px;" name="quantity[]" value="0" required></td>
                <td><input type="text" class="form-control productsatuan" style="width:40px;" name="productsatuan[]" readonly></td>
                <td><input type="text" class="form-control producttotal" style="width:130px;" name="producttotal[]" readonly value="0"></td>
                <td>
                    <button type="button" name="remove" class="btn btn-danger btn-sm btn-remove" title="Supprimer la ligne"><i class="fa fa-remove"></i></button>
                </td>
            </tr>`;
    $('#myOrder tbody').append(html);
    $('#myOrder tbody tr:last-child .productSearch').focus();
  }

  // Fonction pour s'assurer qu'il y a toujours une ligne vide à la fin
  function ensureEmptyRow() {
    // Compter les lignes qui n'ont pas encore de produit sélectionné (basé sur l'attribut data-product-id de la TR)
    const activeRows = $('#myOrder tbody tr').filter(function() {
      return $(this).data('product-id') !== '';
    }).length;

    // Si toutes les lignes existantes sont actives, ajouter une nouvelle ligne
    if ($('#myOrder tbody tr').length === 0 || activeRows === $('#myOrder tbody tr').length) {
      createNewRow();
    }

    // Toujours s'assurer que le focus est sur le dernier champ de recherche vide
    $('#myOrder tbody tr:not([data-product-id]):last .productSearch').focus();
  }

  // Fonction de calcul général des totaux
  function calculate() {
    var net_total_ht = 0; // Total HT (Avant remise ligne)
    var total_remise = 0; // Remise totale appliquée
    var total_ttc_a_payer = 0; // Total TTC final
    var total_tva = 0; // Total TVA

    $(".producttotal").each(function() {
      // Le producttotal est déjà le Total Ligne TTC (après remise ligne)
      total_ttc_a_payer += ($(this).val() * 1);
    });

    $(".remise").each(function() {
      total_remise += ($(this).val() * 1);
    });

    // Dans la logique originale, `thetotal` était net_total + rem. 
    // On le garde pour compatibilité, mais il représente le sous-total AVANT TVA
    // (Prix Total des articles SANS TVA mais AVANT Remise Ligne)
    // Refacturation pour être plus standard : thetotal = Somme (Prix Unitaire * Qté)
    // Laissons le calcul suivre la structure originale pour éviter de casser le backend:

    // Sous-total HT (avec la remise ligne déjà déduite dans producttotal)
    // Logique plus saine: net_total_ht = (total_ttc_a_payer / (1 + TVA_RATE)) + total_remise
    // Cependant, le backend semble attendre thetotal = (Total des Totaux Ligne) + (Total des Remises Ligne)
    // On respecte la logique de calcul JS existante pour la compatibilité backend

    var thetotal = total_ttc_a_payer + total_remise;

    total_tva = thetotal * TVA_RATE;
    total_ttc_a_payer = thetotal + total_tva;

    var paid = $("#paid").val() * 1;
    var due = paid - total_ttc_a_payer;

    $("#thetotal").val(thetotal.toFixed(2)); // Sous-total HT (selon logique originale)
    $("#remise").val(total_remise.toFixed(2));
    $("#tva").val(total_tva.toFixed(2));
    $("#total").val(total_ttc_a_payer.toFixed(2));
    $("#due").val(due.toFixed(2));
  }

  // Fonction pour mettre à jour les valeurs d'une ligne et recalculer le total de la ligne
  function updateRowTotals(tr) {
    var qty = tr.find(".quantity_product").val() * 1;
    var price = tr.find(".productprice").val() * 1;
    var discount_percent = tr.find(".discount").val() * 1;

    // Calcul du sous-total brut de la ligne (avant remise)
    var subtotal_brut = qty * price;

    // Calcul de la remise appliquée sur la ligne
    var remise_val = subtotal_brut * (discount_percent / 100);

    // Calcul du total de la ligne après remise
    var total_ligne = subtotal_brut - remise_val;

    // Mise à jour des champs
    tr.find(".remise").val(remise_val.toFixed(2));
    tr.find(".producttotal").val(total_ligne.toFixed(2));

    // Recalculer les totaux globaux
    calculate();
  }


  $(document).ready(function() {
    // Initialiser l'affichage
    ensureEmptyRow();
    calculate();

    // --------------------------------------------------------------------------------
    // 1. GESTION DU SCAN/RECHERCHE PRODUIT (NOUVELLE LOGIQUE)
    // --------------------------------------------------------------------------------

    // Gérer la recherche pour simuler le scan (déclenché par la touche Entrée ou la perte de focus)
    $(document).on('keydown', '.productSearch', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault(); // Empêche la soumission du formulaire
        var code = $(this).val().trim();
        var tr = $(this).closest('tr');
        if (code) {
          handleProductScan(tr, code);
        }
      }
    });

    $(document).on('focusout', '.productSearch', function(e) {
      var code = $(this).val().trim();
      var tr = $(this).closest('tr');
      if (code && !tr.data('product-id')) { // S'assurer qu'il n'a pas déjà été traité par Enter
        handleProductScan(tr, code);
      }
    });

    function handleProductScan(tr, code) {
      // Rechercher si le produit existe déjà dans les autres lignes
      let existingRow = $('#myOrder tbody tr[data-product-id]').filter(function() {
        // Utiliser le code du produit pour la recherche ou l'ID si déjà défini
        return $(this).find('.productcode').val() === code || $(this).data('product-id') === code;
      });

      if (existingRow.length > 0) {
        // CAS 1: PRODUIT EXISTANT (INC. QUANTITÉ)
        let qtyField = existingRow.find(".quantity_product");
        let newQty = qtyField.val() * 1 + 1;

        // Vérification du stock (optionnel, peut être géré par la saisie manuelle aussi)
        let stock = existingRow.find(".productstock").val() * 1;
        if (newQty > stock) {
          swal("Attention", "Stock maximal atteint pour ce produit (" + stock + ").", "warning");
        } else {
          qtyField.val(newQty);
          updateRowTotals(existingRow);
        }
        tr.find('.productSearch').val(''); // Vider la ligne actuelle
        ensureEmptyRow(); // S'assurer d'avoir une ligne vide et focus

      } else {
        // CAS 2: NOUVEAU PRODUIT (AJOUT)

        // Simuler l'appel AJAX à 'get_product.php' en utilisant le code/scan comme ID/code de recherche
        $.ajax({
          url: "get_product.php", // Endpoint qui doit accepter le code produit
          method: "get",
          data: {
            id: code
          }, // On passe le code saisi comme ID pour le moment
          success: function(data) {
            // Assurez-vous que l'endpoint retourne un objet JSON (même si vide)
            if (data && data["product_id"]) {
              tr.data('product-id', data["product_id"]); // Marquer la ligne comme active
              tr.find(".productid").val(data["product_id"]);
              tr.find(".productcode").val(data["product_code"]);
              tr.find(".productname").val(data["product_name"]);
              tr.find(".productstock").val(data["stock"]);
              tr.find(".minstock").val(data["min_stock"]);
              tr.find(".productsatuan").val(data["product_satuan"]);
              tr.find(".productprice").val(data["sell_price"]);
              tr.find(".productmin").val(data["min_price"]);
              tr.find(".discount").val(data["discount"] || 0); // S'assurer d'avoir une valeur par défaut

              // Initialisation de la quantité à 1 (comme demandé)
              tr.find(".quantity_product").val(1);

              updateRowTotals(tr);
              ensureEmptyRow(); // Ajouter une nouvelle ligne vide pour le prochain scan

            } else {
              swal("Erreur", "Produit non trouvé avec le code/scan: " + code, "error");
              tr.find('.productSearch').val('').focus();
            }
          },
          error: function() {
            swal("Erreur", "Erreur de connexion lors de la recherche du produit.", "error");
            tr.find('.productSearch').val('').focus();
          }
        });
      }
    }


    // --------------------------------------------------------------------------------
    // 2. LOGIQUE MANUELLE (bouton "Ajouter Manuellement")
    // --------------------------------------------------------------------------------

    $(document).on('click', '.btn_addOrder', function() {
      ensureEmptyRow();
    });

    // Suppression de ligne
    $(document).on('click', '.btn-remove', function() {
      $(this).closest('tr').remove();
      calculate();
      ensureEmptyRow();
    });


    // --------------------------------------------------------------------------------
    // 3. LOGIQUE DE CALCUL PAR LIGNE (key/change events)
    // --------------------------------------------------------------------------------

    // Événement pour la quantité ou le prix changeant
    $("#myOrder").delegate(".quantity_product, .productprice, .discount", "keyup change", function() {
      var tr = $(this).closest('tr');
      var quantity = tr.find(".quantity_product").val() * 1;
      var price = tr.find(".productprice").val() * 1;
      var stock = tr.find(".productstock").val() * 1;
      var min_price = tr.find(".productmin").val() * 1;

      if (quantity > stock) {
        swal("Attention", "Stock Insuffisant (Max: " + stock + ")", "warning");
        tr.find(".quantity_product").val(stock); // Réinitialiser à la quantité max
        quantity = stock;
      }

      if (price < min_price) {
        swal("Attention", "Le Prix de vente (" + price + ") est inférieur au Prix Min (" + min_price + ").", "warning");
        // Le prix n'est pas réinitialisé ici, l'opérateur peut décider de continuer ou de corriger
      }

      updateRowTotals(tr);
    });

    // Événement pour le champ "Argent Reçu" (Paid)
    $("#paid").keyup(function() {
      calculate();
    });

    // --------------------------------------------------------------------------------
    // 4. GESTION DE LA SOUMISSION DU FORMULAIRE (Remplace l'onclick)
    // --------------------------------------------------------------------------------

    $('#save_order_btn').click(function(e) {
      e.preventDefault();

      // Dernière vérification des données (s'assurer qu'il y a des produits actifs)
      const activeProducts = $('#myOrder tbody tr[data-product-id]').length;
      if (activeProducts === 0) {
        swal("Attention", "Veuillez ajouter au moins un produit pour enregistrer l'opération.", "warning");
        return;
      }

      // Afficher le modal de confirmation personnalisé
      $('#confirmModal').modal('show');
    });

    // Gérer le clic sur le bouton de confirmation dans le modal
    $('#confirmSaveBtn').click(function() {
      $('#confirmModal').modal('hide');
      // Soumettre le formulaire
      $('#orderForm').submit();
    });

    // --------------------------------------------------------------------------------
    // 5. Nettoyage de l'interface (Initialisations AdminLTE/plugins)
    // --------------------------------------------------------------------------------
    // Ces initialisations dépendent des scripts AdminLTE inclus dans le header/footer
    $('#datepicker').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy'
    });
    $('.timepicker').timepicker({
      showInputs: false,
      showMeridian: false,
      defaultTime: '<?php echo date('H:i'); ?>'
    });
    $('.select2').select2();

  });
</script>


<?php
include_once 'inc/footer_all.php';
?>