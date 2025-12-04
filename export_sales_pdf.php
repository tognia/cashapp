<?php
// Inclure la librairie FPDF
require('./fpdf/fpdf.php');
// Inclure la connexion à la base de données
include_once 'db/connect_db.php';

// --- LOGIQUE DE FILTRAGE DES DONNÉES (Basée sur $_GET) ---

$magasin = $_SESSION['magasin'] ?? '';
$fromdate = $_GET['date_1'] ?? null;
$todate = $_GET['date_2'] ?? null;
$leshop = $_GET['shop'] ?? 'all';

$sql = "SELECT * FROM tbl_invoice";
$conditions = [];
$params = [];

if ($fromdate && $todate) {
    $conditions[] = "order_date BETWEEN :fromdate AND :todate";
    $params[':fromdate'] = $fromdate;
    $params[':todate'] = $todate;

    // Filtre Magasin pour Admin/Responsable
    if (($_SESSION['role'] ?? '') == "Admin" || ($_SESSION['role'] ?? '') == "Responsable") {
        if ($leshop != "all") {
            $conditions[] = "cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :leshop)";
            $params[':leshop'] = $leshop;
        }
    } else {
        // Filtre Magasin pour Opérateur
        $conditions[] = "cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
        $params[':magasin'] = $magasin;
    }
}


if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= " ORDER BY order_date DESC";

$select = $pdo->prepare($sql);
$select->execute($params);
$transactions = $select->fetchAll(PDO::FETCH_ASSOC);

// --- CLASSE PDF PERSONNALISÉE ---

class PDF extends FPDF
{
    // En-tête de la page
    function Header()
    {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, utf8_decode('Rapport de Ventes Détaillé'), 0, 1, 'C');

        // Informations sur le filtre
        $this->SetFont('Arial', 'I', 10);
        $filter_info = "Période : Du " . ($_GET['date_1'] ?? 'Début') . " au " . ($_GET['date_2'] ?? 'Fin');
        if (isset($_GET['shop']) && $_GET['shop'] != 'all') {
            $filter_info .= " | Magasin: " . utf8_decode($_GET['shop']);
        }
        $this->Cell(0, 5, utf8_decode($filter_info), 0, 1, 'C');

        $this->Ln(5);
    }

    // Pied de page
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Page ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Tableau de données
    function LoadData($transactions)
    {
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(200, 220, 255);

        // Largeurs des colonnes (en mm)
        $w = array(15, 50, 45, 45); // No, Opérateur, Date, Montant

        // En-tête du tableau
        $header = array('No', 'Opérateur', 'Date', 'Montant Total (FCFA)');
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
        }
        $this->Ln();

        // Corps du tableau
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

        // Ligne de total
        $this->SetFont('Arial', 'B', 9);
        $this->Cell($w[0] + $w[1] + $w[2], 7, utf8_decode('TOTAL GÉNÉRAL'), 1, 0, 'R', true);
        $this->Cell($w[3], 7, utf8_decode(number_format($total_general, 0, ',', ' ') . ' FCFA'), 1, 0, 'R', true);
        $this->Ln();
    }
}

// --- GÉNÉRATION DU PDF ---

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('P'); // Format Portrait (P)
$pdf->SetMargins(20, 10, 20);

$pdf->LoadData($transactions);

$pdf->Output('I', 'Rapport_Ventes_' . date('Ymd') . '.pdf');
