<?php
require 'ConnectDB.php';



function checkKey($conn,$Model_Number,$Secret_Key)
{
    $query = "SELECT secret_list.Secret_Key,machine_unit.Model_Number FROM secret_list
    INNER JOIN machine_unit ON machine_unit.MU_ID = secret_list.MU_ID WHERE Model_Number = '$Model_Number' AND Secret_Key = '$Secret_Key'";

    $result = mysqli_query($conn,$query);

    if (mysqli_num_rows($result) == 1) {
        //secret code can be used
        return true;
    }else{
        //invalid code
        return false;
    }
}



$contents = file_get_contents('php://input');
$response = array();
if ($contents != null) {
    $data = json_decode($contents);
    $Model_Number = $data->{"Model_Number"};
    $Secret_Key = $data->{"Secret_Key"};
    if (checkKey($conn,$Model_Number,$Secret_Key)) {
        $response['Success'] = true;
    } else {
        $response['Success'] = false;
    }
} else {
    $response['Success'] = false;
}

echo json_encode($response);
mysqli_close($conn);
?>