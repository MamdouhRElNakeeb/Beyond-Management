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

    $result["error"] = TRUE;
    $result["msg"] = "Name is missing";
    echo json_encode($result);
    return;
}
if (empty($email)){

    $result["error"] = TRUE;
    $result["msg"] = "E-mail is missing";
    echo json_encode($result);
    return;
}
if (empty($subject)){

    $result["error"] = TRUE;
    $result["msg"] = "Subject is missing";
    echo json_encode($result);

    return;
}
//if (empty($msg)){
//
//    $result["error"] = TRUE;
//    $result["msg"] = "Message is missing";
//    echo json_encode($result);
//    return;
//}
if (empty($host)){

    $result["error"] = TRUE;
    $result["msg"] = "Host is missing";
    echo json_encode($result);
    return;
}
if (empty($password)){

    $result["error"] = TRUE;
    $result["msg"] = "Password is missing";
    echo json_encode($result);
    return;
}

require ("secure/access.php");
require ("secure/bmconn.php");


$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();

if (empty($id)){

    $result = $access->addEmailTemp($name, $email, $subject, $msg);

    if ($result){

        $result["error"] = FALSE;
        $result["msg"] = "Email Template Added Successfully!";
    }
    else{
        $result["error"] = TRUE;
        $result["msg"] = "An error occurred during adding email template...!!";
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
        $result["error"] = FALSE;
        $result["msg"] = "Email Template updated Successfully!";
    }
}

$access->disconnect();
echo json_encode($result);

?>