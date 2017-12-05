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


    // insert appointment into database
    public function addJob($userName1, $title, $content, $address, $mobile){
        $sql = "INSERT INTO jobs SET username=?, title=?, content=?, address=?, mobile=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 8 parameters of type string to be placed in $sql command
        $statement->bind_param("sssss", $userName1, $title, $content, $address, $mobile);
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
    public function addUser($name, $username1, $password, $role){
        $sql = "INSERT INTO users SET name=?, username=?, password=?, role=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 2 parameters of type string to be placed in $sql command
        $statement->bind_param("ssss", $name, $username1, $password, $role);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    // insert offer into database
    public function updateUser($name, $username1, $password, $role, $id){
        $sql = "UPDATE users SET name=?, username=?, password=?, role=? WHERE id=?";
        $statement = $this->conn->prepare($sql);
        if(!$statement){
            throw new Exception($statement->error);
        }
        // bind 2 parameters of type string to be placed in $sql command
        $statement->bind_param("sssss", $name, $username1, $password, $role, $id);
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

    // insert user into database
    public function registerUser($name, $email, $password, $salt, $phone, $address, $regID){
        $result = $this->selectUser($email);
        if ($result){
            return;
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