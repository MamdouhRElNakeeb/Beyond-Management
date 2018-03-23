<?php
/**
 * Created by PhpStorm.
 * User: mamdouhelnakeeb
 * Date: 1/8/18
 * Time: 7:23 AM
 */


$apnsHost = 'gateway.sandbox.push.apple.com';
$apnsCert = 'secure/APN_DEV.pem';
$apnsPort = 2195;
$apnsPass = 'bm123';
$token = $_POST["token"];
$message = $_POST["msg"];
$title = $_POST["title"];

$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', $apnsCert);
stream_context_set_option($ctx, 'ssl', 'passphrase', $apnsPass);

$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195',
    $err,
    $errstr,
    60,
    STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT,
    $ctx);

//if (!$fp)
//exit("Failed to connect amarnew: $err $errstr" . PHP_EOL);

//echo 'Connected to APNS' . PHP_EOL;

// Create the payload body
$body['aps'] = array(
    'badge' => +1,
    'title' => $title,
    'alert' => $message,
    'sound' => 'default'
);

$payload = json_encode($body);

// Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($payload)) . $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));

if (!$result) {
    $returnArr["success"] = FALSE;
    $returnArr["msg"] = 'Message not delivered';
    $returnArr["err"] = PHP_EOL;
}
else {
    $returnArr["success"] = TRUE;
    $returnArr["msg"] = 'Message successfully delivered';
    $returnArr["err"] = $message. PHP_EOL;
}

// Close the connection to the server
fclose($fp);

?>