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

$result = $access->getApplications();

$documents = $access->getTableContent("documents");

?>


<!doctype html>
<html lang="en">
<head>

    <title>Applications</title>

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
                                <h4 style="margin-left: 5%" class="title">Applications</h4>
                                <p style="margin-left: 5%" class="category">Manage Applications</p>

                            </div>
                            <div class="card-content table-responsive">
                                <input class="form-control" type="text" id="search" onkeyup="searchFn()" placeholder="Search for ..." title="Type in ...">

                                <table id="table" class="table">
                                    <thead class="text-primary">

                                    <th onclick="sortTable(0)">Applicant's Full Name</th>
                                    <th onclick="sortTable(1)">Type of Service</th>
                                    <th onclick="sortTable(2)">Payment</th>
                                    <th onclick="sortTable(3)">Application Status</th>
                                    <th onclick="sortTable(4)">Date</th>
                                    <th>Notes</th>
                                    <th>Documents</th>
                                    <th>Action</th>
                                    </thead>
                                    <tbody>
                                    <?php

                                    while ($row = mysqli_fetch_array($result)) {

                                        ?>
                                        <tr>
                                            <td><?php echo $row["fname"] ." ";echo $row["mname"] ." ";echo $row["lname"]; ?></td>
                                            <td><?php echo $row["visa"]. " " .$row["type"]; ?></td>
                                            <td><?php echo $row["amount"]; ?></td>
                                            <td><?php echo $row["app_status"]; ?></td>
                                            <td><?php echo date( 'M. d, Y h:i A', strtotime($row["created_at"]) ); ?></td>
                                            <td><?php echo $row["seek_for"]; ?></td>
                                            <td>
                                                <button rel="tooltip" title="View Documents" class="btn btn-success btn-simple btn-xs vdoc-btn" value="<?php echo $row["id"];?>">
                                                    <i class="fa fa-file-image-o"></i>
                                                </button>
                                            </td>
                                            <td class="td-actions text-right">
                                                <button rel="tooltip" title="Edit" class="btn btn-success btn-simple btn-xs edit-btn" value="<?php echo $row["id"]. '~' .$row["fname"]. ' ' .$row["mname"]. ' ' .$row["lname"]. '~' .$row["visa"]. ' ' .$row["type"]. '~' .$row["app_status"]; ?>">
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
                <h4 class="modal-title" id="myModalLabel">Edit Application status</h4>
            </div>
            <div class="modal-body">

                <form id="upload_ad" action="" method="post" enctype="multipart/form-data">

                    <input id="id" style="display: none;">

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">format_size</i>
		                        </span>
                            <input id="applicant" type="text" class="form-control" placeholder="Applicant Name">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="input-group">
		                        <span class="input-group-addon">
			                        <i class="material-icons">content_paste</i>
		                        </span>
                            <input id="service" type="text" class="form-control" placeholder="Immigration Service">
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


<!-- Modal Core -->
<div class="modal fade" id="documentsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Submitted Documents</h4>
            </div>
            <div class="modal-body">


                <div class="card-content table-responsive">
                    <table id="docTbl" class="table">
                        <tr>
                            <thead class="text-primary">
                            <th>Document</th>
                            <th>Submission</th>
                            <th>Status</th>
                            <th>Actions</th>
                            </thead>
                        </tr>
                    </table>
                </div>

                <form id="upload_ad" action="" method="post">

                    <input id="application_id" style="display: none;">

                    <div class="col-sm-12">
                        <div class="input-group">
                            <div class="col-md-3 dropdown">
                                <a href="#" class="btn btn-simple dropdown-toggle" data-toggle="dropdown" id="document">
                                    Assign new document
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu" data-background-color="green" id="document_dd">
                                    <?php
                                    while ($row = mysqli_fetch_array($documents)) {

                                        ?>
                                        <li value="<?php echo $row["id"]; ?>"><a><?php echo $row["name"]; ?></a></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>

                </form>
                <h4 id='loading_doc' style="display: none">loading..</h4>
                <div id="message_doc"></div>
            </div>


            <div class="modal-footer">
                <button type="reset" class="btn btn-default btn-simple" data-dismiss="modal">Close</button>
                <button id="edit-userdocs-btn" type="submit" class="btn btn-info btn-simple">Submit</button>
            </div>
        </div>

    </div>
</div>


</body>

<?php include ('scripts.html'); ?>

<script>

    $('.remove-btn').click(function(){
        /* when the submit button in the modal is clicked, submit the form */

        var dataString = "tblName=" + "applications" +  "&id="+ $(this).attr("value");

        $.ajax({
            type: "POST",
            url: "../removeImg.php",
            data: dataString,
            success: function() {
                $(this).hide();
                alert("Application Removed");
                location.reload();
            }
        });
        return false;
    });

    $('.edit-btn').click(function(){
        /* when the submit button in the modal is clicked, submit the form */
        var paramsArr = $(this).attr("value").split(',');

        $('#id').val(paramsArr[0]);
        $('#applicant').val(paramsArr[1]);
        $('#service').val(paramsArr[3]);
        $('#status').val(paramsArr[4]);

        $('#addAD').modal('show');
        return false;
    });

    $('.fab-add').click(function(){
        /* when the submit button in the modal is clicked, submit the form */

        $('#id').val("");
        $('#applicant').val("");
        $('#service').val("");
        $('#status').val("");


        $('#addAD').modal('show');
        return false;
    });

    $('button#upload-ad-btn').on('click', function(e){

        $("#message").empty();
        $('#loading').show();

        //var dataString = 'ad_name=' + $('#ad_name').val() + "&file=" + $("#file").files[0] + "&category=" + category;

        //alert(dataString);
        var form = new FormData();
        form.append("id", $('#id').val());
        form.append("applicant", $('#applicant').val());
        form.append("service", $('#service').val());
        form.append("status", $('#status').val());


        $.ajax({
            url: "../addApplications.php", // Url to which the request is send
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


    $('.vdoc-btn').click(function(){

        var app_id = $(this).attr("value");

        $('#application_id').val(app_id);

        var subTable = document.getElementById("docTbl");


        var form = new FormData();
        form.append("app_id", app_id);


        var rowCount = subTable.rows.length;
        for (var x=rowCount-1; x>0; x--) {
            subTable.deleteRow(x);
        }

        $('#documentsModal').modal('show');

        $.ajax({
            url: "../getDocSubmissions.php", // Url to which the request is send
            type: "POST",
            dataType: 'text',// Type of request to be send, called as method
            data: form, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,        // To send DOMDocument or non processed data file it is set to false
            success: function(data)   // A function to be called if request succeeds
            {
//                alert(data);
                var returnedData = JSON.parse(data);

                subTable.style.display = 'block';

                var tbody = document.createElement("tbody");

                for (var i = 0 ; i < returnedData.length; i++) {
                    var tr = document.createElement("tr");

                    // for each inner array cell
                    // create td then text, append
                    var tdName = document.createElement("td");
                    var name = document.createTextNode(returnedData[i].name);
                    tdName.appendChild(name);

                    var tdStatus = document.createElement("td");
                    var status = document.createTextNode(returnedData[i].status);
                    tdStatus.appendChild(status);

                    var type = returnedData[i].type;
                    var tdUrl = document.createElement("td");
                    var url = returnedData[i].url;

                    var tdActions = document.createElement("td");


                    if (returnedData[i].status != "new"){

                        if (type == "0"){

                            var img = document.createElement("img");
                            img.src = '<?php

                                require ("../secure/bmconn.php");
                                echo REQ_SUB;
                                ?>' + url;

                            var imgUrl = document.createElement("url"); //or grab it by tagname etc

                            imgUrl.innerHTML = '<a target="_blank" href="' + img.src + '"><img style="height: 100px; width: auto" src="'+ img.src + '"/></a>';

                            tdUrl.appendChild(imgUrl);
                        }
                        else{
                            var link = document.createElement("url"); //or grab it by tagname etc
                            link.href = url;
                            link.innerHTML = '<a target="_blank" href="' + url + '">Click here to view</a>';

                            tdUrl.appendChild(link);
                        }

                        tdActions.innerHTML = '';

                    }
                    if (returnedData[i].status === "waiting"){

                        tdActions.innerHTML =
                            '<button rel="tooltip" title="Approve" id="approve-btn" class="btn btn-success btn-simple btn-xs approve-btn" value="' + returnedData[i].id + '">' +
                            '<i class="fa fa-check"></i>' +
                            '</button>' +
                            '<button value="' + returnedData[i].id + '" type="button" rel="tooltip" title="Reject" id="reject-btn" class="btn btn-danger btn-simple btn-xs reject-btn">' +
                            '<i class="fa fa-times"></i>' +
                            '</button>';
                    }


                    tr.appendChild(tdName);
                    tr.appendChild(tdUrl);
                    tr.appendChild(tdStatus);
                    tr.appendChild(tdActions);


                    // append row to table
                    // IE7 requires append row to tbody, append tbody to table
                    tbody.appendChild(tr);
                    subTable.appendChild(tbody);


                }
            }
        });
        return false;
    });


    $(document).on("click",".approve-btn",function(){

        /* when the submit button in the modal is clicked, submit the form */
        var req_id = $(this).attr("value");

        var form = new FormData();
        form.append("req_id", req_id);
        form.append("status", "approved");

        $.ajax({
            url: "../updateDocStatus.php", // Url to which the request is send
            type: "POST",
            dataType: 'json',// Type of request to be send, called as method
            data: form, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData: false,        // To send DOMDocument or non processed data file it is set to false
            success: function (data)   // A function to be called if request succeeds
            {

                alert(data.message);

            }
        });

    });

    $(document).on("click",".reject-btn",function(){

        /* when the submit button in the modal is clicked, submit the form */
        var req_id = $(this).attr("value");

        var form = new FormData();
        form.append("req_id", req_id);
        form.append("status", "rejected");

        $.ajax({
            url: "../updateDocStatus.php", // Url to which the request is send
            type: "POST",
            dataType: 'json',// Type of request to be send, called as method
            data: form, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData: false,        // To send DOMDocument or non processed data file it is set to false
            success: function (data)   // A function to be called if request succeeds
            {

                alert(data.message);

            }
        });

        return false;

    });

    var newDoc = "";
    $("#document_dd li").click(function () {
        $("#document").text($(this).text());
        newDoc = $(this).attr('value');
    });

    $('button#edit-userdocs-btn').on('click', function(e){


        $("#message_doc").empty();
        $('#loading_doc').show();

        //var dataString = 'ad_name=' + $('#ad_name').val() + "&file=" + $("#file").files[0] + "&category=" + category;
        //alert(dataString);
        var form = new FormData();
        form.append("doc_id", newDoc);
        form.append("app_id", $('#application_id').val());

        $.ajax({
            url: "../assignDocument.php", // Url to which the request is send
            type: "POST",
            dataType: 'json',// Type of request to be send, called as method
            data: form, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,        // To send DOMDocument or non processed data file it is set to false
            success: function(data)   // A function to be called if request succeeds
            {

                $('#loading_doc').hide();
                $("#message_doc").html(data.message);

                alert(data.message);

                if (!data.error){
                    location.reload();
                }
            }
        });
    });

    $(document).ready(function (e) {

        $(".nav li:nth-child(4)").addClass('active');

    });
</script>

</html>
