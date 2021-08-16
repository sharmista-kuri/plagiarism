<?php
    //include 'guid.php';
    //$GUID = GUID();

    $PATH = "http://localhost/plagiarism/document_file/";

    //echo $GUID;
?>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js"></script>
        <style>
            body{
                background: #ecf0f5;
            }
            .shadow{
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            }
            
        </style>
    </head>
    <body>  
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
                               
                                <input type="file" id="file_upload" style="display: none" >
                                <button type="button" class="btn btn-light"><i style="color:#11a683" onclick="loadFile()" class="fa fa-upload" aria-hidden="true"> Upload a File</i></button>
                                Search Maximum: <input type="text" id="top" name="top" value="10">
                            </div>

                            <div>
                                <button onclick="indexing()" type="button" class="btn btn-info">Document Index</button>
                                <button onclick="checking()" type="button" class="btn btn-info">Scan for plagarism</button>
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
                        <table id="datatable" class="table table-bordered">
                            <thead>
                                <th>SL</th>
                                <th>Search</th>
                                <th>Percentage</th>
                                <th>Matched Document</th>
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
        } );
        function loadFile(){
            $("#file_upload").trigger('click');
        }

        function checking(){
            
            //alert("hi");
            var form = $('#file_form')[0];
		    var data = new FormData(form);
            //console.log(file_upload.files[0]);
            //console.log(form);
            var guid = "";
            
            get_guid();

            var GUID = $("#guid").val();

            data = new FormData();
            data.append( 'file_upload', $( '#file_upload' )[0].files[0] );
            data.append( 'GUID', GUID);
            data.append( 'top', $("#top").val());
            console.log(data);
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
                    console.log(data);
                    datas = $.parseJSON(data);
                    console.log(datas);
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
                            if(query.length > n) {
                                query = query.substring(0,n);
                                query = query+".....";
                            }
                        }
                        else{
                            i++;
                            console.log(value['id']);
                            console.log(value['value']);
                            console.log(value['percentage']);
                            name = value['value'];

                            name =  name.replace('"', ' ');

                            if(name.length > n) {
                                name = name.substring(0,n);
                                name = name+".....";
                            }

                            id = value['id'];
                            get_file(id);

                            file_path = $("#file_path").val();
                            
                           
                            console.log(file_path);

                            

                            str+="<tr>";
                            str+="<td>"+i+"</td>";
                            str+="<td>"+query+"</td>";
                            str+="<td>";
                            str+='<input type="hidden" id="percentage_'+i+'" value='+value['percentage']+'>';
                            str+='<canvas id="myChart_'+i+'" width="200" height="50"></canvas>';
                            str+="</td>"
                            str+="<td>"+name+"</td>";
                            str+='<td><a id="download_link_'+i+'" download href="'+file_path+'"><i style="color:#11a683" class="fa fa-download"> File</i></a></td>';
                            str+="</tr>";

                        }
                        
                        
                        
                    });
                    $("#datatable tbody").append(str);
                    
                    $('#datatable').DataTable();
                    $("#counter").val(i);
                    
                   
                    for(j=1; j<=i; j++){
                        create_canvas(j);
                    }
                    

                    

                    

                    
                },
                error: function(data) {
                    
                }
            });

            
           
            
        }

        function indexing(){
            console.log("hi");
            //alert("hi");
            
            var form = $('#file_form')[0];
		    var data = new FormData(form);
            console.log(file_upload.files[0]);
            console.log(form);

            var guid = "";

            get_guid();

            var GUID = $("#guid").val();

            data = new FormData();
            data.append( 'file_upload', $( '#file_upload' )[0].files[0] );
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
                },
                error: function(data) {
                    console.log(data);
                }
            });
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
                            boxWidth : 10
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