<?php
// 1. Session jalqabuu (Kun baay'ee murteessaa dha)
session_start();
require_once 'dbconnect.php';

// Session check - User-ichi login gochuu isaa mirkaneessi
if (!isset($_SESSION['user_id'])) {
    die("Error: You must be logged in to send a message.");
}

if (isset($_POST['receiver_id']) && isset($_POST['message']) && isset($_POST['house_id'])) {
    
    // Nageenyaaf: ID-f unka integer fayyadamna
    $me = (int)$_SESSION['user_id'];
    $them = (int)$_POST['receiver_id'];
    $h_id = (int)$_POST['house_id']; // ID Manaa
    $msg = trim($_POST['message']); // Space duwwaa jalaa haquuf

    if (!empty($msg)) {
        // SQL Injection guutummaatti ittisuuf Prepared Statement fayyadamna
        $sql = "INSERT INTO messages (sender_id, receiver_id, house_id, message) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "iiis", $me, $them, $h_id, $msg);
            
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                // --- FURMAATA ISA SIRRII ASITTI ---
                // ID mana sanaa URL irratti dabarsuu qabda
                header("Location: view_details.php?id=" . $h_id . "&status=success");
                exit();
            } else {
                echo "Execution Error: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "SQL Error: " . mysqli_error($conn);
        }
    } else {
        // Ergaan duwwaa yoo ta'e deebisii ID mana sanaa itti kenni
        header("Location: view_details.php?id=" . $h_id . "&status=empty");
        exit();
    }
} else {
    // Yoo ragaan kallaattiin dhufe gara index-tti deebisi
    header("Location: index.php");
    exit();
}

mysqli_close($conn);
?>