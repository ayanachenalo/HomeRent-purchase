<?php
// 1. Session jalqabuu (Kun baay'ee murteessaa dha)
session_start();
require_once 'dbconnect.php';

// User-ichi login gochuu isaa mirkaneessi
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. ID manaa URL irraa jiraachuu isaa mirkaneessi
if (isset($_GET['id'])) {
    // SQL Injection ittisuuf integer cast goona
    $house_id = (int)$_GET['id'];
    $user_id = (int)$_SESSION['user_id'];

    /* Mirkaneessa: Namni kun abbaa mana sanaa ta'uu isaa fi 
        status isaa 'available' gochuu (Prepared Statement fayyadamna).
    */
    $sql = "UPDATE houses SET status = 'available' WHERE id = ? AND owner_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $house_id, $user_id);

        if (mysqli_stmt_execute($stmt)) {
            // Yoo milkaa'e gara fuula view_details.php deebi'i
            header("Location: view_details.php?id=" . $house_id . "&msg=activated");
            exit();
        } else {
            echo "Execution Error: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "SQL Error: " . mysqli_error($conn);
    }
} else {
    header("Location: owner.php");
    exit();
}

mysqli_close($conn);
?>