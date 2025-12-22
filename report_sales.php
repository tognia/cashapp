<?php
// report_sales.php
include_once 'db/connect_db.php';

// Check Session
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
error_reporting(0);

// --- INITIALIZATION ---
$magasin = $_SESSION['magasin'] ?? '';
$is_admin = ($_SESSION['role'] ?? '') == "Admin" || ($_SESSION['role'] ?? '') == "Responsable";

$date_1 = $_POST['date_1'] ?? date('Y-m-01');
$date_2 = $_POST['date_2'] ?? date('Y-m-d');
$leshop = $_POST['shop'] ?? 'all';
$status = "saved";

// --- BUILD QUERY PARAMETERS ---
// We use aliases: inv (invoice), det (details), item (shop_item)
$base_where = " WHERE inv.status = :status AND inv.order_date BETWEEN :fromdate AND :todate ";
$params = [
  ':fromdate' => $date_1,
  ':todate' => $date_2,
  ':status' => $status
];

// Shop Filtering Logic
if ($is_admin) {
  if ($leshop != "all") {
    $base_where .= " AND inv.cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :leshop)";
    $params[':leshop'] = $leshop;
  }
} else {
  $base_where .= " AND inv.cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
  $params[':magasin'] = $magasin;
}

// Export params for JS
$export_params = http_build_query(['date_1' => $date_1, 'date_2' => $date_2, 'shop' => $leshop]);

// --- 1. KEY METRICS CALCULATION (Global KPIs) ---
// We join tables to get Purchase Price (PA) vs Selling Price (PV)
$sql_metrics = "SELECT 
    COUNT(DISTINCT inv.invoice_id) as total_invoices,
    SUM(det.qty * det.price) as total_revenue,
    SUM(det.qty * (det.price - COALESCE(item.purchase_price, 0))) as total_profit
    FROM tbl_invoice inv
    JOIN tbl_invoice_detail det ON inv.invoice_id = det.invoice_id
    LEFT JOIN tbl_shop_item item ON det.product_id = item.product_id
    " . $base_where;

$stmt_metrics = $pdo->prepare($sql_metrics);
$stmt_metrics->execute($params);
$metrics = $stmt_metrics->fetch(PDO::FETCH_ASSOC);

$total_inv = $metrics['total_invoices'] ?? 0;
$total_rev = $metrics['total_revenue'] ?? 0;
$total_profit = $metrics['total_profit'] ?? 0;

// Calculated KPIs
$avg_basket = ($total_inv > 0) ? ($total_rev / $total_inv) : 0; // Panier Moyen
$margin_percent = ($total_rev > 0) ? ($total_profit / $total_rev) * 100 : 0; // Taux de Marge
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Rapport des Ventes <small>Analyse détaillée</small></h1>
  </section>

  <section class="content container-fluid">

    <div class="box box-success collapsed-box">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-filter"></i> Filtres: Du <b><?php echo $date_1; ?></b> au <b><?php echo $date_2; ?></b></h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body" style="display:none;">
        <form action="" method="POST" autocomplete="off">
          <div class="row">
            <div class="col-md-3">
              <label>Date Début</label>
              <div class="input-group date">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <input type="text" class="form-control" id="datepicker_1" name="date_1" value="<?php echo htmlspecialchars($date_1); ?>">
              </div>
            </div>
            <div class="col-md-3">
              <label>Date Fin</label>
              <div class="input-group date">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <input type="text" class="form-control" id="datepicker_2" name="date_2" value="<?php echo htmlspecialchars($date_2); ?>">
              </div>
            </div>
            <?php if ($is_admin): ?>
              <div class="col-md-3">
                <label>Magasin</label>
                <select class="form-control" name="shop">
                  <option value="all" <?php echo $leshop == 'all' ? 'selected' : ''; ?>>Tous</option>
                  <?php
                  $s_q = $pdo->query("SELECT DISTINCT magasin FROM tbl_user WHERE magasin != ''");
                  while ($r = $s_q->fetch(PDO::FETCH_ASSOC)) {
                    $sel = ($leshop == $r['magasin']) ? 'selected' : '';
                    echo "<option value='{$r['magasin']}' $sel>{$r['magasin']}</option>";
                  }
                  ?>
                </select>
              </div>
            <?php endif; ?>
            <div class="col-md-2" style="padding-top:25px;">
              <button type="submit" class="btn btn-success btn-block"><i class="fa fa-search"></i> Filtrer</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3><?php echo number_format($total_inv); ?></h3>
            <p>Transactions</p>
          </div>
          <div class="icon"><i class="ion ion-bag"></i></div>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
          <div class="inner">
            <h3><?php echo number_format($total_rev); ?><sup style="font-size: 20px">FCFA</sup></h3>
            <p>Chiffre d'Affaires</p>
          </div>
          <div class="icon"><i class="ion ion-stats-bars"></i></div>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3><?php echo number_format($total_profit); ?><sup style="font-size: 20px">FCFA</sup></h3>
            <p>Bénéfice Net (Est.)</p>
          </div>
          <div class="icon"><i class="ion ion-pie-graph"></i></div>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
          <div class="inner">
            <h3><?php echo number_format($margin_percent, 1); ?><sup style="font-size: 20px">%</sup></h3>
            <p>Marge Commerciale</p>
          </div>
          <div class="icon"><i class="ion ion-arrow-graph-up-right"></i></div>
        </div>
      </div>
    </div>

    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_dashboard" data-toggle="tab"><i class="fa fa-dashboard"></i> Graphiques</a></li>
        <li><a href="#tab_products" data-toggle="tab"><i class="fa fa-cubes"></i> Détail par Produit (Items Sold)</a></li>
        <li><a href="#tab_history" data-toggle="tab"><i class="fa fa-list"></i> Journal des Ventes</a></li>
        <li class="pull-right">
          <button type="button" onclick="openPrintSalesWindow('<?php echo $export_params; ?>')" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-print"></i> PDF</button>
          <button type="button" onclick="downloadExcelSales('<?php echo $export_params; ?>')" class="btn btn-success btn-sm btn-flat"><i class="fa fa-file-excel-o"></i> Excel</button>
        </li>
      </ul>

      <div class="tab-content">

        <div class="tab-pane active" id="tab_dashboard">
          <div class="row">
            <div class="col-md-7">
              <p class="text-center"><strong>Évolution du C.A. Quotidien</strong></p>
              <div class="chart">
                <canvas id="myChart" style="height:300px;"></canvas>
              </div>
            </div>
            <div class="col-md-5">
              <p class="text-center"><strong>Top 5 Produits (Volume)</strong></p>
              <div class="chart">
                <canvas id="myBestSellItem" style="height:300px;"></canvas>
              </div>
            </div>
          </div>
        </div>

        <div class="tab-pane" id="tab_products">
          <div class="table-responsive">
            <table id="tableProducts" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Code</th>
                  <th>Désignation</th>
                  <th>Catégorie</th>
                  <th>Qté Vendue</th>
                  <th>C.A. (Total)</th>
                  <th>Bénéfice</th>
                  <th>Marge %</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Query for Item Details
                $sql_items = "SELECT 
                                    item.product_code,
                                    det.product_name,
                                    item.product_category,
                                    SUM(det.qty) as qty_sold,
                                    SUM(det.total) as row_revenue,
                                    SUM(det.qty * (det.price - COALESCE(item.purchase_price, 0))) as row_profit
                                    FROM tbl_invoice_detail det
                                    JOIN tbl_invoice inv ON det.invoice_id = inv.invoice_id
                                    LEFT JOIN tbl_shop_item item ON det.product_id = item.product_id
                                    " . $base_where . "
                                    GROUP BY det.product_id
                                    ORDER BY qty_sold DESC";

                $stmt_items = $pdo->prepare($sql_items);
                $stmt_items->execute($params);

                // Arrays for charts
                $chart_pname = [];
                $chart_qty = [];

                while ($row = $stmt_items->fetch(PDO::FETCH_ASSOC)) {
                  // Populate chart data (Top 5 only handled in JS usually, but we collect all here)
                  if (count($chart_pname) < 10) {
                    $chart_pname[] = $row['product_name'];
                    $chart_qty[] = $row['qty_sold'];
                  }

                  $item_margin = ($row['row_revenue'] > 0) ? ($row['row_profit'] / $row['row_revenue']) * 100 : 0;
                ?>
                  <tr>
                    <td><small><?php echo $row['product_code']; ?></small></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['product_category']; ?></td>
                    <td class="text-center text-bold"><?php echo $row['qty_sold']; ?></td>
                    <td class="text-right"><?php echo number_format($row['row_revenue']); ?></td>
                    <td class="text-right text-success"><?php echo number_format($row['row_profit']); ?></td>
                    <td class="text-right"><small class="label label-<?php echo ($item_margin > 20 ? 'success' : 'warning'); ?>"><?php echo number_format($item_margin, 1); ?>%</small></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="tab-pane" id="tab_history">
          <table id="mySalesReport" class="table table-bordered table-striped" style="width:100%">
            <thead>
              <tr>
                <th>Ref</th>
                <th>Opérateur</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Montant</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql_log = "SELECT * FROM tbl_invoice inv " . $base_where . " ORDER BY order_date DESC, time_order DESC";
              $stmt_log = $pdo->prepare($sql_log);
              $stmt_log->execute($params);

              // Data for Date Chart
              $date_data = [];

              while ($row = $stmt_log->fetch(PDO::FETCH_ASSOC)) {
                // Aggregate for Chart
                if (!isset($date_data[$row['order_date']])) $date_data[$row['order_date']] = 0;
                $date_data[$row['order_date']] += $row['total'];
              ?>
                <tr>
                  <td><?php echo $row['invoice_id']; ?></td>
                  <td class="text-uppercase"><?php echo $row['cashier_name']; ?></td>
                  <td><?php echo $row['order_date']; ?></td>
                  <td><?php echo $row['time_order']; ?></td>
                  <td class="text-right text-bold"><?php echo number_format($row['total']); ?> FCFA</td>
                </tr>
              <?php
              }
              ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>

  </section>
</div>

<script>
  $(document).ready(function() {
    // Initialize DataTables
    $('#mySalesReport').DataTable({
      "order": [
        [2, "desc"],
        [3, "desc"]
      ]
    });
    $('#tableProducts').DataTable({
      "order": [
        [3, "desc"]
      ]
    }); // Order by Qty Sold

    // Datepickers
    $('#datepicker_1, #datepicker_2').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd',
      todayHighlight: true,
      todayBtn: "linked"
    });
  });

  // --- CHARTS CONFIGURATION ---

  // 1. Daily Revenue Chart
  var ctx = document.getElementById('myChart');
  var myChart = new Chart(ctx, {
    type: 'line', // Changed to line for better trend visualization
    data: {
      labels: <?php echo json_encode(array_keys($date_data)); ?>,
      datasets: [{
        label: 'Revenu Quotidien',
        data: <?php echo json_encode(array_values($date_data)); ?>,
        backgroundColor: 'rgba(60, 141, 188, 0.2)',
        borderColor: 'rgba(60, 141, 188, 1)',
        borderWidth: 2,
        pointRadius: 4,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false
    }
  });

  // 2. Best Sellers Chart
  var ctx2 = document.getElementById('myBestSellItem');
  var myChart2 = new Chart(ctx2, {
    type: 'doughnut', // Doughnut is better for "Share" visualization
    data: {
      labels: <?php echo json_encode($chart_pname); ?>,
      datasets: [{
        label: 'Quantité',
        data: <?php echo json_encode($chart_qty); ?>,
        backgroundColor: [
          '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#605ca8', '#ff851b', '#39cccc', '#D81B60'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        position: 'right'
      }
    }
  });

  // Export Functions
  function openPrintSalesWindow(params) {
    window.open('export_sales_pdf.php?' + params, 'SalesPDF', 'width=900,height=700');
  }

  function downloadExcelSales(params) {
    window.location.href = 'export_sales_excel.php?' + params;
  }
</script>

<?php include_once 'inc/footer_all.php'; ?>