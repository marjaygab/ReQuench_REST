<?php
require 'ConnectDB.php';
require 'API_Key_Checker.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
function saveTransaction($conn,$Account_Type,$ID,$MU_ID,$Date,$Time,$Amount,$Temperature,$Price_Computed,$WL_Before,$WL_After,$Remaining_Balance)
{
    if ($Account_Type == 'Recorded') {
        $query = "INSERT INTO transaction_history (Acc_ID, MU_ID, Date, Time, Amount, Temperature, Price_Computed, Water_Level_Before, Water_Level_After, Remaining_Balance) VALUES ".
        "('$ID','$MU_ID','$Date','$Time','$Amount','$Temperature','$Price_Computed','$WL_Before','$WL_After','$Remaining_Balance')";
    } else {
        $query = "INSERT INTO transaction_history_unrec (UU_ID, MU_ID, Date, Time, Amount, Temperature, Price_Computed, Water_Level_Before, Water_Level_After, Remaining_Balance) VALUES ".
        "('$ID','$MU_ID','$Date','$Time','$Amount','$Temperature','$Price_Computed','$WL_Before','$WL_After','$Remaining_Balance')";
    }
    
    if (mysqli_query($conn,$query)) {
        return true;
    } else {
        return false;
    }
    
}

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

function updateWaterLevel($conn,$MU_ID,$Current_Water_Level)
{
    $query = "UPDATE machine_unit SET Current_Water_Level = '$Current_Water_Level'";
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
    $api_key = $data -> {"API_KEY"};
    $api_check = checkApi($conn,$api_key);
    $transaction_data = $data-> {'Transaction'};
    $MU_ID = $api_check['MU_ID'];
    if($api_check['Success']){
        $Account_Type = $data -> {"Account_Type"};
        if ($Account_Type == 'Recorded') {
            $Acc_ID = $data -> {"Acc_ID"};
            foreach ($transaction_data as $key => $transaction) {
                $Date = $transaction -> {"Date"};
                $Time = $transaction -> {"Time"};
                $Amount = $transaction -> {"Amount"};
                $Temperature = $transaction -> {"Temperature"};
                $Price_Computed = $transaction -> {"Price_Computed"};
                $WL_Before = $transaction -> {"Water_Level_Before"};
                $WL_After = $transaction -> {"Water_Level_After"};
                $Remaining_Balance = $transaction -> {"Remaining_Balance"};
                if (saveTransaction($conn,$Account_Type,$Acc_ID,$MU_ID,$Date,$Time,$Amount,$Temperature,$Price_Computed,$WL_Before,$WL_After,$Remaining_Balance)) {
                    if ($key == sizeof($transaction_data)-1 && updateBalanceRecorded($conn,$Acc_ID,$Remaining_Balance)) {
                        if (updateWaterLevel($conn,$MU_ID,$WL_After)) {
                            $response['Success'] = true;    
                        } else {
                            $response['Success'] = false;    
                        }
                    } else {
                        $response['Success'] = false;
                    }
                    $response['Success'] = true;
                }else{
                    $response['Success'] = false;
                }
            }
            // var_dump($transaction_data);
        } else if($Account_Type == 'Unrecorded'){
            $UU_ID = $data -> {"UU_ID"};
            foreach ($transaction_data as $key => $transaction) {
                $Date = $transaction -> {"Date"};
                $Time = $transaction -> {"Time"};
                $Amount = $transaction -> {"Amount"};
                $Temperature = $transaction -> {"Temperature"};
                $Price_Computed = $transaction -> {"Price_Computed"};
                $WL_Before = $transaction -> {"Water_Level_Before"};
                $WL_After = $transaction -> {"Water_Level_After"};
                $Remaining_Balance = $transaction -> {"Remaining_Balance"};
                if (saveTransaction($conn,$Account_Type,$UU_ID,$MU_ID,$Date,$Time,$Amount,$Temperature,$Price_Computed,$WL_Before,$WL_After,$Remaining_Balance)) {
                    if ($key == sizeof($transaction_data)-1 && updateBalanceUnrecorded($conn,$UU_ID,$Remaining_Balance)) {
                        if (updateWaterLevel($conn,$MU_ID,$WL_After)) {
                            $response['Success'] = true;    
                        } else {
                            $response['Success'] = false;    
                        }
                    } else {
                        $response['Success'] = false;
                    }
                }else{
                    $response['Success'] = false;
                }
            }
        }else{
            $response['Success'] = false; 
        }
    }else{
        $response['Success'] = false;
    }
} else {
    $response['Success'] = false;    
}

echo json_encode($response);
mysqli_close($conn)
?>