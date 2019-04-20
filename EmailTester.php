<?php
// API access key from Google API's Console
require 'ConnectDB.php';
require 'sendgrid-php\sendgrid-php.php';

function sendEmail($conn,$name_sender,$email_sender,$message)
{
    $email = new \SendGrid\Mail\Mail(); 
    $email->setFrom($email_sender, $name_sender);
    $email->setSubject("User Feedback");
    $email->addTo("tapaymarjay@gmail.com", "Marjay Tapay");
    $email->addContent("text/plain", $message);
    $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
    
    try {
        $response = $sendgrid->send($email);
        print $response->statusCode() . "\n";
        print_r($response->headers());
        print $response->body() . "\n";
    } catch (Exception $e) {
        echo 'Caught exception: '. $e->getMessage() ."\n";
    }
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
