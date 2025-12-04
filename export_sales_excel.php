<?php
// Inclure la connexion à la base de données
include_once 'db/connect_db.php';

// Vérification minimale de session
if (empty($_SESSION['user_name'])) {
    exit('Accès non autorisé.');
}

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

// --- LOGIQUE D'EXPORTATION CSV ---

// 1. Définir le nom du fichier
$filename = 'rapport_ventes_filtre_' . date('Ymd_His') . '.csv';

// 2. Définir les en-têtes HTTP pour forcer le téléchargement
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// 3. Ouvrir un pointeur vers le flux de sortie
$output = fopen('php://output', 'w');
$delimiter = ';';

// Écrire le BOM (Byte Order Mark) pour assurer l'encodage UTF-8 sous Excel
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Écrire l'en-tête du fichier CSV (les titres des colonnes)
fputcsv($output, array('ID Transaction', 'OPERATEUR', 'CLIENT ID', 'DATE', 'MONTANT HT', 'TVA', 'MONTANT TOTAL (FCFA)', 'MODE PAIEMENT'), $delimiter);

// Écrire les données ligne par ligne
foreach ($transactions as $row) {
    // Récupération sécurisée et formatage des données
    $csv_row = [
        $row['invoice_id'],
        $row['cashier_name'],
        $row['id_client'],
        $row['order_date'],
        $row['subtotal'], // Total HT (si subtotal est le champ HT)
        $row['tva'],
        $row['total'],     // Montant Total
        $row['payment_mode']
    ];
    fputcsv($output, $csv_row, $delimiter);
}

// 5. Fermer le pointeur de fichier
fclose($output);
exit();
