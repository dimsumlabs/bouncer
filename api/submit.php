<?php
require '../inc/common.php';
require '../inc/users.php';


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	header('Location: ../submit.html', true, 303);
	die('must be POST');
}

$amount = (int)$_POST['amount'];
$email = trim($_POST['email']);
submit_payment($email, $amount);

$link->close();
unset($link);

header('Location: ../welcome.html', true, 303);

