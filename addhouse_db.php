<?php
// save_house.php
session_start();
require_once 'dbconnect.php';

// 1. Namni kun Login gochuu isaa mirkaneessuuf
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['submit'])) {
    $owner_id = $_SESSION['user_id'];
    
    // Ragaalee form irraa dhufan qabachuu fi clean gochuu
    $title = trim($_POST['textfield2']); // Maqaa manaa
    $price = trim($_POST['number']);     // Gatii
    $city  = trim($_POST['textfield']);  // Magaalaa
    $desc  = trim($_POST['textarea']);

    // --- BACKEND VALIDATION (RAGAALEE MIRKANEESSUU) ---
    if (empty($title) || empty($price) || empty($city) || empty($desc)) {
        echo "<script>alert('Please fill in all the required house details!'); window.history.back();</script>";
        exit();
    }

    if (!is_numeric($price) || $price <= 0) {
        echo "<script>alert('House price must be a valid number greater than zero!'); window.history.back();</script>";
        exit();
    }

    // Suuraan dirqama ta'uu isaa mirkaneessuu
    if (!isset($_FILES['main_image']) || $_FILES['main_image']['error'] != 0) {
        echo "<script>alert('Please upload a primary image for the house!'); window.history.back();</script>";
        exit();
    }

    // --- DIRECTORY FIXED PATHS (FOLDER GUYYAAN QOODU HAQAMEERA) ---
    // Amma kallaattiin folder gurguddoo lamaan qofatti kuusna
    $img_dir = "uploads/images/";
    $vid_dir = "uploads/videos/";

    if (!is_dir($img_dir)) {
        mkdir($img_dir, 0777, true);
    }
    if (!is_dir($vid_dir)) {
        mkdir($vid_dir, 0777, true);
    }

    // Sanadii (strings) database dhowwuuf eegumsa gochuu
    $title = mysqli_real_escape_string($conn, $title);
    $city  = mysqli_real_escape_string($conn, $city);
    $desc  = mysqli_real_escape_string($conn, $desc);

    // --- A. SUURAA UPLOAD GOCHUU (UUIDv4) ---
    $img_name = $_FILES['main_image']['name'];
    $img_tmp  = $_FILES['main_image']['tmp_name'];
    $img_ext  = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
    
    $allowed_img_exts = array("jpg", "jpeg", "png", "webp");
    if (!in_array($img_ext, $allowed_img_exts)) {
        echo "<script>alert('Allowed image formats are JPG, JPEG, PNG, and WEBP only!'); window.history.back();</script>";
        exit();
    }

    // UUID v4 Token uumuu (Concurrency safety)
    $img_uuid = bin2hex(random_bytes(16));
    $new_img_name = "IMG_" . $img_uuid . "." . $img_ext;
    $img_destination = $img_dir . $new_img_name;

    // --- B. VIDEO UPLOAD GOCHUU ---
    $new_vid_name = NULL;
    $vid_destination = NULL;

    if (isset($_FILES['video_path']) && $_FILES['video_path']['error'] == 0) {
        $vid_name = $_FILES['video_path']['name'];
        $vid_tmp  = $_FILES['video_path']['tmp_name'];
        $vid_ext  = strtolower(pathinfo($vid_name, PATHINFO_EXTENSION));
        
        $allowed_vid_exts = array("mp4", "mkv", "mov");
        if (!in_array($vid_ext, $allowed_vid_exts)) {
            echo "<script>alert('Allowed video formats are MP4, MKV, and MOV only!'); window.history.back();</script>";
            exit();
        }

        // Viidiyoofis UUID ijaarru
        $vid_uuid = bin2hex(random_bytes(16));
        $new_vid_name = "VID_" . $vid_uuid . "." . $vid_ext;
        $vid_destination = $vid_dir . $new_vid_name;
    }

    // --- C. FILE SYSTEM IRRATTI MOVE GOCHUU FI DATABASE TRANSACTION ---
    if (move_uploaded_file($img_tmp, $img_destination)) {
        
        // Viidiyoon yoo jiraate kallaattiin move gochuu
        if ($new_vid_name !== NULL) {
            if (!move_uploaded_file($vid_tmp, $vid_destination)) {
                // Viidiyoon yoo gashuu dhabe suuraa ol-fe'ame sanas haquu qabna
                unlink($img_destination);
                echo "<script>alert('An error occurred while uploading the video!'); window.history.back();</script>";
                exit();
            }
        }

        // PHP fi MySQL wal-faana nagaatti akka hojjetan Transaction eegaluu
        mysqli_begin_transaction($conn);

        try {
            $sql = "INSERT INTO houses (owner_id, title, description, price, city, main_image, video_path) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($conn, $sql);
            
            // Database keessatti path guutuu ($img_destination fi $vid_destination) kuusna
            mysqli_stmt_bind_param($stmt, "ississs", $owner_id, $title, $desc, $price, $city, $img_destination, $vid_destination);
            mysqli_stmt_execute($stmt);

            // Hunduu yoo milkaa'e kallaattiin database mirkaneessi (Commit)
            mysqli_commit($conn);
            echo "<script>alert('Your house has been successfully registered!'); window.location.href='owner.php';</script>";

        } catch (Exception $e) {
            // Dogoggorri yoo uumame transaction duubatti deebisi (Rollback)
            mysqli_rollback($conn);
            
            // Faayiloota fe'aman dhabamsiisuu (Clean up storage)
            unlink($img_destination);
            if ($vid_destination !== NULL && file_exists($vid_destination)) {
                unlink($vid_destination);
            }

            echo "Database Error: " . $e->getMessage();
        }

    } else {
        echo "<script>alert('An error occurred while uploading the image!'); window.history.back();</script>";
        exit();
    }
}
?>