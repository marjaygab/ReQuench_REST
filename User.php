
<html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="assets/css/styles.css" />
<link rel="stylesheet" href="assets/css/main.css">
<head>
  <title>ReQuench</title>
  <div class="topnav" id="myTopnav">
    <a href="http://localhost/ReQuench/User.php?option=home" class="active">Home</a>
    <a href="http://localhost/ReQuench/index.php" class="rightnav">Logout <img src="assets/images/sign-out.png" style="width:20px;"></a>
    <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
  </div>
  <script>
  let data = sessionStorage.getItem('JSON_Response');
  console.log(data);
  function myFunction() {
      var x = document.getElementById("myTopnav");
      if (x.className === "topnav") {
          x.className += " responsive";
      } else {
          x.className = "topnav";
      }
  }
  </script>
</head>

<body>
  <?php
  session_start();
  include 'ConnectDB.php';
  $un = $_SESSION['User_Name'];
  $pw = $_SESSION['Password'];

  $SQL = "SELECT * FROM accounts WHERE User_Name = '$un' AND Password = '$pw'";
  $result = mysqli_query($con,$SQL);
  $data= mysqli_fetch_assoc($result);
  $id= $data['Acc_ID'];

  $SQL2 = "SELECT * FROM acc_users WHERE Acc_ID = '$id'";
  $result2 = mysqli_query($con,$SQL2);
  $data2= mysqli_fetch_assoc($result2);

  if(isset($_GET['option']))
  {
    switch($_GET['option'])
    {
      case "home":
      echo '<center>'.'<br>';
      echo 'Good Day'.' '.$data['User_Name'].'<br>'.'<br>';
      echo 'Your Balance is:'. $data2['Balance'];
      ?>
      <th></th>
      <br>
      <br>
      <center>
        <a href="http://localhost/ReQuench/User.php?option=acc">
            <button class="list">My Account</button>
        </a><br>
        <a href="http://localhost/ReQuench/User.php?option=acctran ">
          <button class="list">My Transaction History</button>
        </a><br>
      </center>

      <?php
      break;

      case "acc":
      ?>
        <center>
          <h1> My Account</h1>

          <table style="text-align: left;width:30%">
            <tr><div>
              <td><label>Account Balance:</label>
              <td><?php echo $data2['Balance'];?>
            </div></tr>

            <tr><div>
              <td><label>User Name:</label>
              <td><?php echo $data['User_Name'];?>
            </div></tr>

            <tr><div>
              <td><label>Student Number:</label>
              <td><?php echo $data2['ID_Number'];?>
            </div></tr>

            <tr><div>
              <td><label>First Name:</label>
              <td><?php echo $data2['First_Name'];?>
            </div></tr>

            <tr><div>
              <td><label>Last Name:</label>
              <td><?php echo $data2['Last_Name'];?>
            </div></tr>

            <tr><div>
              <td><label>Email:</label>
              <td><?php echo $data['Email'];?>
            </div></tr>
          </div>
        </table>

        <a href="http://localhost/ReQuench/User.php?option=editacc">
            <button class="list" style="width:20%;background-color:#64da69;font-size: large;color: white;">Edit Account</button>
        </a>
        <?php
        break;


      case "editacc":
      ?>
      <form action="" method="post">
        <center>
          <h1> Edit Account</h1>
          <table style="text-align: left;width:40%">
            <tr><div>
              <td><label>Account Balance:</label>
              <td><input type="text" name="Balance" value="<?php echo $data2['Balance'];?>" readonly>
            </div></tr>

            <tr><div>
              <td><label>User Name:</label>
              <td><input type="text" NAME="User_Name" value="<?php echo $data['User_Name'];?>" required>
            </div></tr>

            <tr><div>
              <td><label>Student Number:</label>
              <td><input type="text" NAME="ID_Number" value="<?php echo $data2['ID_Number'];?>" readonly>
            </div></tr>

            <tr><div>
              <td><label>First Name:</label>
              <td><input type="text" NAME="First_Name" value="<?php echo $data2['First_Name'];?>" required>
            </div></tr>

            <tr><div>
              <td><label>Last Name:</label>
              <td><input type="text" NAME="Last_Name" value="<?php echo $data2['Last_Name'];?>" required>
            </div></tr>

            <tr><div>
              <td><label>Email:</label>
              <td><input type="text" NAME="Email" value="<?php echo $data['Email'];?>" required>
            </div></tr>
          </div>
        </table>
        <button class="list" style="width:20%;background-color:#64da69;font-size: larger;color: white;" name="save">Save Changes</button>
      </center>
  </form>

      <?php
      include 'ConnectDB.php';
      if (isset($_POST['save']))
      {
        $fi = $_POST['First_Name'];
        $la = $_POST['Last_Name'];
        $em = $_POST['Email'];
        $us= $_POST['User_Name'];

        mysqli_query($con,"UPDATE `accounts` SET `User_Name`='$us',`Email`='$em' WHERE Acc_ID='$id'")
        or die(mysqli_error($con));
        mysqli_query($con,"UPDATE `acc_users` SET `First_Name`='$fi',`Last_Name`='$la' WHERE Acc_ID='$id'")
        or die(mysqli_error($con));
        $_SESSION['User_Name'] = $us;
        header("Location: http://localhost/ReQuench/User.php?option=home");
      }

      break;

      case "acctran":
      ?>
      <center>
        <h1 style=" margin-bottom: 50px">My Transaction History</h1>
      <table id='table' class="table" style="margin-top:50px">
        <tr>
          <th>Date</th>
          <th>Time</th>
          <th>Location</th>
          <th>Amount Dispensed</th>
          <th>Temperature</th>
        </tr>

        <?php
        $SQL= mysqli_query($con,"SELECT * FROM `transaction_history` ORDER BY `transaction_history`.`Transaction_ID` ASC");
        while($row = mysqli_fetch_array( $SQL )) {
        $id=$row['MU_ID'];
          $SQL2= mysqli_query($con,"SELECT * FROM `machine_unit` WHERE `MU_ID`=$id ");
          $row2 = mysqli_fetch_array( $SQL2 );

          echo "<tr>";
          echo '<td>' . $row['Date'] . '</td>';
          echo '<td>' . $row['Time'] . '</td>';
          echo '<td>' . $row2['Machine_Location'] . '</td>';
          echo '<td>' . $row['Amount_Dispensed'] . '</td>';
          echo '<td>' . $row['Temperature'] . '</td>';
          echo "</tr>";
        }
        ?>
      </table>
    </center>
    <?php
    break;

    case "edit":

break;
  }
}
  ?>
</body>

</html>
