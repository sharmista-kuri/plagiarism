<?php
    session_start();
    include "config.php"; 

    //echo'<pre>';print_r($_POST['email']);exit;

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query="SELECT * FROM `user_tbl` WHERE email='$email' and password='$password';";
        
    $result=mysqli_query($link,$query);

    if(mysqli_num_rows($result) > 0){
        // Fetch result rows as an associative array
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            
            $_SESSION['username']=$row['username'];
            $_SESSION['user_type']=$row['user_type'];


            echo("<script>location.href = 'plagiarism_checker.php';</script>");
        }
    } else{
        $_SESSION['msg']="Wrong email/ password";
        echo("<script>location.href = 'index.php';</script>");

    }


?>