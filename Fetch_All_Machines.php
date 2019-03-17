<?php 
require 'ConnectDB.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
$response = array();
$query = "SELECT * FROM machine_unit";

$result = mysqli_query($conn,$query);

if (mysqli_num_rows($result) > 0) {
    $rows = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    $response['Machines'] = $rows;
    $response['Success'] = true;
}else{
    $response['Machines'] = '';
    $response['Success'] = false;
}

echo json_encode($response,JSON_PRETTY_PRINT);
mysqli_close($conn);

?>