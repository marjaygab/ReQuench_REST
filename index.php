<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  	<link rel="stylesheet" href="assets/css/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
  <body id="body_id">
    <title>ReQuench</title>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href="#">
        <img src="assets/images/logo.png" width="35" height="30" alt="">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a id="home_button" class="nav-link" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a id="about_button" class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a id="contact_button" class="nav-link" href="#">Contact Us</a>
          </li>
          <!-- for disabling nav bar button -->
          <!-- <li class="nav-item">
            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
          </li> -->
        </ul>
      </div>
    </nav>
    <div id="header_container" class="container">
      <img class="image_banner" id="image_banner" src="assets/images/BrandwLogo.png" alt="">
      <h1 id="header_label">A DLSL Water Vending Machine</h1>
      <div class="container button_header_container">
        <button id="login_button" class="btn btn-info header_button" type="button" name="button">Log In</button>
        <button id="signup_button" class="btn btn-danger header_button" type="button" name="button">Create an Account</button>
      </div>
    </div>
  </body>
  <script src="https://www.gstatic.com/firebasejs/5.8.0/firebase.js"></script>
  <script>
    // Initialize Firebase
    var config = {
      apiKey: "AIzaSyDyQqFON2gburXUvS4bfPTgX7oPIn7gg0g",
      authDomain: "requench.herokuapp.com",
      databaseURL: "https://requench-firebase.firebaseio.com",
      projectId: "requench-firebase",
      messagingSenderId: "327289268444"
    };
    firebase.initializeApp(config);
  </script>
  <script type="text/javascript" src="index.js"></script>
  <script type="text/javascript" src="HttpRequest.js"></script>
  <script type="text/javascript" src="sweetalert2.all.min.js"></script>
</html>

