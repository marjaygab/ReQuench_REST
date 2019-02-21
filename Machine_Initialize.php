<?php
require 'ConnectDB.php';
ini_set('display_errors', 'On'); 
error_reporting(E_ALL); 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);




function fetchRecentPurchase($conn,$Acc_ID)
{
  $query = "SELECT * FROM purchase_history WHERE Acc_ID = '$Acc_ID' ORDER BY Date DESC, Time DESC";
  $result = mysqli_query($conn,$query);
  $row = array();
  if (mysqli_num_rows($result) > 0) {
    while($r = mysqli_fetch_assoc($result)){
      $row[] = $r;
    }
    return $row;
  }else{
    return null;
  }
}

function fetchRecentTransaction($conn,$Acc_ID)
{
  // code...
  $query = "SELECT * FROM transaction_history WHERE Acc_ID = '$Acc_ID' ORDER BY Date DESC, Time DESC";
  $result = mysqli_query($conn,$query);
  $row = array();
  if (mysqli_num_rows($result) > 0) {
    while($r = mysqli_fetch_assoc($result)){
      $row[] = $r;
    }
    return $row;
  }else{
    return null;
  }
}


function fetchRecentPurchaseUnrecorded($conn,$UU_ID)
{
  $query = "SELECT * FROM purchase_history_unrec WHERE UU_ID = '$UU_ID' ORDER BY Date DESC, Time DESC";
  $result = mysqli_query($conn,$query);
  $row = array();
  if (mysqli_num_rows($result) > 0) {
    while($r = mysqli_fetch_assoc($result)){
      $row[] = $r;
    }
    return $row;
  }else{
    return null;
  }
}

function fetchRecentTransactionUnrecorded($conn,$UU_ID)
{
  // code...
  $query = "SELECT * FROM transaction_history_unrec WHERE UU_ID = '$UU_ID' ORDER BY Date DESC, Time DESC";
  $result = mysqli_query($conn,$query);
  $row = array();
  if (mysqli_num_rows($result) > 0) {
    while($r = mysqli_fetch_assoc($result)){
      $row[] = $r;
    }
    return $row;
  }else{
    return null;
  }
}



function getUnrecordedAccountsDetails($conn,$response,$rfid_id = NULL)
{
    $query = "SELECT * FROM unrecorded_users WHERE RFID_ID = '$rfid_id'";
    $result = mysqli_query($conn,$query);
    if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $response['Account'] = $row;
            $response['Success'] = true;
            return $response;
    }else {
      return NULL;
    }
}

function getAccountsDetails($conn,$response,$otp_entered = NULL,$rfid_id = NULL,$login_method)
{

    if ($login_method == 'OTP' && $otp_entered != NULL) {
      $condition = "WHERE OTP = '$otp_entered'";
    } else if($login_method == 'RFID' && $rfid_id != NULL){
      $condition = "WHERE RFID_ID = '$rfid_id'";
    }else{
      return false;
    }

    $query = "SELECT accounts.Acc_ID,acc_levels.Access_Level FROM accounts INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID $condition";
    $result = mysqli_query($conn,$query);
    if (mysqli_num_rows($result) > 0) {
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
            return $response;
        }
        else{
            $response['Success'] = false;
            $response['Account'] = '';
            return NULL;
        }
    }else {
        return NULL;
    }
}


$contents = file_get_contents('php://input');
if ($contents != null) {
    $rfid_id = NULL;
    $otp_entered = NULL;
    $data = json_decode($contents);
    $login_method = $data -> {"Login_Method"};
    if ($login_method == 'OTP') {
      $otp_entered = $data->{"OTP_Entered"};
    } else if($login_method == 'RFID'){
      $rfid_id = $data->{"RFID_ID"};
    }
  
    $response = array();
    $response_unrecorded = array();
    $response = getAccountsDetails($conn,$response,$otp_entered,$rfid_id,$login_method);
    $response_unrecorded =  getUnrecordedAccountsDetails($conn,$response,$rfid_id);
    $returned_accid = $response['Account']['Acc_ID'];
    $returned_uuid = $response_unrecorded['Account']['UU_ID'];
    if ($returned_accid != NULL) {
        $response['Success'] = true;
        $purchase_history = fetchRecentPurchase($conn,$returned_accid);
        $transaction_history = fetchRecentTransaction($conn,$returned_accid);
        $response['Purchase_History'] = $purchase_history[0];
        $response['Transaction_History'] = $transaction_history[0];
        $response['Account_Type'] = 'Recorded';
        echo json_encode($response);
    }else if ($returned_uuid != NULL) {
      $response['Success'] = true;
      $purchase_history = fetchRecentPurchaseUnrecorded($conn,$returned_uuid);
      $transaction_history = fetchRecentTransactionUnrecorded($conn,$returned_uuid);
      $response_unrecorded['Purchase_History'] = $purchase_history[0];
      $response_unrecorded['Transaction_History'] = $transaction_history[0];
      $response_unrecorded['Account_Type'] = 'Unrecorded';
      echo json_encode($response_unrecorded);
    }
    else{
        $response['Success'] = false;
        echo json_encode($response);
    }
    
}
mysqli_close($conn);


?>