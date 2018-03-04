<?php
/**
 * Created by PhpStorm.
 * User: nakeebimac
 * Date: 9/3/17
 * Time: 8:23 AM
 */

session_start();

if ($_SESSION['valid'] === true){


    header('location: index.php');
    return;
}

require ("../secure/access.php");
require ("../secure/bmconn.php");

$username = htmlentities($_REQUEST["username"]);
$password = htmlentities($_REQUEST["password"]);

$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();

$user = $access->selectUser($username);

$flag = 0;

if ($user) {


    // verifying user password
    $salt = $user['salt'];
    $secured_password = $user['password'];
    $hash = $access->checkhashSSHA($salt, $password);

    // check for password equality
    if ($hash == $secured_password){

        if ($user["role"] != "admin"){

            $_SESSION['valid'] = false;
            $flag = 2;
        }
        else{
            $_SESSION['valid'] = true;
            $_SESSION['timeout'] = time();
            $_SESSION['username'] = $username;


            header('location: index.php');
        }
    }
    else{
        $_SESSION['valid'] = false;
        $flag = 3;
    }




}
else{
    $_SESSION['valid'] = false;
    $flag = 0;
}

?>


<!doctype html>
<html lang="ar">
<head>

    <title>Dashboard</title>

    <?php include ('header.html'); ?>

</head>

<body style="background-image: url('assets/img/site_bg.jpg'); background-size: cover; background-position: top center;">

<div class="container-fluid">


    <div class="content align-items-center">

<!--        <div class="content">-->

            <div class="row">
                <div class="col-md-4 col-md-offset-4 col-sm-6">
                    <div class="card card-signup">
                        <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <div class="header header-primary text-center">
                                <h4 class="card-title">Log in</h4>

                            </div>

                            <div class="card-content">

                                <div class="input-group">
									<span class="input-group-addon">
										<i class="material-icons">face</i>
									</span>
                                    <div class="form-group is-empty"><input name="username" type="text" class="form-control" placeholder="Username"><span class="material-input"></span></div>
                                </div>

                                <div class="input-group">
									<span class="input-group-addon">
										<i class="material-icons">lock_outline</i>
									</span>
                                    <div class="form-group is-empty"><input name="password" type="password" placeholder="Password..." class="form-control"><span class="material-input"></span></div>
                                </div>

                                <!-- If you want to add a checkbox to this form, uncomment this code

                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="optionsCheckboxes" checked>
                                        Subscribe to newsletter
                                    </label>
                                </div> -->
                            </div>
                            <div class="footer text-center">
                                <input type="submit" class="btn btn-primary btn-simple btn-wd btn-lg" value="Login"/>
                            </div>
                        </form>
                        <div class="footer text-center">
                            <?php
                            if ($flag === 1){
                                echo "<span id='success'>User isn't existed</span><br/>";
                            }
                            else if ($flag === 2){
                                echo "<span id='success'>User isn't an ADMIN</span><br/>";
                            }
                            else if ($flag === 3){
                                echo "<span id='success'>Password is not correct</span><br/>";
                            }
                            ?>
                            <br>
                        </div>

                    </div>
                </div>
            </div>


<!--        </div>-->

        <?php include ('footer.html'); ?>

    </div>

</div>

</body>


<?php include ('scripts.html'); ?>

</html>
