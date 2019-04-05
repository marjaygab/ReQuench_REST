<?php 
require 'ConnectDB.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
$response = array();


function getMachine($conn,$MU_ID)
{
    $query = "SELECT * FROM machine_unit WHERE MU_ID = '$MU_ID'";
    $result = mysqli_query($conn,$query);
    if (mysqli_num_rows($result) == 1) {
        $rows = array();
        $r = mysqli_fetch_assoc($result);
        $response['Machine'] = $r;
        $response['Success'] = true;
    }else{
        $response['Machines'] = '';
        $response['Success'] = false;
    }
    echo json_encode($response,JSON_PRETTY_PRINT);
}


$contents = file_get_contents('php://input');
if ($contents != null) {
  $data = json_decode($contents);
  $MU_ID = $data->{"MU_ID"};
  getMachine($conn,$MU_ID);
}else{
  die("An Error Occured");
}


mysqli_close($conn);

?>