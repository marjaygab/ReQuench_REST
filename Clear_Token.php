<?php


require 'ConnectDB.php';
 header('Content-Type: application/json');
 header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
function clearToken($conn,$account_id)
{
  // code...
  $response = array();
    //update table
    $query = "UPDATE accounts SET registration_token = NULL WHERE Acc_ID = '$account_id'";
    $results = mysqli_query($conn,$query);

    if (mysqli_query($conn,$query)) {
        $response['Success'] = true;
      }else{
        $response['Success'] = false;
      }
    echo json_encode($response,JSON_PRETTY_PRINT);
}


$contents = file_get_contents('php://input');
if ($contents != null) {
  $data = json_decode($contents);
  $Acc_ID = $data->{"Acc_ID"};
  clearToken($conn,$Acc_ID);
}
else if (isset($_POST['Acc_ID'])) {
  $Acc_ID = $_POST["Acc_ID"];
  clearToken($conn,$Acc_ID);
}else{
  die("An Error Occured");
}

  mysqli_close($conn);

 ?>
