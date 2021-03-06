<?php
/**
 * Created by PhpStorm.
 * User: nakeebimac
 * Date: 9/3/17
 * Time: 7:35 PM
 */

session_start();

if ($_SESSION['valid'] != true){


    header('location: login.php');
    return;
}

$id = htmlentities($_REQUEST["id"]);
$name = htmlentities($_REQUEST["name"]);
$username = htmlentities($_REQUEST["username"]);
$password = htmlentities($_REQUEST["password"]);
$role = htmlentities($_REQUEST["role"]);

require ("secure/access.php");
require ("secure/bmconn.php");


$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();

//secure password
$hash = $access->hashSSHA($password);
$secured_password = $hash["encrypted"]; // encrypted password
$salt = $hash["salt"]; // salt

if (!empty($id))
    $result = $access->updateUser($name, $username, $secured_password, $salt, $role, $id);

if ($result){

    echo "User updated Successfully!";
}
else{

    $result = $access->addUser($name, $username, $secured_password, $salt, $role);

    if ($result){

        echo "User Added Successfully!";
    }
    else{
        echo "Error...!!";
    }

}

?>