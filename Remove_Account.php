<?php
require 'ConnectDB.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
function removeAccount($conn,$Acc_ID,$Account_Type)
{
    if ($Account_Type == 'USER') {
        $acc_type = 'acc_users';
    } else if($Account_Type == 'ADMIN'){
        $acc_type = 'acc_admin';
    }else{
        $acc_type = 'acc_cashier';
    }
    
    $query = "DELETE FROM $acc_type WHERE Acc_ID = $Acc_ID";
    $query1 = "DELETE FROM accounts WHERE Acc_ID = $Acc_ID";

    if (mysqli_query($conn,$query) && mysqli_query($conn,$query1)) {
        return true;
    }else{
        return false;
    }

}

$contents = file_get_contents('php://input');
if ($contents != null) {
    $data = json_decode($contents);
    $Acc_ID = $data->{"Acc_ID"};
    $Account_Type = $data->{"Account_Type"};

    $response = array();
    if (removeAccount($conn,$Acc_ID,$Account_Type)) {
        $response['Success'] = true;
    }else{
        $response['Success'] = false;
    }
}else{
  die("An Error Occured");
}

echo json_encode($response);
mysqli_close($conn);

 ?>
