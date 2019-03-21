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
    switch ($command) {
        case 'accounts':
            $account_id = $data->{"Acc_ID"};
            $id_number = $data->{"ID_Number"};
            $firstname = $data->{"First_Name"};
            $lastname = $data->{"Last_Name"};
            $user_name = $data->{"User_Name"};
            $query = "UPDATE accounts INNER JOIN 
            acc_users ON accounts.Acc_ID = acc_users.Acc_ID
            SET accounts.User_Name = '$user_name',acc_users.ID_Number = '$id_number'
            ,acc_users.First_Name = '$firstname' ,acc_users.Last_Name = '$lastname'
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
            $query = "UPDATE acc_users
      SET acc_users.Phone = '$phone'
      WHERE acc_users.Acc_ID = $account_id";
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
            $new_password = $data->{"New_Password"};
            $old_password = $data->{"Old_Password"};
            $id_number = $data->{"ID_Number"};
            $firstname = $data->{"First_Name"};
            $lastname = $data->{"Last_Name"};
            $user_name = $data->{"User_Name"};
            $my_query = "SELECT Password FROM accounts WHERE Acc_ID = $account_id AND Password='$old_password'";
            $result = mysqli_query($conn, $my_query);
            if (mysqli_num_rows($result) == 1) {
                $query = "UPDATE accounts INNER
                JOIN acc_users ON accounts.Acc_ID = acc_users.Acc_ID
                SET acc_users.First_Name = '$firstname',acc_users.Last_Name = '$lastname',accounts.User_Name = '$user_name',accounts.Password = '$old_password',acc_users.ID_Number = '$id_number'
                WHERE accounts.Acc_ID = $account_id";
            } else {
                $response['Update_Success'] = false;
                $response['Error'] = 'AccPassError';
                echo json_encode($response, JSON_PRETTY_PRINT);
                return;
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
