<?php

require 'ConnectDB.php';

header('Content-type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

$contents = file_get_contents('php://input');

if ($contents != null) {
    $data = json_decode($contents);
    $Acc_ID = $data->{"Acc_ID"};
    $query = "SELECT * FROM accounts INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID WHERE Acc_ID = '$Acc_ID'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $Acc_ID = '';
        $Access_Level = '';
        while ($row = mysqli_fetch_assoc($result)) {
            $Acc_ID = $row['Acc_ID'];
            $Access_Level = $row['Access_Level'];
        }

        switch ($Access_Level) {
          case 'ADMIN':
            $access_level = 'admin';
            $mysql_qry = "SELECT *
            FROM accounts
            INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID
            INNER JOIN acc_admin on accounts.Acc_ID = acc_admin.Acc_ID
            WHERE accounts.Acc_ID = '$Acc_ID'";
            break;
          case 'CASHIER':
            $access_level = 'cashier';
            $mysql_qry = "SELECT *
            FROM accounts
            INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID
            INNER JOIN acc_cashier on accounts.Acc_ID = acc_cashier.Acc_ID
            WHERE accounts.Acc_ID = '$Acc_ID'";
            break;
          case 'USER':
            $access_level = 'user';
            $mysql_qry = "SELECT *
            FROM accounts
            INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID
            INNER JOIN acc_users on accounts.Acc_ID = acc_users.Acc_ID
            WHERE accounts.Acc_ID = '$Acc_ID'";
            break;
          default:
            die("An Error Occured");
            break;
        }

        $result = mysqli_query($conn, $mysql_qry);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        }
        $response = array();
        $response['Success'] = true;
        $response['Account_Details'] = $row;

        $query = "SELECT Image_Path FROM acc_images WHERE Acc_ID = '$Acc_ID'";
        $result = mysqli_query($conn,$query);

        if (mysqli_num_rows($result) == 1) {
          while ($row = mysqli_fetch_assoc($result)) {
            $file_path = $row["Image_Path"];
          }

          $response["file_path"] =  $file_path;
          $file_path = "https://requench-rest.herokuapp.com" . $file_path;
          $response["image"] = base64_encode(file_get_contents($file_path));
          $response["Success"] = "true";
        }else{
          //No Image Exists for specific User. Display Default Image
          $response["Success"] = "false";
        }



        $json_string = json_encode($response, JSON_PRETTY_PRINT);
        echo $json_string;
    }
}

mysqli_close($conn);
