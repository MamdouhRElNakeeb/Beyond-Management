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

$result = $access->getTableContent("immigration");

?>


<!doctype html>
<html lang="en">
<head>

    <title>Services</title>

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
                                <h4 style="margin-left: 5%" class="title">Services</h4>
                                <p style="margin-left: 5%" class="category">Control your services</p>

                                <button class="btn btn-primary btn-fab btn-fab-mini btn-round top-right fab-add" data-background-color="red" data-toggle="modal" data-target="#addAD" style="margin-top: -5%">
                                    <i class="material-icons">add</i>
                                </button>

                            </div>

                            <div class="card-content table-responsive">
                                <input class="form-control" type="text" id="search" onkeyup="searchFn()" placeholder="Search for ..." title="Type in a name">
                                <table id="table" class="table sindu-table">
                                    <thead class="text-primary">

                                    <th onclick="sortTable(0)">Name</th>
                                    <th>Image</th>
                                    <th onclick="sortTable(2)">Info</th>
                                    <th onclick="sortTable(3)">Basic</th>
                                    <th onclick="sortTable(4)">Intermediate</th>
                                    <th onclick="sortTable(5)">Advanced</th>
                                    <th>Action</th>
                                    </thead>
                                    <tbody>
                                    <?php

                                    while ($row = mysqli_fetch_array($result)) {

                                        ?>
                                        <tr>
                                            <td><?php echo $row["name"]; ?></td>
                                            <td><img src="../ServicesImgs/<?php echo $row["img"]; ?>" style="height: 100px; width: auto"></td>
                                            <td><?php echo $row["info"]; ?></td>
                                            <td data-toggle="tooltip" data-placement="bottom" data-container="body" title="<?php echo $row["basic_info"]; ?>"><?php echo $row["basic_price"]; ?></td>
                                            <td data-toggle="tooltip" data-placement="bottom" data-container="body" title="<?php echo $row["inter_info"]; ?>"><?php echo $row["inter_price"]; ?></td>
                                            <td data-toggle="tooltip" data-placement="bottom" data-container="body" title="<?php echo $row["advanced_info"]; ?>"><?php echo $row["advanced_price"]; ?></td>
                                            <td class="td-actions text-right">
                                                <button rel="tooltip" title="Edit" class="btn btn-success btn-simple btn-xs edit-btn" value="<?php echo $row["id"]. '~' .$row["name"]. '~' .$row["img"]. '~' .$row["info"]. '~' .$row["basic_info"]. '~' .$row["basic_price"]. '~' .$row["inter_info"]. '~' .$row["inter_price"]. '~' .$row["advanced_info"]. '~' .$row["advanced_price"]; ?>">
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
                <h4 class="modal-title" id="myModalLabel">Add / Edit Service</h4>
            </div>
            <div class="modal-body">

                <form id="upload_ad" action="" method="post" enctype="multipart/form-data">
                    
                    <input id="serviceid" style="display: none;">

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">face</i>
		                        </span>
                            <input id="name" name="name" type="text" class="form-control" placeholder="VISA Name">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">content_paste</i>
		                        </span>
                            <textarea id="info" class="form-control" placeholder="Info" rows="5"></textarea>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">face</i>
		                        </span>
                            <input id="basic_price" type="number" class="form-control" placeholder="Basic Price">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">content_paste</i>
		                        </span>
                            <textarea id="basic_info" class="form-control" placeholder="Basic Info" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">face</i>
		                        </span>
                            <input id="inter_price" type="number" class="form-control" placeholder="Intermediate Price">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">content_paste</i>
		                        </span>
                            <textarea id="inter_info" class="form-control" placeholder="Info" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">face</i>
		                        </span>
                            <input id="advanced_price" type="number" class="form-control" placeholder="Advanced Price">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">content_paste</i>
		                        </span>
                            <textarea id="advanced_info" class="form-control" placeholder="Advanced Info" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">add_a_photo</i>
		                        </span>
                            <span class="btn btn-primary btn-file">
                                <input type="file" name="file" id="file" required />
                            </span>

                            <img id="previewing" src="#" style="width: 50%"/>
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

        var dataString = "tblName=" + "immigration" + "&dir=" + "ServicesImgs" +  "&id="+ $(this).attr("value");

        $.ajax({
            type: "POST",
            url: "../removeImg.php",
            data: dataString,
            success: function() {
                $(this).hide();
                alert("Immigration Service Removed");
                location.reload();
            }
        });
        return false;
    });

    $('.edit-btn').click(function(){
        /* when the submit button in the modal is clicked, submit the form */
        var paramsArr = $(this).attr("value").split('~');

        $('#serviceid').val(paramsArr[0]);
        $('#name').val(paramsArr[1]);
        $('#info').val(paramsArr[3]);
        $('#basic_info').val(paramsArr[4]);
        $('#basic_price').val(paramsArr[5]);
        $('#inter_info').val(paramsArr[6]);
        $('#inter_price').val(paramsArr[7]);
        $('#advanced_info').val(paramsArr[8]);
        $('#advanced_price').val(paramsArr[9]);
        $('#previewing').attr('src', "../ServicesImgs/" + paramsArr[2]);

        $('#addAD').modal('show');
        return false;
    });

    $('.fab-add').click(function(){
        /* when the submit button in the modal is clicked, submit the form */


        $('#serviceid').val("");
        $('#name').val("");
        $('#info').val("");

        $('#previewing').attr('src', "");


        $('#addAD').modal('show');
        return false;
    });

    $("#category_ddf li").click(function () {
        $("#categoryFilter").text($(this).text());

        // Declare variables
        var input, filter, table, tr, td, i;
        //input = document.getElementById("myInput");
        filter = $(this).attr('value').toLowerCase();
        table = document.getElementById("table-1");
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

    $('button#upload-ad-btn').on('click', function(e){

        $("#message").empty();
        $('#loading').show();

        //var dataString = 'ad_name=' + $('#ad_name').val() + "&file=" + $("#file").files[0] + "&category=" + category;

        //alert(dataString);
        var form = new FormData();
        form.append("id", $('#serviceid').val());
        form.append("name", $('#name').val());
        form.append("info", $('#info').val());
        form.append("basic_info", $('#basic_info').val());
        form.append("basic_price", $('#basic_price').val());
        form.append("inter_info", $('#inter_info').val());
        form.append("inter_price", $('#inter_price').val());
        form.append("advanced_info", $('#advanced_info').val());
        form.append("advanced_price", $('#advanced_price').val());
        form.append("file", $("#file").prop('files')[0]);


        $.ajax({
            url: "../addService.php", // Url to which the request is send
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

        $(".nav li:nth-child(2)").addClass('active');
    });


    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })


</script>

</html>
