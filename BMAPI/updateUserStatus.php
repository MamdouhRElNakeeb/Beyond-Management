<?php
/**
 * Created by PhpStorm.
 * User: mamdouhelnakeeb
 * Date: 1/8/18
 * Time: 6:56 AM
 */

session_start();

if ($_SESSION['valid'] != true){


    header('location: login.php');
    return;
}

$id = htmlentities($_REQUEST["id"]);
$status = htmlentities($_REQUEST["status"]);

require ("secure/access.php");
require ("secure/bmconn.php");


$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();

$result = $access->updateApplicantStatus($status, $id);

if ($result){

    $returnArray["error"] = FALSE;
    $returnArray["message"] = "User status is updated successfully";

}
else{

    $returnArray["error"] = TRUE;
    $returnArray["message"] = "Failed to update user status!";
}

$access->disconnect();

echo json_encode($returnArray);

?>