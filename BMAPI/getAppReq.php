<?php
/**
 * Created by PhpStorm.
 * User: mamdouhelnakeeb
 * Date: 12/8/17
 * Time: 4:48 AM
 */


$id = htmlentities($_REQUEST["app_id"]);

if (empty($id)){

    $returnArray["status"] = "400";
    $returnArray["message"] = "Missing Fields!";
    echo json_encode($returnArray);
    return;
}

require ("secure/access.php");
require ("secure/bmconn.php");

$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();

$result = $access->getAppRequirements($id);

$a = array();
$b = array();

if ($result != false){
    while ($row = mysqli_fetch_array($result)) {
        $b["id"] = $row["id"];
        $b["name"] = $row["name"];
        $b["info"] = $row["info"];
        $b["status"] = $row["status"];
        $b["img"] = $row["img"];
        array_push($a,$b);
    }
    echo json_encode($a);
}



?>