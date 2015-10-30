<?php
// Add this to a daily cronjob using curl or somesuch:
// 0 6 * * * /usr/bin/curl http://localhost/checkpaid.php

require 'inc/mailer.php';
require 'inc/db.php';


$subject = 'Dim Sum Labs membership reminder 點心樂部会员资格到期提醒';
$body = 'Dear Dim Sum Labs member,

Your membership with Dim Sum Labs is about to expire today. Please renew your membership to be able to get into the space.

Thanks for your continuous support!


亲爱的點心樂部会员，

您在點心樂部的会员资格即将于今日到期。为了正常使用點心樂部的空间设施，请为您的会员资格续费！

谢谢您一如既往的支持！

-- the script that sends out these emails';

$result = $link->query("SELECT email FROM Users WHERE paid = DATE('now') OR paid_verified = DATE('now')")
	or die('link->query SELECT error');

while ($row = $result->fetchArray()) {
	mailer($row['email'], $subject, $body);
}

$link->close();
unset($link);

