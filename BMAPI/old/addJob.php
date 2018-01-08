<?php
/**
 * Created by PhpStorm.
 * User: nakeebimac
 * Date: 9/1/17
 * Time: 10:14 PM
 */

$username = htmlentities($_REQUEST["username"]);
$title = htmlentities($_REQUEST["title"]);
$content = htmlentities($_REQUEST["content"]);
$address = htmlentities($_REQUEST["address"]);
$phone = htmlentities($_REQUEST["mobile"]);

if (empty($username)){
    $returnArray["status"] = "400";
    $returnArray["msg"] = "Missing details";
    echo json_encode($returnArray);
    return;
}
require ("secure/access.php");
require("secure/bmconn.php");

$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();
$addJob = $access->addJob($username, $title, $content, $address, $phone);

if ($addJob) {
    $returnArray["added"] = true;

}
else{
    $returnArray["added"] = false;

}

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);