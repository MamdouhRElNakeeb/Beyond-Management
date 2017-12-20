<?php   
   require_once("./btsetup.php");	
   
   $result = Braintree_MerchantAccount::create(
			array(
				'individual' => array(
					'firstName' => $_POST['submerchant_firstName'],
					'lastName' => $_POST['submerchant_lastName'],
					'email' => $_POST['submerchant_email'],
                    'phone' => $_POST['submerchant_phone'],
					'dateOfBirth' => $_POST['submerchant_dateOfBirth'],
					'address' => array(
						'streetAddress' => $_POST['submerchant_streetAddress'],
						'locality' => $_POST['submerchant_locality'],
						'region' => $_POST['submerchant_region'],
						'postalCode' => $_POST['submerchant_postalCode'],
					)
				),                       
				'funding' => array(
					'descriptor' => $_POST['submerchant_firstName'] . " " . $_POST['submerchant_lastName'],
					'destination' => Braintree_MerchantAccount::FUNDING_DESTINATION_BANK,
					'email' => $_POST['submerchant_funding_email'],
					'mobilePhone' => $_POST['submerchant_funding_mobilePhone'],
					'accountNumber' => $_POST['submerchant_funding_accountNumber'],
					'routingNumber' => $_POST['submerchant_funding_routingNumber'],
				),
				'tosAccepted' => true,                         
				//'masterMerchantAccountId' => 'ONTHEGO_marketplace', //mysql_real_escape_string($_POST['masterMerchantAccountId']),
				'masterMerchantAccountId' => $Braintree_Master_Merchant_Account_ID,
				//$_POST['masterMerchantAccountId'],
				//'id' => $_POST['submerchant_user_name'],
			)
	);
	if ($result->success){
		$response['submerchant_id'] = $result->merchantAccount->id;
		$myJSON = json_encode($response, true);
		echo $myJSON; 
		exit;
	} else {
		foreach ($result->errors->deepAll() as $error) {
			$errorFound = $error->message;
		}
		echo $errorFound;
		exit;
	}
   
   
   //print_r($_POST);   
?>
