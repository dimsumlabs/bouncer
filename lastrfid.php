<?php
require 'inc/common.php';

$rfid = exec('/usr/local/bin/doorctl rfid_last');
if ($rfid) {
  echo $rfid;
}
else {
  http_response_code(404);
}
