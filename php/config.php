<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$host = 'db';
$user = 'sweetwater_user';
$password = 'sweetwater_pass';
$dbname = 'sweetwater_db';

// Connect to the database
$conn = mysqli_connect($host, $user, $password, $dbname);

// Check connection
if (!$conn) {
  die("ERROR: Could not connect. " . mysqli_connect_error());
}
