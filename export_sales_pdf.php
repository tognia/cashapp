<?php
// export_sales_pdf.php
require('./fpdf/fpdf.php');
include_once 'db/connect_db.php';

// --- 1. RECEIVE & SANITIZE INPUTS ---
$magasin = $_SESSION['magasin'] ?? '';
$fromdate = $_GET['date_1'] ?? date('Y-m-01');
$todate = $_GET['date_2'] ?? date('Y-m-d');
$leshop = $_GET['shop'] ?? 'all';
$op_filter = $_GET['operator_filter'] ?? 'all';

// --- 2. BUILD QUERY CONDITIONS (SHARED) ---
// We build a condition string that applies to the 'tbl_invoice' table (aliased as 'inv')
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

// A. PRODUCT PERFORMANCE (Détails des produits)
// We join invoice_detail (det), invoice (inv), and shop_item (item) to calculate profit
$sql_prod = "SELECT 
                det.product_code,
                det.product_name,
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


// --- 5. PDF CLASS DEFINITION ---
class PDF extends FPDF
{
    function Header()
    {
        // Title
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, utf8_decode('RAPPORT DE VENTES & PERFORMANCE'), 0, 1, 'C');

        // Subtitle / Filters
        $this->SetFont('Arial', 'I', 10);
        $filter_txt = "Période : " . ($_GET['date_1'] ?? '-') . " au " . ($_GET['date_2'] ?? '-');

        if (isset($_GET['shop']) && $_GET['shop'] != 'all') {
            $filter_txt .= " | Magasin: " . $_GET['shop'];
        }
        if (isset($_GET['operator_filter']) && $_GET['operator_filter'] != 'all') {
            $filter_txt .= " | Opérateur: " . $_GET['operator_filter'];
        }

        $this->Cell(0, 6, utf8_decode($filter_txt), 0, 1, 'C');
        $this->Ln(5);

        // Line break
        $this->SetDrawColor(0, 0, 0);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Page ') . $this->PageNo() . '/{nb} - Généré le ' . date('d/m/Y H:i'), 0, 0, 'C');
    }

    // Section Title Helper
    function SectionTitle($label)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(230, 230, 230);
        $this->Cell(0, 8, utf8_decode("  $label"), 0, 1, 'L', true);
        $this->Ln(2);
    }

    // Summary Box
    function SummaryTable($rev, $prof, $marg)
    {
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(255, 255, 255);

        $this->Cell(63, 15, utf8_decode("Chiffre d'Affaires"), 1, 0, 'C');
        $this->Cell(63, 15, utf8_decode("Bénéfice Net (Est.)"), 1, 0, 'C');
        $this->Cell(63, 15, utf8_decode("Marge Globale"), 1, 1, 'C');

        $this->SetFont('Arial', 'B', 14);
        $this->Cell(63, 10, number_format($rev) . ' FCFA', 1, 0, 'C');
        $this->SetTextColor(0, 150, 0); // Green for profit
        $this->Cell(63, 10, number_format($prof) . ' FCFA', 1, 0, 'C');
        $this->SetTextColor(0); // Reset color
        $this->Cell(63, 10, number_format($marg, 1) . ' %', 1, 1, 'C');
        $this->Ln(10);
    }

    // Product Table
    function TableProducts($data)
    {
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(50, 50, 50);
        $this->SetTextColor(255); // White text

        // Header
        $w = array(25, 75, 20, 35, 35); // Total 190
        $header = array('Code', 'Produit', 'Qté', 'CA Total', 'Bénéfice');

        foreach ($header as $i => $h) {
            $this->Cell($w[$i], 7, utf8_decode($h), 1, 0, 'C', true);
        }
        $this->Ln();

        // Data
        $this->SetFont('Arial', '', 9);
        $this->SetFillColor(245, 245, 245);
        $this->SetTextColor(0);

        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, utf8_decode($row['product_code']), 1, 0, 'L', $fill);

            // Truncate long names
            $name = $row['product_name'];
            if (strlen($name) > 40) $name = substr($name, 0, 37) . '...';

            $this->Cell($w[1], 6, utf8_decode($name), 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, $row['qty_sold'], 1, 0, 'C', $fill);
            $this->Cell($w[3], 6, number_format($row['row_revenue']), 1, 0, 'R', $fill);
            $this->Cell($w[4], 6, number_format($row['row_profit']), 1, 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill; // Zebra striping
        }
        $this->Ln(10);
    }

    // Transaction Table
    function TableTransactions($data)
    {
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(50, 50, 50);
        $this->SetTextColor(255);

        $w = array(20, 60, 40, 30, 40); // Total 190
        $header = array('Ref', 'Opérateur', 'Date', 'Heure', 'Montant');

        foreach ($header as $i => $h) {
            $this->Cell($w[$i], 7, utf8_decode($h), 1, 0, 'C', true);
        }
        $this->Ln();

        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(0);

        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row['invoice_id'], 1, 0, 'C', $fill);
            $this->Cell($w[1], 6, utf8_decode($row['cashier_name']), 1, 0, 'L', $fill);
            $this->Cell($w[2], 6, $row['order_date'], 1, 0, 'C', $fill);
            $this->Cell($w[3], 6, $row['time_order'], 1, 0, 'C', $fill);
            $this->Cell($w[4], 6, number_format($row['total']) . ' FCFA', 1, 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
    }
}

// --- 6. GENERATE PDF ---
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// 1. Executive Summary
$pdf->SectionTitle("Résumé de la Période");
$pdf->SummaryTable($total_revenue, $total_profit, $margin_percent);

// 2. Product Performance
$pdf->SectionTitle("Performance par Produit");
// Check if we need a page break before starting if list is long? 
// FPDF handles automatic page breaks, but nice to ensure header isn't alone.
if ($pdf->GetY() > 250) $pdf->AddPage();
$pdf->TableProducts($products);

// 3. Sales Log
$pdf->AddPage(); // Force new page for the log usually better
$pdf->SectionTitle("Journal des Ventes (Transactions)");
$pdf->TableTransactions($transactions);

$pdf->Output('I', 'Rapport_Ventes_' . date('Ymd_His') . '.pdf');
