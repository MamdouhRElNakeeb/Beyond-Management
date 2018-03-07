<?php
/**
 * Created by PhpStorm.
 * User: mamdouhelnakeeb
 * Date: 1/14/18
 * Time: 4:53 PM
 */


//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require ('PHPMailer/PHPMailer.php');
require ('PHPMailer/SMTP.php');
require ('PHPMailer/Exception.php');

$to = htmlentities($_REQUEST["to"]);
$from = htmlentities($_REQUEST["from"]);
$pass = htmlentities($_REQUEST["pass"]);
$host = htmlentities($_REQUEST["host"]);
$subject = htmlentities($_REQUEST["subject"]);
$msg = htmlentities($_REQUEST["msg"]);

if (empty($to) || empty($from) || empty($subject) || empty($msg)){

    $returnArray["error"] = TRUE;
    $returnArray["status"] = "400";
    $returnArray["message"] = "Missing Fields!";
    echo json_encode($returnArray);
    return;
}


$html = '<!doctype html>' .'</head><body>';
$html .= '<html>';
$html .= '<head>';
$html .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
$html .= '</head>';
$html .= '<body>';

$html .= html_entity_decode($msg);

$html .= '</body>';
$html .= '</html>';

if (empty($host) && empty($pass)){

    // To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Create email headers
    $headers .= 'From: '.$from."\r\n".
        'Reply-To: '.$from."\r\n" .
        'X-Mailer: PHP/' . phpversion();



// Sending email
    if(mail($to, $subject, $html, $headers)){
        echo 'Message has been sent successfully';
    } else{
        echo 'Unable to send msg. Please try again.';
    }
}
else{

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
//date_default_timezone_set('Etc/UTC');

    $mail = new PHPMailer;

//Enable SMTP debugging.
//$mail->SMTPDebug = 3;
//Set PHPMailer to use SMTP.
    $mail->isSMTP();
//Set SMTP host name
    $mail->Host = $host;
//Set this to true if SMTP host requires authentication to send email
    $mail->SMTPAuth = true;
//Provide username and password
    $mail->Username = $from;
    $mail->Password = $pass;
//If SMTP requires TLS encryption then set it
    $mail->SMTPSecure = "tls";
//Set TCP port to connect to
    $mail->Port = 587;

    $mail->From = $from;
    $mail->FromName = "Beyond Management";

    $mail->addAddress($to);

    $mail->isHTML(true);

    $mail->Subject = $subject;
    $mail->Body = $html;
    $mail->AltBody = "Beyond Management Team";

    if(!$mail->send())
    {
        echo "Mailer Error: check email configuration";
    }
    else
    {
        echo "Message has been sent successfully";
    }

}




?>