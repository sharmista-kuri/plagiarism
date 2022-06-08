<?php session_start();?>
<?php
    include "config.php";
    $PATH = $UploadDirectory;
?>
<?php
    $show=0;
    if(isset($_SESSION)){?>
        <?php
        if(isset($_SESSION['user_type'])){
            if(isset($_SESSION['user_type'])=='admin'){
                $show=1;
            }
        } 
    } 
?>
<html>
    <head>
        <title>Plagiarism</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js"></script>

        <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

        <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>

        <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>

        <!-- Filepond stylesheet -->
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
        <style>
            body{
                background: #ecf0f5;
            }
            .shadow{
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            }
            
        </style>

        <style>
            /* use a hand cursor intead of arrow for the action buttons */
            .filepond--file-action-button {
                cursor: pointer;
            }

            /* the text color of the drop label*/
            .filepond--drop-label {
                cursor: pointer;
                color: #50a3b9;
                background: #ecf0f5;
                border-radius: 5px;
            }          

        </style>
    </head>
    <body> 
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light1" style="background-color: #e3f2fd;">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="#"></a>

                <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <li class="nav-item active">
                            <a class="nav-link" href="plagiarism_checker.php">Plagiarism <span class="sr-only">(current)</span></a>
                        </li>
                        <?php 
                            if($show){?>
                                <li class="nav-item">
                                    <a class="nav-link" href="verify.php">Verification</a>
                                </li>
                        <?php } ?>
                        
                    </ul>
                
                    <?php if($show){?>
                        <a class="btn btn-outline-info my-2 my-sm-0" href="logout.php">Logout</a>
                    <?php } 
                        else{
                    ?>
                        <a class="btn btn-outline-info my-2 my-sm-0" href="login_page.php">Log In</a>
                    <?php } ?>
                </div>
            </nav>
        </div>
        <div class="container">
            <div class="col-8 mx-auto">
                <div class="text-center">
                    <div class="shadow-lg p-3 mb-5 bg-body rounded">
                        <h1>Plagiarism Checker</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 text-center">
                    <div class="shadow p-3 mb-5 bg-white rounded">
                        <form id="file_form" method="post" enctype="multipart/form-data">
                            <div class="p-3 mb-5">
                                <input type="hidden" id="counter">
                                <input type="hidden" id="file_path" value="">
                                <input type="hidden" id="guid" value="">
                               
                                <input  class="filepond" type="file" id="file_upload" style="display: none" >
                                <!-- <button type="button" class="btn btn-light"><i style="color:#11a683" onclick="loadFile()" class="fa fa-upload" aria-hidden="true"> Upload a File</i></button> -->
                               
                            </div>
                            <div class="p-3 mb-3">
                                <?php 
                                    if($show){?>
                                        <div class="form-row">
                                            <label class="col-form-label col-sm-2" >URL: </label><input class="form-control col-sm-10" type="text" id="url" name="url" value="" placeholder="https://bn.wikipedia.org/wiki/প্রধান_পাতা">
                                        </div>    
                                <?php }?>
                                <div class="form-row">
                                    <label class="col-form-label col-sm-2" >Search Maximum: </label><input class="form-control col-sm-10" type="text" id="top" name="top" value="10">
                                </div>
                            </div>

                            <div>
                                <div style="display:none;" id="loading">
                                    <div class="spinner-border text-info" role="status">
                                        <span class="sr-only"></span>
                                    </div>
                                    <span>Loading...</span>
                                </div>
                            <?php 
                                if($show){?>
                                    <button id="url_button" onclick="url_indexing()" type="button" class="btn btn-info">Website Index</button>
                                    <button id="index_button" onclick="indexing()" type="button" class="btn btn-info">Document Index</button>
                            <?php } ?>
                                <button id="checking_button" onclick="checking()" type="button" class="btn btn-info">Scan for plagarism</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <div class="shadow p-3 mb-5 bg-white rounded">
                        <span id="query_txt"></spna>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <div class="shadow p-3 mb-5 bg-white rounded">
                        <table id="datatable" class="table table-bordered">
                            <thead>
                                <th>SL</th>
                                <th width="50%">Search</th>
                                <th width="1%">Percentage</th>
                                <th width="40%">Matched Document</th>
                                <th>Download</th>
                            </thead>
                            <tbody id="tbody">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
        $(document).ready(function() {
           // $('#datatable').DataTable();
           //$('.filepond').filepond();
           FilePond.registerPlugin(FilePondPluginFileValidateType);
           pond = FilePond.create(
            document.querySelector('#file_upload'), {
                allowMultiple: false,
                instantUpload: false,
                allowProcess: false,
                allowFileTypeValidation: true,
                acceptedFileTypes: ['.doc','.docx','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'],

                
                fileValidateTypeDetectType: (source, type) =>
        new Promise((resolve, reject) => {
            // Do custom type detection here and return with promise

            resolve(type);
        }),
                credits:false
            });
        } );
        function loadFile(){
            $("#file_upload").trigger('click');
        }

        function checking(){
            // $("#datatable tbody").html("");
            // $('#datatable').DataTable();

            $('#datatable').DataTable().clear().destroy();

            $("#checking_button").hide();
            $("#loading").show();
            
            //alert("hi");
            var form = $('#file_form')[0];
		    var data = new FormData(form);
            //console.log($( '#file_upload' ).files);
            //console.log(form);

            pondFiles = pond.getFiles();
 
            //console.log(pondFiles[0].file);


            var guid = "";
            
            get_guid();

            var GUID = $("#guid").val();

            data = new FormData();
            //data.append( 'file_upload', $( '#file_upload' )[0].files );
            data.append( 'file_upload', pondFiles[0].file );
            data.append( 'GUID', GUID);
            data.append( 'top', $("#top").val());
            //console.log(data);
            url = "plagiarism_checker_checking.php";
           
            jQuery.ajax({
                type: "POST",
                url: url,
                data : data,
                cache: false,
                processData: false,
                contentType: false,
                datatype: "html",
                enctype: 'multipart/form-data',
                success: function(data){
                    $("#checking_button").show();
                    $("#loading").hide();
                    console.log(data);
                    datas = $.parseJSON(data);
                    //console.log(datas);
                    var str="";
                    var name = "query";
                    var query = "";
                    var i = 0;
                    var n = 200;
                    var dir = "<?=$PATH?>";
                    
                    $("#tbody").html("");
                    $.each( datas, function( key, value ) {
                        
                        //var file_path = "";
                        
                        if(name in value){
                            query = value['query'];
                            query =  query.replace('"', ' ');
                            $("#query_txt").text(query);
                            if(query.length > n) {
                                query = query.substring(0,n);
                                query = query+".....";
                            }
                            

                        }
                        else{
                            i++;
                            //console.log(value['id']);
                            //console.log(value['value']);
                            //console.log(value['percentage']);
                            
                            name = value['value'];

                            name =  name.replace('"', ' ');

                            if(name.length > n) {
                                name = name.substring(0,n);
                                name = name+".....";
                            }

                            id = value['id'];
                            search_type = value['type'];
                            download_url_str = "";
                            
                            if(search_type=="file"){
                                get_file(id);

                                file_path = $("#file_path").val();
                                
                                
                                
                                

                                download_url_str='<a id="download_link_'+i+'" download href="'+file_path+'"><i style="color:#11a683" class="fa fa-download"> File</i></a>';
                                
                            }
                            else{

                                check = id.search('xampp');
                                
                                if(check!=-1){
                                    link_path_split = id.split('\\');
                                    //console.log(link_path_split);
                                    link_path = link_path_split[link_path_split.length - 1];
                                    link_code= link_path.replace(".html", "");
                                    id= "https://bn.wikipedia.org/wiki/"+link_code;
                                }
                                download_url_str='<a target="_blank" id="download_link_'+i+'" href="'+id+'"><i style="color:#11a683" class="fa fa-link"> File Link</i></a>';
                            }

                            
                            
                            
                           
                            //console.log(file_path);

                            

                            str+="<tr>";
                            str+="<td>"+i+"</td>";
                            str+="<td>"+query+"</td>";
                            str+="<td>";
                            str+='<input type="hidden" id="percentage_'+i+'" value='+value['percentage']+'>';
                            str+='<canvas id="myChart_'+i+'" width="200" height="50"></canvas>';
                            str+="</td>"
                            str+="<td>"+name+"</td>";
                            str+= "<td>"+download_url_str+"</td>";
                            str+="</tr>";

                        }
                        
                        
                        
                    });
                    $("#datatable tbody").append(str);

                    for(j=1; j<=i; j++){
                        create_canvas(j);
                    }

                    $("#counter").val(i);
                    $('#datatable').DataTable();
                    
                
                    
                },
                error: function(data) {
                    
                }
            });

            
           
            
        }

        function indexing(){
            //console.log("hi");
            //alert("hi");
            $("#index_button").hide();
            $("#loading").show();
            
            var form = $('#file_form')[0];
		    var data = new FormData(form);
            //console.log(file_upload.files[0]);
            //console.log(form);

            var guid = "";

            get_guid();
            pondFiles = pond.getFiles();

            var GUID = $("#guid").val();

            data = new FormData();
            data.append( 'file_upload', pondFiles[0].file );
            //data.append( 'file_upload', $( '#file_upload' )[0].files[0] );
            data.append( 'GUID', GUID);
            
            url = "plagiarism_checker_indexing.php";

            jQuery.ajax({
                type: "POST",
                url: url,
                data : data,
                cache: false,
                processData: false,
                contentType: false,
                datatype: "json",
                enctype: 'multipart/form-data',
                success: function(data){
                    console.log(data);
                    store_db(data, GUID);
                    alert("Successfully Indexed");
                    $("#index_button").show();
                    $("#loading").hide();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function url_indexing(){
            var url_link = $("#url").val();
            if(url_link!=""){
                $("#url_button").hide();
                $("#loading").show();
                
                data = new FormData();
                data.append( 'url', url_link );
                url = "wiki_scrape.php";
                jQuery.ajax({
                    type: "POST",
                    url: url,
                    data : data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    datatype: "json",
                    enctype: 'multipart/form-data',
                    success: function(data){
                        console.log(data);
                        $("#loading").hide();
                        $("#url_button").show();
                        
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });

                
            }
        }
    </script>
    <script>
        function create_canvas(counter) {
            //var counter = $('#counter').val();
            var percentage = $('#percentage_'+counter).val();
            var unique = (100-parseInt(percentage));
            var ctx = document.getElementById('myChart_'+counter);

            //alert(counter);
            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: [
                        percentage+'% Plagiarism Detected',
                        
                    
                    ],
                    datasets: [{
                        label: 'Plagiarism',
                        data: [percentage, unique],
                        backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgba(178, 190, 181, 0.2)',
                        ],
                        borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 255, 255, 0)',
                        ],
                        borderWidth: 1,
                        hoverOffset: 4
                    }]
                },
                options: {
                    plugins: {
                        legend:{
                            display: true,
                            position : 'bottom',
                            boxWidth : 10,
                            itemWidth: 200,
                        }
                    }
                }

            });
        }
    </script>
    <script>
        function store_db(file, GUID){
            //console.log(file);
            var path = "<?=$PATH?>";
            

            var url = "plagiarism_crud.php";
            var action = "insert";

            data = new FormData();
            data.append( 'guid', GUID );
            data.append( 'file_name', file );
            data.append( 'path', path );
            data.append( 'action', action );

            jQuery.ajax({
                type: "POST",
                url: url,
                data : data,
                cache: false,
                processData: false,
                contentType: false,
                datatype: "json",
                enctype: 'multipart/form-data',
                success: function(data){
                    console.log(data);
                   
                },
                error: function(data) {
                    
                }
            });
        }
    </script>

<script>
        function get_file(GUID){
            var url = "plagiarism_crud.php";
            var action = "select";
            
            data = new FormData();
            data.append( 'guid', GUID );
            data.append( 'action', action );

            jQuery.ajax({
                type: "POST",
                url: url,
                data : data,
                async: false,
                cache: false,
                processData: false,
                contentType: false,
                datatype: "json",
                enctype: 'multipart/form-data',
                success: function(data){
                    console.log("file:"+data);
                   
                   $("#file_path").val(data);
                    
                   
                },
                error: function(data) {
                    
                }
            });
            
        }
    </script>
    
    <script>
        function get_guid(){
            var url = "guid.php";
            jQuery.ajax({
                type: "POST",
                url: url,
                async: false,
                cache: false,
                processData: false,
                contentType: false,
                datatype: "json",
                enctype: 'multipart/form-data',
                success: function(data){
                    //console.log("file:"+data);
                   
                   $("#guid").val(data);
                    
                   
                },
                error: function(data) {
                    
                }
            });
        }
    </script>
 
</html>