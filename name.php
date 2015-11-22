<?php
$servername = 'localhost';
$username = 'root';
$password = 'pass';
$dbname = 'platformer';

$conn = mysql_connect($servername, $username, $password);
mysql_select_db('platformer');
$rows = mysql_query("SELECT * FROM lobby");
$numrows = mysql_num_rows($rows);
?>