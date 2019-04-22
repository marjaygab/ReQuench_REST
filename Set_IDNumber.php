<?php
    require 'ConnectDB.php';
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Max-Age: 1000");
    header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
    header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

    function setID($conn,$UU_ID,$ID_Number)
    {
        $query = "UPDATE unrecorded_users SET ID_Number = '$ID_Number' WHERE UU_ID = '$UU_ID'";
        if (mysqli_query($conn,$query)) {
            return true;
        }else{
            return false;
        }
    }

    function checkAccIDNum($conn,$RFID_ID)
    {
        // $query = "SELECT * FROM accounts WHERE ID_Number = '$RFID_ID'";
        $query1 = "SELECT * from acc_users WHERE ID_Number = '$RFID_ID'";
        $query2 = "SELECT * from acc_admin WHERE ID_Number = '$RFID_ID'";
        $query3 = "SELECT * from acc_cashier WHERE ID_Number = '$RFID_ID'";

        $result1 = mysqli_query($conn, $query1);
        $result2 = mysqli_query($conn, $query2);
        $result3 = mysqli_query($conn, $query3);
        
        $response = array();
        if (mysqli_num_rows($result1) == 1) {
            $response['Success'] = true;
            $row = mysqli_fetch_assoc($result1);
            $response['ID_Number'] = $row['ID_Number'];
            $response['Acc_ID'] = $row['Acc_ID'];
        } else if (mysqli_num_rows($result2) == 1) {
            //error occured. Duplicate account detected
            $response['Success'] = true;
            $row = mysqli_fetch_assoc($result2);
            $response['ID_Number'] = $row['ID_Number'];
        } else if (mysqli_num_rows($result3) == 1) {
            $response['Success'] = true;
            $row = mysqli_fetch_assoc($result3);
            $response['ID_Number'] = $row['ID_Number'];
        }else{
            $response['Success'] = false;
        }
        return $response;
    }

    function checkUnrecIDNum($conn,$RFID_ID)
    {
        $query = "SELECT * FROM unrecorded_users WHERE ID_Number = '$RFID_ID'";
        $result = mysqli_query($conn, $query);
        $response = array();
        if (mysqli_num_rows($result) == 1) {
            $response['Success'] = true;
            $row = mysqli_fetch_assoc($result);
            $response['UU_ID'] = $row['UU_ID'];
        } else if (mysqli_num_rows($result) > 1) {
            //error occured. Duplicate account detected
            $response['Success'] = false;
        } else {
            $response['Success'] = false;
        }
        return $response;
    }
        

    function deleteUnrec($conn,$UU_ID)
    {
        $query = "DELETE FROM unrecorded_users WHERE UU_ID = $UU_ID";
        if (mysqli_query($conn,$query)) {
            return true;
        } else {
            return false;
        }
        
    }

    function updateRFID($conn,$RFID_ID,$Acc_ID)
    {
        $query = "UPDATE accounts SET RFID_ID = '$RFID_ID' WHERE Acc_ID = $Acc_ID";
        if (mysqli_query($conn,$query)) {
            return true;
        } else {
            return false;
        }
    }




    $contents = file_get_contents('php://input');
    $response = array();
    if ($contents != NULL) {
        $data = json_decode($contents);
        $UU_ID = $data -> {"UU_ID"};
        $ID_Number = $data -> {"ID_Number"};
        $RFID_ID = $data->{"RFID_ID"};
        $id_num_result = checkAccIDNum($conn,$ID_Number);
        
        if ($id_num_result['Success']) {
            $Acc_ID = $id_num_result['Acc_ID'];
            if (deleteUnrec($conn,$UU_ID) && updateRFID($conn,$RFID_ID,$Acc_ID)) {
                $response['Success'] = true;
                $response['ID_Number'] = $id_num_result['ID_Number'];    
                $response['Account_Type'] = 'Recorded';
            } else {
                $response['Success'] = false;    
            }
        }else if (setID($conn,$UU_ID,$ID_Number)) {
            $response['Success'] = true;
            $response['ID_Number'] = $ID_Number;
            $response['Account_Type'] = 'Unrecorded';
        } else {
            $response['Success'] = false;
        }   
    } else {
        $response['Success'] = false;    
    }
    echo json_encode($response);
    mysqli_close($conn);
?>