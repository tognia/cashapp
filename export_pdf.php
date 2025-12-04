<?php
// Inclure la librairie FPDF
require('./fpdf/fpdf.php');
// Inclure la connexion à la base de données
include_once 'db/connect_db.php';

// --- LOGIQUE DE RÉCUPÉRATION DES DONNÉES ET FILTRES (Répétée de order.php) ---

$sql = "SELECT * FROM tbl_invoice";
$conditions = [];
$params = [];
$magasin = $_SESSION['magasin'] ?? '';
$leshop = $_GET['shop'] ?? null; // Récupérer le filtre magasin si présent
$fromdate = $_GET['date_1'] ?? null;
$todate = $_GET['date_2'] ?? null;

if ($fromdate && $todate) {
    $conditions[] = "order_date BETWEEN :fromdate AND :todate";
    $params[':fromdate'] = $fromdate;
    $params[':todate'] = $todate;

    if (($_SESSION['role'] ?? '') == "Admin" && $leshop != "all") {
        $conditions[] = "cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :leshop)";
        $params[':leshop'] = $leshop;
    } elseif (($_SESSION['role'] ?? '') != "Admin") {
        $conditions[] = "cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
        $params[':magasin'] = $magasin;
    }
} else {
    // Pas de filtre de date
    if (($_SESSION['role'] ?? '') != "Admin") {
        $conditions[] = "cashier_name IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
        $params[':magasin'] = $magasin;
    }
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= " ORDER BY invoice_id DESC";

$select = $pdo->prepare($sql);
$select->execute($params);
$transactions = $select->fetchAll(PDO::FETCH_ASSOC);

// --- CLASSE PDF PERSONNALISÉE ---

class PDF extends FPDF
{
    // En-tête de la page
    function Header()
    {
        // Logo (à personnaliser)
        // $this->Image('logo.png', 10, 8, 33);

        // Police
        $this->SetFont('Arial', 'B', 15);

        // Titre
        $this->Cell(0, 10, utf8_decode('Rapport des Opérations de Caisse'), 0, 1, 'C');

        // Informations sur le filtre (optionnel)
        $this->SetFont('Arial', 'I', 10);
        $filter_info = "Date du rapport: " . date('d/m/Y H:i:s');
        if (isset($_GET['date_1']) && isset($_GET['date_2'])) {
            $filter_info .= " | Filtre: Du " . $_GET['date_1'] . " au " . $_GET['date_2'];
        }
        $this->Cell(0, 5, utf8_decode($filter_info), 0, 1, 'C');

        // Saut de ligne
        $this->Ln(5);
    }

    // Pied de page
    function Footer()
    {
        // Positionnement à 1.5 cm du bas
        $this->SetY(-15);

        // Police Arial italique 8
        $this->SetFont('Arial', 'I', 8);

        // Numéro de page
        $this->Cell(0, 10, utf8_decode('Page ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Tableau de données
    function LoadData($transactions)
    {
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(200, 220, 255); // Couleur de fond pour l'en-tête

        // Largeurs des colonnes (en mm)
        $w = array(10, 35, 30, 25, 35, 25, 30);

        // En-tête du tableau
        $header = array('No', 'Opérateur', 'Client ID', 'Date', 'Montant Total', 'TVA', 'Mode Paiement');
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
        }
        $this->Ln();

        // Corps du tableau
        $this->SetFont('Arial', '', 9);
        $i = 1;
        foreach ($transactions as $row) {
            $this->Cell($w[0], 6, $i++, 1, 0, 'C');
            $this->Cell($w[1], 6, utf8_decode($row['cashier_name']), 1, 0, 'L');
            $this->Cell($w[2], 6, utf8_decode($row['id_client']), 1, 0, 'L');
            $this->Cell($w[3], 6, utf8_decode($row['order_date']), 1, 0, 'C');
            $this->Cell($w[4], 6, utf8_decode(number_format($row['total'], 0, ',', ' ') . ' FCFA'), 1, 0, 'R');
            $this->Cell($w[5], 6, utf8_decode(number_format($row['tva'], 0, ',', ' ') . ' FCFA'), 1, 0, 'R');
            $this->Cell($w[6], 6, utf8_decode($row['payment_mode']), 1, 0, 'L');
            $this->Ln();
        }

        // Ligne de clôture du tableau
        // $this->Cell(array_sum($w), 0, '', 'T');
    }
}

// --- GÉNÉRATION DU PDF ---

$pdf = new PDF();
$pdf->AliasNbPages(); // Permet d'utiliser {nb} pour le nombre total de pages
$pdf->AddPage('L'); // Format Paysage (L) pour avoir plus de place
$pdf->SetMargins(10, 10, 10);

$pdf->LoadData($transactions);

// --- ENVOI AU NAVIGATEUR ET IMPRESSION AUTOMATIQUE (JavaScript) ---

// FPDF n'a pas de fonction intégrée pour l'impression automatique
// On utilise 'I' pour afficher le PDF dans le navigateur.
// L'impression automatique doit être gérée par un code JavaScript inclus dans le PDF, 
// mais FPDF ne supporte pas cela nativement de manière simple.
// La solution la plus courante est de laisser l'utilisateur imprimer manuellement depuis la visionneuse.
// Si l'impression automatique est une exigence stricte, vous devrez utiliser une librairie PDF plus avancée 
// comme TCPDF ou Dompdf avec une extension JavaScript.

// Pour FPDF, le mode 'I' (Inline) est le meilleur choix pour un aperçu immédiat.
$pdf->Output('I', 'Rapport_Transactions_' . date('Ymd') . '.pdf');
