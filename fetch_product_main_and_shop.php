<?php
// fetch_product_main_and_shop.php
// Ce script retourne les informations du stock principal ET du stock boutique pour un produit donné.
include_once 'db/connect_db.php';

header('Content-Type: application/json');

// Vérifiez la session et l'accès (facultatif mais recommandé)
if (empty($_SESSION['user_name'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé.']);
    exit();
}

$product_code_or_sku = $_GET['code'] ?? null;
$shop_code = $_GET['shop'] ?? null;

if (!$product_code_or_sku || !$shop_code) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Code produit ou Code magasin manquant.']);
    exit();
}

try {
    $search_term = trim(str_replace(' ', '', $product_code_or_sku));

    // 1. Chercher les informations du produit dans la table principale (stock entrepôt)
    $select_main = $pdo->prepare("
        SELECT 
            product_id, 
            product_code, 
            product_sku, 
            product_name, 
            stock 
        FROM tbl_product 
        WHERE product_code = :search_term OR product_sku = :search_term 
        LIMIT 1
    ");
    $select_main->bindParam(':search_term', $search_term);
    $select_main->execute();
    $main_product = $select_main->fetch(PDO::FETCH_ASSOC);

    if (!$main_product) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Produit non trouvé dans l\'entrepôt.']);
        exit();
    }

    // 2. Chercher les informations du produit dans la table de la boutique (stock boutique)
    $select_shop = $pdo->prepare("
        SELECT 
            product_id, 
            stock, 
            min_stock 
        FROM tbl_shop_item 
        WHERE product_code = :product_code AND shop_code = :shop_code 
        LIMIT 1
    ");
    // Utiliser le product_code garanti par la recherche principale
    $select_shop->bindParam(':product_code', $main_product['product_code']);
    $select_shop->bindParam(':shop_code', $shop_code);
    $select_shop->execute();
    $shop_item = $select_shop->fetch(PDO::FETCH_ASSOC);

    if (!$shop_item) {
        // Le produit n'existe pas dans l'inventaire de la boutique. 
        // Bien que l'on puisse l'initialiser ici, pour rester simple, nous exigeons qu'il soit déjà dans tbl_shop_item.
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Ce produit n\'est pas initialisé pour la boutique ' . htmlspecialchars($shop_code) . '.']);
        exit();
    }

    // 3. Retourner les données consolidées
    echo json_encode([
        'status' => 'success',
        'data' => [
            'product_id' => $shop_item['product_id'], // Utiliser l'ID de tbl_shop_item si c'est la clé de référence
            'product_code' => $main_product['product_code'],
            'product_name' => $main_product['product_name'],
            'main_stock' => (int)$main_product['stock'],
            'shop_stock' => (int)$shop_item['stock'],
            'shop_min_stock' => (int)$shop_item['min_stock']
        ]
    ]);
} catch (PDOException $e) {
    error_log("Fetch Product Transfer Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erreur base de données.']);
}
