<?php
/**
 * Created by PhpStorm.
 * User: mamdouhelnakeeb
 * Date: 12/8/17
 * Time: 10:19 AM
 */

$customerId = htmlentities($_REQUEST["customerId"]);
$payId = htmlentities($_REQUEST["payId"]);

if (empty($customerId) || empty($payId)){
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

    $result = $access->addSkypeRequest($user["id"], $payId, "skype");

    if ($result){

        require_once("./payments/btsetup.php");

        $transaction = Braintree_Transaction::find($payId);

        if ($transaction){
            $payment = $access->addPayment($result, $payId, $transaction->amount, $transaction->status, "skype");

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

        $returnArray["success"] = true;
        echo $returnArray;
        exit;
    }
    else {
        $returnArray["success"] = false;
        $returnArray["msg"] = "Some error occurred during adding Skype request";
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