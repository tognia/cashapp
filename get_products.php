<?php
// get_products.php
include_once 'db/connect_db.php';

header('Content-Type: text/html; charset=utf-8');

if (isset($_POST['query'])) {
    if (!isset($_SESSION['magasin'])) {
        die('Magasin non défini.');
    }

    $shop_code = $_SESSION['magasin'];
    $query = "%" . $_POST['query'] . "%";

    try {
        // Recherche par product_code OU product_name
        $select = $pdo->prepare("SELECT product_id, product_name, product_code FROM tbl_shop_item WHERE shop_code = :shop AND (product_code LIKE :query OR product_name LIKE :query) AND stock > 0 LIMIT 10");
        $select->bindParam(':shop', $shop_code);
        $select->bindParam(':query', $query);
        $select->execute();

        $output = '<ul class="list-unstyled" style="padding: 5px; cursor: pointer;">';

        if ($select->rowCount() > 0) {
            while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                // Le libellé complet est affiché, l'ID est stocké dans data-product-id
                $output .= '<li data-product-id="' . $row['product_id'] . '" style="padding: 5px; border-bottom: 1px solid #eee;">' . $row['product_code'] . ' - ' . $row['product_name'] . '</li>';
            }
        } else {
            $output .= '<li style="padding: 5px; color: #999;">Aucun produit trouvé.</li>';
        }

        $output .= '</ul>';

        echo $output;
    } catch (PDOException $e) {
        // Gérer l'erreur de base de données
        echo '<li style="padding: 5px; color: red;">Erreur DB: ' . $e->getMessage() . '</li>';
    }
}
