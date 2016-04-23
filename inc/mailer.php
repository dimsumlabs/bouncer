<?php

require_once 'PHPMailer/PHPMailerAutoload.php';
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();

$mail->IsSMTP();
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = 'ssl';                 // sets the prefix to the servier
$mail->CharSet    = 'UTF-8';
$mail->Host       = 'smtp.gmail.com';   // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port for the GMAIL server


$mail->Username   = 'dsl.bouncer@g'.'mail.com';          // GMAIL/sendgrid username
// add SetEnv SMTP_PASSWORD "blah" to this site's Apache conf
$mail->Password   = getenv('SMTP_PASSWORD');              // GMAIL/sendgrid password

$mail->From       = 'dsl.bouncer@g'.'mail.com';
$mail->FromName   = 'Dim Sum Labs Bouncer';

$mail->WordWrap   = 60; // set word wrap

function mailer($mail_to, $subject, $body)
{
  global $mail;

  $mail->Subject = $subject;
  $mail->Body = $body;
  $mail->ClearAddresses();
  $mail->AddAddress($mail_to);
  return $mail->Send();
}

function mail_and_die($subject, $body)
{
  mailer('lio+dsl@l'.'unesu.com', $subject, $body);
  die($subject."\n".$body);
}
