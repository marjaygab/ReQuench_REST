<?php
require 'ConnectDB.php';
require __DIR__ . '/vendor/autoload.php';
use Firebase\JWT\JWT;

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

$username = "";
$password = "";
// Get your service account's email address and private key from the JSON key file
$service_account_email = "firebase-adminsdk-ix063@requenchweb2019.iam.gserviceaccount.com";
$private_key = "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC46ZuDh+yefp19\nn/UXQNRUogTWAUh+m4nptZdRXmKepwjVMdGiuPX5o6HirnZhkTr8LzKjp6Rzk1co\nfboDe051nlhqTsIxAO1c6iVZ30wCTT32iQ178FixyKlzcNbTwzXQ3FtVsdCYDHZQ\nyyTbl0kY4AbptrxQoWo2+97k3hg5TwreklhguFKHRKTdqcxweFUEN/LjlugJWVVC\nY8Atp5PG6i0FqekmvX3t4jQtwBOHyHvDn9P+Bv+Yg5wv5kkmPVMakddM2RlveypH\nJ1YkdX8QFHkjZ/MW7IY9UowfSiyPo3MGkLqpgqoknUaewpVuYhkG/Yk9Z3vzZ/oS\nfgkIVYTlAgMBAAECggEAKZZkov7pgTYBzrSwhzPN7WDpDIaKWus5Z8YvC55aOMth\naa2ENAA8VhJuwNAzELt8lCY7UeJM0q+THSi1sr3DRiHASdx/wUyvlcOVdfKKIkRk\nCLQHQ9Yo5Ic4UR3SOxs+2cQNiKbuvpui6oXdusN6La2I8HxoOwwqmsYn+bpXoV+a\nG1EwXtBT4EI/2dCuDx6FDpi1URU/oKAw+g/z2Uufv3fga+7zp0XWUlAUgISRysgr\nQLjGqLgLvSVj9c/g3F2N2hKzY4bj3BwWaVcwNYKnUqpdBGSLpdny7EmsAbUFXs4k\n1+hG4GZH5xyTh6a941E1EDEFwLmUkDTDBMzm85zc+wKBgQD8/Re1Dpz8wJD5/ngT\n6IEsLBkL+ZiWyHxcgNPTB1NpeSUpscyCZnOiDuXsOK5N4EPOe4oIBP3s+xPJUq5y\nKXpaCwLtouhEwNb9FPCrDWCoFqIXpO5gcSd5AoCHSDIUuDdUBye7tKkLSHgzyt+w\nGrUmBlNE5Uj5jWf+xmWfBJjXwwKBgQC7HRK/Cl0UVvF/J5xdRdxAxFwTHH2Y6JDe\n6F09EM0eCA9MkrAsPfuoDw3PFLjOaRshD3+tDHMbXYKzgI1pDyW+HE5AUalagUJF\nZm36D3NPDaJbinvmRNyUuXNzvSdyAxPKwrSzC5UbJjrKRr8vl7kQ0SlMtiDDIj/O\nY/O8kNuONwKBgQDa4C0unCH+GqxTXXGN58455T0WOy9k5LeTYHHjVac4zXL1i7of\n53uLbdBGexNylOCVOBTHs2ntKZyIxVvfsTsFxBkYd6T0NtLJyuAdXUmOo2ZBhpQm\nJD++VgfVAwUxH9/edwJNR5QpCt3UEWVN+w1WhIpuRODJ5yleJ0+sMFH7+QKBgEqG\ntuu+ffpW8tM5fK8t3x8w1peKFoBryR/vnwtugLRAc4+FMYQ9n9l9PXvIfh9Af9y4\nJptPRR2WLjO+tRQuQ1MoRQabP//bUgEAXjdmJgBLpuodC2JY9R6Liu+DXI2tqhlt\nWbmimF366Rmd+hJDtSN8m52BQSVXo+BZsT/e1oQbAoGBALzMXX87IIoIZPELji1H\nGAS88Edsh7Ydy9Zzgvi19xQSV4/1xgJunpQrUz66qLiPyaHLfIFIeO7NyQCTHdcX\nWxLfgxmzBGfQTEOC9bduhzaKUsxKZc2yGu4eV6Rrh0lJE9PKXJ9g6yFxtTg6IqKK\ntLq9w7j//qWeHNZe6b7K0nzr\n-----END PRIVATE KEY-----\n";

function create_custom_token($uid, $is_premium_account) {
  global $service_account_email, $private_key;

  $now_seconds = time();
  $payload = array(
    "iss" => $service_account_email,
    "sub" => $service_account_email,
    "aud" => "https://identitytoolkit.googleapis.com/google.identity.identitytoolkit.v1.IdentityToolkit",
    "iat" => $now_seconds,
    "exp" => $now_seconds+(60*60),  // Maximum expiration time is one hour
    "uid" => $uid,
    "claims" => array(
      "premium_account" => $is_premium_account
    )
  );
  return JWT::encode($payload, $private_key, "RS256");
}



function verify($conn, $username, $password)
{
    // code...
    //fetch user details here
    $mysql_qry = "SELECT accounts.Acc_ID,acc_levels.Access_Level FROM accounts INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID WHERE User_Name = '$username' AND Password = '$password'";
    $result = mysqli_query($conn, $mysql_qry);
    if (!$conn) {
        die("Connection Failed" . mysqli_connect_error());
    }
    if (mysqli_num_rows($result) > 0 && mysqli_num_rows($result) < 2) {
        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $account_id = $row["Acc_ID"];
            $access_level = $row["Access_Level"];
        }
        switch ($access_level) {
            case 'ADMIN':
                $access_level = 'admin';
                $mysql_qry = "SELECT accounts.Acc_ID,accounts.User_Name,accounts.Password,accounts.Email,acc_levels.Access_Level,acc_admin.Acc_Admin_ID,acc_admin.ID_Number,acc_admin.First_Name,acc_admin.Last_Name,acc_admin.Balance
        FROM accounts
        INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID
        INNER JOIN acc_admin on accounts.Acc_ID = acc_admin.Acc_ID
        WHERE accounts.Acc_ID = '$account_id'";
                break;
            case 'CASHIER':
                $access_level = 'cashier';
                $mysql_qry = "SELECT accounts.Acc_ID,accounts.User_Name,accounts.Password,accounts.Email,acc_levels.Access_Level,acc_cashier.Acc_Cashier_ID,acc_cashier.ID_Number,acc_cashier.First_Name,acc_cashier.Last_Name,acc_cashier.Balance
        FROM accounts
        INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID
        INNER JOIN acc_cashier on accounts.Acc_ID = acc_cashier.Acc_ID
        WHERE accounts.Acc_ID = '$account_id'";
                break;
            case 'USER':
                $access_level = 'user';
                $mysql_qry = "SELECT accounts.Acc_ID,accounts.User_Name,accounts.Password,accounts.Email,acc_levels.Access_Level,acc_users.Acc_user_ID,acc_users.ID_Number,acc_users.First_Name,acc_users.Last_Name,acc_users.Balance
        FROM accounts
        INNER JOIN acc_levels ON accounts.AL_ID = acc_levels.AL_ID
        INNER JOIN acc_users on accounts.Acc_ID = acc_users.Acc_ID
        WHERE accounts.Acc_ID = '$account_id'";
                break;
            default:
                die("An Error Occured");
                break;
        }
        $result = mysqli_query($conn, $mysql_qry);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        }
        $response["Account_Details"] = $row;
        //fetch image base 64 here
        $query = "SELECT Image_Path FROM acc_images WHERE Acc_ID = '$account_id'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) == 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                $file_path = $row["Image_Path"];
            }
            $response["file_path"] = $file_path;
            $response["image"] = base64_encode(file_get_contents(__DIR__ . $file_path));
        } else {
            //No Image Exists for specific User. Display Default Image
		}
		
		if ($response['Account_Details']['Access_Level'] == 'ADMIN') {
			$response['Custom_Token'] = create_custom_token($response['Account_Details']['Acc_ID'],NULL);
		}

        $response["Success"] = "true";
        $json_string = json_encode($response, JSON_PRETTY_PRINT);
        echo $json_string;
    } else {
        $response["Success"] = "false";
        $json_string = json_encode($response, JSON_PRETTY_PRINT);
        echo $json_string;
    }
}
$contents = file_get_contents('php://input');
if ($contents != null) {
    $data = json_decode($contents);
    $username = $data->{"User_Name"};
    $password = $data->{"Password"};
    verify($conn, $username, $password);
} else if (isset($_POST['User_name']) && isset($_POST['Password'])) {
    $username = $_POST["User_Name"];
    $password = $_POST["Password"];
    verify($conn, $username, $password);
} else {
    die("An Error Occured");
}
mysqli_close($conn);