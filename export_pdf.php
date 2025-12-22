<?php
// Inclure la librairie FPDF
require('./fpdf/fpdf.php');
// Inclure la connexion à la base de données
include_once 'db/connect_db.php';

// --- LOGIQUE DE RÉCUPÉRATION DES DONNÉES ET FILTRES ---

$sql = "SELECT * FROM tbl_invoice";
$conditions = [];
$params = [];

$magasin = $_SESSION['magasin'] ?? '';
$role = $_SESSION['role'] ?? '';

// Récupération des paramètres GET
$leshop = $_GET['shop'] ?? null;
$fromdate = $_GET['date_1'] ?? null;
$todate = $_GET['date_2'] ?? null;
$view_status = $_GET['view_status'] ?? 'saved'; // Par défaut 'saved'

// 1. Filtre par Statut
$conditions[] = "status = :status";
$params[':status'] = $view_status;

// 2. Filtre par Date
if ($fromdate && $todate) {
    $conditions[] = "order_date BETWEEN :fromdate AND :todate";
    $params[':fromdate'] = $fromdate;
    $params[':todate'] = $todate;

    // 3. Filtre par Magasin (Seulement si filtre date actif OU par défaut selon la logique voulue)
    if ($role == "Admin" && $leshop != "all") {
        $conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :leshop)";
        $params[':leshop'] = $leshop;
    } elseif ($role != "Admin") {
        $conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
        $params[':magasin'] = $magasin;
    }
} else {
    // Pas de filtre de date, on applique quand même la restriction magasin pour les non-admins
    if ($role != "Admin") {
        $conditions[] = "user IN (SELECT username FROM tbl_user WHERE magasin = :magasin)";
        $params[':magasin'] = $magasin;
    }
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= " ORDER BY invoice_id DESC";

try {
    $select = $pdo->prepare($sql);
    $select->execute($params);
    $transactions = $select->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}

// --- CLASSE PDF PERSONNALISÉE ---

class PDF extends FPDF
{
    // En-tête de la page
    function Header()
    {
        global $view_status; // Récupérer la variable globale pour l'affichage

        $title_status = ($view_status == 'canceled') ? 'ANNULEES' : 'VALIDEES';

        // Police
        $this->SetFont('Arial', 'B', 15);

        // Titre principal
        $this->Cell(0, 10, utf8_decode('Rapport des Opérations de Caisse'), 0, 1, 'C');

        // Sous-titre Statut
        $this->SetFont('Arial', 'B', 12);
        if ($view_status == 'canceled') {
            $this->SetTextColor(200, 0, 0); // Rouge pour annulé
        }
        $this->Cell(0, 6, utf8_decode('Statut : ' . $title_status), 0, 1, 'C');
        $this->SetTextColor(0, 0, 0); // Reset couleur noire

        // Informations sur le filtre
        $this->SetFont('Arial', 'I', 9);
        $filter_info = "Date du rapport: " . date('d/m/Y H:i:s');
        if (isset($_GET['date_1']) && isset($_GET['date_2'])) {
            $filter_info .= " | Periode: Du " . $_GET['date_1'] . " au " . $_GET['date_2'];
        }

        // Affichage du magasin si filtre
        if (isset($_GET['shop']) && $_GET['shop'] != 'all') {
            $filter_info .= " | Magasin: " . $_GET['shop'];
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
        $this->SetFillColor(230, 230, 230); // Gris clair pour l'en-tête

        // Largeurs des colonnes (Total = ~277mm pour A4 Paysage - marges)
        // Ajustement des largeurs pour bien remplir la page
        $w = array(15, 40, 50, 30, 40, 35, 40);

        // En-tête du tableau
        $header = array('No', 'Num Invoice', utf8_decode('Opérateur'), 'Date', 'Montant Total', 'TVA', 'Mode Paiement');
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();

        // Corps du tableau
        $this->SetFont('Arial', '', 9);

        $grand_total = 0;
        $grand_tva = 0;
        $i = 1;

        foreach ($transactions as $row) {
            $this->Cell($w[0], 7, $i++, 1, 0, 'C');
            $this->Cell($w[1], 7, utf8_decode($row['invoice_id']), 1, 0, 'L');
            $this->Cell($w[2], 7, utf8_decode($row['cashier_name']), 1, 0, 'L');
            $this->Cell($w[3], 7, utf8_decode($row['order_date']), 1, 0, 'C');
            $this->Cell($w[4], 7, number_format($row['total'], 0, ',', ' ') . ' FCFA', 1, 0, 'R');
            $this->Cell($w[5], 7, number_format($row['tva'], 0, ',', ' ') . ' FCFA', 1, 0, 'R');
            $this->Cell($w[6], 7, utf8_decode($row['payment_mode']), 1, 0, 'L');
            $this->Ln();

            // Calcul des totaux
            $grand_total += $row['total'];
            $grand_tva += $row['tva'];
        }

        // LIGNE DE TOTAUX
        $this->Ln(1); // Petit espace
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(200, 220, 255); // Bleu clair pour le total

        // Cellule fusionnée pour le libellé (Colonnes 0, 1, 2, 3)
        // Largeur = 15 + 40 + 50 + 30 = 135
        $width_label = $w[0] + $w[1] + $w[2] + $w[3];

        $this->Cell($width_label, 9, utf8_decode('TOTAUX GÉNÉRAUX'), 1, 0, 'R', true);

        // Affichage Total Montant
        $this->Cell($w[4], 9, number_format($grand_total, 0, ',', ' ') . ' FCFA', 1, 0, 'R', true);

        // Affichage Total TVA
        $this->Cell($w[5], 9, number_format($grand_tva, 0, ',', ' ') . ' FCFA', 1, 0, 'R', true);

        // Cellule vide pour la fin
        $this->Cell($w[6], 9, '', 1, 0, 'C', true);
    }
}

// --- GÉNÉRATION DU PDF ---

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L'); // Format Paysage
$pdf->SetMargins(10, 10, 10);

$pdf->LoadData($transactions);

// Sortie
$filename = 'Rapport_' . $view_status . '_' . date('Ymd_His') . '.pdf';
$pdf->Output('I', $filename);
