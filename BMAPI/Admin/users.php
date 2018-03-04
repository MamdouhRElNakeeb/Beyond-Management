<?php
/**
 * Created by PhpStorm.
 * User: mamdouhelnakeeb
 * Date: 8/28/17
 * Time: 5:33 AM
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

$result = $access->getTableContent("users");

?>


<!doctype html>
<html lang="en">
<head>
    <title>Users</title>

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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header" data-background-color="green">
                                <h4 style="margin-left: 5%" class="title">Users</h4>
                                <p style="margin-left: 5%" class="category">Manage your users</p>

                                <button class="btn btn-primary btn-fab btn-fab-mini btn-round top-right" data-background-color="red" data-toggle="modal" data-target="#addAD" style="margin-top: -5%">
                                    <i class="material-icons">add</i>
                                </button>

                            </div>


                            <div class="card-content table-responsive">
                                <input class="form-control" type="text" id="search" onkeyup="searchFn()" placeholder="Search for ..." title="Type in a name">
                                <table id="table" class="table">
                                    <thead class="text-primary">

                                    <th onclick="sortTable(0)">Name</th>
                                    <th onclick="sortTable(1)">Username</th>
                                    <th onclick="sortTable(2)">Role</th>
                                    <th>Actions</th>
                                    </thead>
                                    <tbody>
                                    <?php

                                    while ($row = mysqli_fetch_array($result)) {
                                        //if ($row["status"] ===  "waiting"){

                                        ?>
                                        <tr>
                                            <td><?php echo $row["name"]; ?></td>
                                            <td><?php echo $row["username"]; ?></td>
                                            <td><?php echo $row["role"]; ?></td>
                                            <td class="td-actions text-right">
                                                <button rel="tooltip" title="Edit" class="btn btn-success btn-simple btn-xs edit-btn" value="<?php echo $row["id"]. ',' .$row["name"]. ',' .$row["username"]. ',' .$row["password"]. ',' .$row["role"]; ?>">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button value="<?php echo $row["id"]; ?>" type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs remove-btn">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <?php
                                        //}
                                    }
                                    ?>

                                    </tbody>
                                </table>

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

<!-- Modal Core -->
<div class="modal fade" id="addAD" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Add / Edit User</h4>
            </div>
            <div class="modal-body">

                <form id="upload_ad" action="" method="post" enctype="multipart/form-data">

                    <input id="userid" style="display: none;">

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">person</i>
		                        </span>
                            <input id="name" type="text" class="form-control" placeholder="Name">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">person_outline</i>
		                        </span>
                            <input id="username" type="text" class="form-control" placeholder="Username">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">lock</i>
		                        </span>
                            <input id="password" type="password" class="form-control" placeholder="Password">
                        </div>
                    </div>

                    <div class="col-sm-12 dropdown">
                        <div class="input-group dropdown">
                            <a href="#" class="btn btn-simple dropdown-toggle" data-toggle="dropdown" id="role">
                                Choose User Type
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" data-background-color="green" id="role_dd">
                                <li value="admin"><a>Admin</a></li>
                                <li value="user"><a>Another role</a></li>
                            </ul>
                        </div>
                    </div>

                </form>
                <h4 id='loading' style="display: none">loading..</h4>
                <div id="message"></div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-default btn-simple" data-dismiss="modal">Close</button>
                <button id="upload-ad-btn" type="submit" class="btn btn-info btn-simple">Submit</button>
            </div>
        </div>
    </div>
</div>

</body>

<?php include ('scripts.html'); ?>

<script>

    $('.remove-btn').click(function(){
        /* when the submit button in the modal is clicked, submit the form */

        var dataString = "tblName=" + "users" + "&id="+ $(this).attr("value");

        $.ajax({
            type: "POST",
            url: "../removeImg.php",
            data: dataString,
            success: function() {
                $(this).hide();
                alert("User Removed");
                location.reload();
            }
        });
        return false;
    });

    var id = "";

    $('.edit-btn').click(function(){
        /* when the submit button in the modal is clicked, submit the form */
        var paramsArr = $(this).attr("value").split(',');

        id = paramsArr[0];
        $('#userid').val(paramsArr[0]);
        $('#name').val(paramsArr[1]);
        $('#username').val(paramsArr[2]);
        $('#role').text(paramsArr[4]);

        $('#addAD').modal('show');
        return false;
    });

    var role = "";

    $("#role_dd li").click(function () {
        $("#role").text($(this).text());
        role = $(this).attr('value');
    });

    $('button#upload-ad-btn').on('click', function(e){

        $("#message").empty();
        $('#loading').show();

        //var dataString = 'ad_name=' + $('#ad_name').val() + "&file=" + $("#file").files[0] + "&category=" + category;

        //alert(dataString);
        var form = new FormData();
        form.append("id", $('#userid').val());
        form.append("name", $('#name').val());
        form.append("username", $('#username').val());
        form.append("password", $('#password').val());
        form.append("role", $('#role').text());


        $.ajax({
            url: "../addUser.php", // Url to which the request is send
            type: "POST",
            dataType: 'text',// Type of request to be send, called as method
            data: form, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,        // To send DOMDocument or non processed data file it is set to false
            success: function(data)   // A function to be called if request succeeds
            {
                $('#loading').hide();
                $("#message").html("<span id='success'>" + data  + "\n" + data + "</span><br/>");
                alert(data);
                location.reload();
            }
        });

    });

    $(document).ready(function (e) {

        $(".nav li:nth-child(10)").addClass('active');
    });

</script>

</html>
