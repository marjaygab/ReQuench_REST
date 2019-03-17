<?php
require 'ConnectDB.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
function addMachine($conn,$model_num,$machine_loc,$date_of_purchase,$secret_key)
{
    $date = new DateTime($date_of_purchase);
    $formatted_string = $date->format('Y-m-d');
    $query = "INSERT INTO machine_unit (Model_Number,Machine_Location,Date_of_Purchase,Last_Maintenance_Date)".
    " VALUES ('$model_num','$machine_loc','$formatted_string','$formatted_string')";

    if (mysqli_query($conn,$query)) {
        $last_insert_id = mysqli_insert_id($conn);
        $query = "INSERT INTO secret_list (MU_ID,Secret_Key) VALUES ('$last_insert_id','$secret_key')";
        if (mysqli_query($conn,$query)) {
            return true;
        } else {
            return false;
        }
    }else{
        return false;
    }
}

$contents = file_get_contents('php://input');
if ($contents != null) {
    $data = json_decode($contents);
    $model_num = $data->{"Model_Number"};
    $machine_loc = $data->{"Machine_Location"};
    $date_of_purchase = $data->{"Date_of_Purchase"};
    $secret_key = $data->{"Secret_Key"};
    $response = array();
    if (addMachine($conn,$model_num,$machine_loc,$date_of_purchase,$secret_key)) {
        $response['Success'] = true;
    }else{
        $response['Success'] = false;
    }
}else{
  die("An Error Occured");
}
echo json_encode($response);
mysqli_close($conn);

 ?>
