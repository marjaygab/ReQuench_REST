<?php
// API access key from Google API's Console
define( 'API_ACCESS_KEY', 'AAAAuK236cA:APA91bH3bN70C57BjaMo5YeybIuU4Z_hOlzEe0Qa3hf3fjasAlzgzWwXI5bLrLgLYS11gGb2Mpz4ePhnQsk7PVFDI2NdZn8xHSyTo_mlP5zRyuB5ZwFi90XB96G_1YI2859UhrwVw9OX' );
$registrationIds = $_GET['id'];
// prep the bundle
$msg = array
(
	'title'		=> 'This is a title.',
	'body' 	=> 'here is a message. message',
	'icon'	=> '/assets/images/logo.png'
);
$fields = array
(
	'notification'			=> $msg,
	'to' 	=> $registrationIds
);

$headers = array
(
	'Authorization: key=' . API_ACCESS_KEY,
	'Content-Type: application/json'
);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
echo $result;



 ?>
