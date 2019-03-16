<?php
require 'ConnectDB.php';

$charactercollection = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'a', 'b', 'c',
        'd', 'e', 'f'];

function generateKey()
{
    $api_key = '';
    for ($i = 0; $i < 32; $i++) {
        $index = rand(0, count($charactercollection) - 1);
        array_push($otp, $charactercollection[$index]);
        $api_key .= $charactercollection[$index];
    }

    return $api_key;
}

function generateSecret()
{
    $secret_key = '';
    for ($i = 0; $i < 8; $i++) {
        $index = rand(0, count($charactercollection) - 1);
        array_push($otp, $charactercollection[$index]);
        $secret_key .= $charactercollection[$index];
    }

    return $secret_key;
}

$contents = file_get_contents('php://input');
$response = array();
if ($contents != null) {
    $data = json_decode($contents);
    
} else {
    $response['Success'] = false;
}
?>