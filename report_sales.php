<?php
include_once 'db/connect_db.php';

// Vérification de la session utilisateur
if (empty($_SESSION['user_name'])) {
  header('location:index.php');
  exit(); // Ajout d'exit() après header pour arrêter l'exécution
} else {
  // Inclure le header approprié en fonction du rôle
  if ($_SESSION['role'] == "Admin" || $_SESSION['role'] == "Responsable") {
    include_once 'inc/header_all.php';
  } else {
    include_once 'inc/header_all_operator.php';
  }
}
error_reporting(0);

// Initialisation des variables pour les requêtes
$magasin = $_SESSION['magasin'] ?? '';
$is_admin = ($_SESSION['role'] ?? '') == "Admin" || ($_SESSION['role'] ?? '') == "Responsable";

// Valeurs par défaut ou valeurs POST
$date_1 = $_POST['date_1'] ?? date('Y-m-01');
$date_2 = $_POST['date_2'] ?? date('Y-m-d');
$leshop = $_POST['shop'] ?? 'all';

// --- LOGIQUE DE FILTRAGE PHP ---
$query_where_clause = " WHERE order_date BETWEEN :fromdate AND :todate ";
$query_params = [
  ':fromdate' => $date_1,
  ':todate' => $date_2
];

if ($is_admin) {
  // Si Admin/Responsable et un magasin spécifique est sélectionné
  if ($leshop != "all") {
    $query_where_clause .= " AND cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :leshop)";
    $query_params[':leshop'] = $leshop;
  }
} else {
  // Si Opérateur, filtre uniquement sur son magasin
  $query_where_clause .= " AND cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
  $query_params[':magasin'] = $magasin;
}

// Pour simplifier l'envoi des filtres aux pages d'exportation
$export_params = http_build_query([
  'date_1' => $date_1,
  'date_2' => $date_2,
  'shop' => $leshop
]);
?>

<div class="content-wrapper">
  <section class="content container-fluid">
    <div class="box box-success">
      <form action="" method="POST" autocomplete="off">
        <div class="box-header with-border">
          <h3 class="box-title">Date Début : <?php echo htmlspecialchars($date_1) ?>
          </h3>
          <h3 class="box-title" style="margin-left: 15px;">Date Fin : <?php echo htmlspecialchars($date_2) ?>
          </h3>
          <?php if ($is_admin && $leshop != 'all'): ?>
            <h3 class="box-title" style="margin-left: 15px;">Magasin : <?php echo htmlspecialchars($leshop) ?>
            </h3>
          <?php endif; ?>

          <div class="pull-right">
            <button type="button" onclick="openPrintSalesWindow('<?php echo $export_params; ?>')" class="btn btn-primary btn-sm" style="margin-right: 5px;" title="Ouvrir la liste des ventes dans une fenêtre d'impression">
              <i class="fa fa-file-pdf-o"></i> Générer PDF
            </button>
            <button type="button" onclick="downloadExcelSales('<?php echo $export_params; ?>')" class="btn btn-success btn-sm" title="Télécharger le rapport des ventes au format Excel">
              <i class="fa fa-file-excel-o"></i> Exporter Excel
            </button>
          </div>
        </div>

        <div class="box-body">
          <div class="row">
            <div class="col-md-3">
              <label>Date Début</label>
              <div class="form-group">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker_1" name="date_1" value="<?php echo htmlspecialchars($date_1); ?>">
                </div>
              </div>
            </div>

            <div class="col-md-3">
              <label>Date Fin</label>
              <div class="form-group">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker_2" name="date_2" value="<?php echo htmlspecialchars($date_2); ?>">
                </div>
              </div>
            </div>

            <?php if ($is_admin): ?>
              <div class="col-md-3">
                <label>Magasin</label>
                <div class="form-group">
                  <select class="form-control" name="shop">
                    <option value="all" <?php echo $leshop == 'all' ? 'selected' : ''; ?>>Tous les Magasins</option>
                    <?php
                    // Récupérer la liste des magasins
                    $shop_query = $pdo->query("SELECT DISTINCT magasin FROM tbl_user WHERE magasin IS NOT NULL AND magasin != ''");
                    while ($shop_row = $shop_query->fetch(PDO::FETCH_ASSOC)):
                    ?>
                      <option value="<?php echo htmlspecialchars($shop_row['magasin']); ?>"
                        <?php echo $leshop == $shop_row['magasin'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($shop_row['magasin']); ?>
                      </option>
                    <?php endwhile; ?>
                  </select>
                </div>
              </div>
            <?php endif; ?>

            <div class="col-md-2" style="padding-top: 25px;">
              <input type="submit" name="date_filter" value="Afficher" class="btn btn-success btn-sm">
            </div>
          </div>

          <br>

          <?php
          // Requête 1: Total Transactions et Revenu
          $select_sum = $pdo->prepare("SELECT sum(total) as total, count(invoice_id) as invoice FROM tbl_invoice " . $query_where_clause);
          $select_sum->execute($query_params);
          $row_sum = $select_sum->fetch(PDO::FETCH_OBJ);

          $total_revenue = $row_sum->total ?? 0;
          $invoice_count = $row_sum->invoice ?? 0;
          ?>

          <div class="row">
            <div class="col-md-offset-2 col-md-4 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Total Transactions</span>
                  <span class="info-box-number"><?php echo $invoice_count; ?></span>
                </div>
              </div>
            </div>

            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-offset-1 col-md-5 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">REVENU TOTAL</span>
                  <span class="info-box-number"> <?php echo number_format($total_revenue, 0) . " FCFA"; ?></span>
                </div>
              </div>
            </div>
          </div>

          <h4 class="box-title">Détail des Transactions</h4>
          <div style="overflow-x:auto;">
            <table class="table table-striped" id="mySalesReport">
              <thead>
                <tr>
                  <th>Operateur</th>
                  <th>Date</th>
                  <th>Montant</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Requête 2: Liste des transactions
                $select_list = $pdo->prepare("SELECT * FROM tbl_invoice " . $query_where_clause . " ORDER BY order_date DESC");
                $select_list->execute($query_params);

                while ($row = $select_list->fetch(PDO::FETCH_OBJ)) {
                ?>
                  <tr>
                    <td class="text-uppercase"><?php echo $row->cashier_name; ?></td>
                    <td><?php echo $row->order_date; ?></td>
                    <td><?php echo number_format($row->total) . " FCFA"; ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
          </div>

          <?php
          // Requête 3: Graphique de revenu journalier
          $select_chart = $pdo->prepare("SELECT order_date, sum(total) as price FROM tbl_invoice " . $query_where_clause . " GROUP BY order_date ORDER BY order_date ASC");
          $select_chart->execute($query_params);

          $total_chart = [];
          $date_chart = [];
          while ($row = $select_chart->fetch(PDO::FETCH_ASSOC)) {
            $total_chart[] = $row['price'];
            $date_chart[] = $row['order_date'];
          }
          ?>
          <h4 class="box-title" style="margin-top: 20px;">Revenu Quotidien</h4>
          <div class="chart">
            <canvas id="myChart" style="height:250px;"></canvas>
          </div>

          <?php
          // Requête 4: Graphique des meilleurs produits vendus
          // Note: Il faut modifier la jointure pour appliquer le filtre cashier_name/magasin à tbl_invoice_detail

          $product_query_where = " WHERE detail.order_date BETWEEN :fromdate AND :todate ";
          $product_query_params = $query_params;

          // Si le filtre Magasin est actif, nous devons filtrer par l'invoice
          if (isset($query_params[':leshop'])) {
            // On modifie la requête pour joindre tbl_invoice et appliquer le filtre Magasin
            $product_sql = "
                            SELECT detail.product_name, sum(detail.qty) as q 
                            FROM tbl_invoice_detail detail
                            INNER JOIN tbl_invoice inv ON detail.invoice_id = inv.invoice_id
                            WHERE detail.order_date BETWEEN :fromdate AND :todate 
                            AND inv.cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :leshop)
                            GROUP BY detail.product_id
                            ORDER BY q DESC";

            // Pas besoin de changer $product_query_params car les clés sont les mêmes
            $product_select = $pdo->prepare($product_sql);
          } elseif (isset($query_params[':magasin'])) {
            // On modifie la requête pour joindre tbl_invoice et appliquer le filtre Magasin (Opérateur)
            $product_sql = "
                            SELECT detail.product_name, sum(detail.qty) as q 
                            FROM tbl_invoice_detail detail
                            INNER JOIN tbl_invoice inv ON detail.invoice_id = inv.invoice_id
                            WHERE detail.order_date BETWEEN :fromdate AND :todate 
                            AND inv.cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :magasin)
                            GROUP BY detail.product_id
                            ORDER BY q DESC";

            $product_select = $pdo->prepare($product_sql);
          } else {
            // Pas de filtre Magasin, requête simple sur detail
            $product_sql = "
                            SELECT product_name, sum(qty) as q 
                            FROM tbl_invoice_detail 
                            " . $product_query_where . " 
                            GROUP BY product_id 
                            ORDER BY q DESC";

            $product_select = $pdo->prepare($product_sql);
          }

          $product_select->execute($product_query_params);

          $pname = [];
          $qty = [];
          while ($row = $product_select->fetch(PDO::FETCH_ASSOC)) {
            $pname[] = $row['product_name'];
            $qty[] = $row['q'];
          }
          ?>
          <h4 class="box-title" style="margin-top: 20px;">Meilleurs Produits Vendus</h4>
          <div class="chart">
            <canvas id="myBestSellItem" style="height:250px;"></canvas>
          </div>

        </div>

      </form>
    </div>


  </section>
</div>
<script>
  // Initialisation de DataTables pour la liste des transactions
  $(document).ready(function() {
    $('#mySalesReport').DataTable({
      "order": [
        [1, "desc"]
      ] // Tri par date décroissante
    });
  });

  /*
   * AMÉLIORATION DU DATEPICKER
   */
  // Datepicker DE DÉBUT
  $('#datepicker_1').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    todayHighlight: true,
    todayBtn: "linked"
  });

  // Datepicker DE FIN
  $('#datepicker_2').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    todayHighlight: true,
    todayBtn: "linked"
  });

  // 1. FONCTION POUR GÉNÉRER LE PDF (Fenêtre de style "Application")
  function openPrintSalesWindow(params) {
    var url = 'export_sales_pdf.php?' + params;
    var windowName = 'SalesPDFExport';

    // Paramètres pour une fenêtre sans barre d'adresse/outils
    var features = 'width=900,height=700,scrollbars=yes,resizable=yes,location=no,menubar=no,toolbar=no,status=no';

    // Ouvre la nouvelle fenêtre
    window.open(url, windowName, features);
  }

  // 2. FONCTION POUR TÉLÉCHARGER L'EXCEL
  function downloadExcelSales(params) {
    var url = 'export_sales_excel.php?' + params;

    // Crée un lien temporaire
    var link = document.createElement('a');
    link.href = url;
    link.style.display = 'none'; // Le rend invisible
    document.body.appendChild(link);

    // Déclenche le téléchargement
    link.click();

    // Supprime le lien après le téléchargement
    document.body.removeChild(link);

    // Afficher une alerte utilisateur (facultatif)
    // swal("Téléchargement lancé", "Votre fichier Excel est en cours de téléchargement.", "info");
  }
</script>

<script>
  var ctx = document.getElementById('myChart');
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($date_chart); ?>,
      datasets: [{
        label: 'Revenu Total',
        data: <?php echo json_encode($total_chart); ?>,
        backgroundColor: 'rgb(13, 192, 58)',
        borderColor: 'rgb(32, 204, 75)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>

<style>
  .color {
    backgroundColor: rgb(120, 102, 102);
  }
</style>


<script>
  var ctx = document.getElementById('myBestSellItem');
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($pname); ?>,
      datasets: [{
        label: 'Quantité Vendue',
        data: <?php echo json_encode($qty); ?>,
        backgroundColor: 'rgb(120,112,175)',
        borderColor: 'rgb(255,255,255)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>

<?php
include_once 'inc/footer_all.php';
?>