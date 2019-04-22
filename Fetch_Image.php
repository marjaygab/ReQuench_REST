<?php
require 'ConnectDB.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
function fetchImage($conn,$acc_id){
  $query = "SELECT Image_Path FROM acc_images WHERE Acc_ID = '$acc_id'";
  $result = mysqli_query($conn,$query);
  if (mysqli_num_rows($result) == 1) {
    // $output = mysqli_fetch_assoc();
    $row = mysqli_fetch_assoc($result);
    $file_path = $row["Image_Path"];

    $file_path = "https://requench-rest.herokuapp.com" . $file_path;
    $response = array();
    $response["image"] = base64_encode(file_get_contents($file_path));
    $response["image_link"] = $file_path;
    $response["Success"] = "true";
  }else{
    //No Image Exists for specific User. Display Default Image
    $file_path = "https://requench-rest.herokuapp.com/source_images/MainLogo.png";
    $response = array();
    $response["image"] = base64_encode(file_get_contents($file_path));
    $response["image_link"] = $file_path;
    $response["Success"] = "true";
  }
    return $response;
}



$contents = file_get_contents('php://input');
$response = array();
if ($contents != null) {
    $data = json_decode($contents);
    $Acc_ID = $data->{"Acc_ID"};
    $response = fetchImage($conn,$Acc_ID);
} else {
    $response['Success'] = false;
    echo json_encode($response);
}

echo json_encode($response,JSON_PRETTY_PRINT);
mysqli_close($conn);


 ?>


