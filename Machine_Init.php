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


  function sortlist(list)
  {

    return list
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
  echo 'Testing';

  if ($contents != null) {
    $data = json_decode($contents);
    $Acc_ID = $data->{"Acc_ID"};
    $response = array();
    $purchase_history = fetchRecentPurchase($conn,$Acc_ID);
    $transaction_history = fetchRecentTransaction($conn,$Acc_ID);
    $response['Purchase_History'] = $purchase_history[0];
    $response['Transaction_History'] = $transaction_history[0];
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
