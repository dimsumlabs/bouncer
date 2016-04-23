<?php
require_once dirname(__FILE__) . '/mailer.php';

$link = new SQLite3('/var/bouncer/members.db')
  or mail_and_die('SQLite3 ctor error', __FILE__);

//$link->exec('ALTER TABLE Users ADD COLUMN `name` char(64) NULL');

