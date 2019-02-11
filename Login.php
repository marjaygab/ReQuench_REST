<?php


require 'ConnectDB.php';
header('Content-Type: application/json');

$username = "";
$password = "";


function verify($conn,$username,$password)
{

  // code...
  //fetch user details here
  $mysql_qry = "SELECT accounts.Acc_ID,acc_levels.Access_Level FROM accounts INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID WHERE User_Name = '$username' AND Password = '$password'";
  $result = mysqli_query($conn,$mysql_qry);
  if (!$conn) {
    die("Connection Failed" . mysqli_connect_error());
  }
  if (mysqli_num_rows($result) > 0 && mysqli_num_rows($result) < 2 ) {
    $response = array();
    while($row = mysqli_fetch_assoc($result)){
      $account_id = $row["Acc_ID"];
      $access_level = $row["Access_Level"];
    }

    switch ($access_level) {
      case 'ADMIN':
        $access_level = 'admin';
        $mysql_qry = "SELECT accounts.Acc_ID,accounts.User_Name,accounts.Password,accounts.Email,acc_levels.Access_Level,acc_admin.Acc_Admin_ID,acc_admin.ID_Number,acc_admin.First_Name,acc_admin.Last_Name,acc_admin.Balance
        FROM accounts
        INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID
        INNER JOIN acc_admin on accounts.Acc_ID = acc_admin.Acc_ID
        WHERE accounts.Acc_ID = '$account_id'";
        break;

      case 'CASHIER':
        $access_level = 'cashier';
        $mysql_qry = "SELECT accounts.Acc_ID,accounts.User_Name,accounts.Password,accounts.Email,acc_levels.Access_Level,acc_cashier.Acc_Cashier_ID,acc_cashier.ID_Number,acc_cashier.First_Name,acc_cashier.Last_Name,acc_cashier.Balance
        FROM accounts
        INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID
        INNER JOIN acc_cashier on accounts.Acc_ID = acc_cashier.Acc_ID
        WHERE accounts.Acc_ID = '$account_id'";
        break;

      case 'USER':
        $access_level = 'user';
        $mysql_qry = "SELECT accounts.Acc_ID,accounts.User_Name,accounts.Password,accounts.Email,acc_levels.Access_Level,acc_users.Acc_user_ID,acc_users.ID_Number,acc_users.First_Name,acc_users.Last_Name,acc_users.Balance
        FROM accounts
        INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID
        INNER JOIN acc_users on accounts.Acc_ID = acc_users.Acc_ID
        WHERE accounts.Acc_ID = '$account_id'";
        break;

      default:
        die("An Error Occured");
        break;
    }
    $result = mysqli_query($conn,$mysql_qry);
    if (mysqli_num_rows($result) > 0){
      $row = mysqli_fetch_assoc($result);
    }

    $response["Account_Details"] = $row;
    //fetch image base 64 here
    $query = "SELECT Image_Path FROM acc_images WHERE Acc_ID = '$account_id'";
    $result = mysqli_query($conn,$query);
    if (mysqli_num_rows($result) == 1) {
      while ($row = mysqli_fetch_assoc($result)) {
        $file_path = $row["Image_Path"];
      }
      $response["file_path"] = $file_path;
      $response["image"] = base64_encode(file_get_contents(__DIR__ . $file_path));

    }else{
      //No Image Exists for specific User. Display Default Image

    }
    $response["Success"] = "true";
    $json_string = json_encode($response,JSON_PRETTY_PRINT);
    echo $json_string;
  }else{
    $response["Success"] = "false";
    $json_string = json_encode($response,JSON_PRETTY_PRINT);
    echo $json_string;

  }
}

$contents = file_get_contents('php://input');
if ($contents != null) {
  $data = json_decode($contents);
  $username = $data->{"User_Name"};
  $password = $data->{"Password"};
  verify($conn,$username,$password);
}
else if (isset($_POST['User_name']) && isset($_POST['Password'])) {
  $username = $_POST["User_Name"];
  $password = $_POST["Password"];
  verify($conn,$username,$password);
}else{
  die("An Error Occured");
}
  mysqli_close($conn);
 ?>
