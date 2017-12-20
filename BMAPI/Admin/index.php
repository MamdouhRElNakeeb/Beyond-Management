<?php
/**
 * Created by PhpStorm.
 * User: mamdouhelnakeeb
 * Date: 8/28/17
 * Time: 5:16 AM
 */

session_start();

if ($_SESSION['valid'] != true){


    header('location: login.php');
    return;
}

require ("../secure/access.php");
require ("../secure/bmconn.php");


$access = new access(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$access->connect();

$immigration = mysqli_num_rows($access->getTableContent("immigration"));
$applicants = mysqli_num_rows($access->getTableContent("applicants"));
$applications = mysqli_num_rows($access->getTableContent("applications"));
$checklist = mysqli_num_rows($access->getTableContent("checklist"));
$payments = mysqli_num_rows($access->getTableContent("payments"));
$users = mysqli_num_rows($access->getTableContent("users"));
$documents = mysqli_num_rows($access->getTableContent("documents"));

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Home</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!--  Material Dashboard CSS    -->
    <link href="assets/css/material-dashboard.css" rel="stylesheet"/>

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />

    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300|Material+Icons' rel='stylesheet' type='text/css'>

</head>

<body>

<div class="wrapper">

    <div class="sidebar" data-color="green" data-image="assets/img/sidebar-1.jpg">
        <!--
            Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"

            Tip 2: you can also add an image using data-image tag
        -->

        <div class="logo">
            <a href="#" class="simple-text" target="_blank">
                Beyond Management
            </a>
        </div>

        <div class="sidebar-wrapper">
            <ul class="nav">
                <li class="active">
                    <a href="#">
                        <i class="material-icons">dashboard</i>
                        <p>Home</p>
                    </a>
                </li>
                <li>
                    <a href="services.php">
                        <i class="material-icons">flight</i>
                        <p>Immigration Services</p>
                    </a>
                </li>
                <li>
                    <a href="applicants.php">
                        <i class="material-icons">group</i>
                        <p>Applicants</p>
                    </a>
                </li>
                <li>
                    <a href="applications.php">
                        <i class="material-icons">tap_and_play</i>
                        <p>Applications</p>
                    </a>
                </li>
                <li>
                    <a href="documents.php">
                        <i class="material-icons">content_paste</i>
                        <p>Documents</p>
                    </a>
                </li>
<!--                <li>-->
<!--                    <a href="payments.php">-->
<!--                        <i class="material-icons">attach_money</i>-->
<!--                        <p>Payments</p>-->
<!--                    </a>-->
<!--                </li>-->
                <li>
                    <a href="users.php">
                        <i class="material-icons">group</i>
                        <p>Users</p>
                    </a>
                </li>

            </ul>
        </div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-transparent navbar-absolute">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Beyond Management Admin Panel</a>
                </div>


                <div class="collapse navbar-collapse" style="display: none">
                    <ul class="nav navbar-nav navbar-right">
                        <!--
                        <li>
                            <a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="material-icons">dashboard</i>
                                <p class="hidden-lg hidden-md">Dashboard</p>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="material-icons">notifications</i>
                                <span class="notification">5</span>
                                <p class="hidden-lg hidden-md">Notifications</p>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Mike John responded to your email</a></li>
                                <li><a href="#">You have 5 new tasks</a></li>
                                <li><a href="#">You're now friend with Andrew</a></li>
                                <li><a href="#">Another Notification</a></li>
                                <li><a href="#">Another One</a></li>
                            </ul>
                        </li>
                        -->
                        <li>
                            <a href="logout.php" class="btn btn-white btn-round btn-just-icon">
                                <i class="material-icons">input</i>
                                <p class="hidden-lg hidden-md">Logout</p>
                            </a>
                        </li>
                    </ul>

                    <!--
                    <form class="navbar-form navbar-right" role="search">
                        <div class="form-group  is-empty">
                            <input type="text" class="form-control" placeholder="Search">
                            <span class="material-input"></span>
                        </div>
                        <button type="submit" class="btn btn-white btn-round btn-just-icon">
                            <i class="material-icons">search</i><div class="ripple-container"></div>
                        </button>
                    </form>
                    -->
                </div>


            </div>
        </nav>

        <div class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-4 col-md-8 col-sm-8">
                        <div class="card card-stats" onclick="location.href='services.php';" style="cursor: pointer;">
                            <div class="card-header" data-background-color="purple">
                                <i class="material-icons">flight</i>
                            </div>
                            <div class="card-content">
                                <p class="category">Immigration Services</p>
                                <h3 class="title"><?php echo $immigration; ?></h3>
                            </div>
                            <div class="card-footer">
                                <div class="stats">
                                    <i class="material-icons">update</i> Just Updated
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-8 col-sm-8">
                        <div class="card card-stats" onclick="location.href='applicants.php';" style="cursor: pointer;">
                            <div class="card-header" data-background-color="blue">
                                <i class="material-icons">group</i>
                            </div>
                            <div class="card-content">
                                <p class="category">Applicants</p>
                                <h3 class="title"><?php echo $applicants; ?></h3>
                            </div>
                            <div class="card-footer">
                                <div class="stats">
                                    <i class="material-icons">update</i> Just Updated
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-8 col-sm-8">
                        <div class="card card-stats" onclick="location.href='applications.php';" style="cursor: pointer;">
                            <div class="card-header" data-background-color="orange">
                                <i class="material-icons">tap_and_play</i>
                            </div>
                            <div class="card-content">
                                <p class="category">Applications</p>
                                <h3 class="title"><?php echo $applications; ?></h3>
                            </div>
                            <div class="card-footer">
                                <div class="stats">
                                    <i class="material-icons">update</i> Just Updated
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="row">

                    <div class="col-lg-4 col-md-8 col-sm-8">
                        <div class="card card-stats" onclick="location.href='documents.php';" style="cursor: pointer;">
                            <div class="card-header" data-background-color="blue">
                                <i class="material-icons">content_paste</i>
                            </div>
                            <div class="card-content">
                                <p class="category">Documents</p>
                                <h3 class="title"><?php echo $documents; ?></h3>
                            </div>
                            <div class="card-footer">
                                <div class="stats">
                                    <i class="material-icons">update</i> Just Updated
                                </div>
                            </div>
                        </div>
                    </div>

<!--                    <div class="col-lg-4 col-md-8 col-sm-8">-->
<!--                        <div class="card card-stats" onclick="location.href='payments.php';" style="cursor: pointer;">-->
<!--                            <div class="card-header" data-background-color="red">-->
<!--                                <i class="material-icons">attach_money</i>-->
<!--                            </div>-->
<!--                            <div class="card-content">-->
<!--                                <p class="category">Payments</p>-->
<!--                                <h3 class="title">--><?php //echo $payments; ?><!--</h3>-->
<!--                            </div>-->
<!--                            <div class="card-footer">-->
<!--                                <div class="stats">-->
<!--                                    <i class="material-icons">update</i> Just Updated-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->

                    <div class="col-lg-4 col-md-8 col-sm-8">
                        <div class="card card-stats" onclick="location.href='users.php';" style="cursor: pointer;">
                            <div class="card-header" data-background-color="orange">
                                <i class="material-icons">group</i>
                            </div>
                            <div class="card-content">
                                <p class="category">Users</p>
                                <h3 class="title"><?php echo $users; ?></h3>
                            </div>
                            <div class="card-footer">
                                <div class="stats">
                                    <i class="material-icons">update</i> Just Updated
                                </div>
                            </div>
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
<script src="assets/js/material-kit.js" type="text/javascript"></script>

<!--  Notifications Plugin    -->
<script src="assets/js/bootstrap-notify.js"></script>

<!-- Material Dashboard javascript methods -->
<script src="assets/js/material-dashboard.js"></script>

<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="assets/js/demo.js"></script>

</html>
