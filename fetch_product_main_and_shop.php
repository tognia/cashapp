<?php
include_once 'db/connect_db.php';
header('Content-Type: application/json');

if (!isset($_GET['code']) || !isset($_GET['shop'])) {
    echo json_encode(['status' => 'error', 'message' => 'Paramètres manquants.']);
    exit;
}

$code = $_GET['code'];
$shop = $_GET['shop'];

try {
    // 1. Fetch from Main Warehouse
    $stmt = $pdo->prepare("SELECT product_id, product_code, product_name, stock as main_stock FROM tbl_product WHERE product_code = :code OR product_sku = :code");
    $stmt->execute([':code' => $code]);
    $main_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$main_data) {
        echo json_encode(['status' => 'error', 'message' => 'Produit non trouvé à l\'entrepôt.']);
        exit;
    }

    if ($main_data['main_stock'] <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Stock épuisé à l\'entrepôt.']);
        exit;
    }

    // 2. Fetch current Shop Stock
    $stmt_shop = $pdo->prepare("SELECT stock as shop_stock FROM tbl_shop_item WHERE product_code = :code AND shop_code = :shop");
    $stmt_shop->execute([':code' => $main_data['product_code'], ':shop' => $shop]);
    $shop_data = $stmt_shop->fetch(PDO::FETCH_ASSOC);

    // If product doesn't exist in shop item yet, default stock to 0
    $data = [
        'product_id'   => $main_data['product_id'],
        'product_code' => $main_data['product_code'],
        'product_name' => $main_data['product_name'],
        'main_stock'   => $main_data['main_stock'],
        'shop_stock'   => $shop_data ? $shop_data['shop_stock'] : 0
    ];

    echo json_encode(['status' => 'success', 'data' => $data]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
