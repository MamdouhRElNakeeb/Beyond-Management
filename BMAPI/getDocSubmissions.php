<?php
/**
 * Created by PhpStorm.
 * User: mamdouhelnakeeb
 * Date: 1/8/18
 * Time: 2:30 AM
 */

$app_id = htmlentities($_REQUEST["app_id"]);

session_start();

if ($_SESSION['valid'] != true){

    header('location: login.php');
    return;
}

if (empty($app_id)){
    $returnArray["status"] = "400";
    $returnArray["msg"] = "Missing details";
    echo json_encode($returnArray);
    return;
}

require ("secure/access.php");
require("secure/bmconn.php");

$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();
$result = $access->getDocSubmissions($app_id);

$a = array();
$b = array();

if ($result != false){
    while ($row = mysqli_fetch_array($result)) {
        $b["id"] = $row["id"];
        $b["name"] = $row["name"];
        $b["status"] = $row["status"];
        $b["type"] = $row["type"];
        $b["url"] = $row["url"];
        array_push($a,$b);
    }
    echo json_encode($a);
}

?>