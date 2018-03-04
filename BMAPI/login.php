<?php
/**
 * Created by PhpStorm.
 * User: nakeebimac
 * Date: 9/1/17
 * Time: 9:49 PM
 */

$email = htmlentities($_REQUEST["email"]);
$password = htmlentities($_REQUEST["password"]);
$regID = htmlentities($_REQUEST["token"]);

if (empty($email) || empty($password)){

    $returnArray["status"] = "400";
    $returnArray["message"] = "Missing Fields!";
    echo json_encode($returnArray);
    return;
}

require ("secure/access.php");
require ("secure/bmconn.php");

$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();

$user = $access->selectApplicant($email);

if ($user){

    // verifying user password
    $salt = $user['salt'];
    $secured_password = $user['password'];
    $hash = $access->checkhashSSHA($salt, $password);

    // check for password equality
    if ($hash == $secured_password){

        $returnArray["error"] = FALSE;
        $returnArray["message"] = "Login is Successful";
        $returnArray["id"] = $user["id"];

        $returnArray["fname"] = $user["fname"];
        $returnArray["mname"] = $user["mname"];
        $returnArray["lname"] = $user["lname"];

        $returnArray["phone"] = $user["phone"];
        $returnArray["strAdd"] = $user["str_address"];
        $returnArray["city"] = $user["city"];
        $returnArray["state"] = $user["state"];
        $returnArray["zipCode"] = $user["zip_code"];
        $returnArray["country"] = $user["country"];

        $returnArray["email"] = $user["email"];

        $returnArray["customer_id"] = $user["customer_id"];
        $access->updateApplicantWithRegID($regID, $user["id"]);
    }
    else{
        $returnArray["error"] = TRUE;
        $returnArray["message"] = "Password is incorrect";
    }
}
else{

    $returnArray["error"] = TRUE;
    $returnArray["message"] = "User not found!";
}

$access->disconnect();

echo json_encode($returnArray);

?>