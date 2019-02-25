<?php
require 'ConnectDB.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

function updateAcc($conn,$Acc_ID,$account_details)
{
    if($account_details['Access_Level'] == 'USER'){
        $table = 'acc_users';
    }else if($account_details['Access_Level'] == 'CASHIER'){
        $table = 'acc_cashier';
    }else{
        $table = 'acc_admin';
    }
    
    $ID_Number = $account_details["ID_Number"];
    $First_Name = $account_details["First_Name"];
    $Last_Name = $account_details["Last_Name"];
    $Balance = $account_details["Balance"];
    $User_Name = $account_details["User_Name"];
    $Password = $account_details["Password"];
    $Email = $account_details["Email"];
    $Access_Level = $account_details["Access_Level"];

    if ($Access_Level == 'USER') {
        $al_id = 3;
    } else if ($Access_Level == 'CASHIER') {
        $al_id = 2;
    } else {
        $al_id = 1;
    }

    $query = "UPDATE accounts SET User_Name = '$User_Name', Password = '$Password',Email = '$Email',AL_ID = '$al_id' WHERE Acc_ID = '$Acc_ID'";
    if (mysqli_query($conn,$query)) {
        $query = "UPDATE $table SET ID_Number = '$ID_Number',First_Name = '$First_Name',Last_Name = '$Last_Name',Balance = '$Balance'";
        if (mysqli_query($conn,$query)) {
            return true;
        }else{
            return false;
        }
    }else {
        return false;
    }
}

$contents = file_get_contents('php://input');
$response = array();
$account_details = array();

if ($contents != null) {
    $data = json_decode($contents);
    $Acc_ID =$data->{"Acc_ID"};
    $account_details['ID_Number'] = $data->{"ID_Number"};
    $account_details['First_Name'] = $data->{"First_Name"};
    $account_details['Last_Name'] = $data->{"Last_Name"};
    $account_details['Balance'] = $data->{"Balance"};
    $account_details['User_Name'] = $data->{"User_Name"};
    $account_details['Password'] = $data->{"Password"};
    $account_details['Email'] = $data->{"Email"};
    $account_details['Access_Level'] = $data->{"Access_Level"};
    if (updateAcc($conn,$Acc_ID,$account_details)) {
        $response['Success'] = true;
    } else {
        $response['Success'] = false;
    }
    
} else {
    $response['Success'] = false;
}   

echo json_encode($response);
mysqli_close($conn);

?>