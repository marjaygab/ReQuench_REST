<?php


require 'ConnectDB.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");


function upload($conn,$account_id,$image_string,$file_name)
{


  $relative_path = "/user_images/".$file_name;
  $file_path = __DIR__ . $relative_path;
  $binary = base64_decode($image_string);


  header('Content-Type: application/json; charset=utf-8');


  $file = fopen($file_path,'wb');


  fwrite($file,$binary);


  fclose($file);





  $query = "SELECT * from acc_images WHERE Acc_ID = '$account_id'";





  $result = mysqli_query($conn,$query);





  if (mysqli_num_rows($result) == 1) {


    $query = "UPDATE acc_images SET Image_Path= '$relative_path' WHERE Acc_ID='$account_id'";


  }else{


    $query = "INSERT INTO acc_images (Acc_ID,Image_Path) VALUES ('$account_id','$relative_path')";


  }



  $response = array();

  if (!mysqli_query($conn,$query)) {


    throw new Exception("Error Processing Request", 1);


    $response['Update_Success'] = false;
    echo json_encode($response,JSON_PRETTY_PRINT);
  }

else{
    $response['Update_Success'] = true;
    echo json_encode($response,JSON_PRETTY_PRINT);
  }

  return;
}


$contents = file_get_contents('php://input');
if ($contents != null) {
  $data = json_decode($contents);
  $account_id = $data->{"Acc_ID"};
  $image_string = $data->{"image_string"};
  $file_name = $data->{"file_name"};
  upload($conn,$account_id,$image_string,$file_name);
}
else if (isset($_POST['Acc_ID']) && isset($_POST['image_string']) && isset($_POST['file_name'])) {
  $account_id = $_POST['Acc_ID'];
  $image_string = $_POST['image_string'];
  $file_name = $_POST['file_name'];
  upload($conn,$account_id,$image_string,$file_name);
}else{
  die("An Error Occured");
}



mysqli_close($conn);


 ?>


