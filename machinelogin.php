<?php


require 'ConnectDB.php';
header('Content-type: application/json');
$contents = file_get_contents('php://input');

if ($contents != null) {
  $data = json_decode($contents);
  $otp_entered = $data->{"OTP_Entered"};
  $query = "SELECT accounts.Acc_ID,acc_levels.Access_Level FROM accounts INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID WHERE OTP = '$otp_entered'";
  $result = mysqli_query($conn,$query);
  $response = array();
  if (mysqli_num_rows($result) == 1) {
    $Acc_ID = '';
    $Access_Level = '';
    while ($row  = mysqli_fetch_assoc($result)) {
      $Acc_ID = $row['Acc_ID'];
      $Access_Level = $row['Access_Level'];
    }
      switch ($Access_Level) {
        case 'ADMIN':
          $access_level = 'admin';
          $mysql_qry = "SELECT accounts.Acc_ID,accounts.User_Name,accounts.Password,acc_levels.Access_Level,acc_admin.Acc_Admin_ID,acc_admin.First_Name,acc_admin.Last_Name,acc_admin.Balance
          FROM accounts
          INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID
          INNER JOIN acc_admin on accounts.Acc_ID = acc_admin.Acc_ID
          WHERE accounts.Acc_ID = '$Acc_ID'";
          break;
        case 'CASHIER':
          $access_level = 'cashier';
          $mysql_qry = "SELECT accounts.Acc_ID,accounts.User_Name,accounts.Password,acc_levels.Access_Level,acc_cashier.Acc_Cashier_ID,acc_cashier.First_Name,acc_cashier.Last_Name,acc_cashier.Balance
          FROM accounts
          INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID
          INNER JOIN acc_cashier on accounts.Acc_ID = acc_cashier.Acc_ID
          WHERE accounts.Acc_ID = '$Acc_ID'";
          break;
        case 'USER':
          $access_level = 'user';
          $mysql_qry = "SELECT accounts.Acc_ID,accounts.User_Name,accounts.Password,acc_levels.Access_Level,acc_users.Acc_user_ID,acc_users.First_Name,acc_users.Last_Name,acc_users.Balance
          FROM accounts
          INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID
          INNER JOIN acc_users on accounts.Acc_ID = acc_users.Acc_ID
          WHERE accounts.Acc_ID = '$Acc_ID'";
          break;
        default:
          die("An Error Occured");
          break;
      }
      $result = mysqli_query($conn,$mysql_qry);
      if (mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $response['Account'] = $row;
        $response['Success'] = true;
      }
else{
        $response['Success'] = false;
        $response['Account'] = '';
      }
      $json_string = json_encode($response,JSON_PRETTY_PRINT);
      echo $json_string;
  }
}
mysqli_close($conn);

 ?>


