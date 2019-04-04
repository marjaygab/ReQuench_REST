<?php
require 'ConnectDB.php';
require 'API_Key_Checker.php';
require 'Generate_API_Key.php';
 header('Content-Type: application/json');
 header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
function addMachine($conn,$secret_key)
{
  // code...
  $rows = array();
  $response = array();
  $find_result = findSecretKey($conn,$secret_key);
    if($find_result != false){
        $API_KEY = renewAPIKey($conn,$secret_key);
        if ($API_KEY != NULL) {
            $result = getMachine($conn,$find_result);
            if ($result != null) {
                $response['Success'] = true;
                $response['Secret_Key'] = $secret_key;
                $response['Machine'] = $result;   
            }else{
                $response['Success'] = false;
                $response['Machine'] = null;
                $response['Secret_Key'] = $secret_key;
            }    
        } else {
            $response['Success'] = false;
        }
    }else{
        $response['Success'] = false;
    }
  echo json_encode($response,JSON_PRETTY_PRINT);
}


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
    }else{
        $API_KEY = NULL;
    }
    return $API_KEY;
}


function getMachine($conn,$MU_ID)
{
    $query = "SELECT * FROM machine_unit WHERE MU_ID = '$MU_ID'";
    $result = mysqli_query($conn,$query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row;
    }else{
        return null;
    }
}


function findSecretKey($conn,$secret_key)
{
    $query = "SELECT * FROM secret_list WHERE Secret_Key = '$secret_key'";
    $result = mysqli_query($conn,$query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $MU_ID = $row['MU_ID'];
        // $Secret_Key = $row['Secret_Key'];
        return $MU_ID;
    }else{
        return false;
    }
}

$contents = file_get_contents('php://input');
if ($contents != null) {
  $data = json_decode($contents);
  $secret_key = $data->{"Secret_Key"};
  $response = array();
  addMachine($conn,$secret_key);
}else{

  die("An Error Occured");
}

  mysqli_close($conn);

 ?>
