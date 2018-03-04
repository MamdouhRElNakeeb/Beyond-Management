<?php
/**
 * Created by PhpStorm.
 * User: nakeebimac
 * Date: 9/1/17
 * Time: 9:49 PM
 */

$fName = htmlentities($_REQUEST["fName"]);
$mName = htmlentities($_REQUEST["mName"]);
$lName = htmlentities($_REQUEST["lName"]);

$phone = htmlentities($_REQUEST["phone"]);
$strAdd = htmlentities($_REQUEST["strAdd"]);
$city = htmlentities($_REQUEST["city"]);
$state = htmlentities($_REQUEST["state"]);
$zipCode = htmlentities($_REQUEST["zipCode"]);
$country = htmlentities($_REQUEST["country"]);

$email = htmlentities($_REQUEST["email"]);
$password = htmlentities($_REQUEST["password"]);
$regID = htmlentities($_REQUEST["token"]);

if (empty($fName) || empty($email) || empty($password) || empty($phone) || empty($strAdd)){
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

$result = $access->registerApplicant($fName, $mName, $lName,
    $email, $secured_password, $salt,
    $phone, $strAdd, $city, $state, $zipCode, $country, $regID);

if ($result){

    $user = $access->selectApplicant($email);
    $returnArray["error"] = FALSE;
    $returnArray["message"] = "Registration is Successful";
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

    $url = PAY.'register_customer.php';

    // what post fields?
    $data = array('name' => $user["name"],
        'email' => $user["email"],
        'phone' => $user["phone"]);

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

    $access->updateApplicantWithCustId($user["id"], $result);


    $returnArray["customer_id"] = $result;
    // close connection
    curl_close($ch);

    // Email confirmation
    $emailTemp = $access->getEmailTemp(1);
    $url = ADMIN. 'sendMail.php';

    // what post fields?
    $data = array('to' => $user["email"],
        'from' => $emailTemp["email"],
        'subject' => $emailTemp["subject"],
        'msg' => html_entity_decode($emailTemp["msg"]),
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

    $returnArray["email_msg"] = $result;
//
    $url = ADMIN. 'sendMail.php';

    // what post fields?
    $data = array('to' => ADMIN_EMAIL,
        'from' => NOREPLAY_EMAIL,
        'subject' => "New Applicant Registration",
        'msg' => "A new applicant registered \n" . "Name: ". $user["name"] . "\n" . "E-mail" .$user["email"] . "\nPhone:" . $user["phone"]);

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


}
else{
    $returnArray["error"] = TRUE;
    $returnArray["message"] = "User already existed!";
}

$access->disconnect();

echo json_encode($returnArray);

?>