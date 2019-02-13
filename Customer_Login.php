<?php
    require 'ConnectDB.php';
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Max-Age: 1000");
    header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
    header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

    function checkAcc($conn,$RFID_ID)
    {
        $query = "SELECT * FROM accounts WHERE RFID_ID = '$RFID_ID'";
        $result = mysqli_query($conn,$query);
        $response = array();
        if (mysqli_num_rows($result) == 1) {
            $response['Success'] = true;
            $row = mysqli_fetch_assoc($result);
            $response['Acc_ID'] = $row['Acc_ID'];
        } else if (mysqli_num_rows($result) > 1) {
            //error occured. Duplicate account detected
            $response['Success'] = false;
        }else{
            $response['Success'] = false;
        }
        return $response;
    }

    function checkUnrec($conn,$RFID_ID)
    {
        $query = "SELECT * FROM unrecorded_users WHERE RFID_ID = '$RFID_ID'";
        $result = mysqli_query($conn,$query);
        $response = array();
        if (mysqli_num_rows($result) == 1) {
            $response['Success'] = true;
            $row = mysqli_fetch_assoc($result);
            $response['UU_ID'] = $row['UU_ID'];
        } else if (mysqli_num_rows($result) > 1) {
            //error occured. Duplicate account detected
            $response['Success'] = false;
        }else{
            $response['Success'] = false;
        }
        return $response;
    }
    function getAcc($conn,$Acc_ID)
    {
        $query = "SELECT * FROM accounts WHERE Acc_ID = '$Acc_ID'";
        $result = mysqli_query($conn,$query);
        $row = array();
        if (mysqli_num_rows($result) == 1) {
            while($r = mysqli_fetch_assoc($result)){
                $row[] = $r;
            }
            return $row;
        } else if (mysqli_num_rows($result) > 1) {
            //error occured. Duplicate account detected
            return false;
        }else{
            return false;
        }
    }

    function getUnrec($conn,$UU_ID)
    {
        $query = "SELECT * FROM unrecorded_users WHERE UU_ID = '$UU_ID'";
        $result = mysqli_query($conn,$query);
        $row = array();
        if (mysqli_num_rows($result) == 1) {
            while($r = mysqli_fetch_assoc($result)){
                $row[] = $r;
            }
            return $row;
        } else if (mysqli_num_rows($result) > 1) {
            //error occured. Duplicate account detected
            return false;
        }else{
            return false;
        }
    }

    function createUnrec($conn,$RFID_ID)
    {
        $Balance = 0;
        $query = "INSERT INTO unrecorded_users (RFID_ID,Balance) VALUES ('$RFID_ID','$Balance')";
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

        $RFID_ID = $data -> {"RFID_ID"};

        $rfid_result = checkAcc($conn,$RFID_ID);
        $unrec_result = checkUnrec($conn,$RFID_ID);

        if ($unrec_result['Success']) {
            $response['Success'] = true;
            $response['Account'] = getUnrec($conn,$rfid_result['UU_ID']);
        } else if ($rfid_result['Success']) {
            $response['Success'] = true;
            $response['Account'] = getAcc($conn,$rfid_result['Acc_ID']);
        }else {
            if (createUnrec($conn,$RFID_ID)) {
                $response['Success'] = true;
            } else {
                $response['Success'] = false;
            }
        }
    } else {
        $response['Success'] = false;    
    }
    
    echo json_encode($response);
    mysqli_close($conn);
?>