<?php
// search_product_manual.php
include_once 'db/connect_db.php';
header('Content-Type: application/json');

if (isset($_GET['q'])) {
    $search = "%" . $_GET['q'] . "%";
    $shop = $_SESSION['magasin'];

    try {
        // Recherche par nom OU par code
        $select = $pdo->prepare("SELECT * FROM tbl_shop_item 
                                 WHERE shop_code = :shop 
                                 AND (product_name LIKE :q OR product_code LIKE :q) 
                                 AND stock > 0 
                                 LIMIT 20");
        $select->bindParam(':shop', $shop);
        $select->bindParam(':q', $search);
        $select->execute();

        $results = $select->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
    } catch (PDOException $e) {
        echo json_encode([]);
    }
}
