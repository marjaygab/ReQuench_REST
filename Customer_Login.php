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
        $query = "SELECT accounts.Acc_ID,acc_levels.Access_Level,accounts.RFID_ID FROM accounts INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID WHERE accounts.Acc_ID = '$Acc_ID'";
        $result = mysqli_query($conn,$query);
        $row = array();
        if (mysqli_num_rows($result) == 1) {
            while($r = mysqli_fetch_assoc($result)){
                $row[] = $r;
            }
            $access_level = '';
            switch ($row[0]['Access_Level']) {
                case 'ADMIN':
                    $access_level = 'acc_admin';
                break;
                case 'USER':
                    $access_level = 'acc_users';
                break;
                case 'CASHIER':
                    $access_level = 'acc_cashier';
                break;
                default:
                break;
            }
            
            $query = "SELECT ID_Number,Balance FROM $access_level WHERE Acc_ID = '$Acc_ID'";
            $results = mysqli_query($conn,$query);
            if (mysqli_num_rows($results) == 1) {
                $result_row = mysqli_fetch_assoc($results);
                $row[0]['ID_Number'] = $result_row['ID_Number'];
                $row[0]['Balance'] = $result_row['Balance'];
            }else{
                //duplicate or non detected
            }

            return $row[0];
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
            return $row[0];
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
            return mysqli_insert_id($conn);
        }else{
            return NULL;
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
            $response['Account'] = getUnrec($conn,$unrec_result['UU_ID']);
            $response['Account_Type'] = 'Unrecorded';
        } else if ($rfid_result['Success']) {
            $response['Success'] = true;
            $response['Account'] = getAcc($conn,$rfid_result['Acc_ID']);
            $response['Account_Type'] = 'Recorded';
        }else {
            $insert_id = createUnrec($conn,$RFID_ID);
            if ($insert_id != NULL) {
                $response['Success'] = true;
                $response['Account_Type'] = 'Inserted_Unrecorded';
                $response['Insert_ID'] = $insert_id;
                $response['Balance'] = 0;
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