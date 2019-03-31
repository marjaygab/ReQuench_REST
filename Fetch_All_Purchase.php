<?php 
require 'ConnectDB.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
$response = array();
$query = "SELECT purchase_history.Acc_ID,purchase_history.Date,purchase_history.Time,purchase_history.Amount,purchase_history.Price_Computed
FROM purchase_history";

$query2 = "SELECT purchase_history_unrec.UU_ID,purchase_history_unrec.Date,purchase_history_unrec.Time,purchase_history_unrec.Amount,purchase_history_unrec.Price_Computed
FROM purchase_history_unrec";

$result = mysqli_query($conn,$query);
$result2 = mysqli_query($conn,$query2);

if (mysqli_num_rows($result) > 0) {
    $rows = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    $response['Purchase_List'] = $rows;
}else{
    $response['Purchase_List'] = '';
}

if (mysqli_num_rows($result2) > 0) {
    $rows = array();
    while ($r = mysqli_fetch_assoc($result2)) {
        $rows[] = $r;
    }
    $response['Purchase_List_Unrecorded'] = $rows;
}else{
    $response['Purchase_List_Unrecorded'] = '';
}

echo json_encode($response,JSON_PRETTY_PRINT);
mysqli_close($conn);

?>