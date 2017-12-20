<?php
	require_once("./btsetup.php");


    $braintree_cust_id = htmlentities($_REQUEST["customerId"]);

	if(empty($braintree_cust_id)) {
        return;
	}

    $clientToken = Braintree_ClientToken::generate([
        "customerId" => $braintree_cust_id
    ]);

	$response['token'] =$clientToken;
	$response['customer_id']=$braintree_cust_id;
	$myJSON = json_encode($response, true);
	echo $myJSON;
	exit;
?>