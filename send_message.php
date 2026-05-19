<?php
session_start();
include 'dbconnect.php';

// 1. Session check
if (!isset($_SESSION['user_id'])) {
    die("Error: Login gochuu qabdu.");
}

if (isset($_POST['receiver_id']) && isset($_POST['message']) && isset($_POST['house_id'])) {
    
    $me = $_SESSION['user_id'];
    $them = mysqli_real_escape_string($conn, $_POST['receiver_id']);
    $h_id = mysqli_real_escape_string($conn, $_POST['house_id']); // ID Manaa
    $msg = mysqli_real_escape_string($conn, $_POST['message']);

    if (!empty($msg)) {
        $sql = "INSERT INTO messages (sender_id, receiver_id, house_id, message) 
                VALUES ('$me', '$them', '$h_id', '$msg')";
        
        if (mysqli_query($conn, $sql)) {
            // --- FURMAATA ISA SIRRII ASITTI ---
            // ID mana sanaa URL irratti dabarsuu qabda
            header("Location: view_details.php?id=$h_id&status=success");
            exit();
        } else {
            echo "Dogoggora: " . mysqli_error($conn);
        }
    } else {
        // Ergaan duwwaa yoo ta'e deebisii ID mana sanaa itti kenni
        header("Location: view_details.php?id=$h_id&status=empty");
        exit();
    }
} else {
    // Yoo ragaan kallaattiin dhufe gara index-tti deebisi
    header("Location: index.php");
    exit();
}
?>