<?php
session_start();
require_once 'dbconnect.php';

// 1. User-ichi login gochuu isaa mirkaneessi
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. ID manaa URL irraa jiraachuu isaa mirkaneessi
if (isset($_GET['id'])) {
    $house_id = mysqli_real_escape_string($conn, $_GET['id']);
    $user_id = $_SESSION['user_id'];

    /* 
       Mirkaneessa: Namni kun abbaa mana sanaa ta'uu isaa fi 
       status isaa 'available' gochuu.
    */
    $sql = "UPDATE houses SET status = 'available' 
            WHERE id = '$house_id' AND owner_id = '$user_id'";

    if (mysqli_query($conn, $sql)) {
        // Yoo milkaa'e gara fuula view_details.php deebi'i
        header("Location: view_details.php?id=" . $house_id . "&msg=success");
        exit();
    } else {
        echo "Dogoggorri uumameera: " . mysqli_error($conn);
    }
} else {
    header("Location: owner.php");
    exit();
}
?>