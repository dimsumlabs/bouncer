<?php
require 'inc/common.php';
require 'inc/db.php';

// TODO: use a shared secret + hash for API authentication

$rfid = $_GET['rfid'];
if ($rfid) {
  $rfid2 = $link->escapeString($rfid);
  $group = trim($_GET['group']);
  if ($group) {
    $group2 = $link->escapeString($group);
    $link->exec("UPDATE Groups SET count = count + 1 WHERE \"group\" = '$group2' AND email = (SELECT email FROM Users WHERE DATE('now') <= MAX(IFNULL(paid,0),IFNULL(paid_verified,0)) AND rfid = '$rfid2')")
      or die('link->exec UPDATE error(0)');
  }
  else {
    $link->exec("UPDATE Users SET count = count + 1, last_seen = DATETIME('now') WHERE DATE('now') <= MAX(IFNULL(paid,0),IFNULL(paid_verified,0)) AND rfid = '$rfid2'")
      or die('link->exec UPDATE error(1)');
  }
}
else
{
  $password = $_GET['password'];
  $email = trim($_GET['email']);

  $salt = 'salT';
  $passwordx = sprintf("%08x", crc32($salt.strtoupper($email)));

  $email2 = $link->escapeString($email);
  $password2 = $link->escapeString($password);
  $link->exec("UPDATE Users SET count = count + 1, last_seen = DATETIME('now') WHERE DATE('now') <= MAX(IFNULL(paid,0),IFNULL(paid_verified,0)) AND email = '$email2' AND password = '$password2'")
    or die('link->exec UPDATE error(2)');
}

if ($link->changes() != 1) {
  header('HTTP/1.1 403 Forbidden');
}
else {
  header('HTTP/1.1 200 OK');
}
$link->close();
unset($link);
