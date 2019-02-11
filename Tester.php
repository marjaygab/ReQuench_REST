<?php


require 'ConnectDB.php';


 header('Content-Type: application/json');
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Credentials: true");
 header("Access-Control-Max-Age: 1000");
 header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
 header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

 


 $contents = file_get_contents('php://input');








if($contents != null){


     $data = json_decode($contents);


     $id = $data->{"Acc_ID"};


  $query = "SELECT accounts.Acc_ID,acc_levels.Access_Level FROM accounts INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID WHERE Acc_ID='$id'";


  $result = mysqli_query($conn,$query);


  $rows = array();


  if (mysqli_num_rows($result) > 0) {


    while ($r = mysqli_fetch_assoc($result)) {


      // code...


      $rows[] = $r;


    }


    // code...


    echo json_encode($rows);


  }


  mysqli_close($conn);


}

















 ?>


