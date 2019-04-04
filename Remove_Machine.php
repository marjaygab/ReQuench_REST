<?php
require 'ConnectDB.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
function removeMachine($conn,$MU_ID)
{
    $query1 = "DELETE FROM machine_unit WHERE MU_ID = '$MU_ID'";
    if (mysqli_query($conn,$query1)) {
        return true;
    }else{
        return false;
    }

}

function removeSecretEntry($conn,$MU_ID)
{
    $query1 = "DELETE FROM secret_list WHERE MU_ID = '$MU_ID'";
    if (mysqli_query($conn,$query1)) {
        return true;
    }else{
        return false;
    }
}

$contents = file_get_contents('php://input');
if ($contents != null) {
    $data = json_decode($contents);
    $MU_ID = $data->{"MU_ID"};
    $response = array();
    if (removeMachine($conn,$MU_ID)) {
        if (removeSecretEntry($conn,$MU_ID)) {
            $response['Success'] = true;    
        }else{
            $response['Success'] = false;    
        }        
    }else{
        $response['Success'] = false;
    }
}else{
  die("An Error Occured");
}

echo json_encode($response);
mysqli_close($conn);

 ?>
