<?php
// Inclure la librairie FPDF
require('../fpdf/fpdf.php');
// Inclure le fichier de connexion à la base de données
include_once '../db/connect_db.php';

// --- NOUVELLE LOGIQUE POUR CONTRÔLER LA SORTIE ---
// Si 'output=silent' est passé, on termine sans générer de PDF.
if (isset($_GET['output']) && $_GET['output'] == 'silent') {
    exit;
}

// Récupérer l'ID de la facture depuis l'URL
$invoice_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($invoice_id == 0) {
    exit('Erreur: ID de facture non spécifié.');
}

// 1. Récupérer les détails de la facture (tbl_invoice)
$req_invoice = $pdo->prepare("SELECT * FROM tbl_invoice WHERE invoice_id = :id");
$req_invoice->bindParam(':id', $invoice_id);
$req_invoice->execute();
$invoice_data = $req_invoice->fetch(PDO::FETCH_ASSOC);

if (!$invoice_data) {
    exit('Erreur: Facture introuvable.');
}

// 2. Récupérer les détails des produits (tbl_invoice_detail)
$req_details = $pdo->prepare("SELECT product_id, product_name, qty, price, remise, total FROM tbl_invoice_detail WHERE invoice_id = :id");
$req_details->bindParam(':id', $invoice_id);
$req_details->execute();
$product_details = $req_details->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les infos du client (pour affichage)
$s = $invoice_data['id_client'];
$sel = $pdo->prepare("SELECT firstname, middlename, lastname FROM users WHERE username = :username");
$sel->bindParam(':username', $s);
$sel->execute();
$row1 = $sel->fetch(PDO::FETCH_ASSOC);
$nomclient = $invoice_data['id_client'] . "_" . ($row1 ? $row1['firstname'] . " " . $row1['middlename'] . " " . $row1['lastname'] : 'Client Inconnu');

// Définition de l'objet FPDF pour un format reçu (80mm)
$pdf = new FPDF('P', 'mm', array(80, 200));
$pdf->AddPage();

// --- CORRECTION D'ERREUR FONT : Définir une police par défaut immédiatement après AddPage() ---
$pdf->SetFont('Arial', '', 7);

// --- EN-TÊTE DU REÇU (COMPACTÉ) ---

// Le logo se termine à Y=15 (5 + 10)
$pdf->Image('../images/logo_nk.png', 30, 5, 20, 10);

// Définir la position juste après le logo
$pdf->SetY(16);

// Ligne vide réduite
$pdf->Cell(60, 1, ' ', 0, 1, 'C');

// Ligne de séparation
$pdf->Line(10, $pdf->GetY(), 70, $pdf->GetY());

// Titre (Taille 8)
$pdf->SetFont('Arial', 'B', 8);
// Utilisation de ASCII//TRANSLIT pour remplacer les caractères spéciaux (é -> e, ç -> c)
$pdf->Cell(60, 5, iconv('UTF-8', 'ASCII//TRANSLIT', 'Recu de Vente'), 0, 1, 'C');

// Infos boutique (Taille 6)
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 3, 'Yaounde - CMR', 0, 1, 'C');
$pdf->Cell(60, 3, 'Epicerie', 0, 1, 'C');
$pdf->Cell(60, 3, 'Tel. 00237 621 10 05 00 (SCTE NGNO-KWE SERVICES SARL)', 0, 1, 'C');

// Ligne de séparation
$pdf->Line(10, $pdf->GetY() + 1, 70, $pdf->GetY() + 1);
$pdf->SetY($pdf->GetY() + 2);

// --- DÉTAILS DE LA FACTURE (Taille 7) ---

$pdf->SetFont('Courier', 'B', 7);
$pdf->Cell(25, 4, 'Date / Heure :', 0, 0, 'L');
$pdf->SetFont('Courier', '', 7);
$pdf->Cell(35, 4, date("d-m-Y H:i:s", strtotime($invoice_data['order_date'] . ' ' . $invoice_data['time_order'])), 0, 1, 'R');

$pdf->SetFont('Courier', 'B', 7);
$pdf->Cell(25, 4, 'Facture Num :', 0, 0, 'L');
$pdf->SetFont('Courier', '', 7);
$pdf->Cell(35, 4, $invoice_id, 0, 1, 'R');

$pdf->SetFont('Courier', 'B', 7);
$pdf->Cell(25, 4, 'Opérateur :', 0, 0, 'L');
$pdf->SetFont('Courier', '', 7);
// Contrôle des caractères spéciaux (é -> e, ç -> c)
$pdf->Cell(35, 4, iconv('UTF-8', 'ASCII//TRANSLIT', $invoice_data['cashier_name']), 0, 1, 'R');

$pdf->SetFont('Courier', 'B', 7);
$pdf->Cell(25, 4, 'Client :', 0, 0, 'L');
$pdf->SetFont('Courier', '', 7);
// Contrôle des caractères spéciaux (é -> e, ç -> c)
$pdf->Cell(35, 4, iconv('UTF-8', 'ASCII//TRANSLIT', $nomclient), 0, 1, 'R');

$pdf->Line(10, $pdf->GetY(), 70, $pdf->GetY());

$pdf->Cell(60, 1, ' ', 0, 1, 'C'); // Espace réduit

// --- EN-TÊTES DES PRODUITS (Taille 7) ---
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetX(10);
$pdf->Cell(25, 4, 'Produit', 0, 0, 'L');
$pdf->Cell(10, 4, 'Qte', 0, 0, 'C');
$pdf->Cell(10, 4, 'Prix', 0, 0, 'R');
$pdf->Cell(15, 4, 'Total', 0, 1, 'R');

$pdf->Line(10, $pdf->GetY(), 70, $pdf->GetY());
$pdf->Cell(60, 1, ' ', 0, 1, 'C'); // Espace 1mm

// --- DÉTAILS DES PRODUITS (Taille AUGMENTÉE à 9) ---
$pdf->SetFont('Courier', '', 7);
foreach ($product_details as $item) {
    $pdf->SetX(10);
    // Afficher le nom du produit (Contrôle des caractères spéciaux et limitation de la taille)
    $product_name_display = substr($item['product_name'], 0, 20);
    // Contrôle des caractères spéciaux (é -> e, ç -> c)
    $pdf->Cell(25, 5, iconv('UTF-8', 'ASCII//TRANSLIT', $product_name_display), 0, 0, 'L');
    $pdf->Cell(10, 5, $item['qty'], 0, 0, 'C');
    $pdf->Cell(10, 5, number_format($item['price']), 0, 0, 'R');
    $pdf->Cell(15, 5, number_format($item['total']), 0, 1, 'R');
}

$pdf->Line(10, $pdf->GetY(), 70, $pdf->GetY());
$pdf->Cell(60, 2, ' ', 0, 1, 'C'); // Espace

// --- TOTAUX (Taille 7) ---

$pdf->SetFont('Arial', '', 7);
$pdf->SetX(10);
$pdf->Cell(40, 4, 'Total Remise :', 0, 0, 'L');
$pdf->Cell(20, 4, number_format($invoice_data['remise']) . ' FCFA', 0, 1, 'R');

$pdf->SetX(10);
$pdf->Cell(40, 4, 'TVA (19.25%) :', 0, 0, 'L');
$pdf->Cell(20, 4, number_format($invoice_data['tva']) . ' FCFA', 0, 1, 'R');

// TOTAL TTC (Taille 9, GRAS conservé)
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX(10);
$pdf->Cell(40, 5, 'TOTAL TTC :', 0, 0, 'L');
$pdf->Cell(20, 5, number_format($invoice_data['total']) . ' FCFA', 0, 1, 'R');
$pdf->Line(10, $pdf->GetY(), 70, $pdf->GetY()); // Ligne de séparation

// Mode de Paiement (Taille 7)
$pdf->SetFont('Arial', '', 7);
$pdf->SetX(10);
$pdf->Cell(40, 4, 'Mode de Paiement :', 0, 0, 'L');
// Contrôle des caractères spéciaux (é -> e, ç -> c)
$pdf->Cell(20, 4, iconv('UTF-8', 'ASCII//TRANSLIT', $invoice_data['payment_mode']), 0, 1, 'R');

$pdf->SetX(10);
$pdf->Cell(40, 4, 'Argent Recu :', 0, 0, 'L');
$pdf->Cell(20, 4, number_format($invoice_data['paid']) . ' FCFA', 0, 1, 'R');

$pdf->SetX(10);
$pdf->Cell(40, 4, 'Remboursement :', 0, 0, 'L');
$pdf->Cell(20, 4, number_format($invoice_data['due']) . ' FCFA', 0, 1, 'R');

$pdf->Line(10, $pdf->GetY() + 1, 70, $pdf->GetY() + 1);
$pdf->SetY($pdf->GetY() + 2); // Espace

// --- PIED DE PAGE (Taille 7 / 6) ---

$pdf->SetFont('Arial', '', 7);
// Contrôle des caractères spéciaux (é -> e, ç -> c)
$pdf->Cell(60, 4, iconv('UTF-8', 'ASCII//TRANSLIT', 'Merci de votre achat !'), 0, 1, 'C');
$pdf->SetFont('Arial', 'BU', 6);
// Contrôle des caractères spéciaux (é -> e, ç -> c)
$pdf->Cell(60, 3, iconv('UTF-8', 'ASCII//TRANSLIT', 'Les retours ne sont pas acceptes sans une note d achat'), 0, 1, 'C');


// Sortie du PDF (I pour afficher dans le navigateur)
$pdf->Output('I', 'recu_facture_' . $invoice_id . '.pdf');
