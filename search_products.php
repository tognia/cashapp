<?php
// search_products.php - Recherche AJAX pour Select2
include_once 'db/connect_db.php';

header('Content-Type: application/json; charset=utf-8');

$response = [];

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {

    if (!isset($_SESSION['magasin'])) {
        echo json_encode(['error' => 'Magasin non défini en session.']);
        exit;
    }

    $shop_code = $_SESSION['magasin'];
    $search_term = trim($_GET['search']);

    // Préparer le terme de recherche pour LIKE
    $search_like = "%" . $search_term . "%";

    try {
        // Recherche par product_code OU product_name
        // Tri alphabétique par product_name
        // Afficher uniquement les produits en stock
        $select = $pdo->prepare("SELECT 
                                    product_id,
                                    product_code,
                                    product_name,
                                    stock,
                                    min_stock,
                                    sell_price,
                                    min_price,
                                    product_satuan,
                                    discount,
                                    CONCAT(product_code, ' - ', product_name) as display_text
                                FROM tbl_shop_item 
                                WHERE shop_code = :shop 
                                AND (
                                    product_code LIKE :search 
                                    OR product_name LIKE :search
                                )
                                AND stock > 0 
                                ORDER BY product_name ASC
                                LIMIT 50");

        $select->bindParam(':shop', $shop_code);
        $select->bindParam(':search', $search_like);
        $select->execute();

        $results = $select->fetchAll(PDO::FETCH_ASSOC);

        // Formater les résultats pour Select2
        foreach ($results as $row) {
            $response[] = [
                'id' => $row['product_id'],
                'text' => $row['display_text'],
                'product_id' => $row['product_id'],
                'product_code' => $row['product_code'],
                'product_name' => $row['product_name'],
                'stock' => $row['stock'],
                'min_stock' => $row['min_stock'],
                'sell_price' => $row['sell_price'],
                'min_price' => $row['min_price'],
                'product_satuan' => $row['product_satuan'],
                'discount' => $row['discount']
            ];
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur DB: ' . $e->getMessage()]);
        exit;
    }
}

echo json_encode($response);
