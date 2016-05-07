<?php
require_once dirname(__FILE__) . '/mailer.php';
require_once dirname(__FILE__) . '/db.php';


function amount_to_months($amount)
{
  if ($amount >= 1500)
    return 12;
  else if ($amount >= 150)
    return 1;
  else
    return 0;
}

function submit_payment($email, $amount, $name = NULL)
{
  global $link;

  if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    mail_and_die('invalid email', __FILE__);
  $months = amount_to_months($amount);
  if ($months == 0)
    mail_and_die('wrong amount', __FILE__);

  // TODO: generate this salt
  $salt = 'salT';
  // Friendlier pincode instead of password
  $crc = crc32($salt.strtoupper($email)) & 0x7FFFFFFF;//remove sign
  $password = sprintf("%06u", $crc % 1000000);

  $email2 = $link->escapeString(trim($email));
  $password2 = $link->escapeString($password);
  $salt2 = $link->escapeString($salt);
  $name2 = $link->escapeString(trim($name));

  if (!empty($name2)) {
    $link->exec("INSERT OR IGNORE INTO Users (email,name) VALUES('$email2','$name2')")
      or mail_and_die('link->exec INSERT Users error', __FILE__);
  }
  else {
    $link->exec("INSERT OR IGNORE INTO Users (email) VALUES('$email2')")
      or mail_and_die('link->exec INSERT Users error', __FILE__);
  }
  $isnew = $link->changes() == 1;

  $link->exec("INSERT INTO Payments (email, submitted, amount) VALUES('$email2', DATETIME('now'), $amount)")
    or mail_and_die('link->exec INSERT Payments error', __FILE__);

  // FIXME: might fail because of unique password (change salt)
  $link->exec("UPDATE Users SET paid = DATE(MAX(IFNULL(paid,0), DATE('now')),'+$months MONTH'), salt = '$salt2', password = '$password2' WHERE email = '$email2'")
    or mail_and_die('link->exec UPDATE error', __FILE__);
  if ($link->changes() != 1)
    mail_and_die('link->changes should be 1', __FILE__);

  $subject = 'Welcome to Dim Sum Labs 欢迎加入點心樂部';
  $body = "Welcome! 欢迎！

  Your membership has been extended for $months month(s).
  Note that your access will be revoked if no payment was made.

  You can now open the door by going to http://door/
  PIN: $password

  If you haven't done so already, you can also bind your Octopus card to your PIN by swiping your Octopus card before entering your PIN code.

  -- the script that sends out these emails";
  mailer($email, $subject, $body);

  if ($isnew)
    $neworold = "New";
  else
    $neworold = "Old";
  mailer('accounts@d'.'imsumlabs.com', "$neworold member: $email, paid $amount for $months month(s).", '-- '.__FILE__);
}

