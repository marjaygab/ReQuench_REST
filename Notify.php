<?php
// API access key from Google API's Console
require 'ConnectDB.php';


function firebaseHandler($title,$body,$registrationIds)
{
  define( 'API_ACCESS_KEY', 'AAAAv__JBfw:APA91bHlOU4fVXW4lyk1rxvMmmf2mgcUWC7SPbHdMp6BSxMd2TOuJsmRQd92zSKa-ko4nx65B3pIABQHYp3hv9o8dVi6LgGRKDuA4zScK_1jJBwGjqrC6tfRLy5iCvzqFcITDFKCFERK' );
  $msg = array
  (
  	'title'		=> $title,
    'body' 	=> $body,
    'click_action': "https://requenchweb2019.firebaseapp.com"
  	'icon'	=> 'https://requenchweb2019.firebaseapp.com/assets/images/logo.png'
  );
  $fields = array
  (
  	'notification'			=> $msg,
  	'to' 	=> $registrationIds
  );

  $headers = array
  (
  	'Authorization: key=' . API_ACCESS_KEY,
  	'Content-Type: application/json'
  );

  $ch = curl_init();
  curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
  curl_setopt( $ch,CURLOPT_POST, true );
  curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
  curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
  curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
  curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
  $result = curl_exec($ch );
  curl_close( $ch );

  $result_json = json_decode($result);
  echo $result_json;
  if ($result_json->{"success"}) {
    return true;
  }else{
    return false;
  }
}

// function batchNotify($value='')
// {
//   // code...
// }


function pushNotifs($title,$body,$token_arr)
{
  // code...
  for ($i=0; $i < sizeof($token_arr) ; $i++) {
    if (firebaseHandler($title,$body,$token_arr[$i]->registration_token)) {
      $token_arr[$i]->success = true;
    }
  }
}


function verifyNotifSent($token_arr)
{
  // code...
  $success = false;
  $success_count = 0;
  for ($i=0; $i < sizeof($token_arr); $i++) {
    if ($token_arr[$i]->success == true) {
      $success_count++;
    }
  }
  if ($success_count == sizeof($token_arr)) {
    $success = true;
  }
  return $success;
}

function saveNotifDB($conn,$title,$body)
{

  $dt = new DateTime();
  $time = $dt->format("H:i:s");
  $date = $dt->format("Y-m-d");

  $query = "INSERT INTO notifications (Notif_Title,Notif_Desc,Time_Posted,Date_Posted)
  VALUES ('$title','$body','$time','$date')";

  if (mysqli_query($conn,$query)) {
    echo 'Success Insert!';
  }else{
    echo 'Failed Insert!';
  }

}


function getRegTokens($conn)
{
  $object = new stdClass();
  $token_array = array();
  $query = "SELECT accounts.registration_token FROM accounts INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID WHERE acc_levels.Access_Level = 'ADMIN'";
  $results = mysqli_query($conn,$query);

  if (mysqli_num_rows($results) > 0) {
    while ($row = mysqli_fetch_assoc($results)) {
      if ($row['registration_token'] != NULL) {
        // code...
        $object->registration_token = $row['registration_token'];
        $object->success = false;
        array_push($token_array,$object);
      }
    }
  }

  return $token_array;
}



date_default_timezone_set('Asia/Manila');
$contents = file_get_contents('php://input');
if ($contents != null) {
  $data = json_decode($contents);
  $title = $data->{"title"};
  $body = $data->{"body"};
  $token_arr = getRegTokens($conn);
  saveNotifDB($conn,$title,$body);
  pushNotifs($title,$body,$token_arr);


  // if (verifyNotifSent($token_arr)) {
  //   saveNotifDB($conn,$title,$body);
  // }else{
  //   //handle errors in sending here
  // }

}
else if (isset($_POST['Acc_ID']) && isset($_POST["registration_token"])) {
  $Acc_ID = $_POST["Acc_ID"];
  $Acc_ID = $_POST["registration_token"];

}else{
  die("An Error Occured");
}

mysqli_close($conn);

 ?>
