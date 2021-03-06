<?php
require 'ConnectDB.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
function updateTable($conn, $response, $command, $data)
{       
    $account_id = $data->{"Acc_ID"};
    $Access_Level = findAccLevel($conn,$account_id);

    if($Access_Level == 'ADMIN'){
        $table_name = 'acc_admin';
    }else if($Access_Level == 'CASHIER'){
        $table_name = 'acc_cashier';
    }else{
        $table_name = 'acc_users';
    }

    switch ($command) {
        case 'accounts':
            $account_id = $data->{"Acc_ID"};
            $id_number = $data->{"ID_Number"};
            $firstname = $data->{"First_Name"};
            $lastname = $data->{"Last_Name"};
            $user_name = $data->{"User_Name"};
            $query = "UPDATE accounts INNER JOIN {$table_name} 
            ON accounts.Acc_ID = {$table_name}.Acc_ID
            SET accounts.User_Name = '$user_name' ,{$table_name}.ID_Number = '$id_number'
            ,{$table_name}.First_Name = '$firstname' ,{$table_name}.Last_Name = '$lastname'
            WHERE accounts.Acc_ID = $account_id";
            break;
        case 'email':
            $account_id = $data->{"Acc_ID"};
            $email = $data->{"Email"};
            $query = "UPDATE accounts
      SET accounts.Email = '$email'
      WHERE accounts.Acc_ID = $account_id";
            break;
        case 'phone':
            $account_id = $data->{"Acc_ID"};
            $phone = $data->{"Phone"};
            $query = "UPDATE {$table_name}
      SET {$table_name}.Phone = '$phone'
      WHERE {$table_name}.Acc_ID = $account_id";
            break;
        case 'password':
            $account_id = $data->{"Acc_ID"};
            $new_password = $data->{"New_Password"};
            $old_password = $data->{"Old_Password"};
            $my_query = "SELECT Password FROM accounts WHERE Acc_ID = $account_id AND Password='$old_password'";
            $result = mysqli_query($conn, $my_query);
            if (mysqli_num_rows($result) == 1) {
                $query = "UPDATE accounts
        SET accounts.Password = '$new_password'
        WHERE accounts.Acc_ID = $account_id";
            } else {
                $response['Update_Success'] = false;
                $response['Error'] = 'Mismatch';
                echo json_encode($response, JSON_PRETTY_PRINT);
                return;
            }
            break;
        case 'accpass':
            $account_id = $data->{"Acc_ID"};
            $id_number = $data->{"ID_Number"};
            $firstname = $data->{"First_Name"};
            $lastname = $data->{"Last_Name"};
            $user_name = $data->{"User_Name"};
            $old_password = $data->{"Old_Password"};
            $new_password = $data->{"New_Password"};
            $my_query = "SELECT Password FROM accounts WHERE Acc_ID = $account_id AND Password='$old_password'";
            $result = mysqli_query($conn, $my_query);
            if (mysqli_num_rows($result) == 1) {
                $query = "UPDATE accounts INNER
                JOIN {$table_name} ON accounts.Acc_ID = {$table_name}.Acc_ID
                SET {$table_name}.First_Name = '$firstname',{$table_name}.Last_Name = '$lastname',accounts.User_Name = '$user_name',accounts.Password = '$new_password',{$table_name}.ID_Number = '$id_number'
                WHERE accounts.Acc_ID = $account_id";
            } else {
                $response['Update_Success'] = false;
                $response['Error'] = 'AccPassError';
                echo json_encode($response, JSON_PRETTY_PRINT);
                return;
            }
            break;
        case 'all':
            $account_id = $data->{"Acc_ID"};
            $id_number = $data->{"ID_Number"};
            $rfid_id = $data->{"RFID_ID"};
            $firstname = $data->{"First_Name"};
            $lastname = $data->{"Last_Name"};
            $user_name = $data->{"User_Name"};
            $new_password = $data->{"Password"};
            $email = $data ->{"Email"};
            $access_level = $data ->{"Access_Level"};
            $balance = $data ->{"Balance"};

            if ($Access_Level == $access_level) {
                //just update
                $query = "UPDATE accounts 
                INNER JOIN {$table_name} ON accounts.Acc_ID = {$table_name}.Acc_ID
                SET 
                {$table_name}.First_Name = '$firstname',
                {$table_name}.Last_Name = '$lastname',
                accounts.User_Name = '$user_name',
                accounts.Password = '$new_password',
                {$table_name}.ID_Number = '$id_number',
                accounts.RFID_ID = '$rfid_id',
                accounts.Email = '$email',
                {$table_name}.Balance = '$balance'
                WHERE accounts.Acc_ID = $account_id";
            }else{
                //transfer table
                if($access_level == 'ADMIN'){
                    $new_table = 'acc_admin';
                    $access_id = 1;
                }else if ($access_level == 'CASHIER') {
                    $new_table = 'acc_cashier';
                    $access_id = 2;
                }else{
                    $new_table = 'acc_users';
                    $access_id = 3;
                }

                $query = "INSERT INTO {$new_table} (Acc_ID,ID_Number,First_Name,Last_Name,Balance)
                VALUES ('$account_id','$id_number','$firstname','$lastname','$balance')";
                if (mysqli_query($conn,$query)) {
                    $query = "DELETE FROM {$table_name} WHERE Acc_ID= '$account_id'";
                    if (mysqli_query($conn,$query)) {
                        $query = "UPDATE accounts SET AL_ID = '$access_id' WHERE Acc_ID='$account_id'";
                    }else {
                        $query = '';
                    }
                }else{
                    $query = '';
                }
            }
            


            break;
        default:
            // code...
            break;
    }

    if (mysqli_query($conn, $query)) {
        $response['Update_Success'] = true;
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        $response['Update_Success'] = false;
        echo json_encode($response, JSON_PRETTY_PRINT);
    }

}


function findAccLevel($conn,$Acc_ID)
{
    $query = "SELECT AL_ID FROM accounts WHERE Acc_ID = '$Acc_ID'";
    $results = mysqli_query($conn,$query);

    if (mysqli_num_rows($results) > 0) {
        $row = mysqli_fetch_assoc($results);
        $AL_ID = $row['AL_ID'];

        if ($AL_ID == 1) {
            return 'ADMIN';
        } else if($AL_ID == 2){
            return 'CASHIER';
        }else {
            return 'USER';
        }
        
    }
}

$contents = file_get_contents('php://input');
$response = array();
if ($contents != null) {
    $data = json_decode($contents);
    $command = $data->{"Command"};
    updateTable($conn, $response, $command, $data);
}
// else if () {
//   $username = $_POST["User_Name"];
//   $password = $_POST["Password"];
//   verify($conn,$username,$password);
// }
else {
    $response['Update_Success'] = false;
    echo json_encode($response, JSON_PRETTY_PRINT);
    die("An Error Occured");
}

mysqli_close($conn);
