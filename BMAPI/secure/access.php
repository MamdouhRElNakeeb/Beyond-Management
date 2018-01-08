<?php
/**
 * Created by PhpStorm.
 * User: nakeebimac
 * Date: 9/1/17
 * Time: 11:17 AM
 */


class access{
    //connection global variables
    var $host = null;
    var $username = null;
    var $dpass = null;
    var $dname = null;
    var $conn = null;
    var $result = null;

    public function __construct($dbhost, $dbuser, $dbpass, $dbname){
        $this->host = $dbhost;
        $this->username = $dbuser;
        $this->dpass = $dbpass;
        $this->dname = $dbname;
    }
    public function connect(){
        $this->conn = new mysqli($this->host, $this->username, $this->dpass, $this->dname);
        if (mysqli_connect_errno()) {
            echo "Failed to connect to Database: " . mysqli_connect_error();
        }
        $this->conn->set_charset("utf8");
    }
    public function disconnect(){
        if($this->conn != null){
            $this->conn->close();
        }
    }
    public function getTableContent($tableName){
        $sql = "SELECT * FROM $tableName";
        $result = $this->conn->query($sql);
        return $result;
    }


    public function removeImg($tableName, $dir, $id){
        $sql1 = "SELECT * FROM $tableName WHERE id = $id";
        $result1 = $this->conn->query($sql1);

        $row = mysqli_fetch_array($result1);
        $path = $_SERVER['DOCUMENT_ROOT'] . "/api/". $dir . "/". $row['img'];

        unlink($path);

        $sql = "DELETE FROM $tableName WHERE id = $id";
        $result = $this->conn->query($sql);

        return $result;
    }


    // select user form database
    public function selectUser($username){
        $sql = "SELECT * FROM users WHERE username = '".$username."' ";
        $result = $this->conn->query($sql);
        if($result !=null && (mysqli_num_rows($result) >=1)){
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if(!empty($row)){
                $returnArray = $row;
                return $returnArray;
            }
        }
    }

    // insert application into database
    public function addApplication($applicant_id, $visaName, $visaType, $payId){
        $sql = "INSERT INTO applications (visa_id, applicant_id, type, payment_id)
                SELECT id, '".$applicant_id."', '".$visaType."', '".$payId."'
                FROM immigration WHERE name = '".$visaName."'";


        $result = $this->conn->query($sql);
        $result = $this->conn->insert_id;
        return $result;

    }

    // select application from database
    public function selectApplication($applicant_id){
        $sql = "SELECT * FROM applications WHERE applicant_id = '".$applicant_id."' ";
        $result = $this->conn->query($sql);
        if($result !=null && (mysqli_num_rows($result) >=1)){
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if(!empty($row)){
                $returnArray = $row;
                return $returnArray;
            }
        }
    }

    // select applications from database
    public function getApplications(){
        $sql = "SELECT applications.id, immigration.name AS visa, applications.type, 
                  applications.status AS app_status, applications.created_at,
	              applicants.name, applicants.email, payments.amount, 
	              payments.payment_id, payments.status AS pay_status
                FROM applications
                INNER JOIN applicants ON applications.applicant_id = applicants.id
                INNER JOIN payments ON applications.payment_id = payments.payment_id
                INNER JOIN immigration ON applications.visa_id = immigration.id";

        $result = $this->conn->query($sql);

        return $result;
    }

    // select applications from database
    public function getUserApplications($id){
        $sql = "SELECT applications.id, applications.type, applications.status, applications.created_at,
                  immigration.name, immigration.img
                FROM applications
                INNER JOIN immigration ON applications.visa_id = immigration.id
                WHERE applicant_id = $id";

        $result = $this->conn->query($sql);

        return $result;
    }

    // select applications from database
    public function getAppRequirements($id){
        $sql = "SELECT requirements.id, documents.name, documents.img, documents.info, requirements.status
                FROM requirements
                INNER JOIN documents ON requirements.document_id = documents.id
                WHERE application_id = $id";

        $result = $this->conn->query($sql);

        return $result;
    }

    // insert payment into database
    public function addPayment($application_id, $payId, $amount, $status, $service){
        $sql = "INSERT INTO payments SET application_id=?, payment_id=?, amount=?, status=?, service=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 8 parameters of type string to be placed in $sql command
        $statement->bind_param("sssss", $application_id, $payId, $amount, $status, $service);
        $returnValue = $statement->execute();
        return $returnValue;
    }


    public function addSkypeRequest($applicant_id, $payId){
        $sql = "INSERT INTO skype SET applicant_id=?, payment_id=?";

        $statement = $this->conn->prepare($sql);
        if(!$statement){
            echo $statement->error;
            throw new Exception($statement->error);
        }
        // bind 9 parameters of type string to be placed in $sql command
        $statement->bind_param("ss", $applicant_id, $payId);
        $returnValue = $statement->execute();
        $returnValue = $statement->insert_id;
        return $returnValue;

    }

    // insert service into database
    public function insertService($name, $info, $basic_info, $basic_price, $inter_info, $inter_price, $adv_info, $adv_price, $img){
        $sql = "INSERT INTO immigration SET name=?, info=?, basic_info=?, basic_price=?, inter_info=?, inter_price=?, advanced_info=?, advanced_price=?, img=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            echo $statement->error;
            throw new Exception($statement->error);
        }
        // bind 9 parameters of type string to be placed in $sql command
        $statement->bind_param("sssssssss", $name, $info, $basic_info, $basic_price, $inter_info, $inter_price, $adv_info, $adv_price, $img);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    // update service in database
    public function updateService($name, $info, $basic_info, $basic_price, $inter_info, $inter_price, $adv_info, $adv_price, $id){
        $sql = "UPDATE immigration SET name=?, info=?, basic_info=?, basic_price=?, inter_info=?, inter_price=?, advanced_info=?, advanced_price=? WHERE id=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 2 parameters of type string to be placed in $sql command
        $statement->bind_param("sssssssss", $name, $info, $basic_info, $basic_price, $inter_info, $inter_price, $adv_info, $adv_price, $id);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    // update service in database
    public function updateServiceWithImg($name, $info, $basic_info, $basic_price, $inter_info, $inter_price, $adv_info, $adv_price, $img, $id){
        $sql = "UPDATE immigration SET name=?, info=?, basic_info=?, basic_price=?, inter_info=?, inter_price=?, advanced_info=?, advanced_price=?, img=? WHERE id=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 2 parameters of type string to be placed in $sql command
        $statement->bind_param("ssssssssss", $name, $info, $basic_info, $basic_price, $inter_info, $inter_price, $adv_info, $adv_price, $img, $id);
        $returnValue = $statement->execute();
        return $returnValue;
    }


    // update doc in database
    public function submitRequirement($name, $status, $type, $id){
        $sql = "UPDATE requirements SET url=?, status=?, type=? WHERE id=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 2 parameters of type string to be placed in $sql command
        $statement->bind_param("ssss", $name, $status, $type, $id);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    // insert doc into database
    public function assignDocToUser($doc_id, $app_id){
        $sql = "INSERT INTO requirements SET application_id=?, document_id=?";

        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 2 parameters of type string to be placed in $sql command
        $statement->bind_param("ss", $app_id, $doc_id);
        $returnValue = $statement->execute();
        return $returnValue;

    }

    // update doc in database
    public function getDocSubmissions($id){
        $sql = "SELECT requirements.id, requirements.status, requirements.url, requirements.type,
                documents.name
                FROM requirements
                INNER JOIN  documents ON requirements.document_id = documents.id
                WHERE requirements.application_id = $id";

        $result = $this->conn->query($sql);

        return $result;
    }

    // update doc in database
    public function updateDocStatus($req_id, $status){
        $sql = "UPDATE requirements SET status=? WHERE id=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 2 parameters of type string to be placed in $sql command
        $statement->bind_param("ss", $status,  $req_id);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    // insert doc into database
    public function addDoc($name, $info, $img){
        $sql = "INSERT INTO documents SET name=?, info=?, img=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 2 parameters of type string to be placed in $sql command
        $statement->bind_param("sss", $name, $info, $img);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    // update doc in database
    public function updateDoc($name, $info, $id){
        $sql = "UPDATE documents SET name=?, info=? WHERE id=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 2 parameters of type string to be placed in $sql command
        $statement->bind_param("sss", $name, $info, $id);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    // update service in database
    public function updateDocWithImg($name, $info, $img, $id){
        $sql = "UPDATE documents SET name=?, info=?, img=? WHERE id=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 2 parameters of type string to be placed in $sql command
        $statement->bind_param("ssss", $name, $info, $img, $id);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    // insert offer into database
    public function addUser($name, $username1, $password, $salt, $role){
        $sql = "INSERT INTO users SET name=?, username=?, password=?, salt=?, role=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 2 parameters of type string to be placed in $sql command
        $statement->bind_param("sssss", $name, $username1, $password, $salt, $role);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    // insert offer into database
    public function updateUser($name, $username1, $password, $salt, $role, $id){
        $sql = "UPDATE users SET name=?, username=?, password=?, salt=?, role=? WHERE id=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 2 parameters of type string to be placed in $sql command
        $statement->bind_param("ssssss", $name, $username1, $password, $salt, $role, $id);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    // select user form database
    public function selectApplicant($email){
        $sql = "SELECT * FROM applicants WHERE email = '".$email."' ";
        $result = $this->conn->query($sql);
        if($result !=null && (mysqli_num_rows($result) >=1)){
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if(!empty($row)){
                $returnArray = $row;
                return $returnArray;
            }
        }
    }

    // select user form database
    public function selectApplicantFromDocID($req_id){
        $sql = "SELECT * 
                FROM applicants   
                INNER JOIN applications ON applications.applicant_id = applicants.id
                INNER JOIN requirements ON requirements.application_id = applications.id
                WHERE requirements.id = '".$req_id."' ";
        $result = $this->conn->query($sql);
        if($result !=null && (mysqli_num_rows($result) >=1)){
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if(!empty($row)){
                $returnArray = $row;
                return $returnArray;
            }
        }
    }

    // select user form database
    public function selectApplicantFromAppID($app_id){
        $sql = "SELECT * 
                FROM applicants   
                INNER JOIN applications ON applications.applicant_id = applicants.id
                WHERE applications.id = '".$app_id."' ";
        $result = $this->conn->query($sql);
        if($result !=null && (mysqli_num_rows($result) >=1)){
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if(!empty($row)){
                $returnArray = $row;
                return $returnArray;
            }
        }
    }

    public function updateApplicantWithRegID($regID, $id){
        $sql = "UPDATE applicants SET reg_id=? WHERE id=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 2 parameters of type string to be placed in $sql command
        $statement->bind_param("ss", $regID, $id);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    public function selectApplicantWithCustomerId($customerId){
        $sql = "SELECT * FROM applicants WHERE customer_id = '".$customerId."' ";
        $result = $this->conn->query($sql);
        if($result !=null && (mysqli_num_rows($result) >=1)){
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if(!empty($row)){
                $returnArray = $row;
                return $returnArray;
            }
        }
    }

    // insert user into database
    public function registerUser($name, $email, $password, $salt, $phone, $address, $regID){
        $sql = "INSERT INTO applicants SET name=?, email=?, password=?, salt=?, phone=?, address=?, reg_id=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 9 parameters of type string to be placed in $sql command
        $statement->bind_param("sssssss", $name, $email, $password, $salt, $phone, $address, $regID);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    public function updateApplicantWithCustId($id, $customerId){
        $result = $this->selectUser($id);
        if ($result){
            return false;
        }
        else{
            $sql = "UPDATE applicants SET customer_id=? WHERE id=?";
            $statement = $this->conn->prepare($sql);
            if(!$statement){
                throw new Exception($statement->error);
            }
            // bind 9 parameters of type string to be placed in $sql command
            $statement->bind_param("ss", $customerId, $id);
            $returnValue = $statement->execute();
            return $returnValue;
        }
    }


    // insert user into database
    public function contactMsg($userID, $msg){

            $sql = "INSERT INTO messages SET applicant_id=?, msg=?";
            $statement = $this->conn->prepare($sql);
            if(!$statement){
                throw new Exception($statement->error);
            }
            // bind 9 parameters of type string to be placed in $sql command
            $statement->bind_param("ss", $userID, $msg);
            $returnValue = $statement->execute();
            return $returnValue;

    }

    public function sendAPNPro($title, $msg, $token){

        $apnsCert = 'secure/APN_PRO.pem';
        $apnsPass = 'bm123';

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $apnsCert);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $apnsPass);

        $fp = stream_socket_client('ssl://gateway.push.apple.com:2195',
            $err,
            $errstr,
            60,
            STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT,
            $ctx);


        // Create the payload body
        $body['aps'] = array(
            'badge' => +1,
            'title' => $title,
            'alert' => $msg,
            'sound' => 'default'
        );

        $payload = json_encode($body);

        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($payload)) . $payload;

        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        if (!$result) {
            $returnArr["success"] = FALSE;
            $returnArr["msg"] = 'Message not delivered';
            $returnArr["err"] = PHP_EOL;
        }
        else {
            $returnArr["success"] = TRUE;
            $returnArr["msg"] = 'Message successfully delivered';
            $returnArr["err"] = $msg. PHP_EOL;
        }

        // Close the connection to the server
        fclose($fp);

    }

    public function sendAPNDev($title, $msg, $token){

        $apnsCert = 'secure/APN_DEV.pem';
        $apnsPass = 'bm123';

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $apnsCert);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $apnsPass);

        $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195',
            $err,
            $errstr,
            60,
            STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT,
            $ctx);


        // Create the payload body
        $body['aps'] = array(
            'badge' => +1,
            'title' => $title,
            'alert' => $msg,
            'sound' => 'default'
        );

        $payload = json_encode($body);

        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($payload)) . $payload;

        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        if (!$result) {
            $returnArr["success"] = FALSE;
            $returnArr["msg"] = 'Message not delivered';
            $returnArr["err"] = PHP_EOL;
        }
        else {
            $returnArr["success"] = TRUE;
            $returnArr["msg"] = 'Message successfully delivered';
            $returnArr["err"] = $msg. PHP_EOL;
        }

        // Close the connection to the server
        fclose($fp);

    }

    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */

    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;

    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */

    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }

}
?>