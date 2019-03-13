<?php
require 'ConnectDB.php';

$charactercollection = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'a', 'b', 'c',
        'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y',
        'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U',
        'V', 'W', 'X', 'Y', 'Z'];

function generateKey()
{
    $api_key = '';
    for ($i = 0; $i < 10; $i++) {
        $index = rand(0, count($charactercollection) - 1);
        array_push($otp, $charactercollection[$index]);
        $api_key .= $charactercollection[$index];
    }
}


$contents = file_get_contents('php://input');
$response = array();
if ($contents != null) {
    $data = json_decode($contents);
    
} else {
    $response['Success'] = false;
}
?>