<?php
    include_once'db/connect_db.php';

    $id = $_GET["id"];
    $select = $pdo->prepare("SELECT * FROM tbl_shop_item WHERE (product_id = :ppid OR product_code = :ppid) AND shop_code = :ssid ");
    $select->bindParam(":ppid", $id);
    $select->bindParam(":ssid", $_SESSION['magasin']);
    $select->execute();
    $row = $select->fetch(PDO::FETCH_ASSOC);
    $response=$row;
    header('Content-Type: application/json');
    echo json_encode($response);
?>
