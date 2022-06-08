<?php session_start();?>

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
                        <li class="nav-item ">
                            <a class="nav-link" href="plagiarism_checker.php">Plagiarism <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="verify.php">Verification</a>
                        </li>
                    </ul>
                
                    
                    <a class="btn btn-outline-info my-2 my-sm-0" href="logout.php">Logout</a>
                    
                </div>
            </nav>
        </div>
        <div class="container">
            <div class="col-8 mx-auto">
                <div class="text-center">
                    <div class="shadow-lg p-3 mb-5 bg-body rounded">
                        <h1>User Verification</h1>
                    </div>
                </div>
            </div>
        </div>

      

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <div class="shadow p-3 mb-5 bg-white rounded">
                        <table id="userTable" class="table table-bordered">
                            <thead>
                                <th>SL</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>User Type</th>
                                <th>Verify</th>
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
        $(document).ready(function(){

            var url = 'user_data.php';
            // DataTable
            var DataTable = $('#userTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': url,
                },
                'columns': [
                    { data: 'id' },
                    { data: 'username' },
                    { data: 'email' },
                    { data: 'user_type' },
                    { data: 'verify' },
                   
                ]
            });

          
            // Update reply
            DataTable.on('click','.verify',function(){
                var id = $(this).data('id');
                var reply = $(this).data('reply');
                


               // AJAX request
               $.ajax({
                  url: url,
                  type: 'post',
                  data: {request: 2, id: id, reply: reply},
                  dataType: 'json',
                  success: function(response){
                      if(response.status == 1){
                        $('.alert-success').show();
                        $('#success').text(response.message);
                        $('.alert-success').delay(2000).fadeOut();
                        
                        DataTable.ajax.reload( null, false );

                      }else{
                        $('.alert-danger').show();
                        $('#danger').text(response.message);
                        $('.alert-danger').delay(2000).fadeOut();
                      }
                  }
              });

            });
          });
    </script>
</html>