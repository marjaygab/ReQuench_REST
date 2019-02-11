<?php
require 'ConnectDB.php';
 header('Content-Type: application/json');

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
