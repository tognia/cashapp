<?php
// export_sales_excel.php
ob_start(); // Start buffering to prevent whitespace issues
include_once 'db/connect_db.php';

if (empty($_SESSION['user_name'])) {
    ob_end_clean();
    exit('Accès non autorisé.');
}

// --- 1. RECEIVE & SANITIZE INPUTS ---
$magasin = $_SESSION['magasin'] ?? '';
$fromdate = $_GET['date_1'] ?? date('Y-m-01');
$todate = $_GET['date_2'] ?? date('Y-m-d');
$leshop = $_GET['shop'] ?? 'all';
$op_filter = $_GET['operator_filter'] ?? 'all';

// --- 2. BUILD QUERY CONDITIONS (SHARED) ---
$conditions = [];
$params = [];

// Date Filter
$conditions[] = "inv.order_date BETWEEN :fromdate AND :todate";
$params[':fromdate'] = $fromdate;
$params[':todate'] = $todate;

// Shop Filter
if (($_SESSION['role'] ?? '') == "Admin") {
    if ($leshop != "all") {
        $conditions[] = "inv.cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :leshop)";
        $params[':leshop'] = $leshop;
    }
} else {
    $conditions[] = "inv.cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
    $params[':magasin'] = $magasin;
}

// Operator Filter
if ($op_filter != 'all') {
    $conditions[] = "inv.user = :op_user";
    $params[':op_user'] = $op_filter;
}

// Status Filter (Only valid sales)
$conditions[] = "inv.status = 'saved'";

$where_clause = "";
if (!empty($conditions)) {
    $where_clause = " WHERE " . implode(" AND ", $conditions);
}

// --- 3. DATA FETCHING ---

// A. PRODUCT PERFORMANCE (Détails des produits & Profit)
// We need this to calculate the Global Summary as well
$sql_prod = "SELECT 
                det.product_code,
                det.product_name,
                det.product_satuan as unit,
                SUM(det.qty) as qty_sold,
                SUM(det.total) as row_revenue,
                SUM(det.qty * (det.price - COALESCE(item.purchase_price, 0))) as row_profit
             FROM tbl_invoice_detail det
             JOIN tbl_invoice inv ON det.invoice_id = inv.invoice_id
             LEFT JOIN tbl_shop_item item ON det.product_id = item.product_id
             $where_clause
             GROUP BY det.product_id
             ORDER BY qty_sold DESC";

$stmt_prod = $pdo->prepare($sql_prod);
$stmt_prod->execute($params);
$products = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);

// B. TRANSACTION LOG (Journal des ventes)
$sql_trans = "SELECT inv.* FROM tbl_invoice inv 
              $where_clause 
              ORDER BY inv.order_date DESC, inv.time_order DESC";

$stmt_trans = $pdo->prepare($sql_trans);
$stmt_trans->execute($params);
$transactions = $stmt_trans->fetchAll(PDO::FETCH_ASSOC);

// --- 4. CALCULATE TOTALS FOR SUMMARY ---
$total_revenue = 0;
$total_profit = 0;
$total_qty = 0;

foreach ($products as $p) {
    $total_revenue += $p['row_revenue'];
    $total_profit += $p['row_profit'];
    $total_qty += $p['qty_sold'];
}
$margin_percent = ($total_revenue > 0) ? ($total_profit / $total_revenue) * 100 : 0;


// --- 5. GENERATE CSV OUTPUT ---

$filename = 'Rapport_Ventes_' . date('Ymd_His') . '.csv';

// Headers for download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Clean buffer before writing file
ob_end_clean();
$output = fopen('php://output', 'w');

// Set Delimiter (Excel usually likes semicolon ; in French regions, comma , in English)
// We use semicolon ';' as it is standard in Francophone regions.
$delimiter = ';';

// Add BOM for UTF-8 compatibility (Fixes accents in Excel)
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// ==========================================
// SECTION 1: REPORT HEADER & SUMMARY
// ==========================================
fputcsv($output, ['RAPPORT DE VENTES ANALYTIQUE'], $delimiter);
fputcsv($output, ['Periode', $fromdate . ' au ' . $todate], $delimiter);
fputcsv($output, ['Filtres', 'Magasin: ' . $leshop . ' | Operateur: ' . $op_filter], $delimiter);
fputcsv($output, [], $delimiter); // Blank line

fputcsv($output, ['RESUME DE PERFORMANCE'], $delimiter);
fputcsv($output, ['Chiffre d\'Affaires (FCFA)', 'Benefice Net Est. (FCFA)', 'Marge Globale (%)', 'Total Articles Vendus'], $delimiter);
fputcsv($output, [
    $total_revenue,
    $total_profit,
    number_format($margin_percent, 2),
    $total_qty
], $delimiter);

fputcsv($output, [], $delimiter); // Blank line
fputcsv($output, [], $delimiter); // Blank line


// ==========================================
// SECTION 2: PRODUCT PERFORMANCE (DETAIL)
// ==========================================
fputcsv($output, ['DETAIL PAR PRODUIT (MEILLEURES VENTES)'], $delimiter);
fputcsv($output, [
    'Code Produit',
    'Designation',
    'Unite',
    'Quantite Vendue',
    'CA Total (FCFA)',
    'Benefice (FCFA)',
    'Marge Produit (%)'
], $delimiter);

foreach ($products as $row) {
    // Calculate row specific margin
    $row_margin = ($row['row_revenue'] > 0) ? ($row['row_profit'] / $row['row_revenue']) * 100 : 0;

    $csv_row = [
        $row['product_code'],
        $row['product_name'],
        $row['unit'],
        $row['qty_sold'],
        $row['row_revenue'],
        $row['row_profit'],
        number_format($row_margin, 2) . '%'
    ];
    fputcsv($output, $csv_row, $delimiter);
}

fputcsv($output, [], $delimiter); // Blank line
fputcsv($output, [], $delimiter); // Blank line


// ==========================================
// SECTION 3: TRANSACTION JOURNAL
// ==========================================
fputcsv($output, ['JOURNAL DES TRANSACTIONS'], $delimiter);
fputcsv($output, [
    'ID Facture',
    'Operateur',
    'Client',
    'Date',
    'Heure',
    'Mode Paiement',
    'TVA',
    'Montant Total (FCFA)'
], $delimiter);

foreach ($transactions as $row) {
    $csv_row = [
        $row['invoice_id'],
        $row['cashier_name'],
        $row['id_client'],
        $row['order_date'],
        $row['time_order'],
        $row['payment_mode'],
        $row['tva'],
        $row['total']
    ];
    fputcsv($output, $csv_row, $delimiter);
}

fclose($output);
exit();
