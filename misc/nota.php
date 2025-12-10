<?php
require('../fpdf/fpdf.php');
include_once '../db/connect_db.php';

$id = $_GET['id'];
$select = $pdo->prepare("SELECT * FROM tbl_invoice WHERE invoice_id=$id");
$select->execute();
$row = $select->fetch(PDO::FETCH_OBJ);

$pdf = new FPDF('P', 'mm', array(80, 200));


$s = $row->id_client;

$sel = $pdo->prepare("select * FROM users WHERE username='$s'");
$sel->execute();
$row1 = $sel->fetch(PDO::FETCH_ASSOC);

$nomclient = $row->id_client . "_" . $row1['firstname'] . " " . $row1['middlename'] . " " . $row1['lastname'];



$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 5);
$pdf->Image('../images/logo_nk.png', 30, 5, 20, 10);
$pdf->Cell(60, 10, ' ', 0, 1, 'C');
$pdf->Line(10, 18, 72, 18);

$pdf->SetFont('Arial', '', 3);
$pdf->Cell(60, 3, 'Yaounde - CMR  ', 0, 1, 'C');

$pdf->SetFont('Arial', '', 3);
$pdf->Cell(63, 2, 'Epicerie', 0, 1, 'C');

$pdf->SetFont('Arial', '', 3);
$pdf->Cell(63, 2, 'Tel. 00237 621 10 05 00 (SCTE NGNO-KWE SERVICES SARL)', 0, 1, 'C');

$pdf->Line(10, 30, 72, 30);

$pdf->SetY(31);
$pdf->SetFont('Arial', 'B', 4);
$pdf->Cell(60, 6, 'Ticket de Caisse', 0, 1, 'C');

$pdf->SetFont('Courier', 'B', 6);
$pdf->Cell(20, 4, 'Num :', 0, 0, 'C');

$pdf->SetFont('Courier', 'BI', 6);
$pdf->Cell(10, 4, $row->invoice_id, 0, 1, 'C');

$pdf->SetFont('Courier', 'B', 6);
$pdf->Cell(40, 4, 'Nom Operateur caisse : ', 0, 0, 'C');

$pdf->SetFont('Courier', 'BI', 6);
$pdf->Cell(10, 4, "  " . $row->cashier_name, 0, 1, 'C');

$pdf->SetFont('Courier', 'B', 6);
$pdf->Cell(20, 4, 'Date et heure:', 0, 0, 'C');

$pdf->SetFont('Courier', 'BI', 6);
$pdf->Cell(21, 4, $row->order_date, 0, 0, 'C');

$pdf->SetFont('Courier', 'BI', 6);
$pdf->Cell(10, 4, $row->time_order, 0, 1, 'C');

$pdf->SetFont('Courier', 'B', 6);
$pdf->Cell(40, 4, 'Nom Client: ', 0, 0, 'C');

$pdf->SetFont('Courier', 'BI', 6);
$pdf->Cell(10, 4, "  " . $nomclient, 0, 1, 'C');


//////////////////////////////////////////////
$pdf->SetY(55);
$pdf->SetX(6);
$pdf->SetFont('Arial', 'B', 4);

// Table headers
$pdf->Cell(18, 5, 'Libelle Produit', 1, 0, 'C');
$pdf->Cell(5, 5, 'Qty', 1, 0, 'C');
$pdf->Cell(10, 5, 'PU', 1, 0, 'C');
$pdf->Cell(15, 5, 'Total', 1, 0, 'C');
$pdf->Cell(9, 5, 'Rem', 1, 0, 'C');
$pdf->Cell(13, 5, 'Total Paye', 1, 1, 'C');

$totalHtNoRem = 0;

// Fetching invoice details
$select = $pdo->prepare("SELECT * FROM tbl_invoice_detail WHERE invoice_id=$id");
$select->execute();

// Loop through the items and display them
while ($item = $select->fetch(PDO::FETCH_OBJ)) {
    $pdf->SetX(6);
    $pdf->SetFont('Arial', '', 4);

    // Use MultiCell for text that may exceed the cell width (like product names)
    $x = $pdf->GetX(); // Save the current X position for the row
    $y = $pdf->GetY(); // Save the current Y position for the row

    // Limit product name to 15 characters
    $product_name = $item->product_name;
    if (strlen($product_name) > 15) {
        $product_name = substr($product_name, 0, 15) . '...';
    }

    // Display the truncated product name
    $pdf->MultiCell(18, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $product_name), 1, 'L');


    // Reset position after MultiCell
    $pdf->SetXY($x + 18, $y);

    // Qty, PU, Total, Rem, Total Paye (These fields likely fit in single-line cells)
    $pdf->Cell(5, 5, $item->qty, 1, 0, 'C');
    $pdf->Cell(10, 5, number_format($item->price) . ' F', 1, 0, 'R');
    $pdf->Cell(15, 5, number_format($item->total + $item->remise) . ' F', 1, 0, 'R');
    $pdf->Cell(9, 5, number_format($item->remise) . ' F', 1, 0, 'R');
    $pdf->Cell(13, 5, number_format($item->total) . ' F', 1, 1, 'R');

    // Update the total
    $totalHtNoRem += $item->total + $item->remise;
}



// $pdf->SetX(43);
// $pdf->SetFont('Arial','Bi',5);
// $pdf->Cell(25,4 ,'Total Rem Incl. :',0,0,'C');
// $pdf->SetFont('Arial','BI',5);
// $pdf->Cell(1,4 ,number_format($row->total+$row->remise).' FCFA',0,1,'C');

$pdf->SetX(43);
$pdf->SetFont('Arial', 'Bi', 4);
$pdf->Cell(25, 4, 'Total A Paye :', 0, 0, 'C');
$pdf->SetFont('Arial', 'BI', 4);
$pdf->Cell(1, 4, number_format($row->total) . ' FCFA', 0, 1, 'C');

$pdf->SetX(43);
$pdf->SetFont('Arial', 'BI', 3);
$pdf->Cell(25, 4, 'PAYE PAR LE CLIENT :', 0, 0, 'C');
$pdf->SetFont('Arial', 'BI', 3);
$pdf->Cell(1, 4, number_format($row->paid) . ' FCFA', 0, 1, 'C');

$pdf->SetX(43);
$pdf->SetFont('Arial', 'BI', 3);
$pdf->Cell(25, 4, 'Rembourse    :', 0, 0, 'C');
$pdf->SetFont('Arial', 'BI', 3);
$pdf->Cell(1, 4, number_format($row->due) . ' FCFA', 0, 1, 'C');

$pdf->SetX(43);
$pdf->SetFont('Arial', 'BI', 3);
$pdf->Cell(25, 4, 'Mode Paiement    :', 0, 0, 'C');
$pdf->SetFont('Arial', 'BI', 3);
$pdf->Cell(1, 4, $row->payment_mode, 0, 1, 'C');

//////////////////////////////////////////////
$pdf->SetY(95);
$pdf->SetX(10);
$pdf->SetFont('Arial', 'BU', 3);
$pdf->Cell(75, 4, 'Les retours ne sont pas acceptés sans une note d achat', 0, 1, 'L');

$pdf->Output();

//////////////////////////////////////////////////////////// COPIE II ////////////////////////////////////////////
