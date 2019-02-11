<?php
require 'ConnectDB.php';
header('Content-Type: application/json');

if (isset($_POST["Acc_ID"])) {
  $acc_id = $_POST["Acc_ID"] ;

  $query = "SELECT Image_Path FROM acc_images WHERE Acc_ID = '$acc_id'";
  $result = mysqli_query($conn,$query);

  if (mysqli_num_rows($result) == 1) {
    // $output = mysqli_fetch_assoc();

    while ($row = mysqli_fetch_assoc($result)) {
      $file_path = $row["Image_Path"];
    }
    $response = array();
    $response["image"] = base64_encode(file_get_contents($file_path));
    $response["Success"] = "true";
  }else{
    //No Image Exists for specific User. Display Default Image
    $response["Success"] = "false";
  }

  echo json_encode($response,JSON_PRETTY_PRINT);

}


mysqli_close($conn);
 ?>
