<?php
// get_product_by_code.php - Nouveau fichier pour recherche rapide par code
include_once 'db/connect_db.php';

header('Content-Type: application/json; charset=utf-8');

$response = ['error' => 'Produit non trouvé.'];

if (isset($_POST['code'])) {
    if (!isset($_SESSION['magasin'])) {
        $response = ['error' => 'Magasin non défini en session.'];
    } else {
        $shop_code = $_SESSION['magasin'];
        $product_code = trim($_POST['code']);

        try {
            // Recherche EXACTE par product_code (pour scanner code-barre)
            $select = $pdo->prepare("SELECT * FROM tbl_shop_item 
                                    WHERE shop_code = :shop 
                                    AND product_code = :code 
                                    AND stock > 0 
                                    LIMIT 1");
            $select->bindParam(':shop', $shop_code);
            $select->bindParam(':code', $product_code);
            $select->execute();

            $row = $select->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $response = $row;
            } else {
                // Fallback: Recherche LIKE si code exact non trouvé
                $product_code_like = "%" . $product_code . "%";
                $select = $pdo->prepare("SELECT * FROM tbl_shop_item 
                                        WHERE shop_code = :shop 
                                        AND product_code LIKE :code 
                                        AND stock > 0 
                                        LIMIT 1");
                $select->bindParam(':shop', $shop_code);
                $select->bindParam(':code', $product_code_like);
                $select->execute();

                $row = $select->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    $response = $row;
                }
            }
        } catch (PDOException $e) {
            $response = ['error' => 'Erreur DB: ' . $e->getMessage()];
        }
    }
}

echo json_encode($response);
