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
$skype = mysqli_num_rows($access->getTableContent("skype"));
$messages = mysqli_num_rows($access->getTableContent("messages"));

?>


<!doctype html>
<html lang="en">
<head>
    <title>Home</title>

    <?php include ('header.html'); ?>

</head>

<body>

<div class="wrapper">

    <?php include('sidebar.html'); ?>

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


        <?php include ('footer.html'); ?>
    </div>
</div>

</body>

<?php include ('scripts.html'); ?>

<script>
    $(document).ready(function (e) {

        $(".nav li:nth-child(1)").addClass('active');
    });

</script>

</html>
