<?php
require('../fpdf/fpdf.php');
include_once'../db/connect_db.php';

$id = $_GET['id'];
$select = $pdo->prepare("SELECT * FROM tbl_commandes_magasin WHERE invoice_id=$id");
$select->execute();
$row = $select->fetch(PDO::FETCH_OBJ);

// $pdf = new FPDF('P','mm', array(80,200));
// $pdf->AddPage();

$pdf = new FPDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Select Arial bold 15
$pdf->SetFont('Arial', 'B', 15);
// Move to the right
$pdf->Cell(80);
// Title
$pdf->Cell(30, 10, 'COMMANDE DE '.$row->shop, 0, 0, 'C');
// Line break
$pdf->Ln(10);


$pdf->SetFont('Courier','B',10);
$pdf->Cell(80);
$pdf->Cell(30,10 ,'Num Commande :',0,0,'C');
$pdf->SetFont('Courier','BI',10);
$pdf->Cell(30,10 ,$row->invoice_id,0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Courier','B',10);
$pdf->Cell(80);
$pdf->Cell(30,5 ,'Initiee par : ',0,0,'C');
$pdf->SetFont('Courier','BI',10);
$pdf->Cell(30,5 ,"  ".$row->cashier_name,0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Courier','B',10);
$pdf->Cell(80);
$pdf->Cell(30,5 ,'Date et heure:',0,0,'C');
$pdf->SetFont('Courier','BI',10);
$pdf->Cell(30,5 ,$row->order_date,0,0,'C');
$pdf->SetFont('Courier','BI',10);
$pdf->Cell(30,5 ,$row->time_order,0,1,'C');
$pdf->Ln(5);

//////////////////////////////////////////////
// $pdf->SetXY(2,40);
// $pdf->SetX(10);
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(224, 235, 255);
$pdf->Cell(50);
$pdf->Cell(40,5 ,'Libelle Produit',1,0,'C');
$pdf->Cell(20,5 ,'Qty',1,0,'C');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,5 ,'PU',1,0,'C');
$pdf->Cell(30,5 ,'Total',1,0,'C');
$pdf->Ln(5);

$totalHtNoRem = 0;

$select1 = $pdo->prepare("SELECT * FROM tbl_commandes_magasin_details WHERE invoice_id=$id");
$select1->execute();
$pdf->SetFont('Arial','B',10);
// $pdf->SetXY(2,50);
$fill = false;
while($item = $select1->fetch(PDO::FETCH_OBJ)){
    // $pdf->SetX(6);    
    $pdf->Cell(50);
    $pdf->Cell(40,10,$item->product_name,1,0,'C');
    $pdf->Cell(20,10,$item->qty,1,0,'C');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20,10,number_format($item->price).' F',1,0,'C');
    $pdf->Cell(30,10,number_format($item->total).' F',1,0,'C');
    $totalHtNoRem = $totalHtNoRem + $item->total;
    $pdf->Ln();
    $fill = !$fill;
}
$pdf->Ln(5);
$pdf->SetFont('Arial','Bi',10);
$pdf->Cell(80);
$pdf->Cell(25,4 ,'VALEUR COMMANDE :',0,0,'C');
$pdf->SetFont('Arial','BI',10);
$pdf->Cell(40,4 ,number_format($totalHtNoRem).' FCFA',0,1,'C');


$pdf->Output();

//////////////////////////////////////////////////////////// COPIE II ////////////////////////////////////////////


