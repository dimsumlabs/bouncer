<?php
require 'inc/common.php';

$context = new ZMQContext();
$subscriber = $context->getSocket(ZMQ::SOCKET_SUB);
$subscriber->connect("ipc:///tmp/octopusd");
$subscriber->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "rfid_last");
$subscriber->recv();  // Discard channel
$rfid = $subscriber->recv();

if ($rfid) {
  echo $rfid;
}
else {
  http_response_code(404);
}
