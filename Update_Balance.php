<?php
    require 'ConnectDB.php';
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Max-Age: 1000");
    header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
    header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");


    function updateBalanceUnrecorded($conn,$UU_ID,$Load)
    {
        $query = "UPDATE unrecorded_users SET Balance = '$Load' WHERE UU_ID = '$UU_ID'";
        if (mysqli_query($conn,$query)) {
            return true;
        }else{
            return false;
        }
    }

    function updateBalanceRecorded($conn,$Acc_ID,$Load)
    {
        $query = "SELECT acc_levels.Access_Level FROM accounts INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID WHERE accounts.Acc_ID = '$Acc_ID'";
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
        }
        $query = "UPDATE $access_level SET Balance = '$Load' WHERE Acc_ID = '$Acc_ID'";
        if (mysqli_query($conn,$query)) {
            return true;
        }else{
            return false;
        }
    }

    function getCurrentUnrecordedBalance($conn,$UU_ID)
    {
        $query = "SELECT Balance FROM unrecorded_users WHERE UU_ID = '$UU_ID'";
        $result = mysqli_query($conn,$query);
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            return $row['Balance'];
        }else{
            //error occured
        }
    }


    function getCurrentRecordedBalance($conn,$Acc_ID)
    {
        $query = "SELECT acc_levels.Access_Level FROM accounts INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID WHERE accounts.Acc_ID = '$Acc_ID'";
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
            
            $query = "SELECT Balance FROM $access_level WHERE Acc_ID = '$Acc_ID'";
            $results = mysqli_query($conn,$query);
            $current_balance = 0;
            if (mysqli_num_rows($results) == 1) {
                $result_row = mysqli_fetch_assoc($results);
                $current_balance =(int) $result_row['Balance'];
            }else{
                //duplicate or non detected
            }

            return $current_balance;
        } else if (mysqli_num_rows($result) > 1) {
            //error occured. Duplicate account detected
            return false;
        }else{
            return false;
        }
    }


    function newPurchaseHistory($conn,$Acc_ID,$Amount,$Price)
    {
        $dt = new DateTime();
        $time = $dt->format("H:i:s");
        $date = $dt->format("Y-m-d");
        $query = "INSERT INTO purchase_history (Acc_ID,Amount,Price_Computed,Time,Date) VALUES ('$Acc_ID','$Amount','$Price','$time','$date')";
        if (mysqli_query($conn,$query)) {
            return true;
        } else {
            return false;
        }
        
    }

    function newPurchaseHistoryUnrec($conn,$UU_ID,$Amount,$Price)
    {
        $dt = new DateTime();
        $time = $dt->format("H:i:s");
        $date = $dt->format("Y-m-d");
        $query = "INSERT INTO purchase_history_unrec (UU_ID,Amount,Price_Computed,Time,Date) VALUES ('$UU_ID','$Amount','$Price','$time','$date')";
        if (mysqli_query($conn,$query)) {
            return true;
        } else {
            return false;
        }
        
    }

    date_default_timezone_set('Asia/Manila');
    $contents = file_get_contents('php://input');
    $response = array();
    if ($contents != NULL) {
        $data = json_decode($contents);
        $Account_Type = $data -> {"Account_Type"};
        $load_string = $data -> {"Load"};
        $price_string = $data -> {"Price"};
        $price = (int) $price_string;
        $load = (int) $load_string;
        if ($Account_Type == 'Recorded') {
            $Acc_ID = $data -> {"Acc_ID"};
            $current_balance = getCurrentRecordedBalance($conn,$Acc_ID);
            $current_balance = $current_balance + $load;
            if (updateBalanceRecorded($conn,$Acc_ID,$current_balance) && newPurchaseHistory($conn,$Acc_ID,$load,$price)) {
                $response['Success'] = true;
            }
            else{
                $response['Success'] = false;
            }
        } else if($Account_Type == 'Unrecorded'){
            $UU_ID = $data -> {"UU_ID"};
            $current_balance = getCurrentUnrecordedBalance($conn,$UU_ID);
            $current_balance = $current_balance + $load;
            if (updateBalanceUnrecorded($conn,$UU_ID,$current_balance) && newPurchaseHistoryUnrec($conn,$UU_ID,$load,$price)) {
                $response['Success'] = true;
            } else {
                $response['Success'] = false;
            }
            
        }else{
            $response['Success'] = false;
        }
    } else {
        $response['Success'] = false;    
    }
    echo json_encode($response);
    mysqli_close($conn);
?>