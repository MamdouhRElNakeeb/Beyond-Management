<?php
/**
 * Created by PhpStorm.
 * User: nakeebimac
 * Date: 9/1/17
 * Time: 9:49 PM
 */

$name = htmlentities($_REQUEST["name"]);
$email = htmlentities($_REQUEST["email"]);
$phone = htmlentities($_REQUEST["phone"]);
$address = htmlentities($_REQUEST["address"]);
$password = htmlentities($_REQUEST["password"]);
$regID = htmlentities($_REQUEST["reg_id"]);

if (empty($name) || empty($email) || empty($password) || empty($phone) || empty($regID)){
    $returnArray["error"] = TRUE;
    $returnArray["message"] = "Missing Fields!";
    echo json_encode($returnArray);
    return;
}

require ("secure/access.php");
require ("secure/bmconn.php");

$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();

//secure password
$hash = $access->hashSSHA($password);
$secured_password = $hash["encrypted"]; // encrypted password
$salt = $hash["salt"]; // salt

$result = $access->registerUser($name, $email, $secured_password, $salt, $phone, $address, $regID);

if ($result){

    $user = $access->selectUser($email);
    $returnArray["error"] = FALSE;
    $returnArray["message"] = "Registration is Successful";
    $returnArray["id"] = $user["id"];
    $returnArray["name"] = $user["name"];
    $returnArray["email"] = $user["email"];
    $returnArray["phone"] = $user["phone"];

}
else{
    $returnArray["error"] = TRUE;
    $returnArray["message"] = "User already existed!";
}

$access->disconnect();

echo json_encode($returnArray);

?>