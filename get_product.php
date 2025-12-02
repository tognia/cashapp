<?php
// get_product.php
include_once 'db/connect_db.php';

header('Content-Type: application/json; charset=utf-8');

$response = ['error' => 'Produit non trouvé.'];

if (isset($_GET['id'])) {
    if (!isset($_SESSION['magasin'])) {
        $response = ['error' => 'Magasin non défini en session.'];
    } else {
        $shop_code = $_SESSION['magasin'];
        $product_id = $_GET['id'];

        try {
            // Sélection de tous les détails du produit
            $select = $pdo->prepare("SELECT * FROM tbl_shop_item WHERE shop_code = :shop AND product_id = :id");
            $select->bindParam(':shop', $shop_code);
            $select->bindParam(':id', $product_id);
            $select->execute();

            $row = $select->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                // Retourner le tableau associatif du produit
                $response = $row;
            }
        } catch (PDOException $e) {
            $response = ['error' => 'Erreur DB: ' . $e->getMessage()];
        }
    }
}

// Le résultat est encodé en JSON
echo json_encode($response);
