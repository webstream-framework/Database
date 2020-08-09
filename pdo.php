<?php
$dsn = sprintf('mysql:host=%s:3306;dbname=%s',  'webstream-framework-database-mysql', 'sandbox');
$user = 'mysql';
$password = 'mysql';
$dbh = new PDO($dsn, $user, $password);
$sql = "SELECT version();";
foreach ($dbh->query($sql, PDO::FETCH_ASSOC) as $row) {
	print_r($row);
}
