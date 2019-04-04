<?php


require 'ConnectDB.php';
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
        $result = getMachine($conn,$find_result);
        if ($result != null) {
            $response['Success'] = true;
            $response['Machine'] = $result;
        }
    }else{
        $response['Success'] = false;
    }
  echo json_encode($response,JSON_PRETTY_PRINT);
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
