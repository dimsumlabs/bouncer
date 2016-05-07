<?php
require_once 'inc/common.php';
require_once 'inc/db.php';

// TODO: use a shared secret + hash for API authentication

$group = trim($_GET['group']);
$rfid = $_GET['rfid'];
if ($rfid) {
  $rfid2 = $link->escapeString($rfid);
  if ($group) {
    // ?rfid=xxx&group=xxx  Check membership of group (by rfid)
    $group2 = $link->escapeString($group);
    $link->exec("UPDATE Groups SET count = count + 1 WHERE \"group\" = '$group2' AND email = (SELECT email FROM Users WHERE DATE('now') <= MAX(IFNULL(paid,0),IFNULL(paid_verified,0)) AND rfid = '$rfid2')")
      or mail_and_die('link->exec UPDATE error(0)', __FILE__);
  }
  else {
    // ?rfid=xxx  Check validity of member (by rfid)
    $link->exec("UPDATE Users SET count = count + 1, last_seen = DATETIME('now') WHERE DATE('now') <= MAX(IFNULL(paid,0),IFNULL(paid_verified,0)) AND rfid = '$rfid2'")
      or mail_and_die('link->exec UPDATE error(1)', __FILE__);
  }
}
else
{
  $email = trim($_GET['email']);
  $email2 = $link->escapeString($email);
  if ($group) {
    // ?email=xxx&group=xxx  Add user to group (by email)
    $group2 = $link->escapeString($group);
    $link->exec("INSERT OR IGNORE INTO Groups (email,\"group\") VALUES('$email2','$group2')")
      or mail_and_die('link->exec INSERT error', __FILE__);
  }
  else {
    // ?email=xxx&password=xxx  Check validity of username and password
    $password = $_GET['password'];
    $salt = 'salT';
    $passwordx = sprintf("%08x", crc32($salt.strtoupper($email)));
    $password2 = $link->escapeString($password);
    $link->exec("UPDATE Users SET count = count + 1, last_seen = DATETIME('now') WHERE DATE('now') <= MAX(IFNULL(paid,0),IFNULL(paid_verified,0)) AND email = '$email2' AND password = '$password2'")
      or mail_and_die('link->exec UPDATE error(2)', __FILE__);
  }
}

if ($link->changes() != 1) {
  header('HTTP/1.1 403 Forbidden');
  if ($rfid2) {
    $email = $link->querySingle("SELECT email FROM Users WHERE rfid = '$rfid2'");
  }
  mailer('accounts@d'.'imsumlabs.com', 'Access denied', "Email:$email\nRFID:$rfid\nGroup:$group\n");
}
else {
  header('HTTP/1.1 204 No Content');
}
$link->close();
unset($link);
