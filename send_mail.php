<?php
use PHPMailer\PHPMailer\PHPMailer;
class Send_Mail {
  function send_mail_function($email,$subject,$str){

      @include('vendor/autoload.php');

      $mail = new PHPMailer();
      $mail->isSMTP();
      $mail->isHTML(true);
      $mail->Mailer = "smtp";
      $mail->SMTPSecure = 'tls';
      $mail->SMTPAuth = true;
      $mail->Host = 'smtp.gmail.com';
      $mail->Port = 587;
      $mail->Username = 'co_letter@du.ac.bd';
      $mail->Password = '###sabbir01922###';
      $mail->setFrom('co_letter@du.ac.bd');
      $mail->FromName = "co_letter@du.ac.bd";
      $mail->addAddress($email);
      $mail->Subject = $subject;
      $mail->msgHTML($str);
      $mail->send();
      print_r($mail->ErrorInfo);

  }
}

//send_mail('skuri.cse@gmail.com',"test","test");
