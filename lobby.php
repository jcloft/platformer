<?php
header('Location: ' . $_SERVER['HTTP_REFERER']);

$servername = 'localhost';
$username = 'root';
$password = 'pass';
$dbname = 'platformer';

$conn = mysql_connect($servername, $username, $password);

if(!$conn){
  die ('Could not connect: '.mysql_error());
}

$name=$_POST['name'];
$sql = "INSERT INTO lobby (name) VALUES ('".$_POST['name']."')";

mysql_select_db('platformer');
$retval = mysql_query($sql, $conn);
if( !$retval){
  die('could not enter data' . mysql_error());
}

echo "Entered data successfully";


  



mysql_close($conn);

?>