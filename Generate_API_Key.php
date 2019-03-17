<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
function generateKey()
{
    $charactercollection = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'a', 'b', 'c',
        'd', 'e', 'f'];
    $api_key = '';
    $temp_array = array();
    for ($i = 0; $i < 32; $i++) {
        $index = rand(0, count($charactercollection) - 1);
        // array_push($api_key, $charactercollection[$index]);
        $api_key .= $charactercollection[$index];
    }

    return $api_key;
}

function generateSecret()
{
    $charactercollection = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'a', 'b', 'c',
        'd', 'e', 'f'];
    $secret_key = '';
    $temp_array = array();
    for ($i = 0; $i < 8; $i++) {
        $index = rand(0, count($charactercollection) - 1);
        // array_push($secret_key, $charactercollection[$index]);
        $secret_key .= $charactercollection[$index];
    }

    return $secret_key;
}

$contents = file_get_contents('php://input');
$response = array();
if ($contents != null) {
    $data = json_decode($contents);
    if ($data->{'Command'} == 'Secret') {
        $response['Secret'] = generateSecret();
        $response['Success'] = true;
        echo json_encode($response);
    }else if($data->{'Command'} == 'API'){
        $response['API'] = generateKey();
        $response['Success'] = true;
        echo json_encode($response);
    }
} else {
    $response['Success'] = false;
    echo json_encode($response);
}

?>