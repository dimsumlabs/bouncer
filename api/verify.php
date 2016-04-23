<?php
require dirname(__FILE__) . '/../inc/common.php';
require dirname(__FILE__) . '/../inc/mailer.php';
require dirname(__FILE__) . '/../inc/db.php';
require dirname(__FILE__) . '/../inc/users.php';

$paymentid = (int)$_GET['id'];
$ok = (int)$_GET['ok'];
$email = urldecode($_GET['email']);

$email2 = $link->escapeString($email);

$result = $link->query("SELECT email,amount FROM Payments WHERE id = $paymentid;")
	or die('link->query SELECT error');

if ($row = $result->fetchArray())
	$amount = $row['amount'];
$months = amount_to_months($amount);

$link->exec("UPDATE Payments SET verified = $ok WHERE id = $paymentid")
	or mail_and_die('link->exec UPDATE Payments error', __FILE__);

if ($ok) {
	$link->exec("UPDATE Users SET paid_verified = DATE( (SELECT submitted FROM Payments WHERE id = $paymentid), '+$months MONTH') WHERE email = '$email2'")
		or mail_and_die('link->exec UPDATE Users error', __FILE__);
}
else {
	$link->exec("UPDATE Users SET paid = DATE(paid, '-$months MONTH') WHERE email = '$email2'")
		or mail_and_die('link->exec UPDATE Users error', __FILE__);
	//mailer($email, $subject, $body);
}

$link->close();
unset($link);
