<?php
	require_once("./btsetup.php");	

	if (!(isset($_POST["customerId"]) || isset($_POST["payment_method_nonce"]))){

	    return;
    }

    if (!empty($_POST["amount"])) {
        $result = Braintree_Transaction::sale([
            'amount' => $_POST["amount"],
            'paymentMethodNonce' => $_POST["payment_method_nonce"],
            'options' => [
                'storeInVaultOnSuccess' => true,
                'submitForSettlement' => True
            ]
        ]);

        if ($result->success){
            if ($result->transaction) {
                $response['data'] = $result->transaction;
                $response['paySuccess'] = true;

                $url = 'http://bm.nakeeb.me/skypeRequest.php';

                // what post fields?
                $data = array('customerId' => $_POST["customerId"],
                                'payId' => $result->transaction->id);

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
                if (!$result["success"]){
                    $response["paySuccess"] = false;
                    $response['payError'] = $result["msg"];
                }

                // close connection
                curl_close($ch);

            } else {
                $response['paySuccess'] = false;
                $response['payError'] = "Some error occurred with your payment, Try again later." ;
            }

        }
        else {
            foreach ($result->errors->deepAll() as $error) {
                $errorFound = $error->message;
            }
            $response['paySuccess'] = false;
            $response['payError'] = $errorFound ;

        }
        $myJSON = json_encode($response, true);
        echo $myJSON;
        exit;
    }
    else {
        $result = Braintree_PaymentMethod::create([
            'customerId' => $_POST["customerId"],
            'paymentMethodNonce' => $_POST["payment_method_nonce"]
        ]);

        if ($result->success){
            $response['result'] = $result;
            $response['methodSuccess'] = true;

        }
        else {
            foreach ($result->errors->deepAll() as $error) {
                $errorFound = $error->message;
            }
            $response['methodSuccess'] = false;
            $response['methodError'] = $errorFound ;
        }
        $myJSON = json_encode($response, true);
        echo $myJSON;
        exit;
    }


?>