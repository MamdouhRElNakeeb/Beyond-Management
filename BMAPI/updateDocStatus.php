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


$req_id = htmlentities($_REQUEST["req_id"]);
$status = htmlentities($_REQUEST["status"]);

require ("secure/access.php");
require ("secure/bmconn.php");


$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();

$result = $access->updateDocStatus($req_id, $status);

if ($result){

    $applicant = $access->selectApplicantFromDocID($req_id);

    if ($applicant){

        $returnArray["error"] = FALSE;

        $sendAPNPro = $access->sendAPNPro("Document Status Update", "Your document status is changed to " .$status, $applicant["reg_id"]);
        $sendAPNDev = $access->sendAPNDev("Document Status Update", "Your document status is changed to " .$status, $applicant["reg_id"]);


        $returnArray["message"] = $sendAPNPro["msg"];

    }
    else{

        $returnArray["error"] = TRUE;
        $returnArray["message"] = "User not found!";
    }

}
else{

    $returnArray["error"] = TRUE;
    $returnArray["message"] = "Failed to update document status!";
}


$access->disconnect();

echo json_encode($returnArray);

?>