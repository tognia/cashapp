<?php
// fetch_product_data.php
// Ce script retourne les informations d'un produit en JSON pour l'ajout en masse.
include_once 'db/connect_db.php';

header('Content-Type: application/json');

// Vérifiez la session et l'accès (facultatif mais recommandé)
if (empty($_SESSION['user_name'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé.']);
    exit();
}

$product_code_or_sku = $_GET['code'] ?? null;

if (!$product_code_or_sku) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Code produit manquant.']);
    exit();
}

try {
    // Nettoyage et préparation de la requête.
    $search_term = trim(str_replace(' ', '', $product_code_or_sku));

    // Recherche par product_code OU product_sku
    $select = $pdo->prepare("
        SELECT 
            product_id, 
            product_code, 
            product_sku, 
            product_name, 
            stock, 
            purchase_price, 
            supplier, 
            product_satuan
        FROM tbl_product 
        WHERE product_code = :search_term OR product_sku = :search_term 
        LIMIT 1
    ");
    $select->bindParam(':search_term', $search_term);
    $select->execute();
    $product = $select->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        echo json_encode([
            'status' => 'success',
            'data' => [
                'product_id' => $product['product_id'],
                'product_code' => $product['product_code'],
                'product_sku' => $product['product_sku'],
                'product_name' => $product['product_name'],
                'stock' => (int)$product['stock'],
                'purchase_price' => (float)$product['purchase_price'],
                'supplier' => $product['supplier'],
                'product_satuan' => $product['product_satuan']
            ]
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Produit non trouvé.']);
    }
} catch (PDOException $e) {
    error_log("Fetch Product Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erreur base de données.']);
}
