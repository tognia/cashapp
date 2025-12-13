<?php
// Inclure la librairie FPDF
require('../fpdf/fpdf.php');
// Inclure le fichier de connexion à la base de données
include_once '../db/connect_db.php';

// --- NOUVELLE LOGIQUE POUR CONTRÔLER LA SORTIE ---
// Nous cherchons un paramètre 'output' dans l'URL.
// Si 'output=silent' (appelé par openDesktopReceiptWindow), on arrête ici.
// Si 'output' est absent ou 'output=view', on continue pour générer et afficher le PDF.
if (isset($_GET['output']) && $_GET['output'] == 'silent') {
    // Si la demande est silencieuse, on termine l'exécution sans générer de sortie.
    exit;
}

// Récupérer l'ID de la facture depuis l'URL
$invoice_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($invoice_id == 0) {
    // Gérer l'absence d'ID
    // Note: FPDF est utilisé, donc un simple 'echo' ne fonctionnera pas comme sur une page HTML normale.
    // Pour les erreurs, on peut générer un PDF d'erreur ou simplement rediriger/terminer.
    // Ici, nous choisissons de terminer l'exécution.
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

// Récupérer les infos du client (tel que dans l'original)
$s = $invoice_data['id_client'];
$sel = $pdo->prepare("SELECT firstname, middlename, lastname FROM users WHERE username = :username");
$sel->bindParam(':username', $s);
$sel->execute();
$row1 = $sel->fetch(PDO::FETCH_ASSOC);
$nomclient = $invoice_data['id_client'] . "_" . ($row1 ? $row1['firstname'] . " " . $row1['middlename'] . " " . $row1['lastname'] : 'Client Inconnu');

// Définition de l'objet FPDF pour un format reçu
// Largeur 80mm, hauteur auto (200mm est une valeur de départ)
$pdf = new FPDF('P', 'mm', array(80, 200));
$pdf->AddPage();

// --- EN-TÊTE DU REÇU (Similaire au HTML) ---

$pdf->SetFont('Arial', 'B', 5);
// Chemin ajusté pour l'image
$pdf->Image('../images/logo_nk.png', 30, 5, 20, 10); // x, y, width, height

$pdf->SetY(17);
$pdf->Cell(60, 3, ' ', 0, 1, 'C');
$pdf->Line(10, 20, 70, 20); // Ligne de séparation

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(60, 5, 'Reçu de Vente', 0, 1, 'C');

$pdf->SetFont('Arial', '', 4);
$pdf->Cell(60, 3, 'Yaounde - CMR', 0, 1, 'C');
$pdf->Cell(60, 3, 'Epicerie', 0, 1, 'C');
$pdf->Cell(60, 3, 'Tel. 00237 621 10 05 00 (SCTE NGNO-KWE SERVICES SARL)', 0, 1, 'C');

$pdf->Line(10, 35, 70, 35);

// --- DÉTAILS DE LA FACTURE ---

$pdf->SetFont('Courier', 'B', 5);
$pdf->Cell(25, 4, 'Date / Heure :', 0, 0, 'L');
$pdf->SetFont('Courier', '', 5);
$pdf->Cell(35, 4, date("d-m-Y H:i:s", strtotime($invoice_data['order_date'] . ' ' . $invoice_data['time_order'])), 0, 1, 'R');

$pdf->SetFont('Courier', 'B', 5);
$pdf->Cell(25, 4, 'Facture N° :', 0, 0, 'L');
$pdf->SetFont('Courier', '', 5);
$pdf->Cell(35, 4, $invoice_id, 0, 1, 'R');

$pdf->SetFont('Courier', 'B', 5);
$pdf->Cell(25, 4, 'Opérateur :', 0, 0, 'L');
$pdf->SetFont('Courier', '', 5);
$pdf->Cell(35, 4, $invoice_data['cashier_name'], 0, 1, 'R');

$pdf->SetFont('Courier', 'B', 5);
$pdf->Cell(25, 4, 'Client :', 0, 0, 'L');
$pdf->SetFont('Courier', '', 5);
$pdf->Cell(35, 4, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $nomclient), 0, 1, 'R');

$pdf->Line(10, $pdf->GetY(), 70, $pdf->GetY());

$pdf->Cell(60, 2, ' ', 0, 1, 'C'); // Espace

// --- EN-TÊTES DES PRODUITS ---
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetX(10);
$pdf->Cell(25, 4, 'Produit', 0, 0, 'L');
$pdf->Cell(10, 4, 'Qté', 0, 0, 'C');
$pdf->Cell(10, 4, 'Prix', 0, 0, 'R');
$pdf->Cell(15, 4, 'Total', 0, 1, 'R');

$pdf->Line(10, $pdf->GetY(), 70, $pdf->GetY());
$pdf->Cell(60, 1, ' ', 0, 1, 'C'); // Espace

// --- DÉTAILS DES PRODUITS ---
$pdf->SetFont('Courier', '', 5);
foreach ($product_details as $item) {
    $pdf->SetX(10);
    // Afficher le nom du produit
    $pdf->Cell(25, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($item['product_name'], 0, 20)), 0, 0, 'L');
    // Afficher la quantité
    $pdf->Cell(10, 3, $item['qty'], 0, 0, 'C');
    // Afficher le Prix Unitaire
    $pdf->Cell(10, 3, number_format($item['price']), 0, 0, 'R');
    // Afficher le Total de la ligne (incluant les remises s'il y en a)
    $pdf->Cell(15, 3, number_format($item['total']), 0, 1, 'R');
}

$pdf->Line(10, $pdf->GetY(), 70, $pdf->GetY());
$pdf->Cell(60, 2, ' ', 0, 1, 'C'); // Espace

// --- TOTAUX ---

$pdf->SetFont('Arial', '', 6);
$pdf->SetX(10);
$pdf->Cell(40, 4, 'Total Remise :', 0, 0, 'L');
$pdf->Cell(20, 4, number_format($invoice_data['remise']) . ' FCFA', 0, 1, 'R');

$pdf->SetX(10);
$pdf->Cell(40, 4, 'TVA (19.25%) :', 0, 0, 'L');
$pdf->Cell(20, 4, number_format($invoice_data['tva']) . ' FCFA', 0, 1, 'R');

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetX(10);
$pdf->Cell(40, 4, 'TOTAL TTC :', 0, 0, 'L');
$pdf->Cell(20, 4, number_format($invoice_data['total']) . ' FCFA', 0, 1, 'R');
$pdf->Line(10, $pdf->GetY(), 70, $pdf->GetY()); // Ligne de séparation

$pdf->SetFont('Arial', '', 6);
$pdf->SetX(10);
$pdf->Cell(40, 4, 'Mode de Paiement :', 0, 0, 'L');
$pdf->Cell(20, 4, $invoice_data['payment_mode'], 0, 1, 'R');

$pdf->SetX(10);
$pdf->Cell(40, 4, 'Argent Reçu :', 0, 0, 'L');
$pdf->Cell(20, 4, number_format($invoice_data['paid']) . ' FCFA', 0, 1, 'R');

$pdf->SetX(10);
$pdf->Cell(40, 4, 'Remboursement :', 0, 0, 'L');
$pdf->Cell(20, 4, number_format($invoice_data['due']) . ' FCFA', 0, 1, 'R');

$pdf->Line(10, $pdf->GetY() + 1, 70, $pdf->GetY() + 1);
$pdf->SetY($pdf->GetY() + 2); // Espace

// --- PIED DE PAGE ---

$pdf->SetFont('Arial', '', 6);
$pdf->Cell(60, 4, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Merci de votre achat !'), 0, 1, 'C');
$pdf->SetFont('Arial', 'BU', 4);
$pdf->Cell(60, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Les retours ne sont pas acceptés sans une note d\'achat'), 0, 1, 'C');


// Sortie du PDF (I pour afficher dans le navigateur, D pour télécharger)
$pdf->Output('I', 'reçu_facture_' . $invoice_id . '.pdf');
