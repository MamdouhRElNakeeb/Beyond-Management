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


$id = htmlentities($_REQUEST["id"]);
$email = htmlentities($_REQUEST["email"]);
$response = htmlentities($_REQUEST["response"]);

require ("secure/access.php");
require ("secure/bmconn.php");


$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();

$result = $access->respondMsg($id, $response);

if ($result){

    $applicant = $access->selectApplicant($email);

    if ($applicant){

        $returnArray["error"] = FALSE;

        $sendAPNPro = $access->sendAPNPro("BMG Support", $response, $applicant["reg_id"]);
        $sendAPNDev = $access->sendAPNDev("BMG Support", $response, $applicant["reg_id"]);

        $returnArray["message"] = $sendAPNPro["msg"];


        $emailTemp = $access->getEmailTemp(2);
        $url = ADMIN.'sendMail.php';

        // what post fields?
        $data = array('to' => $email,
            'from' => $emailTemp["email"],
            'subject' => $emailTemp["subject"],
            'msg' => $response);

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
        curl_exec($ch);
        curl_close($ch);

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