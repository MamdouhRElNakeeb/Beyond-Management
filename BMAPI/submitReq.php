<?php
/**
 * Created by PhpStorm.
 * User: mamdouhelnakeeb
 * Date: 1/8/18
 * Time: 12:18 AM
 */

$req_id = htmlentities($_REQUEST["req_id"]);
$type = htmlentities($_REQUEST["type"]);
$url = htmlentities($_REQUEST["url"]);

//if (empty($req_id) || empty($type)){
//
//    $returnArray["error"] = TRUE;
//    $returnArray["status"] = "400";
//    $returnArray["message"] = "Missing Fields!";
//    echo json_encode($returnArray);
//    return;
//}

require ("secure/access.php");
require ("secure/bmconn.php");

$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();



if ($type === "0"){

// Save the image file
    move_uploaded_file($_FILES["docFile"]["tmp_name"],
        "ReqSubmissions/".$_FILES["docFile"]["name"]);

    $upload = $access->submitRequirement($_FILES["docFile"]["name"], "waiting", $type, $req_id);
}
else{

    $upload = $access->submitRequirement($url, "waiting", $type, $req_id);
}

if ($upload){
    // Send some dummy result back to the iOS app
    $result = array();
    $result["error"] = FALSE;
    $result["message"] = "Document is submitted successfully!";

    echo json_encode($result);
}
else{
    $result = array();
    $result["error"] = TRUE;
    $result["message"] = "Failed to update document!";

    echo json_encode($result);
}

?>