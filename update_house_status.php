<?php
session_start();
require_once 'dbconnect.php';

if (isset($_POST['rent_now'])) {
    $h_id = mysqli_real_escape_string($conn, $_POST['house_id']);
    $u_id = $_SESSION['user_id'];

    // Mirkaneessa dhumaa
    $update = "UPDATE houses SET status = 'rented' WHERE id = '$h_id' AND owner_id = '$u_id'";
    
    if (mysqli_query($conn, $update)) {
        header("Location: view_details.php?id=" . $h_id); // Deebisee fuuluma sana fida
        exit();
    }
}
?>