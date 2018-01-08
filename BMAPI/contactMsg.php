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

if ($user){

    $returnArray["success"] = TRUE;
    $returnArray["message"] = "Message sent";
}
else{

    $returnArray["success"] = FALSE;
    $returnArray["message"] = "Failed to send message!";
}

$access->disconnect();

echo json_encode($returnArray);

?>