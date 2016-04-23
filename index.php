<?php
require 'inc/common.php';
require 'inc/mailer.php';
require 'inc/db.php';

// Find known MAC address
$mac = find_mac();
if ($mac)
	$mac2 = sha1('salT'.$mac);
else
	$mac2 = 'whatever';

$link->exec("UPDATE Users SET count = count + 1, last_seen = DATETIME('now') WHERE DATE('now') <= MAX(IFNULL(paid_verified,0),IFNULL(paid,0)) AND mac = '$mac2'")
	or mail_and_die('link->exec UPDATE error', __FILE__);

if ($link->changes() == 1)
{
        ignore_user_abort(true);
        header('Location: welcomeback.html', true, 303);
        header("Connection: close");
        header("Content-Length: 0");
        ob_end_flush();
        flush();
	open_door();
}
else {
	header('Location: index.html', true, 303);
}
$link->close();
unset($link);

