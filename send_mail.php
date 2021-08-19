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
      $mail->Username = 'skuri.cse@gmail.com';
      $mail->Password = 'S@umitra@123#';
      $mail->setFrom('skuri.cse@gmail.com');
      $mail->FromName = "skuri.cse@gmail.com";
      $mail->addAddress($email);
      $mail->Subject = $subject;
      $mail->msgHTML($str);
      $mail->send();
      print_r($mail->ErrorInfo);

  }
}

//send_mail('skuri.cse@gmail.com',"test","test");
