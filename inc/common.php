<?php
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header('Expires: -1');

function find_mac()
{
  $ipAddress = '('.$_SERVER['REMOTE_ADDR'].')';
  // Remember to: chmod +s /usr/sbin/arp
  exec('/usr/sbin/arp -na', $lines);
  foreach($lines as $line)
  {
    $cols = preg_split('/\s+/', trim($line));
    if ($cols[1] == $ipAddress)
    {
      return strtolower($cols[3]);
    }
  }
  return null;
}

function open_door()
{
  $context = new ZMQContext();
  $publisher = $context->getSocket(ZMQ::SOCKET_PUB);
  $publisher->connect("tcp://127.0.0.1:9000");
  usleep(100000);
  $publisher->send("OPEN");
}
