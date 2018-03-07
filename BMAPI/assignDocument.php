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

error_reporting(E_ERROR | E_PARSE);

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


        $emailTemp = $access->getEmailTemp(3);
        $url = ADMIN.'sendMail.php';

        // what post fields?
        $data = array('to' => $applicant["email"],
            'from' => $emailTemp["email"],
            'subject' => $emailTemp["subject"],
            'msg' => $emailTemp["msg"],
            'pass' => $emailTemp["password"],
            'host' => $emailTemp["host"]);

        // build the urlencoded data
        $postvars = http_build_query($data);

        // open connection
        $ch = curl_init();

        // set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);


        // execute post
        $result = curl_exec($ch);
        curl_close($ch);

        $returnArray["message"] = $result;

    }
    else{

        $returnArray["error"] = TRUE;
        $returnArray["message"] = "User not found!";
    }

}
else{

    $returnArray["error"] = TRUE;
    $returnArray["message"] = "Failed to assign document!";
}


$access->disconnect();

echo json_encode($returnArray);

?>