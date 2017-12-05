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
$name = htmlentities($_REQUEST["name"]);
$info = htmlentities($_REQUEST["info"]);
$basic_info = htmlentities($_REQUEST["basic_info"]);
$basic_price = htmlentities($_REQUEST["basic_price"]);
$inter_info = htmlentities($_REQUEST["inter_info"]);
$inter_price = htmlentities($_REQUEST["inter_price"]);
$adv_info = htmlentities($_REQUEST["advanced_info"]);
$adv_price = htmlentities($_REQUEST["advanced_price"]);


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
                if (file_exists("ServicesImgs/" . $_FILES["file"]["name"])) {
                    echo $_FILES["file"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";
                }
                else
                {
                    $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
                    $targetPath = "ServicesImgs/".$_FILES['file']['name']; // Target path where file is to be stored
                    move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
                    echo "<span id='success'>Image is updated Successfully...\n</span><br/>";
                    echo "<span id='success'>Service is updated Successfully...</span><br/>";
                    $result = $access->updateServiceWithImg($name, $info, $basic_info, $basic_price, $inter_info, $inter_price, $adv_info, $adv_price, $_FILES["file"]["name"], $id);

                    if ($result)
                        echo "<span id='success'>Service is updated Successfully...</span><br/>";
                    else
                        echo "<span id='danger'>Failed to update service...</span><br/>";
                }
            }
        }
        else
        {
            echo "<span id='invalid'>***Invalid image Size or Type***<span>";
        }
    }
    else{
        $result = $access->updateService($name, $info, $basic_info, $basic_price, $inter_info, $inter_price, $adv_info, $adv_price, $id);

        if ($result)
            echo "<span id='success'>Service is updated Successfully...</span><br/>";
        else
            echo "<span id='danger'>Failed to update service...</span><br/>";

    }
}
else{

    if(isset($_FILES["file"]["type"]))
    {
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
                if (file_exists("ServicesImgs/" . $_FILES["file"]["name"])) {
                    echo $_FILES["file"]["name"] . "<span id='invalid'><b>already exists.</b></span> ";
                }
                else
                {
                    $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
                    $targetPath = "ServicesImgs/".$_FILES['file']['name']; // Target path where file is to be stored
                    move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file

                    $result = $access->insertService($name, $info, $basic_info, $basic_price, $inter_info, $inter_price, $adv_info, $adv_price, $_FILES["file"]["name"]);

                    if ($result)
                        echo $result["id"]."<span id='success'>Service is added Successfully...</span><br/>";
                    else
                        echo "<span id='success'>Failed to add service...</span><br/>";
                }
            }
        }
        else
        {
            echo "<span id='invalid'>***Invalid image Size or Type***<span>";
        }
    }
    else{
        $result = $access->insertService($name, $info, $basic_info, $basic_price, $inter_info, $inter_price, $adv_info, $adv_price, "");

        if ($result)
            echo "<span id='success'>Service is added Successfully...</span><br/>";
        else
            echo "<span id='success'>Failed to add service...</span><br/>";

    }

}

?>