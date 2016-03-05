<!DOCTYPE html>
<html>
<head>
  <title>DSL active members</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name='viewport' content='width=320'/>
</head>
<body>
 <h1>Active Members</h1>
 <table>
  <thead>
   <tr>
    <th>E-mail</th>
    <th>Since</th>
    <th>Paid</th>
   </tr>
  </thead>
  <tbody><?php
require 'inc/mailer.php';
require 'inc/db.php';

$result = $link->query("SELECT email,DATE(since) AS since,paid FROM Users WHERE paid >= DATE('now') ORDER BY email;")
	or mail_and_die('link->query SELECT error: '.$link->lastErrorMsg, __FILE__);

while ($row = $result->fetchArray()) {?>
   <tr>
    <td><?php $i = filter_var($row['email'], FILTER_VALIDATE_EMAIL); if ($i) echo "<a href='mailto:$row[email]'>"; echo $row['email']; if ($i) echo '</a>'; ?></td>
    <td><?php echo $row['since']; ?></td>
    <td><?php echo $row['paid']; ?></td>
   </tr><?php
}

$link->close();
unset($link);
?></tbody>
 </table>
</body>
</html>
