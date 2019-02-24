<?php


require 'ConnectDB.php';
 header('Content-Type: application/json');
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Credentials: true");
 header("Access-Control-Max-Age: 1000");
 header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
 header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
 function checkForDuplicatesEmail($conn,$Email)
 {
   $query = "SELECT * FROM accounts WHERE Email = '$Email'";
   $result = mysqli_query($conn,$query);

   if (mysqli_num_rows($result) == 0) {
     return true;
   }else{
     return false;
   }

 }
 function checkForDuplicateUserName($conn,$User_Name)
 {
   $query = "SELECT * FROM accounts WHERE User_Name = '$User_Name'";
   $result = mysqli_query($conn,$query);

   if (mysqli_num_rows($result) == 0) {
     return true;
   }else{
     return false;
   }
 }
 // check for duplicate Id number from a specific user category
 function checkForDuplicateIDNum($conn,$ID_Number)
 {
   $query = "SELECT ID_Number FROM acc_users WHERE ID_Number='$ID_Number' UNION
   SELECT ID_Number FROM acc_admin WHERE ID_Number='$ID_Number' UNION
   SELECT ID_Number FROM acc_cashier WHERE ID_Number='$ID_Number'";

   // $query_user = "SELECT * FROM acc_users WHERE ID_Number = '$ID_Number'";
   // $query_cashier = "SELECT * FROM acc_cashier WHERE ID_Number = '$ID_Number'";
   // $query_admin = "SELECT * FROM acc_admin WHERE ID_Number = '$ID_Number'";
   // $result_admin = mysqli_query($conn,$query_admin);
   // $result_cashier = mysqli_query($conn,$query_cashier);
   // $result_user = mysqli_query($conn,$query_user);
   $result = mysqli_query($conn,$query);
   if (mysqli_num_rows($result) == 0) {
     return true;
   }else{
     return false;
   }
 }

$contents = file_get_contents('php://input');
if ($contents != null) {
  $data = json_decode($contents);
  $Command = $data->{"Command"};
  $var = $data->{"Variable"};
  $response = array();
  if ($Command != NULL) {
    switch ($Command) {
      case 'ID_NUM':
        $response['Success'] = checkForDuplicateIDNum($conn,$var);
        break;
      case 'EMAIL':
        $response['Success'] = checkForDuplicatesEmail($conn,$var);
        break;
      case 'USER_NAME':
        $response['Success'] = checkForDuplicateUserName($conn,$var);
        break;
      default:
        break;
    }

    echo json_encode($response,JSON_PRETTY_PRINT);
  }else{
    die("An Error Occured");
  }

}
  mysqli_close($conn);

 ?>
