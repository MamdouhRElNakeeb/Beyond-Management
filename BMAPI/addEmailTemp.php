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
    echo "Not Logged in";
    return;
}

$id = htmlentities($_REQUEST["id"]);
$name = htmlentities($_REQUEST["name"]);
$email = htmlentities($_REQUEST["email"]);
$subject = htmlentities($_REQUEST["subject"]);
$msg = htmlentities($_REQUEST["msg"]);
$host = htmlentities($_REQUEST["host"]);
$password = htmlentities($_REQUEST["password"]);

if (empty($name)){

    $returnArray["error"] = TRUE;
    $returnArray["msg"] = "Name is missing";
    echo json_encode($returnArray);
    return;
}
if (empty($email)){

    $returnArray["error"] = TRUE;
    $returnArray["msg"] = "E-mail is missing";
    echo json_encode($returnArray);
    return;
}
if (empty($subject)){

    $returnArray["error"] = TRUE;
    $returnArray["msg"] = "Subject is missing";
    echo json_encode($returnArray);

    return;
}
//if (empty($msg)){
//
//    $returnArray["error"] = TRUE;
//    $returnArray["msg"] = "Message is missing";
//    echo json_encode($returnArray);
//    return;
//}
if (empty($host)){

    $returnArray["error"] = TRUE;
    $returnArray["msg"] = "Host is missing";
    echo json_encode($returnArray);
    return;
}
if (empty($password)){

    $returnArray["error"] = TRUE;
    $returnArray["msg"] = "Password is missing";
    echo json_encode($returnArray);
    return;
}

require ("secure/access.php");
require ("secure/bmconn.php");


$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();

if (empty($id)){

    $result = $access->addEmailTemp($name, $email, $subject, $msg);

    if ($result){

        $returnArray["error"] = FALSE;
        $returnArray["msg"] = "Email Template Added Successfully!";
    }
    else{
        $returnArray["error"] = TRUE;
        $returnArray["msg"] = "An error occurred during adding email template...!!";
    }

}
else{

    if (empty($msg)){
        $result = $access->updateEmailTemp($name, $email, $subject, $host, $password, $id);
    }
    else{
        $result = $access->updateEmailTempWithMsg($name, $email, $subject, $msg, $host, $password, $id);
    }

    if ($result) {
        $returnArray["error"] = FALSE;
        $returnArray["msg"] = "Email Template updated Successfully!";
    }
    else{
        $returnArray["error"] = TRUE;
        $returnArray["msg"] = "An error occurred during updating email template...!!";
    }
}

$access->disconnect();
echo json_encode($returnArray);

?>