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

$result = $access->getTableContent("email_temps");

?>


<!doctype html>
<html lang="en">
<head>


    <title>Email Templates</title>

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
                                <h4 style="margin-left: 5%" class="title">Email Templates</h4>
                                <p style="margin-left: 5%" class="category">Control your emails</p>

                                <button class="btn btn-primary btn-fab btn-fab-mini btn-round top-right fab-add" data-background-color="red" data-toggle="modal" data-target="#addEmailModal" style="margin-top: -5%">
                                    <i class="material-icons">add</i>
                                </button>

                            </div>

                            <div class="card-content table-responsive">
                                <input class="form-control" type="text" id="search" onkeyup="searchFn()" placeholder="Search for ..." title="Type in a name">
                                <table id="table" class="table sindu-table">
                                    <thead class="text-primary">

                                    <th onclick="sortTable(0)">Name</th>
                                    <th onclick="sortTable(1)">E-mail</th>
                                    <th onclick="sortTable(2)">Subject</th>
                                    <th onclick="sortTable(3)">Message</th>
                                    <th>Action</th>
                                    </thead>
                                    <tbody>
                                    <?php

                                    while ($row = mysqli_fetch_array($result)) {

                                        ?>
                                        <tr>
                                            <td><?php echo $row["name"]; ?></td>
                                            <td><?php echo $row["email"]; ?></td>
                                            <td><?php echo $row["subject"]; ?></td>
                                            <td>
                                                <button rel="tooltip" title="View Email" class="btn btn-success btn-simple btn-xs vemail-btn" value="<?php echo $row["id"];?>">
                                                    <i class="fa fa-file-image-o"></i>
                                                </button>
                                            </td>
                                            <td class="td-actions text-right">
                                                <button rel="tooltip" title="Edit" class="btn btn-success btn-simple btn-xs edit-btn" value="<?php echo $row["id"]. '~' .$row["name"]. '~' .$row["email"]. '~' .$row["subject"]. '~' .$row["msg"]. '~' .$row["host"]; ?>">
                                                    <i class="fa fa-edit"></i>
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
<div class="modal fade" id="addEmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Add / Edit E-mail</h4>
            </div>
            <div class="modal-body">

                <form id="upload_ad" action="" method="post" enctype="multipart/form-data">
                    
                    <input id="emailid" style="display: none;">

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">face</i>
		                        </span>
                            <input id="name" name="name" type="text" class="form-control" placeholder="Name">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">face</i>
		                        </span>
                            <input id="email" name="email" type="email" class="form-control" placeholder="E-mail from">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">face</i>
		                        </span>
                            <input id="host" name="host" type="text" class="form-control" placeholder="Host">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">face</i>
		                        </span>
                            <input id="password" name="password" type="password" class="form-control" placeholder="Password">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">face</i>
		                        </span>
                            <input id="subject" type="text" class="form-control" placeholder="subject">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">content_paste</i>
		                        </span>
                            <textarea id="msg" style="width: 100%;" placeholder="HTML Message" rows="10" cols="100"></textarea>
                        </div>
                    </div>

                </form>
                <h4 id='loading' style="display: none">loading..</h4>
                <div id="message"></div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-default btn-simple" data-dismiss="modal">Close</button>
                <button id="submit-email-temp" type="submit" class="btn btn-info btn-simple">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Core -->
<div class="modal fade" id="emailTempModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">E-mail Template</h4>
            </div>
            <div id="modal-body" class="modal-body">

            </div>

        </div>

    </div>
</div>

</body>

<?php include ('scripts.html'); ?>

<script>


    $('.remove-btn').click(function(){
        /* when the submit button in the modal is clicked, submit the form */

        var dataString = "tblName=" + "email_temps" + "&id="+ $(this).attr("value");

        $.ajax({
            type: "POST",
            url: "../removeImg.php",
            data: dataString,
            success: function() {
                $(this).hide();
                alert("E-mail Template Removed");
                location.reload();
            }
        });
        return false;
    });

    $('.edit-btn').click(function(){
        /* when the submit button in the modal is clicked, submit the form */
        var paramsArr = $(this).attr("value").split('~');

        $('#emailid').val(paramsArr[0]);
        $('#name').val(paramsArr[1]);
        $('#email').val(paramsArr[2]);
        $('#subject').val(paramsArr[3]);
//        $('#msg').val(paramsArr[4]);
        tinyMCE.activeEditor.setContent(paramsArr[4]);
        $('#host').val(paramsArr[5]);

        $('#addEmailModal').modal('show');
        return false;
    });

    $('.fab-add').click(function(){
        /* when the submit button in the modal is clicked, submit the form */


        $('#emailid').val("");
        $('#name').val("");
        $('#email').val("");
        $('#subject').val("");
        $('#msg').val("");
        $('#host').val("");
        $('#password').val("");

        $('#addEmailModal').modal('show');
        return false;
    });

    $("#category_ddf li").click(function () {
        $("#categoryFilter").text($(this).text());

        // Declare variables
        var input, filter, table, tr, td, i;
        //input = document.getElementById("myInput");
        filter = $(this).attr('value').toLowerCase();
        table = document.getElementById("table");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[5];
            if (td) {

                if (td.innerHTML.indexOf(filter) > -1 || filter === "all") {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }

    });

    var category = "";

    $("#category_dd li").click(function () {
        $("#category").text($(this).text());
        category = $(this).attr('value');
    });

    $('button#submit-email-temp').on('click', function(e){

        $("#message").empty();
        $('#loading').show();

        //var dataString = 'ad_name=' + $('#ad_name').val() + "&file=" + $("#file").files[0] + "&category=" + category;

        //alert(dataString);
        var form = new FormData();
        form.append("id", $('#emailid').val());
        form.append("name", $('#name').val());
        form.append("email", $('#email').val());
        form.append("subject", $('#subject').val());
        form.append("msg", $('#msg').val());
        form.append("host", $('#host').val());
        form.append("password", $('#password').val());

        $.ajax({
            url: "../addEmailTemp.php", // Url to which the request is send
            type: "POST",
            dataType: 'json',// Type of request to be send, called as method
            data: form, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,        // To send DOMDocument or non processed data file it is set to false
            success: function(data)   // A function to be called if request succeeds
            {
                alert(data.msg);
                $('#loading').hide();
                $("#message").html(data.msg);

                if (!data.error){
                    location.reload();
                }
            }
        });

    });

    $('.vemail-btn').click(function(){

        var id = $(this).attr("value");

        var modalBody = document.getElementById("modal-body");

        $('#modal-body').text("");

        var form = new FormData();
        form.append("id", id);


        $('#emailTempModal').modal('show');
        modalBody.innerHTML = '';

            $.ajax({
            url: "../getEmailTemp.php", // Url to which the request is send
            type: "POST",
            dataType: 'json',// Type of request to be send, called as method
            data: form, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,        // To send DOMDocument or non processed data file it is set to false
            success: function(data)   // A function to be called if request succeeds
            {

                // Decode HTML from DB
                var emailStr = $("<div />").html(data.msg).text();

                $('#modal-body').append(emailStr);

            }
        });
        return false;
    });


    $(document).ready(function (e) {

        $(".nav li:nth-child(9)").addClass('active');

    });


    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });


</script>

<!--<script src="assets/js/nicEdit.js" type="text/javascript"></script>-->
<!--<script src='https://cloud.tinymce.com/stable/tinymce.min.js'></script>-->
<script src="assets/js/tinymce/tinymce.min.js" type="text/javascript"></script>
<script type="text/javascript">
    tinymce.init({
        selector: 'textarea',
        height: 500,
        menubar: false,
        branding: false,
        entity_encoding: 'named',
        document_base_url: 'https://crete-dev.com/bmg/Admin/',
        plugins: [
            'advlist autolink lists link image charmap print preview anchor textcolor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code wordcount'
        ],
        toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css'],
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });

    // Prevent bootstrap dialog from blocking focusin
    $(document).on('focusin', function(e) {
        if ($(e.target).closest(".mce-window").length) {
            e.stopImmediatePropagation();
        }
    });
</script>

</html>
