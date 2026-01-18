<?php
require('./fpdf/fpdf.php');
// Inclure la connexion à la base de données
include_once 'db/connect_db.php';
session_start();

$shop = $_GET['shop'];
$start = $_GET['start'];
$end = $_GET['end'];

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, "Rapport d'Expedition : $shop", 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 10, "Periode: $start au $end", 0, 1, 'C');
$pdf->Ln(5);

$html = '<table border="1" cellpadding="5">
            <thead>
                <tr bgcolor="#f2f2f2">
                    <th><b>Code</b></th>
                    <th><b>Produit</b></th>
                    <th><b>SKU</b></th>
                    <th><b>Total</b></th>
                </tr>
            </thead>
            <tbody>';

$stmt = $pdo->prepare("SELECT product_code, product_name, product_sku, SUM(shipped_quantity) as total_qty 
                       FROM tbl_product_shipment WHERE code_agence=? AND shipment_date BETWEEN ? AND ? GROUP BY product_code");
$stmt->execute([$shop, $start, $end]);

while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
    $html .= "<tr>
                <td>{$row->product_code}</td>
                <td>{$row->product_name}</td>
                <td>{$row->product_sku}</td>
                <td>{$row->total_qty}</td>
              </tr>";
}

$html .= '</tbody></table>';
$pdf->Cell(0, 10, $html, 0, 1);
$pdf->Output("Rapport_$shop.pdf", 'D');
