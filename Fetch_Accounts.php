<?php
require 'ConnectDB.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
function getAccounts($conn, $Access_Level)
{
  // code...
  $rows = array();
  $response = array();
  switch ($Access_Level) {
    case 'ADMIN':
      $access_level = 'admin';
      $mysql_qry = "SELECT accounts.Acc_ID,accounts.User_Name,accounts.Password,accounts.Email,acc_levels.Access_Level,acc_admin.Acc_Admin_ID,acc_admin.ID_Number,acc_admin.First_Name,acc_admin.Last_Name,acc_admin.Balance,
      acc_images.Image_Path
      FROM accounts
      INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID
      INNER JOIN acc_admin on accounts.Acc_ID = acc_admin.Acc_ID
      LEFT JOIN acc_images on accounts.Acc_ID = acc_images.Acc_ID";
      break;
    case 'CASHIER':
      $access_level = 'cashier';
      $mysql_qry = "SELECT accounts.Acc_ID,accounts.User_Name,accounts.Password,accounts.Email,acc_levels.Access_Level,acc_cashier.Acc_Cashier_ID,acc_cashier.ID_Number,acc_cashier.First_Name,acc_cashier.Last_Name,acc_cashier.Balance,
      acc_images.Image_Path
      FROM accounts
      INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID
      INNER JOIN acc_cashier on accounts.Acc_ID = acc_cashier.Acc_ID
      LEFT JOIN acc_images ON acc_images.Acc_ID = accounts.Acc_ID";
      break;
    case 'USER':
      $access_level = 'user';
      $mysql_qry = "SELECT accounts.Acc_ID,accounts.User_Name,accounts.Password,accounts.Email,acc_levels.Access_Level,acc_users.Acc_user_ID,acc_users.ID_Number,acc_users.First_Name,acc_users.Last_Name,acc_users.Balance,
      acc_images.Image_Path
      FROM accounts
      INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID
      INNER JOIN acc_users on accounts.Acc_ID = acc_users.Acc_ID
      LEFT JOIN acc_images ON acc_images.Acc_ID = accounts.Acc_ID";
      break;
    default:
      die("An Error Occured");
      break;
  }

  $results = mysqli_query($conn,$mysql_qry);

  if (mysqli_num_rows($results) > 0) {

    while ($r = mysqli_fetch_assoc($results)) {
      $rows[] = $r;
    }
    $response[$Access_Level] = $rows;
    $response['Success'] = true;
  }else{
    $response[$Access_Level] = '';
    $response['Success'] = false;
  }

    echo json_encode($response,JSON_PRETTY_PRINT);
}


$contents = file_get_contents('php://input');
if ($contents != null) {
  $data = json_decode($contents);
  $Access_Level = $data->{"Access_Level"};
  getAccounts($conn,$Access_Level);
}
else if (isset($_POST['Access_Level'])) {
  $Acc_ID = $_POST["Access_Level"];
  getAccounts($conn,$Access_Level);
}else{
  die("An Error Occured");
}



mysqli_close($conn);




 ?>
