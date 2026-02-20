<?php
include_once 'db/connect_db.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM tbl_invoice_detail WHERE invoice_id = :id");
$stmt->execute([':id' => $id]);
$details = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo '<table class="table table-bordered">
        <thead><tr class="active"><th>Produit</th><th>Qté</th><th>Prix</th><th>Total</th></tr></thead>
        <tbody>';
foreach ($details as $row) {
    echo '<tr>
            <td>' . $row['product_name'] . '</td>
            <td>' . $row['qty'] . '</td>
            <td>' . number_format($row['price']) . '</td>
            <td>' . number_format($row['total']) . '</td>
          </tr>';
}
echo '</tbody></table>';
