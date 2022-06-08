<?php
    session_start();
    include "config.php"; 

    //echo'<pre>';print_r($_POST['email']);exit;

    $email = $_POST['email'];
    $password = $_POST['password'];

    
    $query="SELECT * FROM `user_tbl` WHERE email='$email' and password='$password' and email_verified=1 and admin_verified=1 and sts=1;";
    $result1=mysqli_query($link,$query);

    $query="SELECT * FROM `user_tbl` WHERE email='$email' and password='$password' and email_verified=1 and admin_verified=0 and sts=1;";
    $result2=mysqli_query($link,$query);

    $query="SELECT * FROM `user_tbl` WHERE email='$email' and password='$password' and email_verified=0 and admin_verified=0 and sts=1;";
    $result3=mysqli_query($link,$query);

    $query="SELECT * FROM `user_tbl` WHERE email='$email' and password='$password' and email_verified=1 and admin_verified=1 and sts=1;";
    $result=mysqli_query($link,$query);

    
    if(mysqli_num_rows($result) > 0){
        
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            
            $_SESSION['username']=$row['username'];
            $_SESSION['user_type']=$row['user_type'];


            echo("<script>location.href = 'index.php';</script>");
        }
        
    } 
    
    else{
        if(mysqli_num_rows($result2) > 0){
            $_SESSION['msg']="Please wait approx. 2 working days to verify your ID from admin panel.";
        }
        else if(mysqli_num_rows($result3) > 0){
            $_SESSION['msg']="Email Not Verified";
        }
        else{
            $_SESSION['msg']="Wrong email/ password";
        }
 
        echo("<script>location.href = 'login_page.php';</script>");

    }


?>