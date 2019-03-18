<?php

require 'ConnectDB.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
function generateOTP($conn,$Acc_ID){
    $charactercollection = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'a', 'b', 'c',
        'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y',
        'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U',
        'V', 'W', 'X', 'Y', 'Z'];
    $otp_string = "";
    $otp = array();
    for ($i = 0; $i < 6; $i++) {
        $index = rand(0, count($charactercollection) - 1);
        array_push($otp, $charactercollection[$index]);
        $otp_string .= $charactercollection[$index];
    }
    $Acc_ID = $_POST['Acc_ID'];
    $query = "SELECT OTP FROM accounts WHERE OTP = '$otp_string'";
    $result = mysqli_query($conn, $query);
    $response = array("Updated" => false);
    if (mysqli_num_rows($result) > 0) {
        $response['Updated'] = false;
    } else {
        $query = "UPDATE accounts SET OTP = '$otp_string' WHERE Acc_ID='$Acc_ID'";
        if (mysqli_query($conn, $query)) {
            $response['Updated'] = true;
        } else {
            $response['Updated'] = false;
        }
    }
    $response['OTP'] = $otp;
    $response['OTP_String'] = $otp_string;
    return $response;   
}

$contents = file_get_contents('php://input');
$response = array();
if ($contents != null) {
    $data = json_decode($contents);
    $Acc_ID = $data->{"Acc_ID"};
    $response = generateOTP($conn,$Acc_ID);
    if ($response['Updated']) {
        $response['Success'] = true;
    } else {
        $response['Success'] = false;
    }
    
} else {
    $response['Success'] = false;
    echo json_encode($response);
}
echo json_encode($response, JSON_PRETTY_PRINT);
mysqli_close($conn);
