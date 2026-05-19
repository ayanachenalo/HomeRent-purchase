<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "house_db";

// MySQLi connect (i-fannoo dabalata)
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Walitti hidhamuu isaa mirkaneessuuf
if (!$conn) {
    die("Database-tti makamuun hin danda'amne: " . mysqli_connect_error());
}
?>