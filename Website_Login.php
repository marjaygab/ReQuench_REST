<?php
require 'ConnectDB.php';
$contents = file_get_contents('php://input');

if ($contents != null) {
  // code...
  $data = json_decode($contents);
  $username = $data->{"User_Name"};
  $password = $data->{"Password"};

  $SQL = "SELECT Acc_ID,Access_Level FROM accounts WHERE User_Name = '$username' AND Password = '$password' AND AL_ID='1'";
  $result = mysqli_query($conn, $SQL);
  $data = mysqli_fetch_assoc($result);


  $SQL2 = "SELECT Acc_ID,Access_Level FROM accounts WHERE User_Name = '$username' AND Password = '$password' AND AL_ID='2'";
  $result2 = mysqli_query($conn,$SQL2);
  $data2= mysqli_fetch_assoc($result2);

  $SQL3 = "SELECT Acc_ID,Access_Level FROM accounts WHERE User_Name = '$username' AND Password = '$password' AND AL_ID='3'";
  $result3 = mysqli_query($conn,$SQL3);
  $data3= mysqli_fetch_assoc($result3);

  //verifylogin.php
  // if (($username == $data['User_Name'])&& ($password==$data['Password'])  ){
  //   $_SESSION['User_Name'] = $data['User_Name'];
  //   $_SESSION['Password'] = $data['Password'];
  //   //header('location:http://localhost/ReQuench/Admin.php?option=home');
  // }
  // else if ($username == $data2['User_Name']&& ($password==$data2['Password'])  ){
  //   $_SESSION['User_Name'] = $data2['User_Name'];
  //   $_SESSION['Password'] = $data2['Password'];
  //     //header('location:http://localhost/ReQuench/Cashier.php?option=scan');
  // }
  // else if ($username == $data3['User_Name']&& ($password==$data3['Password'])  ){
  //   $_SESSION['User_Name'] = $data3['User_Name'];
  //   $_SESSION['Password'] = $data3['Password'];
  //     //header('location:http://localhost/ReQuench/User.php?option=home');
  // }
  // else {
  //     // header('location:index.php');
  //
  // }

  $response = array();
  if (mysqli_num_rows($result)) {
    // code...
  }


}


mysqli_close($conn);
 ?>
