<?php
// API access key from Google API's Console
require 'ConnectDB.php';
require 'sendgrid-php\sendgrid-php.php';

function sendEmail($conn,$name_sender,$email_sender,$message)
{
    $api_key = $_ENV['SENDGRID_API_KEY'];
    define( 'SENDGRID_API_KEY', $api_key);
    echo SENDGRID_API_KEY;
    $personalizations = array();
    

    $from_email = array();
    $sender[] = $email_sender;
    
    $to = array();
    $content = array();
    $email = array(
        'email'=> 'requench2019@gmail.com'
    );
    $to[] = $email;
    $email_construct = constructEmail($name_sender,$email_sender,$message);

    $body = array(
        'type' => 'text/html',
        'value' => $email_construct
    );
    $content[] = $body;
    $to_personalize = array(
        'to'=>$to
    );
    $personalizations[] = $to_personalize;


    $json_content = array(
        'personalizations' => $personalizations,
        'from' => array(
            'email' => $email_sender
        ),
        'subject' => 'User Feedback',
        'content' => $content
    );

    // echo json_encode($json_content);
    $headers = array
    (
        'Authorization: Bearer ' . SENDGRID_API_KEY,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://api.sendgrid.com/v3/mail/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    // curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    // curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $json_content ) );
    $result = curl_exec($ch);
    curl_close( $ch );
    // $result_json = json_decode($result);
    if ($result) {
        return true;
    }else{
        return false;
    }
}

function constructEmail($name_sender,$email_sender,$message)
{
    $dt = new DateTime();
    $time = $dt->format("H:i:s");
    $date = $dt->format("Y-m-d");
    $html_string = "<html>
    <head>
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css'
            integrity='sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS' crossorigin='anonymous'>
    </head>
    <style>
        .bar {
            height: 20%;
            background: #1EB3BE
        }
        .header_label{
            color: white;
        }
        body{
            background-color: rgb(238, 238, 238);
        }
        .card{
            margin-top: 5%;
        }
    </style>
    
    <body>
        <nav class='navbar navbar-light bg-dark'>
            <span id='id_banner' class='navbar-brand mb-0 h1'><img id='banner' src='https://requench-rest.herokuapp.com/source_images/bannerwhite.png' height='30'></span>
            <h3 class='header_label'>User Feed Back</h3>
        </nav>
        <div class='card container'>
            <p>$date $time</p>
            <h3>From: $name_sender</h3>
            <h4>Message: </h4>
            <p>$message</p>
        </div>
    </body>
    </html>";

    return $html_string;
}



date_default_timezone_set('Asia/Manila');
$contents = file_get_contents('php://input');
$response = array();
if ($contents != null) {
    $data = json_decode($contents);
    $name_sender = $data->{"name_sender"};
    $email_sender = $data->{"email_sender"};
    $message = $data->{"message"};
    sendEmail($conn,$name_sender,$email_sender,$message);
}else{
  die("An Error Occured");
}

echo json_encode($response);
mysqli_close($conn);
 ?>
