<?php
//require connectdb
require 'ConnectDB.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

//functions here
function getUserTransaction($conn,$Acc_ID)
{
    $query = "SELECT * from transaction_history WHERE Acc_ID = '$Acc_ID'";
    $result = mysqli_query($conn,$query);

    if (mysqli_num_rows($result) != 0) {
        while ($r = mysqli_fetch_assoc($result)) {
            $row[] = $r;
        }
    }
    return $row;
}


$contents = file_get_contents('php://input');
$response = array();
if ($contents != null) {
  $data = json_decode($contents);
//   $command = $data->{"Variable ID"};
    $Acc_ID = $data->{"Acc_ID"};
    $row = getUserTransaction($conn,$Acc_ID);
    $response["User_Transaction"] = $row;
    $response['client']= 'loren';
    $response['Success'] = true;
    echo json_encode($response,JSON_PRETTY_PRINT);

}else{
  $response['Update_Success'] = false;
  echo json_encode($response,JSON_PRETTY_PRINT);
  die("An Error Occured");
}


  mysqli_close($conn);









?>
