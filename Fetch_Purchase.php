<?php
  require 'ConnectDB.php';

  if (isset($_POST['Acc_ID'])) {
    $account_id = $_POST['Acc_ID'];

    $query = "SELECT * FROM purchase_history WHERE Acc_ID = '$account_id'";
    $results = mysqli_query($conn,$query);

    if (mysqli_num_rows($results) > 0) {
       {
        // code...
        $rows = array();
        while ($r = mysqli_fetch_assoc($results)) {
          $rows[] = $r;
        }

        echo json_encode($rows,JSON_PRETTY_PRINT);
      }
    }
  }

mysqli_close($conn);

 ?>
