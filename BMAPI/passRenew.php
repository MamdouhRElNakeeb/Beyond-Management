<?php
/**
 * Created by PhpStorm.
 * User: nakeebimac
 * Date: 9/1/17
 * Time: 9:49 PM
 */

$key = htmlentities($_REQUEST["key"]);
$reset = htmlentities($_REQUEST["reset"]);
$email = htmlentities($_REQUEST["email"]);
$password = htmlentities($_REQUEST["password"]);

if ($_POST['key'] && $_POST['reset']){

    $returnArray["status"] = "400";
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


$user = $access->selectApplicant($email);

if ($user){

    $passwordUpdate = $access->updateApplicantWithPass($secured_password, $salt, $user["id"]);
    $access->disconnect();

    if($passwordUpdate){
        $returnArray["error"] = FALSE;
        $returnArray["message"] = "Password have been reset successfully!";
        echo "Password have been reset successfully!";
        return;
//        $access->updatePassResetStatus($id);

    }
    else{
        // password already reset
        $returnArray["error"] = TRUE;
        $returnArray["message"] = "Failed to update password, Try again later!";
        echo "Failed to update password, Try again later!";
        return;
    }

}
else{
// password already reset
    $returnArray["error"] = TRUE;
    $returnArray["message"] = "No password reset request submitted, Reset it again";
    echo "No password reset request submitted, Reset it again";
    return;
}


//echo json_encode($returnArray);

?>