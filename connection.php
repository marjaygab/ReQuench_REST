<?php

$con = mysqli_connect("localhost","id8325910_admin","admin");
if (!$con){
    die ('Could not connect:' . mysqli_error());
}
mysqli_select_db($con,'id8325910_vending_machine');
?>
