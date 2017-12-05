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

$result = $access->getADs("");

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>ADs</title>

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

    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
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
                <li>
                    <a href="index.php">
                        <i class="material-icons">dashboard</i>
                        <p>Home</p>
                    </a>
                </li>
                <li class="active">
                    <a href="#">
                        <i class="material-icons">aspect_ratio</i>
                        <p>ADs</p>
                    </a>
                </li>
                <li>
                    <a href="services1.php">
                        <i class="material-icons">settings</i>
                        <p>Services</p>
                    </a>
                </li>
                <li>
                    <a href="payments.php">
                        <i class="material-icons">tap_and_play</i>
                        <p>News</p>
                    </a>
                </li>
                <li>
                    <a href="offers.php">
                        <i class="material-icons">card_giftcard</i>
                        <p>Offers</p>
                    </a>
                </li>
                <li>
                    <a href="applicants.php">
                        <i class="material-icons">work</i>
                        <p>Jobs</p>
                    </a>
                </li>
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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header" data-background-color="green">
                                <h4 style="margin-left: 5%" class="title">Advertisements</h4>
                                <p style="margin-left: 5%" class="category">Control your ADs</p>

                                <button class="btn btn-primary btn-fab btn-fab-mini btn-round top-right" data-background-color="red" data-toggle="modal" data-target="#addAD" style="margin-top: -5%">
                                    <i class="material-icons">add</i>
                                </button>

                            </div>


                            <div class="card-content table-responsive">
                                <table class="table">
                                    <thead class="text-primary">

                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Category</th>
                                    <th class="text-left">Actions</th>
                                    </thead>
                                    <tbody>
                                    <?php

                                    while ($row = mysqli_fetch_array($result)) {
                                        //if ($row["status"] ===  "waiting"){

                                        ?>
                                        <tr>
                                            <td><?php echo $row["name"]; ?></td>
                                            <td><img src="../ADs/<?php echo $row["img"]; ?>" style="height: 50%; width: auto"></td>
                                            <td><?php echo $row["category"]; ?></td>
                                            <td class="td-actions text-right">
                                                <button type="button" rel="tooltip" title="Edit AD" class="btn btn-success btn-simple btn-xs" style="display: none;">
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
                            <a href="http://dalilelshrouk.com" target="_blank">
                                Beyond Management
                            </a>
                        </li>
                        <li>
                            <a href="https://play.google.com/store/apps/details?id=me.nakeeb.dalilelshrouk" target="_blank">
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
                <h4 class="modal-title" id="myModalLabel">Add / Edit Advertisement</h4>
            </div>
            <div class="modal-body">

                <form id="upload_ad" action="" method="post" enctype="multipart/form-data">

                    <div class="col-sm-12">
                        <div class="input-group">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                             <span class="input-group-addon">
                                 <div class="form-group label-floating">

                                    <i class="material-icons">face</i>
                                    <label class="control-label" for="ad_name">AD name</label>
                                    <input id="ad_name" name="ad_name" type="text" class="form-control" placeholder="AD name">
                                </div>
                             </span>


                            </div>

                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                             <span class="input-group-addon">
                                 <i class="material-icons">add_a_photo</i> Upload image
                             </span>
                                <span class="btn btn-primary btn-file">

                            <span>Choose file</span>
                                <input type="file" name="file" id="file" required />
                            </span>
                                <img id="previewing" src="#" style="width: 50%"/>
                            </div>

                        </div>
                    </div>

                    <div class="input-group">
                        <div class="col-md-3 dropdown">
                            <a href="#" class="btn btn-simple dropdown-toggle" data-toggle="dropdown" id="category">
                                Choose Category
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" data-background-color="green" id="category_dd">
                                <li value="supermarket"><a>Supermarket</a></li>
                                <li value="restaurant"><a>Restaurants</a></li>
                                <li value="cafe"><a>Cafe</a></li>
                                <li value="clinic"><a>Clinics</a></li>
                                <li value="pharmacy"><a>Pharmacies</a></li>
                                <li value="building"><a>Real Estates</a></li>
                                <li value="nursery"><a>Nurseries</a></li>
                                <li value="teacher"><a>Teachers</a></li>
                                <li value="workman"><a>Workmen</a></li>
                                <li value="limousine"><a>Limousine</a></li>
                                <li value="bakery"><a>Bakery</a></li>
                                <li value="cloth"><a>Clothes</a></li>
                                <li value="furniture"><a>Furniture</a></li>
                                <li value="gym"><a>Gym</a></li>
                                <li value="school"><a>School</a></li>
                                <li value="other"><a>Others</a></li>
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

<!--   Core JS Files   -->
<script src="assets/js/jquery-3.1.0.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/js/material.min.js" type="text/javascript"></script>

<!--  Notifications Plugin    -->
<script src="assets/js/bootstrap-notify.js"></script>

<!-- Material Dashboard javascript methods -->
<script src="assets/js/material-dashboard.js"></script>

<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="assets/js/demo.js"></script>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>


<script>

    $('.remove-btn').click(function(){
        /* when the submit button in the modal is clicked, submit the form */

        var dataString = "tblName=" + "ads" + "&dir=" + "ADs" +  "&id="+ $(this).attr("value");

        $.ajax({
            type: "POST",
            url: "../removeImg.php",
            data: dataString,
            success: function() {
                $(this).hide();
                alert("AD Removed");
                location.reload();
            }
        });
        return false;
    });

    var category = "";

    $("#category_dd li").click(function () {
        $("#category").text($(this).text());
        category = $(this).attr('value');
    });

    $('button#upload-ad-btn').on('click', function(e){

        $("#message").empty();
        $('#loading').show();

        //var dataString = 'ad_name=' + $('#ad_name').val() + "&file=" + $("#file").files[0] + "&category=" + category;

        //alert(dataString);
        var form = new FormData();
        form.append("ad_name", $('#ad_name').val());
        form.append("file", $("#file").prop('files')[0]);
        form.append("category", category);


        $.ajax({
            url: "../uploadAD.php", // Url to which the request is send
            type: "POST",
            dataType: 'text',// Type of request to be send, called as method
            data: form, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,        // To send DOMDocument or non processed data file it is set to false
            success: function(data)   // A function to be called if request succeeds
            {
                $('#loading').hide();
                $("#message").html(data);
                location.reload();
            }
        });

    });


    $(document).ready(function (e) {

        // Function to preview image after validation
        $(function() {
            $("#file").change(function() {
                $("#message").empty(); // To remove the previous error message
                var file = this.files[0];
                var imagefile = file.type;
                var match= ["image/jpeg","image/png","image/jpg"];
                if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
                {
                    $('#previewing').attr('src','noimage.png');
                    $("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
                    return false;
                }
                else
                {
                    var reader = new FileReader();
                    reader.onload = imageIsLoaded;
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
        function imageIsLoaded(e) {
            $("#file").css("color","green");
            $('#previewing').attr('src', e.target.result);
        }
    });

</script>

</html>
