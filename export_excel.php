<?php
// Inclure la connexion à la base de données
include_once 'db/connect_db.php';

// Vérification minimale de session (optionnel mais recommandé)
if (empty($_SESSION['user_name'])) {
    exit('Accès non autorisé.');
}

// --- LOGIQUE DE RÉCUPÉRATION DES DONNÉES ET FILTRES (Répétée de order.php) ---

$sql = "SELECT * FROM tbl_invoice";
$conditions = [];
$params = [];
$magasin = $_SESSION['magasin'] ?? '';
// Les paramètres de filtre sont récupérés via $_GET car ils sont passés dans la querystring par le bouton.
$leshop = $_GET['shop'] ?? null;
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

// --- LOGIQUE D'EXPORTATION CSV ---

// 1. Définir le nom du fichier
$filename = 'rapport_transactions_' . date('Ymd_His') . '.csv';

// 2. Définir les en-têtes HTTP pour forcer le téléchargement en tant que CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// 3. Ouvrir un pointeur vers le flux de sortie
$output = fopen('php://output', 'w');

// Utiliser le point-virgule (;) ou la virgule (,) comme délimiteur. 
// Le point-virgule est souvent préféré pour la compatibilité avec Excel dans les régions francophones.
$delimiter = ';';

// Écrire le BOM (Byte Order Mark) pour assurer l'encodage UTF-8 sous Excel
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Écrire l'en-tête du fichier CSV (les titres des colonnes)
fputcsv($output, array('ID', 'OPERATEUR', 'CLIENT ID', 'DATE', 'MONTANT TOTAL (FCFA)', 'TVA (FCFA)', 'MODE PAIEMENT'), $delimiter);

// Écrire les données ligne par ligne
foreach ($transactions as $row) {
    // Récupération sécurisée et formatage des données
    $csv_row = [
        $row['invoice_id'],
        $row['cashier_name'],
        $row['id_client'],
        $row['order_date'],
        number_format($row['total'], 0, ',', ' '), // Montant formaté
        number_format($row['tva'], 0, ',', ' '),   // TVA formatée
        $row['payment_mode']
    ];
    fputcsv($output, $csv_row, $delimiter);
}

// 5. Fermer le pointeur de fichier
fclose($output);
exit();
