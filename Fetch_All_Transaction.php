<?php 
require 'ConnectDB.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
$account_id = $_POST["Acc_ID"];
$response = array();
$query = "SELECT transaction_history.Acc_ID,machine_unit.Machine_Location,transaction_history.Date,transaction_history.Time,transaction_history.Amount,transaction_history.Temperature
,transaction_history.Price_Computed
FROM transaction_history
INNER JOIN machine_unit ON transaction_history.MU_ID = machine_unit.MU_ID";

$query2 = "SELECT transaction_history_unrec.UU_ID,machine_unit.Machine_Location,transaction_history_unrec.Date,transaction_history_unrec.Time,transaction_history_unrec.Amount,transaction_history_unrec.Temperature
,transaction_history_unrec.Price_Computed
FROM transaction_history_unrec
INNER JOIN machine_unit ON transaction_history_unrec.MU_ID = machine_unit.MU_ID";

$result = mysqli_query($conn,$query);
$result2 = mysqli_query($conn,$query2);

if (mysqli_num_rows($result) > 0) {
    $rows = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    $response['Transaction_List'] = $rows;
}else{
    $response['Transaction_List'] = '';
}

if (mysqli_num_rows($result2) > 0) {
    $rows = array();
    while ($r = mysqli_fetch_assoc($result2)) {
        $rows[] = $r;
    }
    $response['Transaction_List_Unrecorded'] = $rows;
}else{
    $response['Transaction_List_Unrecorded'] = '';
}

echo json_encode($response,JSON_PRETTY_PRINT);
mysqli_close($conn);

?>