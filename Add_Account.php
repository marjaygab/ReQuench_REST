<?php


require 'ConnectDB.php';
 header('Content-Type: application/json');
function addAccount($conn,$ID_Number,$First_Name,$Last_Name,$User_Name,$Password,$Email,$Access_Level)
{
  // code...
  $rows = array();
  $response = array();

  $al_id = 0;
  if ($Access_Level == 'USER') {
    // code...
    $al_id = 3;
  }elseif ($Access_Level == 'ADMIN') {
    // code...
    $al_id = 1;
  }elseif ($Access_Level == 'CASHIER') {
    $al_id = 2;
  }

  $query = "INSERT INTO accounts (AL_ID,User_Name,Password,Email) VALUES ('$al_id','$User_Name','$Password','$Email')";
  $result = mysqli_query($conn,$query);
  if ($result) {
    $Acc_ID = mysqli_insert_id($conn);
    if ($Acc_ID != null) {
      switch ($Access_Level) {
        case 'USER':
          $query = "INSERT INTO acc_users (Acc_ID,ID_Number,First_Name,Last_Name,Balance) VALUES ('$Acc_ID','$ID_Number','$First_Name','$Last_Name','0')";
          break;
        case 'ADMIN':
          $query = "INSERT INTO acc_admin (Acc_ID,ID_Number,First_Name,Last_Name,Balance) VALUES ('$Acc_ID','$ID_Number','$First_Name','$Last_Name','0')";
          break;
        case 'Cashier':
          $query = "INSERT INTO acc_cashier (Acc_ID,ID_Number,First_Name,Last_Name,Balance) VALUES ('$Acc_ID','$ID_Number','$First_Name','$Last_Name','0')";
          break;
        default:
          break;
      }
      if (mysqli_query($conn,$query)) {
        $response['Success'] = true;
      }else{
        $response['Success'] = false;
        $response['Error'] = 'DB_ERROR_3';
      }
    }else{
      $response['Success'] = false;
      $response['Error'] = 'DB_ERROR_2';
    }
  }else{
    $response['Success'] = false;
    $response['Error'] = 'DB_ERROR_1';
  }

    echo json_encode($response,JSON_PRETTY_PRINT);
}

$contents = file_get_contents('php://input');
if ($contents != null) {
  $data = json_decode($contents);
  $ID_Number = $data->{"ID_Number"};
  $First_Name = $data->{"First_Name"};
  $Last_Name = $data->{"Last_Name"};
  $User_Name = $data->{"User_Name"};
  $Password = $data->{"Password"};
  $Email = $data->{"Email"};
  $Access_Level = $data->{"Access_Level"};
  $response = array();
  addAccount($conn,$ID_Number,$First_Name,$Last_Name,$User_Name,$Password,$Email,$Access_Level);
}
else if (isset($_POST['ID_Number']) && isset($_POST["First_Name"])
&& isset($_POST["Last_Name"]) && isset($_POST["User_Name"])
&& isset($_POST["Password"]) && isset($_POST["Email"])
&& isset($_POST["Access_Level"])) {
  $ID_Number = $_POST["ID_Number"];
  $First_Name= $_POST["First_Name"];
  $Last_Name = $_POST["Last_Name"];
  $User_Name = $_POST["User_Name"];
  $Password = $_POST["Password"];
  $Email = $_POST["Email"];
  $Access_Level = $_POST["Access_Level"];
  addAccount($conn,$ID_Number,$First_Name,$Last_Name,$User_Name,$Password,$Email,$Access_Level);
}else{

  die("An Error Occured");
}

  mysqli_close($conn);

 ?>
