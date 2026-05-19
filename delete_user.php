<?php
session_start();
require_once 'dbconnect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    exit("Aangoo hin qabdu!");
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "DELETE FROM users WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: " . $_SERVER['HTTP_REFERER']); // Gara fuula duraan jiruutti deebi'uuf
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>