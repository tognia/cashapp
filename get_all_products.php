<?php
include_once 'db/connect_db.php';
header('Content-Type: application/json; charset=utf-8');

$query = $pdo->prepare("SELECT product_code, product_name FROM tbl_product ORDER BY product_name ASC");
$query->execute();
$products = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($products);
