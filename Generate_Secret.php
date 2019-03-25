<?php
require "ConnectDB.php";

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

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

function insertSecret($conn,$secret_key,$MU_ID)
{
    $query = "INSERT INTO secret_list (MU_ID,Secret_Key) VALUES ('$MU_ID','$secret_key')";
    if (mysqli_query($conn,$query)) {
        return true;
    } else {
        return false;
    }
}

function getDuplicate($conn,$secret)
{
    $query = "SELECT Secret from secret_list WHERE Secret_Key= $secret";
    $result = mysqli_query($conn,$query);
    if (mysqli_num_rows($result) > 0) {
        return false;
    }else{
        return true;
    }

}

$contents = file_get_contents('php://input');   
$response = array();
if ($contents != null) {
    $data = json_decode($contents);
    $MU_ID = $data->{"MU_ID"};

    while(true){
        $secret_key = generateSecret();
        if (getDuplicate($conn,$secret_key)) {
            break;
        }
    }
    if (insertSecret($conn,$secret_key,$MU_ID)) {
        $response['Secret'] = $secret_key;
        $response['Success'] = true;    
    }else{
        $response['Secret'] = '';
        $response['Success'] = false;    
    }
    echo json_encode($response);
} else {
    $response['Success'] = false;
    echo json_encode($response);
}

?>