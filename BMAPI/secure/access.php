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

    public function getJobs(){
        $sql = "SELECT * FROM jobs WHERE status = 'approved'";
        $result = $this->conn->query($sql);
        return $result;
    }

    public function getADs($category){
        if ($category === ""){

            $sql = "SELECT * FROM ads";
        }
        else{

            $sql = "SELECT * FROM ads WHERE category LIKE '%$category%'";
        }
        $result = $this->conn->query($sql);
        return $result;
    }

    public function removeAD($id){
        $sql1 = "SELECT * FROM ads WHERE id = $id";
        $result1 = $this->conn->query($sql1);

        $row = mysqli_fetch_array($result1);
        $path = $_SERVER['DOCUMENT_ROOT'] . "/api/ADs/".$row['img'];

        if (unlink($path)){
            $sql = "DELETE FROM ads WHERE id = $id";
            $result = $this->conn->query($sql);
        }

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

    public function getCategoryItems($category){
        $sql = "SELECT * FROM category_items WHERE category LIKE '%$category%'";
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
    public function addApplication($id, $visaName, $visaType, $payId){
        $sql = "INSERT INTO applications SET applicant_id=?, visa=?, type=?, payment_id=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 8 parameters of type string to be placed in $sql command
        $statement->bind_param("ssss", $id, $visaName, $visaType, $payId);
        $returnValue = $statement->execute();
        $returnValue = $statement->insert_id;
        return $returnValue;
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
        $sql = "SELECT applications.id, applications.visa, applications.type, 
                  applications.status AS app_status, applications.created_at,
	              applicants.name, applicants.email, payments.amount, 
	              payments.payment_id, payments.status AS pay_status
                FROM applications
                INNER JOIN applicants ON applications.applicant_id = applicants.id
                INNER JOIN payments ON applications.payment_id = payments.payment_id";

        $result = $this->conn->query($sql);

        return $result;
    }

    // insert payment into database
    public function addPayment($application_id, $payId, $amount, $status){
        $sql = "INSERT INTO payments SET application_id=?, payment_id=?, amount=?, status=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 8 parameters of type string to be placed in $sql command
        $statement->bind_param("ssss", $application_id, $payId, $amount, $status);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    // insert appointment into database
    public function approveJob($id){
        $status = "approved";
        $sql = "UPDATE jobs SET status=? WHERE id=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 8 parameters of type string to be placed in $sql command
        $statement->bind_param("ss", $status, $id);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    // insert appointment into database
    public function add_job($userName1, $title, $content, $address, $latitude, $longitude, $mobile, $img){
        $sql = "INSERT INTO jobs SET username=?, title=?, content=?, address=?, latitude=?, longitude=?, mobile=?, img=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 8 parameters of type string to be placed in $sql command
        $statement->bind_param("ssssssss", $userName1, $title, $content, $address, $latitude, $longitude, $mobile, $img);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    // update job in database
    public function updateJob($userName1, $title, $content, $address, $latitude, $longitude, $mobile, $id){
        $sql = "UPDATE jobs SET username=?, title=?, content=?, address=?, latitude=?, longitude=?, mobile=? WHERE id=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 2 parameters of type string to be placed in $sql command
        $statement->bind_param("ssssssss", $userName1, $title, $content, $address, $latitude, $longitude, $mobile, $id);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    // update job in database
    public function updateJobWithImg($userName1, $title, $content, $address, $latitude, $longitude, $mobile, $img, $id){
        $sql = "UPDATE jobs SET username=?, title=?, content=?, address=?, latitude=?, longitude=?, mobile=?, img=? WHERE id=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 2 parameters of type string to be placed in $sql command
        $statement->bind_param("sssssssss", $userName1, $title, $content, $address, $latitude, $longitude, $mobile, $img, $id);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    // insert ad into database
    public function insertAD($name, $img, $category){
        $sql = "INSERT INTO ads SET name=?, img=?, category=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 2 parameters of type string to be placed in $sql command
        $statement->bind_param("sss", $name, $img, $category);
        $returnValue = $statement->execute();
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

    // insert news into database
    public function insertNews($title, $content, $img){
        $sql = "INSERT INTO news SET title=?, content=?, img=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 2 parameters of type string to be placed in $sql command
        $statement->bind_param("sss", $title, $content, $img);
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
        $result = $this->selectUser($email);
        if ($result){
            return false;
        }
        else{
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