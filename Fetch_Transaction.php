<?php


require 'ConnectDB.php';
 header('Content-Type: application/json');

if (isset($_POST["Acc_ID"])) {
  $account_id = $_POST["Acc_ID"];
  $query = "SELECT transaction_history.Transaction_ID,machine_unit.Machine_Location,transaction_history.Date,transaction_history.Time,transaction_history.Amount_Dispensed,transaction_history.Temperature
  ,transaction_history.Price_Computed,transaction_history.Remaining_Balance
  FROM transaction_history
  INNER JOIN machine_unit ON transaction_history.MU_ID = machine_unit.MU_ID
  WHERE transaction_history.Acc_ID = '$account_id'";
  $result = mysqli_query($conn,$query);

  if (mysqli_num_rows($result) > 0) {
    $rows = array();

    while ($r = mysqli_fetch_assoc($result)) {
      $rows[] = $r;
    }
    echo json_encode($rows,JSON_PRETTY_PRINT);
  }
  mysqli_close($conn);

}


 ?>
