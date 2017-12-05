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
$title = htmlentities($_REQUEST["name"]);
$content = htmlentities($_REQUEST["info"]);


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
            ) && ($_FILES["file"]["size"] < 4000000)//Approx. 100kb files can be uploaded.
            && in_array($file_extension, $validextensions)) {
            if ($_FILES["file"]["error"] > 0)
            {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
            }
            else
            {
                if (file_exists("DocsImgs/" . $_FILES["file"]["name"])) {
                    echo $_FILES["file"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
                }
                else
                {
                    $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
                    $targetPath = "DocsImgs/".$_FILES['file']['name']; // Target path where file is to be stored
                    move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
                    echo "<span id='success'>Image is updated Successfully...\n</span><br/>";
                    echo "<span id='success'>Document is updated Successfully...</span><br/>";
                    $access->updateDocWithImg($title, $content, $_FILES["file"]["name"], $id);
                }
            }
        }
        else
        {
            echo "<span id='invalid'>***Invalid image Size or Type***<span>";
        }
    }
    else{
        $access->updateDoc($title, $content, $id);

        echo "<span id='success'>Document is updated Successfully...</span><br/>";

    }
}
else{

    if(isset($_FILES["file"]["type"]))
    {
        $validextensions = array("jpeg", "jpg", "png");
        $temporary = explode(".", $_FILES["file"]["name"]);
        $file_extension = end($temporary);
        if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")
            ) && ($_FILES["file"]["size"] < 2000000)//Approx. 100kb files can be uploaded.
            && in_array($file_extension, $validextensions)) {
            if ($_FILES["file"]["error"] > 0)
            {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
            }
            else
            {
                if (file_exists("DocsImgs/" . $_FILES["file"]["name"])) {
                    echo $_FILES["file"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
                }
                else
                {
                    $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
                    $targetPath = "DocsImgs/".$_FILES['file']['name']; // Target path where file is to be stored
                    move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
                    echo "<span id='success'>Document is added Successfully...</span><br/>";
                    $access->addDoc($title, $content, $_FILES["file"]["name"]);
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