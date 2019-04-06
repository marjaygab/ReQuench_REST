<?php
  require 'ConnectDB.php';
  


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


function fetchRecentPurchaseUnrec($conn,$UU_ID)
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




function fetchRecentTransactionUnrec($conn,$UU_ID)
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


  // function sorter($HIST1,$HIST2)
  // {
  //   $date1 = new Date($HIST1->)

  //   if(($HIST1->PRIO)==($HIST2->PRIO)){
  //     return ($HIST1->AT)<=($HIST2->AT);
  //   }else{
  //     return ($HIST1->PRIO)<=($HIST2->PRIO);
  //   }
  // }


  $contents = file_get_contents('php://input');

  if ($contents != null) {
    $data = json_decode($contents);
    $account_type = $data->{"Account_Type"};
    $response = array();

    if ($account_type == 'Recorded') {
      $Acc_ID = $data->{"Acc_ID"};  
      $purchase_history = fetchRecentPurchase($conn,$Acc_ID);
      $transaction_history = fetchRecentTransaction($conn,$Acc_ID);
      $response['Purchase_History'] = $purchase_history[0];
      $response['Transaction_History'] = $transaction_history[0];
    }else{
      $UU_ID = $data->{"UU_ID"};  
      $purchase_history = fetchRecentPurchaseUnrec($conn,$UU_ID);
      $transaction_history = fetchRecentTransactionUnrec($conn,$UU_ID);
      $response['Purchase_History'] = $purchase_history[0];
      $response['Transaction_History'] = $transaction_history[0];
    }
    print(json_encode($response,JSON_PRETTY_PRINT));
  }
  else if (isset($_POST['Access_Level'])) {
    $Acc_ID = $_POST["Access_Level"];
    getAccounts($conn,$Access_Level);
  }else{
    die("An Error Occured");
  }



  mysqli_close($conn);
   ?>
