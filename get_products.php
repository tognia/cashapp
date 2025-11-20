<?php
include_once 'db/connect_db.php';

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    $s = $_SESSION['magasin'];

    $req = "SELECT * FROM tbl_shop_item WHERE shop_code = ? AND product_code LIKE ?";// product_name LIKE ? 
    $select = $pdo->prepare($req);
    $select->execute([$s, "%$query%"]);
    $result = $select->fetchAll();

    $req1 = "SELECT * FROM tbl_shop_item WHERE shop_code = ? AND product_name LIKE ? ";
    $select1 = $pdo->prepare($req1);
    $select1->execute([$s, "%$query%"]);
    $result1 = $select1->fetchAll();

    $output = '<ul class="list-unstyled">';
    foreach ($result1 as $row) {
        $output .= '<li data-product-id="' . $row['product_id'] . '">' . $row['product_name'] . '</li>';
    }
     foreach ($result as $row) {
        $output .= '<li data-product-id="' . $row['product_id'] . '">' . $row['product_code'] . '</li>';
    }
    $output .= '</ul>';

    echo $output;
}
?>