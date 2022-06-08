<?php
    include "config.php"; 
    if (isset($_GET['email']) && isset($_GET['code']))
    {

        $email=$_GET['email'];
        $code=$_GET['code'];

        $query="SELECT * FROM `user_tbl`WHERE code='$code' and email ='$email'";

        $check=mysqli_query($link,$query);
        $result1 = mysqli_num_rows($check);

        if( $result1 == 0){
            $msg_reg="The email address is not verified.<br>";
        }
        else{

            $query="UPDATE `user_tbl`SET email_verified=1 WHERE code='$code' and email ='$email'";

            $result=mysqli_query($link,$query);
            if($result){
                $msg_reg="The email address has been verified<br> Please wait approx. 2 working days to verify your ID from admin panel.";
            }
            else{
                $msg_reg="The email address is not verified.<br>";
            }

            
        }

        $_SESSION['msg']=$msg_reg;
    }

?>