<?php


require 'ConnectDB.php';


 header('Content-Type: application/json');
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Credentials: true");
 header("Access-Control-Max-Age: 1000");
 header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
 header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");




if(isset($_POST['Acc_ID'])){
  $Acc_ID = $_POST['Acc_ID'];
  $response = array("Updated"=>false);
    $query = "UPDATE accounts SET OTP = null WHERE Acc_ID='$Acc_ID'";
    if (mysqli_query($conn,$query)) {
        $response['Updated'] = true;
    }else{
        $response['Updated'] = false;
    }
  echo json_encode($response,JSON_PRETTY_PRINT);
}
mysqli_close($conn);

 ?>


