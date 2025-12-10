<?php
include_once 'db/connect_db.php';
header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['term'])) {
    echo json_encode([]);
    exit;
}

$term = $_GET['term'];

$query = $pdo->prepare("
    SELECT product_code, product_name, buy_price, sell_price
    FROM tbl_product
    WHERE product_code LIKE :term
       OR product_name LIKE :term
    ORDER BY product_name ASC
    LIMIT 15
");

$query->execute(['term' => "%$term%"]);
$results = $query->fetchAll(PDO::FETCH_ASSOC);

$data = [];
foreach ($results as $row) {
    $data[] = [
        "label" => $row['product_code'] . " – " . $row['product_name'],
        "value" => $row['product_code'], // renvoyé dans l’input
        "product" => $row
    ];
}

echo json_encode($data);
