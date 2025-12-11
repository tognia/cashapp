<?php
// Inclure la connexion à la base de données
include_once 'db/connect_db.php';

// Vérification de session
if (empty($_SESSION['user_name'])) {
    exit('Accès non autorisé.');
}

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

    // 3. Filtre par Magasin (Seulement si filtre date actif OU par défaut selon logique)
    if ($role == "Admin" && $leshop != "all") {
        // Correction : on utilise 'user' au lieu de 'cashier_name'
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
    exit("Erreur SQL : " . $e->getMessage());
}

// --- LOGIQUE D'EXPORTATION CSV ---

// 1. Définir le nom du fichier (avec le statut pour être clair)
$status_label = ($view_status == 'canceled') ? 'ANNULEES' : 'VALIDEES';
$filename = 'rapport_transactions_' . $status_label . '_' . date('Ymd_His') . '.csv';

// 2. Définir les en-têtes HTTP
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// 3. Ouvrir le flux de sortie
$output = fopen('php://output', 'w');

// Délimiteur (point-virgule pour Excel français)
$delimiter = ';';

// BOM pour UTF-8
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// En-têtes des colonnes
fputcsv($output, array('ID', 'OPERATEUR', 'CLIENT ID', 'DATE', 'MONTANT TOTAL (FCFA)', 'TVA (FCFA)', 'MODE PAIEMENT'), $delimiter);

// Variables pour les totaux
$grand_total = 0;
$grand_tva = 0;

// Écrire les données ligne par ligne
foreach ($transactions as $row) {
    $csv_row = [
        $row['invoice_id'],
        $row['cashier_name'],
        $row['id_client'],
        $row['order_date'],
        number_format($row['total'], 0, ',', ' '), // Format visuel
        number_format($row['tva'], 0, ',', ' '),
        $row['payment_mode']
    ];
    fputcsv($output, $csv_row, $delimiter);

    // Accumulation des totaux (sur les valeurs brutes de la BDD)
    $grand_total += $row['total'];
    $grand_tva += $row['tva'];
}

// 4. Ajouter une ligne vide puis les totaux
fputcsv($output, [], $delimiter); // Ligne vide

$row_totals = [
    '', // ID vide
    '', // Opérateur vide
    '', // Client vide
    'TOTAUX GÉNÉRAUX', // Label
    number_format($grand_total, 0, ',', ' '), // Total Montant
    number_format($grand_tva, 0, ',', ' '),   // Total TVA
    ''  // Paiement vide
];
fputcsv($output, $row_totals, $delimiter);

// 5. Fermer le pointeur
fclose($output);
exit();
