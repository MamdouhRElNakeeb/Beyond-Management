<?php
/**
 * Created by PhpStorm.
 * User: nakeebimac
 * Date: 9/1/17
 * Time: 9:49 PM
 */

$email = htmlentities($_REQUEST["email"]);

if (empty($email)){

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
$apiLink = ROOT;

if ($user){

    $email = md5($user['email']);
    $pass = md5($user['password']);

    $body = "<a href='".$apiLink."passwordReset.php?key=".$email."&reset=".$pass."'>Click To Reset password</a>";

    // Email confirmation
    $emailTemp = $access->getEmailTemp(1);
    $url = ADMIN. 'sendMail.php';

    // what post fields?
    $data = array('to' => $user["email"],
        'from' => $emailTemp["email"],
        'subject' => "Reset your password",
        'msg' => html_entity_decode($body),
        'host' => $emailTemp["host"],
        'pass' => $emailTemp["password"]);

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


    $returnArray["error"] = FALSE;
    $returnArray["message"] = "Password is reset successfully, Check your E-mail";


}
else{
// password already reset
    $returnArray["error"] = TRUE;
    $returnArray["message"] = "No password reset request submitted, Reset it again";
}

$access->disconnect();

echo json_encode($returnArray);

?>
