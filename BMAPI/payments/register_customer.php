<?php 
	require_once("./btsetup.php");

	$name = htmlentities($_REQUEST["name"]);
	$email = htmlentities($_REQUEST["email"]);
	$phone = htmlentities($_REQUEST["phone"]);

	$result = Braintree_Customer::create([
			 'firstName' => $name,
			 'email' => $email,
			 'phone' => $phone
		]
	);

	if ($result->success){                                                       
		$response['customer_id'] = $result->customer->id;
		$myJSON = json_encode($response);
		echo $result->customer->id;
		exit;

	} else {
		foreach ($result->errors->deepAll() as $error) {
			$errorFound = $error->message . "<br />";
		}
		echo $errorFound;
		exit;
	}
?>
