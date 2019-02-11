<?php
require 'ConnectDB.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
function getNotifs($conn, $account_id)
{
  // code...
  $rows = array();
  $response = array();
  $seen_notifs = array();

  // $query = "SELECT seen_notifs.Seen_ID,seen_notifs.Acc_ID,seen_notifs.Time_Seen,seen_notifs.Date_Seen,
  // seen_notifs.Status,notifications.Notif_ID,notifications.Notif_Title,notifications.Notif_Desc,
  // notifications.Time_Posted,notifications.Date_Posted FROM notifications INNER JOIN seen_notifs
  // WHERE Acc_ID = '$account_id'";

  $query = "SELECT Seen_ID,Notif_ID,Time_Seen,Date_Seen,Status FROM seen_notifs WHERE Acc_ID = '$account_id'";
  $results = mysqli_query($conn,$query);

  if (mysqli_num_rows($results) > 0) {

    while ($r = mysqli_fetch_assoc($results)) {
      $rows[] = $r;
      $seen_status = new stdClass();
      $seen_status->Seen_ID = $r['Seen_ID'];
      $seen_status->Notif_ID = $r['Notif_ID'];
      $seen_status->Time_Seen = $r['Time_Seen'];
      $seen_status->Date_Seen = $r['Date_Seen'];
      $seen_status->Status = $r['Status'];
      array_push($seen_notifs,$seen_status);
    }

  }else{

  }
  $query = "SELECT * FROM notifications";
  $results = mysqli_query($conn,$query);

    if (mysqli_num_rows($results) > 0) {
      $rows = array();
      $notif_list = array();
      while ($r = mysqli_fetch_assoc($results)) {
        $notifications = new stdClass();
        $notifications->Notif_ID = $r['Notif_ID'];
        $notifications->Notif_Title = $r['Notif_Title'];
        $notifications->Notif_Desc = $r['Notif_Desc'];
        $notifications->Time_Posted = $r['Time_Posted'];
        $notifications->Date_Posted = $r['Date_Posted'];

        for ($i=0; $i < sizeof($seen_notifs) ; $i++) {
          if ($notifications->Notif_ID == $seen_notifs[$i]->Notif_ID) {
            if ($seen_notifs[$i]->Status == '1') {
              // code...
              $notifications->Seen = true;
            }else{
                $notifications->Seen = false;
            }
          }
        }

        array_push($notif_list,$notifications);
      }

      for ($i=0; $i < sizeof($notif_list) ; $i++) {
        if (!property_exists($notif_list[$i],'Seen')) {
          $notif_list[$i]->Seen = false;
        }
      }

      $response['Notifications'] = $notif_list;
      $response['Success'] = true;
    }else{
      $response['Notifications'] = '';
      $response['Success'] = false;
    }
    echo json_encode($response,JSON_PRETTY_PRINT);
}


$contents = file_get_contents('php://input');
if ($contents != null) {
  $data = json_decode($contents);
  $Acc_ID = $data->{"Acc_ID"};
  getNotifs($conn,$Acc_ID);
}
else if (isset($_POST['Acc_ID'])) {
  $Acc_ID = $_POST["Acc_ID"];
  getNotifs($conn,$Acc_ID);
}else{
  die("An Error Occured");
}



mysqli_close($conn);




 ?>
