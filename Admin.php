<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>ReQuench</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/css/w3.css">
  <!-- <link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap.min.css" /> -->
	<link rel="stylesheet" type="text/css" href="assets/css/styles.css" />
	<link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
  <div class="topnav" id="myTopnav">
    <a href="http://localhost/ReQuench/Admin.php?option=home" class="active">Home</a>
    <a href="http://localhost/ReQuench/index.php" class="rightnav">Logout <img src="assets/images/sign-out.png" style="width:20px;"></a>
    <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
  </div>
  <script>
  function myFunction() {
      var x = document.getElementById("myTopnav");
      if (x.className === "topnav") {
          x.className += " responsive";
      } else {
          x.className = "topnav";
      }
  }
  </script>
    <script>
    function delalert({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    })
  .then((result) => {
    if (result.value) {
      delalert(
        'Deleted!',
        'Your file has been deleted.',
        'success'
      )}})
  </script>

  <center>
    <body>
    <?php
    if(isset($_GET['option']))
    {
      switch($_GET['option'])
      {
        //show buttons (devices, accounts, history)
        case "home":
        ?>
        <br>
        <br>
        <a href="http://localhost/ReQuench/Admin.php?option=devlist">
          <button class="list">View Devices</button>
        </a><br>

        <a href="http://localhost/ReQuench/Admin.php?option=acclist">
          <button class="list">List of Accounts</button>
        </a><br>

        <a href="http://localhost/ReQuench/Admin.php?option=tranlist">
          <button class="list">Transaction History</button>
        </a><br>

        <?php
        break;

        //show device list
        case "devlist":
        ?>
        <center>
          <h1 style="margin-bottom: 10px">List of Devices</h1>
        </center>

        <a href="http://localhost/ReQuench/Admin.php?option=devadd" class="">
          <span class="	fa fa-user-plus" ></span> Add
        </a>
        <div style="margin-bottom: 15px"></div>
        <center>

          <table id='table' class="table">
          <tr></tr>
          <th>MU ID</th>
          <th>Model Number</th>
          <th>MAC Address</th>
          <th>Machine Location</th>
          <th>Date of Purchase</th>
          <th>Last Maintenance Date</th>
          <th></th>
          <th></th>
          <tr></tr>

          <?php
          include 'ConnectDB.php';
          $SQL= mysqli_query($con,"SELECT * FROM `machine_unit` ORDER BY `machine_unit`.`MU_ID` ASC, `machine_unit`.`Model Number` ASC");

          while($row = mysqli_fetch_array( $SQL ))
          {
            echo "<tr>";
            echo '<td>' . $row['MU_ID'] . '</td>';
            echo '<td>' . $row['Model Number'] . '</td>';
            echo '<td>' . $row['MAC_ADD'] . '</td>';
            echo '<td>' . $row['Machine_Location'] . '</td>';
            echo '<td>' . $row['Date of Purchase'] . '</td>';
            echo '<td>' . $row['Last_Maintenance_Date'] . '</td>';
            echo '<td><span class="fa fa-edit" >'.'<a href="http://localhost/ReQuench/Admin.php?option=devedit&ID='.$row['MU_ID'] . '">Edit</a></td>';
            echo '<td><span class="fa fa-trash" >'.'<a href="http://localhost/ReQuench/Admin.php?option=devdel&ID='.$row['MU_ID'].'">Delete</a></td>';

            echo "</tr>";
          }
          echo "</table>";
        break;

        //show account list
        case "acclist":
        ?>
        <center>
          <h1 style="margin-bottom: 10px">List of Accounts</h1>
        </center>

        <a href="http://localhost/ReQuench/Admin.php?option=accadd">
          <span class="	fa fa-user-plus" ></span> Add
        </a>

        <div style="margin-bottom: 15px"></div>
        <center>

          <table id='table' class="table">
            <tr>
              <th>ACC ID</th>
              <th>AL ID</th>
              <th>Username</th>
              <th>OTP</th>
              <th>Password</th>
              <th>Email</th>
              <th></th>
              <th></th>
            </tr>

            <?php
            include 'ConnectDB.php';
            $SQL= mysqli_query($con,"SELECT * FROM `accounts` ORDER BY `accounts`.`AL_ID` ASC, `accounts`.`User_Name` ASC");

            while($row = mysqli_fetch_array( $SQL ))
            {
              echo "<tr>";
              echo '<td>' . $row['Acc_ID'] . '</td>';
              echo '<td>' . $row['AL_ID'] . '</td>';
              echo '<td>' . $row['User_Name'] . '</td>';
              echo '<td>' . $row['OTP'] . '</td>';
              echo '<td>' . $row['Password'] . '</td>';
              echo '<td>' . $row['Email'] . '</td>';
              echo '<td><span class="fa fa-edit" >'.'<a href="http://localhost/ReQuench/Admin.php?option=accedit&ID='.$row['Acc_ID'] . '">Edit</a></td>';
              echo '<td><span class="fa fa-trash" >'.'<a href="http://localhost/ReQuench/Admin.php?option=accdel&ID='.$row['Acc_ID'].'">Delete</a></td>';
              echo "</tr>";
            }
            ?>
          </table>
        </center>
        <?php
        break;

        //add account
        case "accadd":
        include 'ConnectDB.php';
        ?>
        <center>
          <div>
          <form action="" method="post">
            <center>
              <h1 style=" margin-bottom: 10px">Add an Account</h1>
              <table class="edittable">
                <tr><div>
                  <td><label>Access Level ID:</label>
                  <td><input type="text" NAME="AL_ID" required>
                </div></tr>

                <tr><div>
                  <td><label>Student ID/ Employee ID:</label>
                  <td><input type="text" NAME="StudentEmployeeID" required>
                </div></tr>
                <tr><div>
                  <td><label>Username:</label>
                  <td><input type="text" NAME="User_Name" required>
                </div></tr>

                <tr><div>
                  <td><label>First Name:</label>
                  <td><input type="text" NAME="First_Name" required>
                </div></tr>

                <tr><div>
                  <td><label>Last Name:</label>
                  <td><input type="text" NAME="Last_Name" required>
                </div></tr>

                <tr><div>
                  <td><label>OTP:</label>
                  <td><input type="text" NAME="OTP" >
                </div></tr>

                <tr><div>
                  <td><label>Password:</label>
                  <td><input type="password" NAME="Password" required>
                </div></tr>

                <tr><div>
                  <td><label>Email:</label>
                  <td><input type="text" NAME="Email" required>
                </div></tr>
              </table>

                  <div class="form-group row">
                    <button class="list" style="width:20%;background-color:#64da69;font-size: larger;color: white;" name="add">Add Account</button>
                  </div>
                </div>
            </center>
          </form>
        </div>
          <?php
          if (isset($_POST['add']))
          {
            $al = $_POST['AL_ID'];
            $st = $_POST['StudentEmployeeID'];
            $us = $_POST['User_Name'];
            $fi = $_POST['First_Name'];
            $la = $_POST['Last_Name'];
            $ot = $_POST['OTP'];
            $pa = $_POST['Password'];
            $em = $_POST['Email'];
            $id= $data['Acc_ID'];

            mysqli_query($con,"INSERT INTO `accounts` (`Acc_ID`, `AL_ID`, `User_Name`, `OTP`, `Password`, `Email`) VALUES ('','$al','$us','$ot','$pa','$em')")
            or die(mysqli_error($con));

            $SQL = "SELECT * FROM accounts WHERE User_Name = '$us' AND Password = '$pa'";
            $result = mysqli_query($con,$SQL);
            $data= mysqli_fetch_assoc($result);
            $id= $data['Acc_ID'];

            if ($al=='3')
            {
              mysqli_query($con,"INSERT INTO  `acc_users`(`Acc_ID`, `Acc_User_ID`, `ID_Number`, `First_Name`, `Last_Name`, `Balance`) VALUES ('$id','','$st','$fi','$la','')")
              or die(mysqli_error($con));
            }
            else if ($al=='1')
            {
              mysqli_query($con,"INSERT INTO `acc_admin`(`Acc_ID`, `Acc_Admin_ID`, `ID_Number`, `First_Name`, `Last_Name`, `Balance`) VALUES ('$id','','$st','$fi','$la','')")
              or die(mysqli_error($con));
            }
            else if ($al=='2'){
              mysqli_query($con,"INSERT INTO `acc_cashier`(`Acc_ID`, `Acc_Cashier_ID`, `ID_Number`, `First_Name`, `Last_Name`, `Balance`)  VALUES ('$id','','$st','$fi','$la','')")
              or die(mysqli_error($con));
            }

            header("Location: http://localhost/ReQuench/Admin.php?option=acclist");
          }
          ?>
        </center>
        <?php
        break;

        // delete accounts
        case "accdel":
        include 'ConnectDB.php';
        ?>
        <script>delalert();</scipt>
        <?php
        $id = $_GET['ID'];
        $result = mysqli_query($con,"SELECT * FROM accounts  WHERE Acc_ID=$id")
        or die(mysqli_error());
        while($row = mysqli_fetch_array( $result ))
        {
          $id = $row ['Acc_ID'];
          $ac = $row ['AL_ID'];
        }
        mysqli_query($con,"DELETE FROM acc_users WHERE Acc_ID=$id")
        or die(mysqli_error($con));
        mysqli_query($con,"DELETE FROM acc_admin WHERE Acc_ID=$id")
        or die(mysqli_error($con));
        mysqli_query($con,"DELETE FROM acc_cashier WHERE Acc_ID=$id")
        or die(mysqli_error($con));
        mysqli_query($con,"DELETE FROM accounts WHERE Acc_ID=$id") or die(mysqli_error());
        header("Location:http://localhost/ReQuench/Admin.php?option=acclist");
        break;

        //edit accounts
        case "accedit":
        include 'ConnectDB.php';
        $id = $_GET['ID'];
        $result = mysqli_query($con,"SELECT * FROM accounts  WHERE Acc_ID=$id")
        or die(mysqli_error());

        while($row = mysqli_fetch_array( $result ))
        {
          $ac = $row ['Acc_ID'];
          $al = $row ['AL_ID'];
          $us = $row ['User_Name'];
          $ot = $row ['OTP'];
          $pa = $row ['Password'];
          $em = $row ['Email'];
        }
        ?>

        <center>
          <h1 style="margin-bottom: 10px">Edit Account</h1>
          <form action="" method="post">
            <table class= "edittable" >
            <tr><div>
              <td><label>Account ID:</label>
              <td><input type="text"  name="Acc_ID" value="<?php echo $ac;?>" readonly>
            </tr></div>

            <tr><div>
              <td><label>Access Level ID:</label>
              <td><input type="text"  NAME="AL_ID" value="<?php echo $al;?>" required>
            </tr></div>

            <tr><div>
              <td><label>Username:</label>
              <td><input type="text"  NAME="User_Name" value="<?php echo $us;?>" required>
            </tr></div>

            <tr><div>
              <td><label>OTP:</label>
              <td><input type="text"  NAME="OTP" value="<?php echo $ot;?>" >
            </tr></div>

            <tr><div>
              <td><label>Password:</label>
              <td><input type="password"  NAME="Password" value="<?php echo $pa;?>" required>
            </tr></div>

            <tr><div>
              <td><label>Email:</label>
              <td><input type="text"  NAME="Email" value="<?php echo $em;?>" required>
            </tr></div>
          </table>

            <button class="list" style="width:20%;background-color:#64da69;font-size: larger;color: white;" name="update">Update Account</button>
          </div>
          </center>
        </form>

        <?php
        include 'ConnectDB.php';
        if (isset($_POST['update']))
        {
          $ac = $_POST['Acc_ID'];
          $al = $_POST['AL_ID'];
          $us = $_POST['User_Name'];
          $ot = $_POST['OTP'];
          $pa = $_POST['Password'];
          $em = $_POST['Email'];
          mysqli_query($con,"UPDATE `accounts` SET `Acc_ID`='$ac',`AL_ID`='$al',`User_Name`='$us',`OTP`='$ot',`Password`='$pa',`Email`='$em' WHERE Acc_ID='$id'")
          or die(mysqli_error($con));
          header("Location: http://localhost/ReQuench/Admin.php?option=acclist");
        }
        ?>
      </center>
      <?php
      break;



        //add device
        case "devadd":
        include 'ConnectDB.php';
        ?>

          <h1 style="margin-bottom: 10px">Add Device</h1>
            <form action="" method="post">
              <table class= "edittable" >
              <tr><div>
                <td><label>Machine Unit ID:</label>
                <td><input type="text" NAME="MU_ID" required>
              </div></tr>

              <tr><div>
                <td><label>Model Number:</label>
                <td><input type="text" NAME="ModelNumber" required>
              </div></tr>

              <tr><div>
                <td><label>MAC Address:</label>
                <td><input type="text" NAME="MAC_ADD" required>
              </div></tr>

              <tr><div>
                <td><label>Machine Location:</label>
                <td><input type="text" NAME="Machine_Location" required>
              </div></tr>

              <tr><div>
                <td><label>Date of Purchase:</label>
                <td><input type="date" NAME="DateofPurchase" required>
              </div></tr>

              <tr><div>
                <td><label>Last Maintenance Date:</label>
                <td><input type="date" NAME="Last_Maintenance_Date" required>
              </div></tr>
            </table>

                <div class="form-group row">
                  <button class="list" style="width:20%;background-color:#64da69;font-size: larger;color: white;" name="add">Add Device</button>
                </div>
              </div>

          </form>

          <?php
          if (isset($_POST['add']))
          {
            $id = $_POST['MU_ID'];
            $mod = $_POST['ModelNumber'];
            $mac = $_POST['MAC_ADD'];
            $loc = $_POST['Machine_Location'];
            $dat = $_POST['DateofPurchase'];
            $las = $_POST['Last_Maintenance_Date'];

            mysqli_query($con,"INSERT INTO `machine_unit`(`MU_ID`, `Model Number`, `MAC_ADD`, `Machine_Location`, `Date of Purchase`, `Last_Maintenance_Date`) VALUES ('$id','$mod','$mac','$loc','$dat','$las')")
            or die(mysqli_error($con));
            header("Location: http://localhost/ReQuench/Admin.php?option=devlist");
          }
        break;

        // delete device
        case "devdel":
        include 'ConnectDB.php';
        $id = $_GET['ID'];
        $result = mysqli_query($con,"SELECT * FROM machine_unit  WHERE MU_ID=$id")
        or die(mysqli_error());

        while($row = mysqli_fetch_array( $result ))
        {
          $id = $row ['MU_ID'];
        }

        mysqli_query($con,"DELETE FROM machine_unit WHERE MU_ID=$id") or die(mysqli_error());
        header("Location:http://localhost/ReQuench/Admin.php?option=devlist");

        break;

        //edit device
        case "devedit":

        include 'ConnectDB.php';
        $id = $_GET['ID'];
        $result = mysqli_query($con,"SELECT * FROM machine_unit WHERE MU_ID=$id")
        or die(mysqli_error());

        while($row = mysqli_fetch_array( $result ))
        {
          $id = $row ['MU_ID'];
          $mod = $row ['Model Number'];
          $mac = $row ['MAC_ADD'];
          $loc = $row ['Machine_Location'];
          $dat = $row ['Date of Purchase'];
          $las = $row ['Last_Maintenance_Date'];
        }
        ?>
        <center>
          <h1 style=" margin-bottom: 10px">Edit Device</h1>
            <form action="" method="post">
              <table class= "edittable" >
              <tr><div>
                <td><label>Machine Unit ID:</label>
                <td><input type="text" name="MU_ID" value="<?php echo $id;?>" readonly>
              </div></tr>

              <tr><div>
                <td><label>Model Number:</label>
                <td><input type="text" NAME="ModelNumber" value="<?php echo $mod;?>" required>
              </div></tr>

              <tr><div>
                <td><label>MAC Address:</label>
                <td><input type="text" NAME="MAC_ADD" value="<?php echo $mac;?>" required>
              </div></tr>

              <tr><div>
                <td><label>Machine Location:</label>
                <td><input type="text" NAME="Machine_Location" value="<?php echo $loc;?>" required>
              </div></tr>

              <tr><div>
                <td><label>Date of Purchase:</label>
                <td><input type="date" NAME="DateofPurchase" value="<?php echo $dat;?>" required>
              </div></tr>

              <tr><div>
                <td><label>Last Maintenance Date:</label>
                <td><input type="date" NAME="Last_Maintenance_Date" value="<?php echo $las;?>" required>
              </div></tr>
            </table>
              <div class="container" >
                <button class="list" style="width:20%;background-color:#64da69;font-size: larger;color: white;" name="update">Update Device</button>
              </div>

            </center>
          </form>

          <?php
          include 'ConnectDB.php';
          if (isset($_POST['update']))
          {
            $id = $_POST['MU_ID'];
            $mod = $_POST['ModelNumber'];
            $mac = $_POST['MAC_ADD'];
            $loc = $_POST['Machine_Location'];
            $dat = $_POST['DateofPurchase'];
            $las = $_POST['Last_Maintenance_Date'];

            mysqli_query($con,"UPDATE `machine_unit` SET `MU_ID`='$id',`Model Number`='$mod',`MAC_ADD`='$mac',`Machine_Location`='$loc',`Date of Purchase`='$dat',`Last_Maintenance_Date`='$las' WHERE MU_ID='$id'")
            or die(mysqli_error($con));
            header("Location: http://localhost/ReQuench/Admin.php?option=devlist");
          }
          ?>
        </center>
        <?php
        break;

        //transactionlist
        case "tranlist":
        ?>
        <center>
          <h1 style="margin-bottom: 10px">Transaction History</h1>
          <a href="http://localhost/ReQuench/Admin.php?option=tranadd" class="">
            <span class="	fa fa-user-plus" ></span> Add
          </a>
          <a href="pdf.php" style="float:right;margin-right: 10%" >
            <span class="glyphicon glyphicon-print"></span> Print to PDF
          </a><br>

          <table id='table' class="table">
            <tr>
              <th>Transaction ID</th>
              <th>Acc ID</th>
              <th>MU ID</th>
              <th>Date</th>
              <th>Time</th>
              <th>Location</th>
              <th>Amount Dispensed</th>
              <th>Temperature</th>
              <th>Computed Price</th>
              <th>Water Level Before</th>
              <th>Water Level After</th>
              <th>Remaining Balance</th>
              <th></th>
              <th></th>
            </tr>

            <?php
            include 'ConnectDB.php';
            $SQL= mysqli_query($con,"SELECT * FROM `transaction_history` ORDER BY `transaction_history`.`Transaction_ID` ASC");
            while($row = mysqli_fetch_array( $SQL )) {
            $id=$row['MU_ID'];
              $SQL2= mysqli_query($con,"SELECT * FROM `machine_unit` WHERE `MU_ID`=$id ");
              $row2 = mysqli_fetch_array( $SQL2 );
              echo "<tr>";
              echo '<td>' . $row['Transaction_ID'] . '</td>';
              echo '<td>' . $row['Acc_ID'] . '</td>';
              echo '<td>' . $row['MU_ID'] . '</td>';
              echo '<td>' . $row['Date'] . '</td>';
              echo '<td>' . $row['Time'] . '</td>';
              echo '<td>' . $row2['Machine_Location'] . '</td>';
              echo '<td>' . $row['Amount_Dispensed'] . '</td>';
              echo '<td>' . $row['Temperature'] . '</td>';
              echo '<td>' . $row['Price_Computed'] . '</td>';
              echo '<td>' . $row['Water_Level_Before'] . '</td>';
              echo '<td>' . $row['Water_Level_After'] . '</td>';
              echo '<td>' . $row['Remaining_Balance'] . '</td>';
              echo '<td><span class="fa fa-edit" >'.'<a href="http://localhost/ReQuench/Admin.php?option=transedit&ID='.$row['Transaction_ID'] . '">Edit</a></td>';
              echo '<td><span class="fa fa-trash" >'.'<a href="http://localhost/ReQuench/Admin.php?option=transdel&ID='.$row['Transaction_ID'].'">Delete</a></td>';
              echo "</tr>";
            }
            ?>
          </table>
        </center>
        <?php
        break;

        //add device
        case "tranadd":
        include 'ConnectDB.php';
        ?>

          <h1 style="margin-bottom: 10px">Add Transaction</h1>
            <form action="" method="post">
              <table class= "edittable" >
                <tr><div>
                  <td><label>Transaction ID:</label>
                  <td><input type="text" name="Transaction_ID"  readonly>
                </div></tr>

                <tr><div>
                  <td><label>Account ID:</label>
                  <td><input type="text" name="Acc_ID" required>
                </div></tr>

              <tr><div>
                <td><label>Machine Unit ID:</label>
                <td><input type="text" name="MU_ID"  required>
              </div></tr>

              <tr><div>
                <td><label>Date:</label>
                <td><input type="date" NAME="Date" required>
              </div></tr>

              <tr><div>
                <td><label>Time:</label>
                <td><input type="time" NAME="Time" required>
              </div></tr>

              <tr><div>
                <td><label>Machine Location:</label>
                <td><input type="text" NAME="Machine_Location" required>
              </div></tr>

              <tr><div>
                <td><label>Amount Dispensed:</label>
                <td><input type="text" NAME="Amount_Dispensed" required>
              </div></tr>

              <tr><div>
                <td><label>Temperature:</label>
                <td><input type="text" NAME="Temperature" required>
              </div></tr>

              <tr><div>
                <td><label>Price Computed:</label>
                <td><input type="text" NAME="Price_Computed" required>
              </div></tr>

              <tr><div>
                <td><label>Water Level Before:</label>
                <td><input type="text" NAME="Water_Level_Before" required>
              </div></tr>

              <tr><div>
                <td><label>Water Level After:</label>
                <td><input type="text" NAME="Water_Level_After" required>
              </div></tr>

              <tr><div>
                <td><label>Remaining Balance:</label>
                <td><input type="text" NAME="Remaining_Balance" required>
              </div></tr>
            </table>

                <div class="form-group row">
                  <button class="list" style="width:20%;background-color:#64da69;font-size: larger;color: white;" name="add">Add Device</button>
                </div>
              </div>

          </form>

          <?php
          if (isset($_POST['add']))
          {
            $id  = $_POST ['Transaction_ID'];
            $ac = $_POST ['Acc_ID'];
            $mu = $_POST ['MU_ID'];
            $dat = $_POST ['Date'];
            $tim = $_POST ['Time'];
            $mac = $_POST['Machine_Location'];
            $amo = $_POST ['Amount_Dispensed'];
            $tem = $_POST ['Temperature'];
            $pri = $_POST ['Price_Computed'];
            $watb = $_POST ['Water_Level_Before'];
            $wata = $_POST ['Water_Level_After'];
            $rem = $_POST ['Remaining_Balance'];

            mysqli_query($con,"INSERT INTO `transaction_history`(`Transaction_ID`, `Acc_ID`, `MU_ID`, `Date`, `Time`, `Amount_Dispensed`, `Temperature`, `Price_Computed`, `Water_Level_Before`, `Water_Level_After`, `Remaining_Balance`) VALUES ('$id','$ac','$mu','$dat','$tim','$amo','$tem','$pri','$watb','$wata','$rem')")
            or die(mysqli_error($con));
            header("Location: http://localhost/ReQuench/Admin.php?option=tranlist");
          }
        break;

          // delete transaction
          case "transdel":
          include 'ConnectDB.php';
          $id = $_GET['ID'];
          $result = mysqli_query($con,"SELECT * FROM transaction_history  WHERE Transaction_ID=$id")
          or die(mysqli_error());
          while($row = mysqli_fetch_array( $result ))
          {
            $id = $row ['Transaction_ID'];
          }
          mysqli_query($con,"DELETE FROM transaction_history WHERE Transaction_ID=$id") or die(mysqli_error());
          header("Location:http://localhost/ReQuench/Admin.php?option=tranlist");
          break;

          //edit transaction
          case "transedit":

          include 'ConnectDB.php';
          $id = $_GET['ID'];
          $result = mysqli_query($con,"SELECT * FROM transaction_history WHERE Transaction_ID=$id")
          or die(mysqli_error());
          while($row = mysqli_fetch_array( $result ))
          {
            $mu1= $row ['MU_ID'];
          $result2 = mysqli_query($con,"SELECT * FROM machine_unit WHERE MU_ID=$mu1")
          or die(mysqli_error());
          $row2 = mysqli_fetch_array( $result2 );

            $id  = $row ['Transaction_ID'];
            $ac = $row ['Acc_ID'];
            $mu = $row ['MU_ID'];
            $dat = $row ['Date'];
            $tim = $row ['Time'];
            $mac = $row2['Machine_Location'];
            $amo = $row ['Amount_Dispensed'];
            $tem = $row ['Temperature'];
            $pri = $row ['Price_Computed'];
            $watb = $row ['Water_Level_Before'];
            $wata = $row ['Water_Level_After'];
            $rem = $row ['Remaining_Balance'];
          }
          ?>
          <center>
            <h1 style=" margin-bottom: 10px">Edit Device</h1>
              <form action="" method="post">
                <table class= "edittable" >
                  <tr><div>
                    <td><label>Transaction ID:</label>
                    <td><input type="text" name="Transaction_ID" value="<?php echo $id;?>" readonly>
                  </div></tr>

                  <tr><div>
                    <td><label>Account ID:</label>
                    <td><input type="text" name="Acc_ID" value="<?php echo $ac;?>" readonly>
                  </div></tr>

                <tr><div>
                  <td><label>Machine Unit ID:</label>
                  <td><input type="text" name="MU_ID" value="<?php echo $mu;?>" readonly>
                </div></tr>

                <tr><div>
                  <td><label>Date:</label>
                  <td><input type="date" NAME="Date" value="<?php echo $dat;?>" required>
                </div></tr>

                <tr><div>
                  <td><label>Time:</label>
                  <td><input type="time" NAME="Time" value="<?php echo $tim;?>" required>
                </div></tr>

                <tr><div>
                  <td><label>Machine Location:</label>
                  <td><input type="text" NAME="Machine_Location" value="<?php echo $mac;?>" required>
                </div></tr>

                <tr><div>
                  <td><label>Amount Dispensed:</label>
                  <td><input type="text" NAME="Amount_Dispensed" value="<?php echo $amo;?>" required>
                </div></tr>

                <tr><div>
                  <td><label>Temperature:</label>
                  <td><input type="text" NAME="Temperature" value="<?php echo $tem;?>" required>
                </div></tr>

                <tr><div>
                  <td><label>Price Computed:</label>
                  <td><input type="text" NAME="Price_Computed" value="<?php echo $pri;?>" required>
                </div></tr>

                <tr><div>
                  <td><label>Water Level Before:</label>
                  <td><input type="text" NAME="Water_Level_Before" value="<?php echo $watb;?>" required>
                </div></tr>

                <tr><div>
                  <td><label>Water Level After:</label>
                  <td><input type="text" NAME="Water_Level_After" value="<?php echo $wata;?>" required>
                </div></tr>

                <tr><div>
                  <td><label>Remaining Balance:</label>
                  <td><input type="text" NAME="Remaining_Balance" value="<?php echo $rem;?>" required>
                </div></tr>
              </table>
                <div class="container" >
                  <button class="list" style="width:20%;background-color:#64da69;font-size: larger;color: white;" name="update">Update transaction</button>
                </div>

              </center>
            </form>

            <?php
            include 'ConnectDB.php';
            if (isset($_POST['update']))
            {
              $id  = $_POST ['Transaction_ID'];
              $ac  = $_POST ['Acc_ID'];
              $mu  = $_POST ['MU_ID'];
              $dat = $_POST ['Date'];
              $tim = $_POST ['Time'];
              $mac = $_POST ['Machine_Location'];
              $amo = $_POST ['Amount_Dispensed'];
              $tem = $_POST ['Temperature'];
              $pri = $_POST ['Price_Computed'];
              $watb = $_POST['Water_Level_Before'];
              $wata = $_POST['Water_Level_After'];
              $rem = $_POST ['Remaining_Balance'];

              $SQL = "SELECT * FROM machine_unit WHERE Machine_Location = '$mac'";
              $result = mysqli_query($con,$SQL);
              $data= mysqli_fetch_assoc($result);
              $mu= $data['MU_ID'];

              mysqli_query($con,"UPDATE `transaction_history` SET `Acc_ID`='$ac',`MU_ID`='$mu',`Date`='$dat',`Time`='$tim',`Amount_Dispensed`='$amo',`Temperature`='$tem',`Price_Computed`='$pri',`Water_Level_Before`='$watb',`Water_Level_After`='$wata',`Remaining_Balance`='$rem' WHERE Transaction_ID='$id'")
              or die(mysqli_error($con));

              header("Location: http://localhost/ReQuench/Admin.php?option=tranlist");
            }
            ?>
          </center>
          <?php
          break;
          ?>
        </table>
        <?php
        break;

        case "acc":
        session_start();
        include 'ConnectDB.php';
        $un = $_SESSION['User_Name'];
        $pa = $_SESSION['Password'];

        $SQL = "SELECT * FROM accounts WHERE User_Name = '$un' AND Password = '$pa'";
        $result = mysqli_query($con,$SQL);
        $data= mysqli_fetch_assoc($result);
        $id= $data['Acc_ID'];

        $SQL2 = "SELECT * FROM acc_admin WHERE Acc_ID = '$id'";
        $result2 = mysqli_query($con,$SQL2);
        $data2= mysqli_fetch_assoc($result2);
        ?>
        <table id='table' border='1px'>
          <form action="" method="post">
            <center>
              <h1 style="margin-bottom: 10px">Set up Account</h1>
              <table class="edittable">
                <tr><div>
                  <td><label>Account Balance:</label>
                  <td><input type="text" name="Balance" value="<?php echo $data2['Balance'];?>" readonly>
                </div></tr>

                <tr><div>
                  <td><label>User Name:</label>
                  <td><input type="text" NAME="User_Name" value="<?php echo $data['User_Name'];?>" required>
                </div></tr>

                <tr><div>
                  <td><label>Employee Number:</label>
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
            mysqli_query($con,"UPDATE `acc_admin` SET `First_Name`='$fi',`Last_Name`='$la' WHERE Acc_ID='$id'")
            or die(mysqli_error($con));
            $_SESSION['User_Name'] = $us;
            header("Location: http://localhost/ReQuench/Admin.php?option=home");
          }

          break;
              }
            }

            ?>
          </body>
        </html>
