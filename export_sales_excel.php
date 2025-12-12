<?php
// export_sales_excel.php
ob_start();
include_once 'db/connect_db.php';

if (empty($_SESSION['user_name'])) {
    ob_end_clean();
    exit('Accès non autorisé.');
}

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
if (($_SESSION['role'] ?? '') == "Admin" || ($_SESSION['role'] ?? '') == "Responsable") {
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

$filename = 'rapport_ventes_filtre_' . date('Ymd_His') . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

ob_end_clean();
$output = fopen('php://output', 'w');
$delimiter = ';';
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

fputcsv($output, array('ID Transaction', 'OPERATEUR', 'CLIENT ID', 'DATE', 'TVA', 'MONTANT TOTAL (FCFA)', 'MODE PAIEMENT'), $delimiter);

foreach ($transactions as $row) {
    $csv_row = [
        $row['invoice_id'],
        $row['cashier_name'],
        $row['id_client'],
        $row['order_date'],
        $row['tva'],
        $row['total'],
        $row['payment_mode']
    ];
    fputcsv($output, $csv_row, $delimiter);
}

fclose($output);
exit();
