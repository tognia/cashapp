<?php
require('../fpdf/fpdf.php');
include_once'../db/connect_db.php';
session_start();

$id = $_GET['id'];

if ($id==null) {
        
        $id = $_SESSION["delivered_invoice"];
    }

$select = $pdo->prepare("SELECT * FROM tbl_invoice_client WHERE invoice_id=$id");
$select->execute();
$row = $select->fetch(PDO::FETCH_OBJ);

$date = date('Y-m-d H:i:s');

$pdf = new FPDF('P','mm', array(80,200));

$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(60,10,'BEVILEC',0,1,'C');

$pdf->Line(10,18,72,18);
$pdf->Line(10,19,72,19);

$pdf->SetFont('Arial','',8);
$pdf->Cell(60,3,'Yaounde - CMR  ',0,1,'C');

$pdf->SetFont('Arial','',8);
$pdf->Cell(63,3,'Vente de materiel electrique et interieur maison',0,1,'C');

$pdf->SetFont('Arial','',8);
$pdf->Cell(63,4,'Tel. 00237 699 45 67 00 (BVLC)',0,1,'C');

$pdf->Line(10,30,72,30);
$pdf->Line(10,31,72,31);

$pdf->SetY(31);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(60,6 ,' COMMANDE LIVREE',0,1,'C');

$pdf->SetFont('Courier','B',8);
$pdf->Cell(20,4 ,'N° :',0,0,'C');

$pdf->SetFont('Courier','BI',8);
$pdf->Cell(10,4 ,$row->invoice_id,0,1,'C');

$pdf->SetFont('Courier','B',8);
$pdf->Cell(40,4 ,'Nom Client: ',0,0,'C');

$pdf->SetFont('Courier','BI',8);
$pdf->Cell(10,4 ,"  ".$row->name_client,0,1,'C');

$pdf->SetFont('Courier','B',8);
$pdf->Cell(20,4 ,'Date et heure:',0,0,'C');

$pdf->SetFont('Courier','BI',8);
$pdf->Cell(21,4 ,$row->order_date,0,0,'C');

$pdf->SetFont('Courier','BI',8);
$pdf->Cell(10,4 ,$row->time_order,0,1,'C');
//////////////////////////////////////////////
$pdf->SetY(55);

$pdf->SetX(6);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(27,8 ,'Libelle Produit',1,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(7,8 ,'Qty',1,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(18,8 ,'PU',1,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(18,8 ,'Total',1,1,'C');

$select = $pdo->prepare("SELECT * FROM tbl_invoice_detail_client WHERE invoice_id=$id");
$select->execute();
while($item = $select->fetch(PDO::FETCH_OBJ)){
    $pdf->SetX(6);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(27,5,$item->product_name,1,0,'L');
    $pdf->Cell(7,5,$item->qty,1,0,'C');
    $pdf->SetFont('Arial','B',7);
    $pdf->Cell(18,5,number_format($item->price,0,null," ").' FCFA',1,0,'R');
    $pdf->Cell(18,5,number_format($item->total,0,null," ").' FCFA',1,1,'R');
}

//////////////////////////////////////////////
$pdf->SetX(43);
$pdf->SetFont('Arial','Bi',8);
$pdf->Cell(25,8 ,'Total  :',0,0,'C');

$pdf->SetFont('Arial','BI',7);
$pdf->Cell(1,8 ,number_format($row->total).' FCFA',0,1,'C');

$pdf->SetX(43);
$pdf->SetFont('Arial','BI',7);
$pdf->Cell(25,4 ,'STATUS :',0,0,'C');

$pdf->SetFont('Arial','BI',7);
$pdf->Cell(1,4 , $row->Status,0,1,'C');

/*$pdf->SetX(43);
$pdf->SetFont('Arial','BI',8);
$pdf->Cell(25,8 ,'Due    :',0,0,'C');

$pdf->SetFont('Arial','BI',7);
$pdf->Cell(1,8 ,number_format($row->due).' FCFA',0,1,'C');*/

//////////////////////////////////////////////
$pdf->SetY(95);
$pdf->SetX(10);
$pdf->SetFont('Arial','BU',5);
$pdf->Cell(75,4 ,'Merci pour votre confiance',0,1,'L');

$pdf->SetFont('Arial','BU',5);
$pdf->Cell(45,4 ,'Commande annulable dans un delai de 3 jours maximum',0,0,'C');


//$num = $date."_".$id;
$pdf->Output('../commandes/commande_Livree_'.$_SESSION["id_client"].'_'.$id.'.pdf','F');
//$pdf->Output('commande_'.$id.'pdf','D');




/////////////////////////////////////    PRINT FILE ///////////////////////////


//$pdf->Output('../commandes/commande_'.$date."_".$id.'.pdf','F');

