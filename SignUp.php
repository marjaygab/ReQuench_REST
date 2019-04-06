<?php


require 'ConnectDB.php';
 header('Content-Type: application/json');
 header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
function addAccount($conn,$ID_Number,$First_Name,$Last_Name,$User_Name,$Password,$Email,$Access_Level)
{
  // code...
  $rows = array();
  $response = array();

  if ($Access_Level == 'USER') {
    // code...
    $al_id = 3;
  }elseif ($Access_Level == 'ADMIN') {
    // code...
    $al_id = 1;
  }elseif ($Access_Level == 'CASHIER') {
    // code...
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
        case 'CASHIER':
          $query = "INSERT INTO acc_cashier (Acc_ID,ID_Number,First_Name,Last_Name,Balance) VALUES ('$Acc_ID','$ID_Number','$First_Name','$Last_Name','0')";
          break;
        default:
          break;
      }
      if (mysqli_query($conn,$query)) {
        $response['Success'] = true;
        return $Acc_ID;
      }else{
        $response['Success'] = false;
        $response['Error'] = 'DB_ERROR_3';
        return false;
      }
    }else{
      $response['Success'] = false;
      $response['Error'] = 'DB_ERROR_2';
      return false;
    }
  }else{
    $response['Success'] = false;
    $response['Error'] = 'DB_ERROR_1';
    return false;
  }
  
    // echo json_encode($response,JSON_PRETTY_PRINT);
}


function checkIfUnrecorded($conn,$ID_Number)
{
    $query = "SELECT * FROM unrecorded_users WHERE ID_Number = '$ID_Number'";
    $result = mysqli_query($conn,$query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        $UNREC_ACC = $row;
        return $UNREC_ACC;
    }else{
        return false;
    }
}

function deleteUnrec($conn,$UU_ID)
{
    $query = "DELETE FROM unrecorded_users WHERE UU_ID = '$UU_ID'";
    if (mysqli_query($conn,$query)) {
        return true;
    }else{
        return false;
    }
}

function fetchTransactions($conn,$UU_ID)
{
    $query = "SELECT * FROM transaction_history_unrec WHERE UU_ID = '$UU_ID'" ;
    $result = mysqli_query($conn,$query);
    if (mysqli_num_rows($result) > 0) {
        while ($r = mysqli_fetch_assoc($result)) {
            $row[] = $r;
        }
        return $row;
    }else{
        return null;
    }
}

function fetchPurchases($conn,$UU_ID)
{
    $query = "SELECT * FROM purchase_history_unrec WHERE UU_ID = '$UU_ID'" ;
    $result = mysqli_query($conn,$query);
    if (mysqli_num_rows($result) > 0) {
        while ($r = mysqli_fetch_assoc($result)) {
            $row[] = $r;
        }
        return $row;
    }else{
        return null;
    }
}

function transferTransactions($conn,$transactions,$Acc_ID)
{

    for ($i=0; $i < sizeof($transactions); $i++) { 
        $query = "INSERT INTO transactions_history (Acc_ID,MU_ID,Date,Time,Amount,Temperature,Price_Computed,Water_Level_Before,Water_Level_After,Remaining_Balance) VALUES 
        ('$Acc_ID','$transactions[$i]['MU_ID']','$transactions[$i]['Date']','$transactions[$i]['Time']','$transactions[$i]['Amount']','$transactions[$i]['Temperature']',
        '$transactions[$i]['Price_Computed']','$transactions[$i]['Water_Level_Before']','$transactions[$i]['Water_Level_After']','$transactions[$i]['Remaining_Balance']')";

    }
}

function transferPurchases($conn,$purchases)
{
    
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
  $Access_Level = "USER";
  addAccount($conn,$ID_Number,$First_Name,$Last_Name,$User_Name,$Password,$Email,$Access_Level);
}else{

  die("An Error Occured");
}

  mysqli_close($conn);

 ?>
