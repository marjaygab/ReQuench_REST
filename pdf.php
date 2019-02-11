<?php
include_once('connection_1.php');
include_once('libs\fpdf.php');

class PDF extends FPDF
{
  // Page header
  function Header()
  {
    // Logo
    $this->SetFont('Arial','B',7);
    $this->Write(0, 'Batangas II Electric Cooperative, Inc.');
    $this->Ln(5);
    $this->Write(0, 'Antipolo del Norte, Lipa City');
    $this->Ln(10);
    $this->SetFont('Arial','B',12);
    $this->Write(0,'WORKSTATION RECORD');
    $this->Ln(10);
  }
  // Page footer
  function Footer()
  {
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
  }
}

$db = new dbObj();
$connString =  $db->getConnstring();

$result = mysqli_query($connString, "SELECT * FROM `transaction_history` order by `Transaction_ID`") or die("database error:". mysqli_error($connString));

$pdf = new PDF();
//header
$count=0;
$pdf->addPage('L');
//foter page
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 7);

$pdf->Cell(5,  6,'Transaction_ID'             ,1,0 ,'C');
$pdf->Cell(30, 6,'Acc_ID'     ,1,0 ,'C');
$pdf->Cell(25, 6,'MU_ID'           ,1,0 ,'C');
$pdf->Cell(25, 6,'Date'   ,1,0 ,'C');
$pdf->Cell(35, 6,'Time'   ,1,0 ,'C');
$pdf->Cell(25, 6,'Amount_Dispensed'  ,1,0 ,'C');
$pdf->Cell(50, 6,'Temperature'      ,1,0 ,'C');
$pdf->Cell(25, 6,'Price_Computed',1,0 ,'C');
$pdf->Cell(25, 6,'Water_Level_Before'      ,1,0 ,'C');
$pdf->Cell(30, 6,'Water_Level_After'        ,1,0 ,'C');
$pdf->Ln();
while($row = mysqli_fetch_array( $result )) {
$count=$count   +1;
$pdf->Cell(5,  6,$row['Transaction_ID']                 ,1,0 ,'C');
$pdf->Cell(30, 6,$row['Acc_ID']     ,1,0 ,'C');
$pdf->Cell(25, 6,$row['MU_ID']           ,1,0 ,'C');
$pdf->Cell(25, 6,$row['Date']   ,1,0 ,'C');
$pdf->Cell(35, 6,$row['Time']   ,1,0 ,'C');
$pdf->Cell(25, 6,$row['Amount_Dispensed']  ,1,0 ,'C');
$pdf->Cell(50, 6,$row['Temperature']      ,1,0 ,'C');
$pdf->Cell(25, 6,$row['Price_Computed'],1,0 ,'C');
$pdf->Cell(25, 6,$row['Water_Level_Before']      ,1,0 ,'C');
$pdf->Cell(30, 6,$row['Water_Level_After']  ,1,0 ,'C');
$pdf->Ln();
}

$pdf->Output();
?>
