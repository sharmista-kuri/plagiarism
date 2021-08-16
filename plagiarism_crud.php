<?php
    include "config.php"; 

    //print_r($link);exit;
    if(isset($_POST)){
        $action = $_POST['action'];
        if($action=='insert'){
            $result = insert($link);
            echo $result;
        }

        else if($action=='select'){
            $result = select($link);
            echo $result;
        }

    }

    function insert($link){
        $guid = $_POST['guid'];
        $file_name = $_POST['file_name'];
        $path = $_POST['path'];

        $query="INSERT INTO `document_tbl` (`guid`,`file_name`, `path`) VALUES ('$guid','$file_name', '$path')";
        
        $result=mysqli_query($link,$query);

        return $result;
    }

    function select($link){
        $guid = $_POST['guid'];

        $query="SELECT * FROM `document_tbl` WHERE guid='$guid';";
        
        $result=mysqli_query($link,$query);

        if(mysqli_num_rows($result) > 0){
            // Fetch result rows as an associative array
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $file_path = $row["path"].$row["file_name"];
                $msg = $file_path;
            }
        } else{
            $msg =  "No matches found";

        }

        return $msg;
    }
    