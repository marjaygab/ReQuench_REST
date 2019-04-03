<?php
require 'ConnectDB.php';
require_once "libs/fpdf.php";
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

// Begin configuration
function getpdf($conn, $start_date, $end_date)
{

    //lagay mo nalang ang $start_date saka $end_date na variable sa mga queries mo.
    //format ng $start_date at $end_date ay YYYY-MM-DD baka lang kailangan mo

    $textColour = array(0, 0, 0);
    $headerColour = array(100, 100, 100);
    $tableHeaderTopTextColour = array(255, 255, 255);
    $tableHeaderTopFillColour = array(125, 152, 179);
    $tableHeaderTopProductTextColour = array(0, 0, 0);
    $tableHeaderTopProductFillColour = array(143, 173, 204);
    $tableHeaderLeftTextColour = array(99, 42, 57);
    $tableHeaderLeftFillColour = array(184, 207, 229);
    $tableBorderColour = array(50, 50, 50);
    $tableRowFillColour = array(213, 170, 170);
    $reportName = "ReQuench: Sales and Inventory Report";
    $reportNameYPos = 140;
    $logoFile = "user_images/BrandwLogo.png";
    $logoXPos = 30;
    $logoYPos = 108;
    $logoWidth = 150;
    $columnLabels = array("Q1", "Q2", "Q3", "Q4");
    $rowLabels = array("SupaWidget", "WonderWidget", "MegaWidget", "HyperWidget");
    $chartXPos = 20;
    $chartYPos = 250;
    $chartWidth = 160;
    $chartHeight = 80;
    $chartXLabel = "Product";
    $chartYLabel = "2009 Sales";
    $chartYStep = 20000;

    $chartColours = array(
        array(255, 100, 100),
        array(100, 255, 100),
        array(100, 100, 255),
        array(255, 255, 100),
    );

    $data = array(
        array(9940, 10100, 9490, 11730),
        array(19310, 21140, 20560, 22590),
        array(25110, 26260, 25210, 28370),
        array(27650, 24550, 30040, 31980),
    );

// End configuration

/**
Create the title page
 **/

    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->SetTextColor($textColour[0], $textColour[1], $textColour[2]);
    $pdf->AddPage();

// Logo
    $pdf->Image($logoFile, $logoXPos, $logoYPos, $logoWidth);

// Report Name
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->Ln($reportNameYPos);
    $pdf->Cell(0, 15, $reportName, 0, 0, 'C');

/**
Create the page header, main heading, and intro text
 **/
    $transaction = mysqli_query($conn,
    "SELECT * FROM transaction_history
    LEFT JOIN machine_unit ON transaction_history.MU_ID= machine_unit.MU_ID
    WHERE Date BETWEEN $start_date AND $end_date
    ORDER BY Transaction_ID ASC") or die("database error:" . mysqli_error($connString));
    $gettransaction_rows = mysqli_num_rows($transaction);


    $result3 = mysqli_query($conn,
        "SELECT SUM(Price_Computed) as hot FROM transaction_history
    LEFT JOIN machine_unit ON transaction_history.MU_ID= machine_unit.MU_ID
    WHERE Date BETWEEN $start_date AND $end_date AND Temperature= 'HOT'")
    or die("database error:" . mysqli_error($connString));
    $gethot = mysqli_num_rows($result3);
    $row1 = mysqli_fetch_array($result3);

    $result4 = mysqli_query($conn,
        "SELECT SUM(Price_Computed) as cold FROM transaction_history
    LEFT JOIN machine_unit ON transaction_history.MU_ID= machine_unit.MU_ID
    WHERE Date BETWEEN $start_date AND $end_date AND Temperature= 'COLD'")
    or die("database error:" . mysqli_error($connString));
    $getcold = mysqli_num_rows($result4);
    $row2 = mysqli_fetch_array($result4);

    $pdf->AddPage();
    $pdf->SetTextColor($headerColour[0], $headerColour[1], $headerColour[2]);
    $pdf->SetFont('Arial', '', 17);

    $pdf->Ln(16);

    $pdf->SetTextColor($textColour[0], $textColour[1], $textColour[2]);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Write(6, "There are a total of ");
    $pdf->Write(6, $gettransaction_rows);
    $pdf->Write(6, " transaction/s from ");
    $pdf->Write(6, $start_date);
    $pdf->Write(6, " to ");
    $pdf->Write(6, $end_date);
    // $pdf->Write(6, date('F'));
    $pdf->Ln(12);
    $pdf->Write(6, $row1['hot'] * 250);
    $pdf->Write(6, " mL of cold water dispensed");
    $pdf->Ln(12);
    $pdf->Write(6, $row2['cold'] * 250);
    $pdf->Write(6, " mL of hot water dispensed");

    
    $pdf->Ln(12);
    $pdf->Write(6, "List of Transactions");
    $pdf->Ln(12);

//header

    $count = 0;

//footer page

    $pdf->AliasNbPages();

    $pdf->SetFont('Arial', '', 7);

    $pdf->Cell(15, 6, 'Tran ID', 1, 0, 'C');

    $pdf->Cell(15, 6, 'Acc ID', 1, 0, 'C');

    $pdf->Cell(40, 6, 'Machine Location', 1, 0, 'C');

    $pdf->Cell(25, 6, 'Date', 1, 0, 'C');

    $pdf->Cell(40, 6, 'Temperature', 1, 0, 'C');

    $pdf->Cell(25, 6, 'Price Computed', 1, 0, 'C');

    $pdf->Ln();

    while ($row = mysqli_fetch_array($transaction)) {
        $count = $count + 1;
        $pdf->Cell(15, 6, $row['Transaction_ID'], 1, 0, 'C');
        $pdf->Cell(15, 6, $row['Acc_ID'], 1, 0, 'C');
        $pdf->Cell(40, 6, $row['Machine_Location'], 1, 0, 'C');
        $pdf->Cell(25, 6, $row['Date'], 1, 0, 'C');
        $pdf->Cell(40, 6, $row['Temperature'], 1, 0, 'C');
        $pdf->Cell(25, 6, $row['Price_Computed'], 1, 0, 'C');
        $pdf->Ln();
    }


    $pdf->Output();
}

if (isset($_GET['Start_Date']) && isset($_GET['End_Date'])) {
    $start_date = $_GET['Start_Date'];
    $end_date = $_GET['End_Date'];
    getpdf($conn,$start_date,$end_date);
}
