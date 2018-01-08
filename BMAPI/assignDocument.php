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


$doc_id = htmlentities($_REQUEST["doc_id"]);
$app_id = htmlentities($_REQUEST["app_id"]);


require ("secure/access.php");
require ("secure/bmconn.php");


$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();

$result = $access->assignDocToUser($doc_id, $app_id);

if ($result){

    $applicant = $access->selectApplicantFromAppID($app_id);

    if ($applicant){

        $returnArray["error"] = FALSE;

        $sendAPNPro = $access->sendAPNPro("Your Application Update", "New document is required, please check your application requirements", $applicant["reg_id"]);
        $sendAPNDev = $access->sendAPNDev("Your Application Update", "New document is required, please check your application requirements", $applicant["reg_id"]);


        $returnArray["message"] = $sendAPNPro["msg"];

    }
    else{

        $returnArray["error"] = TRUE;
        $returnArray["message"] = "User not found!";
    }

}
else{

    $returnArray["error"] = TRUE;
    $returnArray["message"] = "Failed assign document!";
}


$access->disconnect();

echo json_encode($returnArray);

?>