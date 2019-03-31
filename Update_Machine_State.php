<?php

require 'ConnectDB.php';
 header('Content-Type: application/json');
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Credentials: true");
 header("Access-Control-Max-Age: 1000");
 header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
 header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
function updateMachineState($conn,$MU_ID,$Model_Number,$API_KEY,$Machine_Location,$Date_of_Purchase,$Last_Maintenance_Date,
$Current_Water_Level,$STATUS,$Price_Per_ML,$Critical_Level,$Critical_Level,$Notify_Admin)
{
  // code...
    $rows = array();
    $response = array();
    
    $query = "UPDATE machine_unit SET Model_Number = '$Model_Number', API_KEY= '$API_KEY',
    Machine_Location = '$Machine_Location', Date_of_Purchase = '$Date_of_Purchase',Last_Maintenance_Date = '$Last_Maintenance_Date',
    Current_Water_Level = '$Current_Water_Level',STATUS = '$STATUS',Price_Per_ML='$Price_Per_ML',Critical_Level = '$Critical_Level',
    Notify_Admin = $Notify_Admin WHERE MU_ID = '$MU_ID'";

    if (mysqli_query($conn,$query)) {
        $response['Success'] = true;
    }else{
        $response['Success'] = false;
    }
    echo json_encode($response,JSON_PRETTY_PRINT);
}

$contents = file_get_contents('php://input');
if ($contents != null) {
  $data = json_decode($contents);
  $MU_ID = $data->{"mu_id"};
  $Model_Number = $data->{"Model_Number"};
  $API_KEY = $data->{"api_key"};
  $Machine_Location = $data->{"location"};
  $Date_of_Purchase = $data->{"date_of_purchase"};
  $Last_Maintenance_Date = $data->{"last_maintenance_date"};
  $Current_Water_Level = $data->{"current_water_level"};
  $STATUS = $data->{"status"};
  $Price_Per_ML = $data->{"price_per_ml"};
  $Critical_Level = $data->{"critical_level"};
  $Notify_Admin = $data->{"notify_admin"};
  updateMachineState($conn,$MU_ID,$Model_Number,$API_KEY,$Machine_Location,$Date_of_Purchase,$Last_Maintenance_Date,
  $Current_Water_Level,$STATUS,$Price_Per_ML,$Critical_Level,$Critical_Level,$Notify_Admin);
  
}else{
  die("An Error Occured");
}

  mysqli_close($conn);

 ?>
