<?php
	require_once("./btsetup.php");	 

	$token = $_POST["token"];
	$amountToCharge = $_POST["amount"];
	$driverPaymentId = $_POST["driverPaymentId"];
	$serviceFeeAmount = $_POST["serviceFeeAmount"];
	
	$result = Braintree_Transaction::sale([
	  'amount' => $amountToCharge,
	  'paymentMethodToken' => $token,
	  'merchantAccountId' => $driverPaymentId,
	  'serviceFeeAmount' => $serviceFeeAmount,
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