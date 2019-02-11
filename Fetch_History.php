<?php


require 'ConnectDB.php';
 header('Content-Type: application/json');
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Credentials: true");
 header("Access-Control-Max-Age: 1000");
 header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
 header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");





function getHistory($conn,$account_id)
{
  // code...

  $rows = array();
  $response = array();
  $query = "SELECT transaction_history.Transaction_ID,machine_unit.Machine_Location,transaction_history.Date,transaction_history.Time,transaction_history.Amount,transaction_history.Temperature


  ,transaction_history.Price_Computed,transaction_history.Remaining_Balance


  FROM transaction_history


  INNER JOIN machine_unit ON transaction_history.MU_ID = machine_unit.MU_ID


  WHERE transaction_history.Acc_ID = '$account_id'";





  $result = mysqli_query($conn,$query);





  if (mysqli_num_rows($result) > 0) {


    while ($r = mysqli_fetch_assoc($result)) {


      $rows[] = $r;


    }




    $response['Transactions'] = $rows;
  }else{
    $response['Transactions'] = '';
  }


  $query = "SELECT * FROM purchase_history WHERE Acc_ID = '$account_id'";
  $results = mysqli_query($conn,$query);

  if (mysqli_num_rows($results) > 0) {
     {
      $rows = array();
      while ($r = mysqli_fetch_assoc($results)) {
        $rows[] = $r;
      }
      $response['Purchase'] = $rows;
    }
    }else{
      $response['Purchase'] = '';
    }
    echo json_encode($response,JSON_PRETTY_PRINT);

}




$contents = file_get_contents('php://input');
if ($contents != null) {
  $data = json_decode($contents);
  $Acc_ID = $data->{"Acc_ID"};
  getHistory($conn,$Acc_ID);
}
else if (isset($_POST['Acc_ID'])) {
  $Acc_ID = $_POST["Acc_ID"];
  getHistory($conn,$Acc_ID);
}else{
  die("An Error Occured");
}



  mysqli_close($conn);











 ?>


