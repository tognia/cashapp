<?php
// export_sales_pdf.php
require('./fpdf/fpdf.php');
include_once 'db/connect_db.php';

$magasin = $_SESSION['magasin'] ?? '';
$fromdate = $_GET['date_1'] ?? null;
$todate = $_GET['date_2'] ?? null;
$leshop = $_GET['shop'] ?? 'all';
$op_filter = $_GET['operator_filter'] ?? 'all'; // Nouveau

$sql = "SELECT * FROM tbl_invoice";
$conditions = [];
$params = [];

if ($fromdate && $todate) {
    $conditions[] = "order_date BETWEEN :fromdate AND :todate";
    $params[':fromdate'] = $fromdate;
    $params[':todate'] = $todate;
}

// Filtre Magasin
if (($_SESSION['role'] ?? '') == "Admin") {
    if ($leshop != "all") {
        $conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :leshop)";
        $params[':leshop'] = $leshop;
    }
} else {
    $conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
    $params[':magasin'] = $magasin;
}

// NOUVEAU: Filtre Opérateur
if ($op_filter != 'all') {
    $conditions[] = "user = :op_user";
    $params[':op_user'] = $op_filter;
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= " ORDER BY order_date DESC";

$select = $pdo->prepare($sql);
$select->execute($params);
$transactions = $select->fetchAll(PDO::FETCH_ASSOC);

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, utf8_decode('Rapport de Ventes Détaillé'), 0, 1, 'C');

        $this->SetFont('Arial', 'I', 10);
        $filter_info = "Période : Du " . ($_GET['date_1'] ?? '') . " au " . ($_GET['date_2'] ?? '');

        if (isset($_GET['shop']) && $_GET['shop'] != 'all') {
            $filter_info .= " | Magasin: " . utf8_decode($_GET['shop']);
        }
        // Affichage Operateur dans le header
        if (isset($_GET['operator_filter']) && $_GET['operator_filter'] != 'all') {
            $filter_info .= " | Opérateur: " . utf8_decode($_GET['operator_filter']);
        }

        $this->Cell(0, 5, utf8_decode($filter_info), 0, 1, 'C');
        $this->Ln(5);
    }
    // ... Footer and LoadData remain unchanged ...
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Page ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function LoadData($transactions)
    {
        // ... (Same as previous code) ...
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(200, 220, 255);
        $w = array(15, 50, 45, 45);
        $header = array('No', 'Opérateur', 'Date', 'Montant Total (FCFA)');
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
        }
        $this->Ln();
        $this->SetFont('Arial', '', 9);
        $i = 1;
        $total_general = 0;
        foreach ($transactions as $row) {
            $this->Cell($w[0], 6, $i++, 1, 0, 'C');
            $this->Cell($w[1], 6, utf8_decode($row['cashier_name']), 1, 0, 'L');
            $this->Cell($w[2], 6, utf8_decode($row['order_date']), 1, 0, 'C');
            $this->Cell($w[3], 6, utf8_decode(number_format($row['total'], 0, ',', ' ')), 1, 0, 'R');
            $this->Ln();
            $total_general += $row['total'];
        }
        $this->SetFont('Arial', 'B', 9);
        $this->Cell($w[0] + $w[1] + $w[2], 7, utf8_decode('TOTAL GÉNÉRAL'), 1, 0, 'R', true);
        $this->Cell($w[3], 7, utf8_decode(number_format($total_general, 0, ',', ' ') . ' FCFA'), 1, 0, 'R', true);
        $this->Ln();
    }
}
// ... Generation logic ...
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('P');
$pdf->SetMargins(20, 10, 20);
$pdf->LoadData($transactions);
$pdf->Output('I', 'Rapport_Ventes_' . date('Ymd') . '.pdf');
