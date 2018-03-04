<?php
/**
 * Created by PhpStorm.
 * User: mamdouhelnakeeb
 * Date: 12/8/17
 * Time: 4:48 AM
 */

$id = htmlentities($_REQUEST["id"]);

session_start();

if ($_SESSION['valid'] != true){

    header('location: login.php');
    return;
}

if (empty($id)){
    $returnArray["status"] = "400";
    $returnArray["msg"] = "Missing details";
    echo json_encode($returnArray);
    return;
}

require ("secure/access.php");
require ("secure/bmconn.php");

$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();

$result = $access->getEmailTemp($id);


if ($result){
    $b["id"] = $result["id"];
    $b["name"] = $result["name"];
    $b["email"] = $result["email"];
    $b["subject"] = $result["subject"];
    $b["msg"] = $result["msg"];//stripslashes
    echo json_encode($b);
}
else {
    $returnArray["status"] = "400";
    $returnArray["msg"] = "Not found";
    echo json_encode($returnArray);
}


?>