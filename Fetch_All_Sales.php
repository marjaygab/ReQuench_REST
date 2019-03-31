<?php 
require 'ConnectDB.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
$response = array();
$trans_query = "SELECT transaction_history.Acc_ID,machine_unit.Machine_Location,transaction_history.Date,transaction_history.Time,transaction_history.Amount,transaction_history.Temperature
,transaction_history.Price_Computed
FROM transaction_history
INNER JOIN machine_unit ON transaction_history.MU_ID = machine_unit.MU_ID";

$trans_query2 = "SELECT transaction_history_unrec.UU_ID,machine_unit.Machine_Location,transaction_history_unrec.Date,transaction_history_unrec.Time,transaction_history_unrec.Amount,transaction_history_unrec.Temperature
,transaction_history_unrec.Price_Computed
FROM transaction_history_unrec
INNER JOIN machine_unit ON transaction_history_unrec.MU_ID = machine_unit.MU_ID";

$purch_query = "SELECT purchase_history.Acc_ID,purchase_history.Date,purchase_history.Time,purchase_history.Amount,purchase_history.Price_Computed
FROM purchase_history";

$purch_query2 = "SELECT purchase_history_unrec.UU_ID,purchase_history_unrec.Date,purchase_history_unrec.Time,purchase_history_unrec.Amount,purchase_history_unrec.Price_Computed
FROM purchase_history_unrec";

$trans_result = mysqli_query($conn,$trans_query);
$trans_result2 = mysqli_query($conn,$trans_query2);
$purch_result = mysqli_query($conn,$purch_query);
$purch_result2 = mysqli_query($conn,$purch_query2);

if (mysqli_num_rows($trans_result) > 0) {
    $rows = array();
    while ($r = mysqli_fetch_assoc($trans_result)) {
        $response['Transaction_History'][] = $r;
    }
}else{
    $trans_rows2 = '';    
}

if (mysqli_num_rows($trans_result2) > 0) {
    $rows = array();
    while ($r = mysqli_fetch_assoc($trans_result2)) {
        $response['Transaction_History'][] = $r;
    }
}else{
    $trans_rows2 = '';
}


if (mysqli_num_rows($purch_result) > 0) {
    $rows = array();
    while ($r = mysqli_fetch_assoc($purch_result)) {
        $response['Purchase_History'][] = $r;
    }
}else{
    $purch_rows2 = '';    
}

if (mysqli_num_rows($purch_result2) > 0) {
    $rows = array();
    while ($r = mysqli_fetch_assoc($purch_result2)) {
        $response['Purchase_History'][] = $r;
    }
}else{
    $purch_rows2 = '';
}

echo json_encode($response,JSON_PRETTY_PRINT);
mysqli_close($conn);

?>