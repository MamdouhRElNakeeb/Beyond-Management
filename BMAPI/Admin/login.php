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
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Dashboard</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!--  Material Dashboard CSS    -->
    <link href="assets/css/material-dashboard.css" rel="stylesheet"/>

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />


    <link href="assets/css/material-kit.min.css?v=1.1.1" rel="stylesheet"/>

    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300|Material+Icons' rel='stylesheet' type='text/css'>

    <script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/30/3/common.js" style=""/>
    <script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/30/3/util.js"></script>
    <script type="text/javascript" charset="UTF-8" src="https://maps.googleapis.com/maps-api-v3/api/js/30/3/stats.js"></script>

</head>

<body>

<div class="wrapper">


    <div class="page-header header-filter" style="background-image: url('assets/img/site_bg.jpg'); background-size: cover; background-position: top center;">


        <div class="container">

            <div class="row">
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
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


        </div>

        <footer class="footer">
            <div class="container-fluid">
                <nav class="pull-left">
                    <ul>
                        <li>
                            <a href="http://nakeeb.me" target="_blank">
                                Beyond Management
                            </a>
                        </li>
                        <li>
                            <a href="" target="_blank">
                                Android
                            </a>
                        </li>
                        <li>
                            <a href="#" target="_blank">
                                iOS
                            </a>
                        </li>

                    </ul>
                </nav>
                <p class="copyright pull-right">
                    &copy; <script>document.write(new Date().getFullYear())</script> <a href="http://nakeeb.me">Mamdouh El Nakeeb</a>, All rights reserved
                </p>
            </div>
        </footer>
    </div>
</div>

</body>

<!--   Core JS Files   -->
<script src="assets/js/jquery-3.1.0.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/js/material.min.js" type="text/javascript"></script>

<!--  Charts Plugin -->
<script src="assets/js/chartist.min.js"></script>

<!--  Notifications Plugin    -->
<script src="assets/js/bootstrap-notify.js"></script>

<!--  Google Maps Plugin    -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

<!-- Material Dashboard javascript methods -->
<script src="assets/js/material-dashboard.js"></script>
<script src="assets/js/material-kit.min.js?v=1.1.1" type="text/javascript"></script>

<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="assets/js/demo.js"></script>

<script type="text/javascript">
    $(document).ready(function(){

        // Javascript method's body can be found in assets/js/demos.js
        demo.initDashboardPageCharts();

    });
</script>

</html>
