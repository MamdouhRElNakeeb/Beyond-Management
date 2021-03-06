<?php
/**
 * Created by PhpStorm.
 * User: nakeebimac
 * Date: 9/3/17
 * Time: 7:35 PM
 */

session_start();

if ($_SESSION['valid'] != true){


    header('location: login.php');
    return;
}


$id = htmlentities($_REQUEST["id"]);
$title = htmlentities($_REQUEST["title"]);
$content = htmlentities($_REQUEST["content"]);
$address = htmlentities($_REQUEST["address"]);
$latitude = htmlentities($_REQUEST["latitude"]);
$longitude = htmlentities($_REQUEST["longitude"]);
$mobile = htmlentities($_REQUEST["mobile"]);
$username = $_SESSION['username'];

require ("secure/access.php");
require ("secure/bmconn.php");


$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();

if (!empty($id)){

    if (isset($_FILES["file"]["type"])){

        $validextensions = array("jpeg", "jpg", "png");
        $temporary = explode(".", $_FILES["file"]["name"]);
        $file_extension = end($temporary);
        if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")
            ) && ($_FILES["file"]["size"] < 200000)//Approx. 100kb files can be uploaded.
            && in_array($file_extension, $validextensions)) {
            if ($_FILES["file"]["error"] > 0)
            {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
            }
            else
            {
                if (file_exists("JobsImgs/" . $_FILES["file"]["name"])) {
                    echo $_FILES["file"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
                }
                else
                {
                    $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
                    $targetPath = "JobsImgs/".$_FILES['file']['name']; // Target path where file is to be stored
                    move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
                    echo "<span id='success'>Image is updated Successfully...\n</span><br/>";
                    echo "<span id='success'>Job is updated Successfully...</span><br/>";
                    $access->updateJobWithImg($username, $title, $content, $address, $latitude, $longitude, $mobile, $_FILES["file"]["name"], $id);
                }
            }
        }
        else
        {
            echo "<span id='invalid'>***Invalid image Size or Type***<span>";
        }
    }
    else{
        $access->updateJob($username, $title, $content, $address, $latitude, $longitude, $mobile, $id);

        echo "<span id='success'>Job is updated Successfully...</span><br/>";

    }
}
else{

    if(isset($_FILES["file"]["type"]))
    {
        $validextensions = array("jpeg", "jpg", "png");
        $temporary = explode(".", $_FILES["file"]["name"]);
        $file_extension = end($temporary);
        if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")
            ) && ($_FILES["file"]["size"] < 100000)//Approx. 100kb files can be uploaded.
            && in_array($file_extension, $validextensions)) {
            if ($_FILES["file"]["error"] > 0)
            {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
            }
            else
            {
                if (file_exists("JobsImgs/" . $_FILES["file"]["name"])) {
                    echo $_FILES["file"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
                }
                else
                {
                    $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
                    $targetPath = "JobsImgs/".$_FILES['file']['name']; // Target path where file is to be stored
                    move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
                    echo "<span id='success'>Job is added Successfully...</span><br/>";
                    $access->add_job($username, $title, $content, $address, $latitude, $longitude, $mobile, $_FILES["file"]["name"]);
                }
            }
        }
        else
        {
            echo "<span id='invalid'>***Invalid image Size or Type***<span>";
        }
    }
    else{
        echo "<span id='danger'>Please upload an image...!!</span><br/>";
    }

}

?>