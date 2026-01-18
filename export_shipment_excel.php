<?php
include_once 'db/connect_db.php';
session_start();

if (empty($_SESSION['user_id'])) exit;

$shop = $_GET['shop'];
$start = $_GET['start'];
$end = $_GET['end'];

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=Rapport_Expedition_' . $shop . '_' . $start . '.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['Code Produit', 'Nom Produit', 'SKU', 'Quantite Totale']);

$sql = "SELECT product_code, product_name, product_sku, SUM(shipped_quantity) as total_qty 
        FROM tbl_product_shipment 
        WHERE code_agence = ? AND shipment_date BETWEEN ? AND ? 
        GROUP BY product_code";

$stmt = $pdo->prepare($sql);
$stmt->execute([$shop, $start, $end]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}
fclose($output);
