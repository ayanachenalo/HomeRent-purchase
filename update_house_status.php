<?php
// 1. Session jalqabuu (Kun baay'ee murteessaa dha)
session_start();
require_once 'dbconnect.php';

// User-ichi login gochuu isaa mirkaneessi
if (!isset($_SESSION['user_id'])) {
    die("Error: You must be logged in to rent a house.");
}

if (isset($_POST['rent_now'])) {
    // SQL Injection guutummaatti ittisuuf integer cast goona
    $h_id = (int)$_POST['house_id'];
    $u_id = (int)$_SESSION['user_id'];

    /* Mirkaneessa: Status mana sanaa gara 'rented' tti jijjiirra.
       Prepared Statement fayyadamna.
    */
    $update = "UPDATE houses SET status = 'rented' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $h_id);
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            // Erga milkiin rawwatamee booda gara fuula view_details.php deebi'i
            header("Location: view_details.php?id=" . $h_id . "&msg=rented");
            exit();
        } else {
            echo "Execution Error: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "SQL Error: " . mysqli_error($conn);
    }
} else {
    // Yoo gubbaan kallaattiin dhufe gara index-tti deebisi
    header("Location: index.php");
    exit();
}

mysqli_close($conn);
?>