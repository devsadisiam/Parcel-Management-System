<?php

$host = "localhost";
$username = "root";
$db_password = ""; // your DB password
$dbname = "parcel_delivery"; // change if needed

$conn = new mysqli($host, $username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed!");
}
?>
