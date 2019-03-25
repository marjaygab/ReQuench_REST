<?php
require 'ConnectDB.php';
require 'API_Key_Checker.php';
require 'Generate_API_Key.php';



function renewAPIKey($conn,$Secret_Key)
{
    $response = array();
    $query = "SELECT MU_ID FROM secret_list WHERE Secret_Key = '$Secret_Key'";
    $result = mysqli_query($conn,$query);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $MU_ID = $row['MU_ID'];

        $API_KEY = generateKey();
        while(true){
            $API_KEY = generateKey();
            if (!checkApi($conn,$API_KEY)['Success']) {
                $query = "UPDATE machine_unit SET API_KEY = '$API_KEY' WHERE MU_ID = $MU_ID";
                if(mysqli_query($conn,$query)){
                    break;
                };
            }
        }
        $response['Success'] = true;
        $response['API_KEY'] = $API_KEY;
    }else{
        $response['Success'] = false;
        $response['API_KEY'] = NULL;
    }
    return $response;
}



$contents = file_get_contents('php://input');
$response = array();
if ($contents != null) {
    $data = json_decode($contents);
    $Secret_Key = $data->{"Secret_Key"};
    $results = renewAPIKey($conn,$Secret_Key);
    if ($results['Success']) {
        $response['Success'] = true;
        $response['API_KEY'] = $results['API_KEY'];
    } else {
        $response['Success'] = false;
    }
} else {
    $response['Success'] = false;
}

echo json_encode($response);
mysqli_close($conn);
?>