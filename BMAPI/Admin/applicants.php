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

$result = $access->getTableContent("applicants");

?>


<!doctype html>
<html lang="en">
<head>

    <title>Manage Applicants</title>


    <?php include ('header.html');?>
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
                                <h4 style="margin-left: 5%" class="title">Applicants</h4>
                                <p style="margin-left: 5%" class="category">Manage applicants</p>

                            </div>
                            <div class="card-content table-responsive">
                                <input class="form-control" type="text" id="search" onkeyup="searchFn()" placeholder="Search for ..." title="Type in ...">

                                <table id="table" class="table">
                                    <thead class="text-primary">

                                    <th onclick="sortTable(0)">Full Name</th>
                                    <th onclick="sortTable(1)">Email</th>
                                    <th onclick="sortTable(2)">Phone</th>
                                    <th onclick="sortTable(3)">Address</th>
                                    <th onclick="sortTable(5)">Date</th>
                                    <th onclick="sortTable(4)">Status</th>
                                    <th>Action</th>
                                    </thead>
                                    <tbody>
                                    <?php

                                    while ($row = mysqli_fetch_array($result)) {

                                        ?>
                                        <tr>
                                            <td><?php echo $row["fname"] ." ";echo $row["mname"] ." ";echo $row["lname"]; ?></td>
                                            <td><?php echo $row["email"]; ?></td>
                                            <td><?php echo $row["phone"]; ?></td>
                                            <td><?php echo $row["str_address"] . ",\r\n"; ?> <br> <?php echo $row["city"] . ", ";echo $row["state"].", " ;echo $row["zip_code"] . ",\r\n" ; ?> <br> <?php echo $row["country"]; ?></td>
                                            <td><?php echo date( 'M. d, Y h:i A', strtotime($row["created_at"]) ); ?></td>
                                            <td>
                                            <?php

                                            if ($row["status"] === "activated"){
                                                ?>

                                                <button value="<?php echo $row["id"]; ?>" type="button" title="Activated" class="btn btn-success btn-simple btn-xs activate-btn">
                                                    Activated
                                                </button>

                                            <?php
                                            }
                                            else{
                                                ?>

                                                <button value="<?php echo $row["id"]; ?>" type="button" title="Deactivated" class="btn btn-danger btn-simple btn-xs deactivate-btn">
                                                    Deactivated
                                                </button>

                                                <?php
                                            }
                                            ?>
                                            </td>
                                            <td class="td-actions text-right">

<!--                                                <button rel="tooltip" title="Edit" class="btn btn-success btn-simple btn-xs edit-btn" value="--><?php //echo $row["id"]. '~' .$row["fname"]. '~' .$row["mname"]. '~' .$row["lname"]. '~' .$row["email"]. '~' .$row["phone"]. '~' .$row["str_address"]. '~'.$row["city"]. '~'.$row["state"]. '~'.$row["zip_code"]. '~' .$row["country"]. '~' .$row["status"]; ?><!--">-->
<!--                                                    <i class="fa fa-edit"></i>-->
<!--                                                </button>-->
                                                <button value="<?php echo $row["id"]; ?>" type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs remove-btn">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </td>

                                        </tr>

                                        <?php

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

        <?php include ('footer.html'); ?>
    </div>
</div>


<!-- Modal Core -->
<div class="modal fade" id="addAD" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Edit Applicant Information</h4>
            </div>
            <div class="modal-body">

                <form id="upload_ad" action="" method="post" enctype="multipart/form-data">

                    <input id="id" style="display: none;">


                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">format_size</i>
		                        </span>
                                <input id="fname" type="text" class="form-control" placeholder="First Name">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">format_size</i>
		                        </span>
                                <input id="mname" type="text" class="form-control" placeholder="Middle Name">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">format_size</i>
		                        </span>
                                <input id="lname" type="text" class="form-control" placeholder="Last Name">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">format_size</i>
		                        </span>
                            <input id="email" type="email" class="form-control" placeholder="Email">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">format_size</i>
		                        </span>
                            <input id="phone" type="tel" class="form-control" placeholder="phone">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">place</i>
		                        </span>
                            <input id="str_address" type="text" class="form-control" placeholder="Street Address">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">place</i>
		                        </span>
                                <input id="city" type="text" class="form-control" placeholder="City">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">place</i>
		                        </span>
                                <input id="state" type="text" class="form-control" placeholder="State">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">place</i>
		                        </span>
                                <input id="zip_code" type="text" class="form-control" placeholder="Zip Code">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">place</i>
		                        </span>
                            <input id="country" type="text" class="form-control" placeholder="Country">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">format_size</i>
		                        </span>
                            <input id="status" type="text" class="form-control" placeholder="Status" disabled>
                        </div>
                    </div>
                </form>
                <div id="message"></div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-default btn-simple" data-dismiss="modal">Close</button>
                <button id="upload-ad-btn" type="submit" class="btn btn-info btn-simple">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Activate User Modal -->
<div class="modal fade" id="activateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Change User Status</h4>
            </div>
            <div class="modal-body">

                <form id="activate-form" action="" method="post">

                    <input id="activatedID" style="display: none;">

                    <div class="col-sm-12">
                        <h6 class="modal-title">Are you sure you want to activate this user?</h6>
                    </div>

                </form>
                <br>
                <br>

                <h6 id='loading_act' style="display: none">loading..</h6>
                <div id="message_act"></div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-default btn-simple" data-dismiss="modal">Close</button>
                <button id="activate-user-btn" type="submit" class="btn btn-info btn-simple">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Deactivate User Modal -->
<div class="modal fade" id="deactivateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Change User Status</h4>
            </div>
            <div class="modal-body">

                <form id="deactivate-form" action="" method="post">

                    <input id="deactivatedID" style="display: none;">

                    <div class="col-sm-12">
                        <h6 class="modal-title">Are you sure you want to deactivate this user?</h6>
                    </div>

                </form>
                <br>
                <br>
                <h6 id='loading_deact' style="display: none">loading..</h6>
                <div id="message_deact"></div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-default btn-simple" data-dismiss="modal">Close</button>
                <button id="deactivate-user-btn" type="submit" class="btn btn-info btn-simple">Confirm</button>
            </div>
        </div>
    </div>
</div>


</body>

<?php include ('scripts.html'); ?>

<script>

    $('.remove-btn').click(function(){
        /* when the submit button in the modal is clicked, submit the form */

        var dataString = "tblName=" + "applicants" + "&dir=" + "" +  "&id="+ $(this).attr("value");

        $.ajax({
            type: "POST",
            url: "../removeImg.php",
            data: dataString,
            success: function() {
                $(this).hide();
                alert("Applicant Removed");
                location.reload();
            }
        });
        return false;
    });

    // TODO: Edit applicant

    $('.edit-btn').click(function(){
        /* when the submit button in the modal is clicked, submit the form */
        var paramsArr = $(this).attr("value").split('~');

        $('#id').val(paramsArr[0]);
        $('#fname').val(paramsArr[1]);
        $('#mname').val(paramsArr[2]);
        $('#lname').val(paramsArr[3]);
        $('#email').val(paramsArr[4]);
        $('#phone').val(paramsArr[5]);
        $('#str_address').val(paramsArr[6]);
        $('#city').val(paramsArr[7]);
        $('#state').val(paramsArr[8]);
        $('#zip_code').val(paramsArr[9]);
        $('#country').val(paramsArr[10]);
        $('#status').val(paramsArr[11]);

        $('#addAD').modal('show');
        return false;
    });

    $(document).ready(function (e) {

        $(".nav li:nth-child(3)").addClass('active');

    });

    $('.activate-btn').click(function(){
        /* when the submit button in the modal is clicked, submit the form */
        var paramsArr = $(this).attr("value");

        $('#deactivatedID').val(paramsArr);

        $('#deactivateModal').modal('show');
        return false;
    });

    $('.deactivate-btn').click(function(){
        /* when the submit button in the modal is clicked, submit the form */
        var paramsArr = $(this).attr("value");

        $('#activatedID').val(paramsArr);

        $('#activateModal').modal('show');
        return false;
    });


    $('button#activate-user-btn').on('click', function(e){

        $("#message_act").empty();
        $('#loading_act').show();

        var form = new FormData();
        form.append("id", $('#activatedID').val());
        form.append("status", "activated");

        $.ajax({
            url: "../updateUserStatus.php", // Url to which the request is send
            type: "POST",
            dataType: 'json',// Type of request to be send, called as method
            data: form, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,        // To send DOMDocument or non processed data file it is set to false
            success: function(data)   // A function to be called if request succeeds
            {
                $('#loading_act').hide();
                $("#message_act").html(data.message);

                alert(data.message);

                if (!data.error){
                    location.reload();
                }
            }
        });

    });

    $('button#deactivate-user-btn').on('click', function(e){

        $("#message_deact").empty();
        $('#loading_deact').show();

        var form = new FormData();
        form.append("id", $('#deactivatedID').val());
        form.append("status", "deactivated");

        $.ajax({
            url: "../updateUserStatus.php", // Url to which the request is send
            type: "POST",
            dataType: 'json',// Type of request to be send, called as method
            data: form, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,        // To send DOMDocument or non processed data file it is set to false
            success: function(data)   // A function to be called if request succeeds
            {
                $('#loading_deact').hide();
                $("#message_deact").html(data.message);

                alert(data.message);

                if (!data.error){
                    location.reload();
                }
            }
        });

    });

</script>

</html>