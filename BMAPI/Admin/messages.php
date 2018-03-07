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

$result = $access->getMessages();

?>


<!doctype html>
<html lang="en">
<head>

    <title>Applicants Messages</title>


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
                                <h4 style="margin-left: 5%" class="title">Applicants Messages</h4>
                                <p style="margin-left: 5%" class="category">Respond to messages</p>

                            </div>
                            <div class="card-content table-responsive">
                                <input class="form-control" type="text" id="search" onkeyup="searchFn()" placeholder="Search for ..." title="Type in a name">
                                <table id="table" class="table">
                                    <thead class="text-primary">

                                    <th onclick="sortTable(0)">Full Name</th>
                                    <th onclick="sortTable(1)">E-mail</th>
                                    <th onclick="sortTable(2)">Message</th>
                                    <th onclick="sortTable(3)">Response</th>
                                    <th onclick="sortTable(4)">Date</th>
                                    <th>Action</th>
                                    </thead>
                                    <tbody>
                                    <?php

                                    while ($row = mysqli_fetch_array($result)) {

                                        ?>
                                        <tr>
                                            <td><?php echo $row["fname"] ." ";echo $row["mname"] ." ";echo $row["lname"]; ?></td>
                                            <td><?php echo $row["email"]; ?></td>
                                            <td><?php echo $row["msg"]; ?></td>
                                            <td><?php
                                                if ($row["response"] === NULL){
                                                    $row["response"] = "waiting";
                                                }
                                                echo $row["response"];
                                                ?></td>
                                            <td><?php echo $row["created_at"]; ?></td>
                                            <td class="td-actions text-right">

                                                <button rel="tooltip" title="Response" class="btn btn-success btn-simple btn-xs edit-btn" value="<?php echo $row["id"]. '~' .$row["fname"]. ' ' .$row["mname"]. ' ' .$row["lname"]. '~' .$row["email"]. '~' .$row["msg"]. '~' .$row["response"]. '~' .$row["created_at"]; ?>">
                                                    <i class="fa fa-edit"></i>
                                                </button>
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
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">format_size</i>
		                        </span>
                            <input id="name" type="text" class="form-control" placeholder="Name" disabled>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">format_size</i>
		                        </span>
                            <input id="email" type="email" class="form-control" placeholder="E-mail" disabled>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">format_size</i>
		                        </span>
                            <textarea id="msg" class="form-control" placeholder="Message" rows="3" disabled></textarea>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">format_size</i>
		                        </span>
                            <input id="date" type="text" class="form-control" placeholder="Date" disabled>
                        </div>
                    </div>


                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">format_size</i>
		                        </span>
                            <textarea id="response" class="form-control" placeholder="Response" rows="5"></textarea>
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


</body>

<?php include ('scripts.html'); ?>

<script>

    $('button#upload-ad-btn').on('click', function(e){

        $("#message").empty();
        $('#loading').show();


        //alert(dataString);
        var form = new FormData();
        form.append("id", $('#id').val());
        form.append("response", $('#response').val());
        form.append("email", $('#email').val());


        $.ajax({
            url: "../respondMsg.php", // Url to which the request is send
            type: "POST",
            dataType: 'json',// Type of request to be send, called as method
            data: form, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,        // To send DOMDocument or non processed data file it is set to false
            success: function(data)   // A function to be called if request succeeds
            {
                $('#loading').hide();
                $("#message").html(data.message);

                alert(data.message);

                if (!data.error){
                    location.reload();
                }
            }
        });

    });

    $('.remove-btn').click(function(){
        /* when the submit button in the modal is clicked, submit the form */

        var dataString = "tblName=" + "messages" + "&dir=" + "" +  "&id="+ $(this).attr("value");

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

    $('.edit-btn').click(function(){
        /* when the submit button in the modal is clicked, submit the form */
        var paramsArr = $(this).attr("value").split('~');

        $('#id').val(paramsArr[0]);
        $('#name').val(paramsArr[1]);
        $('#email').val(paramsArr[2]);
        $('#msg').val(paramsArr[3]);
        $('#date').val(paramsArr[5]);

        if (paramsArr[4] != "waiting"){
            $('#response').prop('disabled', "disabled");
            $('#response').val(paramsArr[4]);
            $("#upload-ad-btn").prop('disabled', "disabled");
        }
        else {
            $('#response').prop('disabled', "");
            $('#response').val("");
            $("#upload-ad-btn").prop('disabled', "");
        }


        $('#addAD').modal('show');
        return false;
    });

    $(document).ready(function (e) {

        $(".nav li:nth-child(6)").addClass('active');
    });

</script>

</html>
