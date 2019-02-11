<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Cashier</title>
    <link rel="stylesheet" href="cashier.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

</head>

<body>
  <body OnLoad="document.myform.scan.focus();">

<div class="column">
  <center>
    <h3 class="title">ReQuench: A DLSL Water Vending Machine</h3>
  </center>
  <div class="account">
    <form name="myform" method="post">
      <input type="text" name="scan" id="scan">
      <input type="submit" class="btn btn-success" value="Enter" id="enter" name="enter"/>
    </form>
    <?php
    // session_start();
    include 'ConnectDB.php';

    if (isset($_POST["enter"]))
    {
    $scan = (int) $_POST['scan'];
    $SQL = "SELECT * FROM acc_users WHERE ID_Number= '$scan'";
    $result = mysqli_query($con,$SQL);
    $data= mysqli_fetch_assoc($result);

    $stud_name= $data['First_Name'];
    $current = $data['Balance'];
    
    ?>
    <h6 class="text_account">Account Name: <?php echo $stud_name; ?> </h6>
    <h6 class="text_account">Available Balance: <?php echo $current; ?></h6>
    <?php
    }

    if (isset($_POST["nexttransaction"]))
    {

      $vol_purchased = (int) $_POST['vol_purchased'];
      $updated= $current + $vol_purchased;
      $query  = "UPDATE acc_users SET Balance='$updated' WHERE ID_Number ='$scan'";
      mysqli_query($con, $query)
      or die(mysqli_error($con));

    }
    ?>

  </div>
  <!-- insert customer ID -->
  <center>
    <div>
      <p>Amount ( P ):
        <input id="textBox" name="textBox" class="infield"  oninput="Volumeconvert(this.value)" onchange="Volumeconvert(this.value)"  readonly/>
      </p>
      <p>Volume (mL):
        <input id="outputmL" class="infield" type="text"  readonly/>
      </p>
    </div>

<!------------------------------------- preset values ---------------------------------------------------->
              <div id="option" class="separator">
                <input type="button" class="btn default btn-lg option" value="Preset Values" onclick=""/>
                <input type="button" class="btn default btn-lg option" value="Open Keypad" onclick=""/>
              </div>
              <div class="separator">
                <button type="button" class="btn info btn-lg set1" value="10" onclick="setText(this)">P 10</button>
                <button type="button" class="btn info btn-lg set1" value="25" onclick="setText(this)">P 25</button>
                <button type="button" class="btn info btn-lg set1" value="50" onclick="setText(this)">P 50</button>
                <button type="button" class="btn info btn-lg set1" value="75" onclick="setText(this)">P 75</button>
                <button type="button" class="btn info btn-lg set1" value="100" onclick="setText(this)">P 100</button>
                <button type="button" class="btn info btn-lg set1" value="150" onclick="setText(this)">P 150</button>
              </div>

<button id="btnSubmit" type="button" class="btn btn-success btn-lg" value="confirm" onclick="this.disabled = true;">CONFIRM</button>
</center>
</div>
<div class="rightcolumn">
<center>
  <div class="table" style="height:300px;">
  <table id="purch_summary" class="table" style="width:100%;">
    <tr>
      <th>Volume (mL)</th>
      <th>Price per mL</th>
    </tr>
  </table>
</div>
<center>
<form name="" method="POST" action="">
  <div class="table">
  <table id= "purch_sub" class="table">

      <tr>
        <th>Total</th>
        <td><input type="text" name="total" id="total" class="cash" ></td>
        <!-- total volume purchased -->
        <td><input type="hidden" name="vol_purchased" id="vol_purchased" class="cash" value="vol_purchased"></td>
      </tr>
      <tr>
        <th>Cash</th>
        <td><input type="text" name="cash" id="cash" class="cash" onchange="getchange()"></td>
      </tr>
      <tr>
        <th>Change</th>
        <td><input type="text" name="change" id="change" class="cash" ></td>
      </tr>

    </table>
    <center>
  <div class="bottom">
  <input id="cancel" type="submit" class="btn btn-danger btn-lg" value="Cancel"/>
  <input id="nexttransaction" name="nexttransaction" type="submit" class="btn btn-warning btn-lg" value="Next Transaction" />
  </div>
</center>
  </div>
</form>


</div>
<script type="text/javascript">
  document.getElementById('scan').submit();
</script>
<script>
//---------------------------declaration of table names-------------------------
table1 = document.getElementById("purch_summary");
table2 = document.getElementById("purch_sub");
///---------------------declaration of all variables used-----------------------
var confirm = document.getElementById("btnSubmit");
var pay = document.getElementById("pay");
var cancel = document.getElementById("cancel");
var total= 0;
var idno= document.getElementById("Input_ID_btn");

confirm.addEventListener("click", function (event) {

  var newRow = table1.insertRow(table1.rows.length);
  //volume
  var cell1 = newRow.insertCell(0);
  cell1.innerHTML = document.getElementById('outputmL').value ;
  //price per ml
  var cell2 = newRow.insertCell(1);
  cell2.innerHTML = "25";
// =======================================================

  //total
  var x = document.getElementById('textBox').value ;
  document.getElementById("total").value = x;

  var y = document.getElementById('outputmL').value ;
  document.getElementById("vol_purchased").value = y;
});

cancel.addEventListener("click", function (event) {
location.reload();
});
</script>
<script>
function getchange() {
var total = document.getElementsByName('total')[0].value;
var cash = document.getElementsByName('cash')[0].value;
var vol_purchased = document.getElementById('vol_purchased');
var change = (+cash) - (+total);
document.getElementsByName('change')[0].value = change;
vol_purchased.value = document.getElementById('outputmL').value;
}
</script>
<script>
    function setText(obj){
    var val = obj.value;
    console.log(val);
    document.getElementById('textBox').value = val;
    document.getElementById('outputmL').value=val*25;
  }

function Volumeconvert(valNum) {
  document.getElementById('outputmL').value=valNum*25;
}
</script>

<script>
    mobiscroll.settings = {
        theme: 'ios',                    // Specify theme like: theme: 'ios' or omit setting to use default
        lang: 'en'                       // Specify language like: lang: 'pl' or omit setting to use default
    };

    var numpad,
        stepperPrice = document.querySelector('.md-price'),
        numpadCons = document.querySelector('.md-numpad');

    numpad = mobiscroll.numpad('.md-numpad', {
        preset: 'decimal',               // More info about preset: https://docs.mobiscroll.com/4-5-0/javascript/numpad#opt-preset
        min: 5,
        max: 50000,
        prefix: '',
        onSet: function (event, inst) {  // More info about onSet: https://docs.mobiscroll.com/4-5-0/javascript/numpad#event-onSet
            stepperPrice.value = event.valueText;
        }
    });

    numpad.setVal(stepperPrice.value);

    numpad.attachShow(stepperPrice);

    stepperPrice.addEventListener('change', function (ev) {
        numpadCons.value = this.value;
        ev.target.value = '' + this.value;
    });

    stepperPrice.value = '' + stepperPrice.value;
</script>
<script>
function SetFocus () {
var input = document.getElementById ("theFieldID");
input.focus ();
}
</script>




</body>
</html>
