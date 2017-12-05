<?php
/**
 * Created by PhpStorm.
 * User: nakeebimac
 * Date: 9/3/17
 * Time: 9:06 AM
 */


    session_start();
    if(session_destroy()) {
        header("Location: index.php"); // Redirecting To Home Page
    }
?>
