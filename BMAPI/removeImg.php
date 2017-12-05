<?php
/**
 * Created by PhpStorm.
 * User: nakeebimac
 * Date: 9/3/17
 * Time: 10:31 AM
 */

session_start();

if ($_SESSION['valid'] != true){

    header('location: login.php');
    return;
}


$tblName = htmlentities($_REQUEST["tblName"]);
$dir = htmlentities($_REQUEST["dir"]);
$id = htmlentities($_REQUEST["id"]);

if (empty($id)){
    return;
}

require ("secure/access.php");
require("secure/bmconn.php");

$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();
$result = $access->removeImg($tblName, $dir, $id);

?>

