<?php
ini_set('display_errors', '0');
error_reporting(0);

$servername = "localhost:3307";
$username = "localname";
$password = "1234";
$dbname = "municipal_complaints";

$con = mysqli_connect($servername, $username, $password, $dbname);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
 //echo "Connected successfully";

?>
