<?php
	require_once("./btsetup.php");	 

	$nonceFromTheClient = $_POST["nonce"];
	$amountToCharge = $_POST["amount"];
	
	$result = Braintree_Transaction::sale([
	    'amount' => $amountToCharge,
	    'paymentMethodNonce' => $nonceFromTheClient,
        'options' => [
            'submitForSettlement' => True
        ]
	]);
	
	
	if ($result->success) {
		if ($result->transaction) {	
			$response['data'] = $result->transaction;
			$myJSON = json_encode($response, true);
			echo $myJSON; 
		} else {
			echo "error";
		}
	} else {
		foreach ($result->errors->deepAll() as $error) {
			echo $error->message;
		}
		echo '{"OrderStatus": [{"status":"0"}]}';
		exit;   
	}
?>