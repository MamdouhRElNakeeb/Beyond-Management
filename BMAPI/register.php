<?php
/**
 * Created by PhpStorm.
 * User: nakeebimac
 * Date: 9/1/17
 * Time: 9:49 PM
 */

$name = htmlentities($_REQUEST["name"]);
$email = htmlentities($_REQUEST["email"]);
$phone = htmlentities($_REQUEST["phone"]);
$address = htmlentities($_REQUEST["address"]);
$password = htmlentities($_REQUEST["password"]);
$regID = htmlentities($_REQUEST["token"]);

if (empty($name) || empty($email) || empty($password) || empty($phone)){
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

$result = $access->registerUser($name, $email, $secured_password, $salt, $phone, $address, $regID);

if ($result){

    $user = $access->selectApplicant($email);
    $returnArray["error"] = FALSE;
    $returnArray["message"] = "Registration is Successful";
    $returnArray["id"] = $user["id"];
    $returnArray["name"] = $user["name"];
    $returnArray["email"] = $user["email"];
    $returnArray["phone"] = $user["phone"];
    $returnArray["address"] = $user["address"];

    $url = 'http://bm.nakeeb.me/payments/register_customer.php';

    // what post fields?
    $data = array('name' => $user["name"], 'email' => $user["email"],
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


}
else{
    $returnArray["error"] = TRUE;
    $returnArray["message"] = "User already existed!";
}

$access->disconnect();

echo json_encode($returnArray);

?>