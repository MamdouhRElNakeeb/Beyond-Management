<?php
/**
 * Created by PhpStorm.
 * User: mamdouhelnakeeb
 * Date: 12/8/17
 * Time: 4:48 AM
 */

$userID = htmlentities($_REQUEST["user_id"]);

if (empty($userID)){

    $returnArray["error"] = TRUE;
    $returnArray["message"] = "Missing Fields!";
    echo json_encode($returnArray);
    return;
}

require ("secure/access.php");
require ("secure/bmconn.php");

$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();

$applicant = $access->selectApplicantWithId($userID);

if ($applicant){


    if ($applicant["status"] === "activated"){

        $result = $access->getTableContent("immigration");

        $a = array();
        $b = array();

        if ($result != false){
            while ($row = mysqli_fetch_array($result)) {
                $b["id"] = $row["id"];
                $b["name"] = $row["name"];
                $b["info"] = $row["info"];
                $b["img"] = $row["img"];
                $b["basic_info"] = $row["basic_info"];
                $b["basic_price"] = $row["basic_price"];
                $b["inter_info"] = $row["inter_info"];
                $b["inter_price"] = $row["inter_price"];
                $b["advanced_info"] = $row["advanced_info"];
                $b["advanced_price"] = $row["advanced_price"];
                array_push($a,$b);
            }

            $returnArray["error"] = FALSE;
            $returnArray["services"] = $a;
        }
        else{

            $returnArray["error"] = TRUE;
            $returnArray["message"] = "There's no available VISA services now, Try again later!";

        }

    }
    else{

        $returnArray["error"] = TRUE;
        $returnArray["message"] = "Account is not activated, Please contact us for more details.";

    }


}
else{

    $returnArray["error"] = TRUE;
    $returnArray["message"] = "User is not existed, Please contact us for more details.";

}


$access->disconnect();
echo json_encode($returnArray);




?>