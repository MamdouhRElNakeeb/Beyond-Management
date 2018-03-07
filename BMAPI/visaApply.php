<?php
/**
 * Created by PhpStorm.
 * User: mamdouhelnakeeb
 * Date: 12/8/17
 * Time: 10:19 AM
 */

$customerId = htmlentities($_REQUEST["customerId"]);
$visaName = htmlentities($_REQUEST["visaName"]);
$visaType = htmlentities($_REQUEST["visaType"]);
$payId = htmlentities($_REQUEST["payId"]);
$seekFor = htmlentities($_REQUEST["seekFor"]);

if (empty($customerId) || empty($visaName) || empty($visaType) || empty($payId)){
    $returnArray["success"] = false;
    $returnArray["message"] = "Missing Fields!";
    echo json_encode($returnArray);
    return;
}


require ("secure/access.php");
require ("secure/bmconn.php");


$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();

$user = $access->selectApplicantWithCustomerId($customerId);

if ($user){

    $result = $access->selectApplication($user["id"]);

    if ($result){
        while ($row = mysqli_fetch_array($result)) {

            if ($row["visa"] === $visaName && $row["type"] === $visaType && (time() - strtotime($row["created_at"]) < 43200 /* 30 days*/)){

                $returnArray["success"] = false;
                $returnArray["msg"] = "You have already applied for this VISA within this month";
                echo $returnArray;
                exit;

            }

        }
    }

    $result = $access->addApplication($user["id"], $visaName, $visaType, $payId, $seekFor);

    if ($result){

        require_once("./payments/btsetup.php");

        $transaction = Braintree_Transaction::find($payId);

        if ($transaction){
            $payment = $access->addPayment($result, $payId, $transaction->amount, $transaction->status, $visaName. ' ' .$visaType);

            if (!$payment){
                $returnArray["success"] = false;
                $returnArray["msg"] = "Some error occurred during adding payment";
                echo $returnArray;
                exit;
            }
        }
        else {
            $returnArray["success"] = false;
            $returnArray["msg"] = "Transaction is not existed!";
            echo $returnArray;
            exit;
        }

        //
        $url = ADMIN. 'sendMail.php';

        // what post fields?
        $data = array('to' => ADMIN_EMAIL,
            'from' => NOREPLAY_EMAIL,
            'subject' => "New VISA Application",
            'msg' => $user["name"] ." applied for " . $visaName . " " .$visaType . " with payment ID: " . $payId);

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

        $returnArray["success"] = true;
        echo $returnArray;
        exit;
    }
    else {
        $returnArray["success"] = false;
        $returnArray["msg"] = "Some error occurred during adding application";
        echo $returnArray;
        exit;
    }

}
else {
    $returnArray["success"] = false;
    $returnArray["msg"] = "User is not existed!";
    echo $returnArray;
    exit;
}

?>