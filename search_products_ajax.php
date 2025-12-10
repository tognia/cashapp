<?php
// search_products_ajax.php
include_once 'db/connect_db.php';

header('Content-Type: application/json; charset=utf-8');

// Vérification de la session magasin
if (!isset($_SESSION['magasin'])) {
    echo json_encode(['products' => [], 'total_count' => 0]);
    exit();
}

$shop_code = $_SESSION['magasin'];
$search_term = isset($_GET['q']) ? '%' . $_GET['q'] . '%' : ''; // Le terme de recherche
$limit = 30; // Nombre maximum de résultats à retourner

try {
    // 1. Compter le total des produits correspondant au terme de recherche
    $count_query = $pdo->prepare("SELECT COUNT(*) 
                                  FROM tbl_shop_item 
                                  WHERE shop_code = :shop 
                                  AND stock > 0
                                  AND (product_name LIKE :term OR product_code LIKE :term)");
    $count_query->bindParam(':shop', $shop_code);
    $count_query->bindParam(':term', $search_term);
    $count_query->execute();
    $total_count = $count_query->fetchColumn();

    // 2. Récupérer les données des produits
    $select_query = $pdo->prepare("SELECT product_code, product_name 
                                    FROM tbl_shop_item 
                                    WHERE shop_code = :shop 
                                    AND stock > 0
                                    AND (product_name LIKE :term OR product_code LIKE :term)
                                    ORDER BY product_name ASC
                                    LIMIT :limit");
    $select_query->bindParam(':shop', $shop_code);
    $select_query->bindParam(':term', $search_term);
    $select_query->bindParam(':limit', $limit, PDO::PARAM_INT); // Limite le nombre de résultats
    $select_query->execute();

    $products_data = [];
    while ($row = $select_query->fetch(PDO::FETCH_ASSOC)) {
        $products_data[] = [
            'id' => $row['product_code'], // Select2 utilise 'id' comme valeur/code
            'text' => $row['product_code'] . ' - ' . $row['product_name'] // Le texte affiché dans la liste
        ];
    }

    // Retourner les résultats au format attendu par Select2
    echo json_encode([
        'products' => $products_data,
        'total_count' => $total_count
    ]);
} catch (PDOException $e) {
    // Gérer l'erreur de base de données
    echo json_encode(['products' => [], 'total_count' => 0, 'error' => 'DB Error: ' . $e->getMessage()]);
}

// Nettoyage : On peut supprimer get_all_products.php car il n'est plus utilisé.
