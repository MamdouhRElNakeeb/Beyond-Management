<?php 
	require_once 'lib/Braintree.php';
    Braintree_Configuration::environment('sandbox');
    Braintree_Configuration::merchantId('4xsstq4y5qj8xxt4');
    Braintree_Configuration::publicKey('9cxsfdng2g48426k');
    Braintree_Configuration::privateKey('168078161c84a6efacbeb62c983547dd');
	$Braintree_Master_Merchant_Account_ID = 'Beyond Management';
?>