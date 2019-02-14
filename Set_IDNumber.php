<?php
    require 'ConnectDB.php';
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Max-Age: 1000");
    header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
    header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

    function setID($conn,$UU_ID,$ID_Number)
    {
        $query = "UPDATE unrecorded_users SET ID_NUmber = '$ID_Number' WHERE UU_ID = '$UU_ID'";
        if (mysqli_query($conn,$query)) {
            return true;
        }else{
            return false;
        }
    }
    $contents = file_get_contents('php://input');
    $response = array();
    if ($contents != NULL) {
        $data = json_decode($contents);
        $UU_ID = $data -> {"UU_ID"};
        $ID_Number = $data -> {"ID_Number"};
        if (setID($conn,$UU_ID,$ID_Number)) {
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