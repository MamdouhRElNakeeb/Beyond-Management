<?php
/**
 * Created by PhpStorm.
 * User: mamdouhelnakeeb
 * Date: 1/8/18
 * Time: 11:25 AM
 */


$userID = htmlentities($_REQUEST["user_id"]);
$msg = htmlentities($_REQUEST["msg"]);

if (empty($msg) || empty($userID)){

    $returnArray["status"] = "400";
    $returnArray["message"] = "Missing Fields!";
    echo json_encode($returnArray);
    return;
}

require ("secure/access.php");
require ("secure/bmconn.php");

$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();


$user = $access->contactMsg($userID, $msg);

$applicant = $access->selectApplicantWithId($userID);

if ($user){

    $returnArray["success"] = TRUE;
    $returnArray["message"] = "Message sent";

    //
    $url = ADMIN. 'sendMail.php';

    // what post fields?
    $data = array('to' => ADMIN_EMAIL,
        'from' => $applicant["email"],
        'subject' => "New Message Received from " .$applicant["name"],
        'msg' => $msg);

    // build the urlencoded data
    $postvars = http_build_query($data);

    // open connection
    $ch = curl_init();

    // set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, count($data));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);


    // execute post
    $result = curl_exec($ch);
    curl_close($ch);
}
else{

    $returnArray["success"] = FALSE;
    $returnArray["message"] = "Failed to send message!";
}

$access->disconnect();

echo json_encode($returnArray);

?>