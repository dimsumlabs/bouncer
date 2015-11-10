<?php
$rfid = exec('doorctl rfid_last');
if ($rfid) {
  echo $rfid;
}
else {
  http_response_code(404);
}
