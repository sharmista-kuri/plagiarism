<?php
include "config.php"; 
session_start();

$request = 1;
if(isset($_POST['request'])){
    $request = $_POST['request'];
}

$table = "user_tbl";

// DataTable data
if($request == 1){
    ## Read value
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length']; // Rows display per page
    $columnIndex = $_POST['order'][0]['column']; // Column index
    $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc

    //echo'<pre>';print_r($columnName);exit;

    $searchValue = mysqli_escape_string($link,$_POST['search']['value']); // Search value

    ## Search 
    $searchQuery = " ";
    /* if($searchValue != ''){
        $searchQuery = " and (id like '%".$searchValue."%' or ref_number like '%".$searchValue."%' or 
        recipient like'%".$searchValue."%' or
        send_date like '%".$searchValue."%' or  
        subject like'%".$searchValue."%' or
        body like'%".$searchValue."%' or
        deadline like'%".$searchValue."%' or
        email like'%".$searchValue."%'
        ) ";
    } */

    $condition = "and email_verified=1 and admin_verified=0 and sts=1";
    $searchQuery = $condition;

    ## Total number of records without filtering
    $sel = mysqli_query($link,"select count(*) as allcount from $table WHERE 1 ".$condition);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    ## Total number of records with filtering
    $sel = mysqli_query($link,"select count(*) as allcount from $table WHERE 1 ".$searchQuery);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];

    ## Fetch records
    $empQuery = "select * from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
    $empRecords = mysqli_query($link, $empQuery);
    $data = array();

    while ($row = mysqli_fetch_assoc($empRecords)) {

        // Update Button
        $updateButton = "<button class='btn btn-sm btn-primary update' data-id='".$row['id']."'>Update</button>";

        // Delete Button
        $deleteButton = "<button class='btn btn-sm btn-danger deleteUser' data-id='".$row['id']."'>Delete</button>";

        //pdf
        $pdfButton = "<a class='btn btn-sm btn-info text-center' target='_blank' href='".$row['id']."'>Pdf</a>";
        
        //verify
        $verifyButton = "<button class='btn btn-sm btn-primary verify' data-id='".$row['id']."'>Verify</button>";
        
        $action = '<div class="btn-group">'. $verifyButton.' </div> ';
        $pdf = '<div>'. $pdfButton.' </div> ';

        

       

        $data[] = array(
                "id" => $row['id'],
                "username" => $row['username'],
                "email" => $row['email'],
                "user_type" => $row['user_type'],
                "verify" => $action
            );
    }

    ## Response
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    );

    echo json_encode($response);
    exit;
}

// Update
if($request == 2){
    $id = 0;

    if(isset($_POST['id'])){
        $id = mysqli_escape_string($link,$_POST['id']);
    }

    // Check id
    $record = mysqli_query($link,"SELECT id FROM $table WHERE id=".$id);
    if(mysqli_num_rows($record) > 0){

        $reply = mysqli_escape_string($link,trim($_POST['reply']));
      

        if( $reply != ''){

            if($reply==1){
                $reply_sts = 0;
            }
            else{
                $reply_sts = 1;
            }
            $reply_by = $_SESSION['name'];
            $reply_dt = date("d/m/Y h:i:sa");

            $sql = "UPDATE $table SET reply_sts=$reply_sts, reply_by='$reply_by', reply_dt='$reply_dt'  WHERE id=".$id;
            mysqli_query($link, $sql);

            echo json_encode( array("status" => 1,"message" => "Record updated.") );
            exit;
        }else{
            echo json_encode( array("status" => 0,"message" => "Please fill all fields.") );
            exit;
        }
        
    }else{
        echo json_encode( array("status" => 0,"message" => "Invalid ID.") );
        exit;
    }
}





