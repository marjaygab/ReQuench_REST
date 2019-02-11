<?php


require 'ConnectDB.php';
 header('Content-Type: application/json');
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Credentials: true");
 header("Access-Control-Max-Age: 1000");
 header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
 header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
function updateSeen($conn,$account_id,$notif_id,$seen)
{
  // code...
  $rows = array();
  $response = array();
  $Status = 0;

  if($seen==true){
    $Status = 1;
  }

  $query = "SELECT Seen_ID FROM seen_notifs WHERE Acc_ID = '$account_id' AND Notif_ID='$notif_id'";
  $result = mysqli_query($conn,$query);

  if (mysqli_num_rows($result) == 0) {
    $dt = new DateTime();
    $time = $dt->format("H:i:s");
    $date = $dt->format("Y-m-d");

    $query = "INSERT INTO seen_notifs (Acc_ID,Notif_ID,Time_Seen,Date_Seen,Status)
    VALUES ('$account_id','$notif_id','$time','$date',1)";

    if (mysqli_query($conn,$query)) {
        $response['Success'] = true;
    }else{
        $response['Success'] = false;
    }
  }else{
    $query = "UPDATE seen_notifs SET Status = '$Status' WHERE Acc_ID = '$account_id' AND Notif_ID = '$notif_id'";
      //update table
      $results = mysqli_query($conn,$query);
      if (mysqli_query($conn,$query)) {
        $response['Success'] = true;
      }else{
        $response['Success'] = false;
      }
  }
    echo json_encode($response,JSON_PRETTY_PRINT);
}




$contents = file_get_contents('php://input');
if ($contents != null) {
  $data = json_decode($contents);
  $Notif_ID = $data->{"Notif_ID"};
  $Acc_ID = $data->{"Acc_ID"};
  $Seen = $data->{"Seen"};
  updateSeen($conn,$Acc_ID,$Notif_ID,$Seen);
}
else if (isset($_POST['Acc_ID']) && isset($_POST["registration_token"])) {
  $Notif_ID = $_POST["Notif_ID"];
  $Acc_ID = $_POST["Acc_ID"];
  $Seen = $_POST["Seen"];
  updateSeen($conn,$Acc_ID,$Notif_ID,$Seen);
}else{

  die("An Error Occured");
}

  mysqli_close($conn);

 ?>
