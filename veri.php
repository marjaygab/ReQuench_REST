<html>
    <head>
        <meta charset="UTF-8">
        <title>ReQuench</title>
    </head>
</html>

<?php
session_start();
require 'ConnectDB.php';
$un = $_POST[('User_Name')];
$pw = $_POST[('Password')];

$SQL = "SELECT * FROM accounts WHERE User_Name = '$un' AND Password = '$pw' AND AL_ID='1'";
$result = mysqli_query($conn, $SQL);
$data = mysqli_fetch_assoc($result);


$SQL2 = "SELECT * FROM accounts WHERE User_Name = '$un' AND Password = '$pw'AND AL_ID='2'";
$result2 = mysqli_query($conn,$SQL2);
$data2= mysqli_fetch_assoc($result2);

$SQL3 = "SELECT * FROM accounts WHERE User_Name = '$un' AND Password = '$pw'AND AL_ID='3'";
$result3 = mysqli_query($conn,$SQL3);
$data3= mysqli_fetch_assoc($result3);

//verifylogin.php
if (($un == $data['User_Name'])&& ($pw==$data['Password'])  ){
  $_SESSION['User_Name'] = $data['User_Name'];
  $_SESSION['Password'] = $data['Password'];
  header('location:http://localhost/ReQuench/Admin.php?option=home');
}
else if ($un == $data2['User_Name']&& ($pw==$data2['Password'])  ){
  $_SESSION['User_Name'] = $data2['User_Name'];
  $_SESSION['Password'] = $data2['Password'];
    header('location:http://localhost/ReQuench/Cashier.php?option=scan');
}
else if ($un == $data3['User_Name']&& ($pw==$data3['Password'])  ){
  $_SESSION['User_Name'] = $data3['User_Name'];
  $_SESSION['Password'] = $data3['Password'];
    header('location:http://localhost/ReQuench/User.php?option=home');
}
else {
    // header('location:index.php');
    var_dump($data);
    echo "</br>";
    var_dump($data2);
    echo "</br>";
    var_dump($data3);
}

?>
