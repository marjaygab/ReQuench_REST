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
      }else{
        $row = array();
      }
    return $row;
}

function fetchPurchases($conn,$UU_ID)
{
    $query = "SELECT * FROM purchase_history_unrec WHERE UU_ID = '$UU_ID'" ;
    $result = mysqli_query($conn,$query);
    if (mysqli_num_rows($result) > 0) {
        while ($r = mysqli_fetch_assoc($result)) {
            $row[] = $r;
        }
    }else{
      $row = array();
    }
    return $row;
}

function transferTransactions($conn,$transactions,$Acc_ID)
{
    if (sizeof($transactions) != 0) {
      for ($i=0; $i < sizeof($transactions); $i++) {
        $MU_ID = $transactions[$i]['MU_ID'];
        $Date = $transactions[$i]['Date'];
        $Time = $transactions[$i]['Time'];
        $Amount = $transactions[$i]['Amount'];
        $Temperature = $transactions[$i]['Temperature'];
        $Price_Computed = $transactions[$i]['Price_Computed'];
        $Water_Level_Before = $transactions[$i]['Water_Level_Before'];
        $Water_Level_After = $transactions[$i]['Water_Level_After'];
        $Remaining_Balance = $transactions[$i]['Remaining_Balance'];
        $Transacation_ID = $transactions[$i]['Transaction_ID'];
        $query = "INSERT INTO transaction_history (Acc_ID,MU_ID,Date,Time,Amount,Temperature,Price_Computed,Water_Level_Before,Water_Level_After,Remaining_Balance) VALUES 
        ('$Acc_ID','$MU_ID','$Date','$Time','$Amount','$Temperature','$Price_Computed','$Water_Level_Before','$Water_Level_After','$Remaining_Balance')";
        $query1 = "DELETE FROM transaction_history_unrec WHERE Transaction_ID = '$Transacation_ID'";
        if (!mysqli_query($conn,$query)) {
          continue;
        }else{
          if (!mysqli_query($conn,$query1)) {
            continue;
          }
        }
    }
    }

    return true;
}

function transferPurchases($conn,$purchases,$Acc_ID)
{

  if (sizeof($purchases) != 0) {
    for ($i=0; $i < sizeof($purchases); $i++) { 
      $Amount = $purchases[$i]['Amount'];
      $Price_Computed = $purchases[$i]['Price_Computed'];
      $Time = $purchases[$i]['Time'];
      $Date = $purchases[$i]['Date'];
      $Purchase_ID = $purchases[$i]['Purchase_ID'];
      $query = "INSERT INTO purchase_history (Acc_ID,Amount,Price_Computed,Time,Date) VALUES 
      ('$Acc_ID','$Amount','$Price_Computed','$Time','$Date')";

      $query1 = "DELETE FROM purchase_history_unrec WHERE Purchase_ID = '$Purchase_ID'";
      if (!mysqli_query($conn,$query)) {
        continue;
      }else{
        if (!mysqli_query($conn,$query1)) {
          continue;
        }
      }
  }
  }  
   return true;
}


function updateBalance($conn,$Acc_ID,$Balance)
{
  $query = "UPDATE acc_users SET Balance = '$Balance' WHERE Acc_ID = '$Acc_ID'";
  if (mysqli_query($conn,$query)) {
    return true;
  }else{
    return false;
  }
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
  $Access_Level = "USER";
  $response = array();
  $add_response = addAccount($conn,$ID_Number,$First_Name,$Last_Name,$User_Name,$Password,$Email,$Access_Level);
  if ($add_response != false) {
    $Acc_ID = $add_response;
    $check_response = checkIfUnrecorded($conn,$ID_Number);
    var_dump($check_response);
    if ($check_response != false) {
      $UU_ID = $check_response['UU_ID'];
      if (updateBalance($conn,$Acc_ID,$check_response['Balance'])) {
        if (deleteUnrec($conn,$UU_ID)) {
          $transactions_list = fetchTransactions($conn,$UU_ID);
          $purchases_list = fetchPurchases($conn,$UU_ID);
          if (transferPurchases($conn,$purchases_list,$Acc_ID) && transferTransactions($conn,$transactions_list,$Acc_ID)) {
            $response['Success'] = true;
          }else{
            $response['Success'] = false;
            $response['Errors'][] = 'Transferring Error';  
          }
        }else{
          $response['Success'] = false;
          $response['Errors'][] = 'Deleting Error';
        } 
      }else{
        $response['Success'] = false;
        $response['Errors'][] = 'Update Balance Error';
      }
    }else{
      //not unrecorded. totally new account
      $response['Success'] = true;
    }
  }else{
    $response['Success'] = false;
    $response['Errors'][] = "Adding Account Error";
  }
}else{
  die("An Error Occured");
}
  echo json_encode($response,JSON_PRETTY_PRINT);
  mysqli_close($conn);

 ?>
