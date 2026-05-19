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
    // Ragaalee form irraa dhufan qabachuu
    $owner_id = $_SESSION['user_id'];
    $title    = mysqli_real_escape_string($conn, $_POST['textfield2']); // Maqaa manaa
    $price    = $_POST['number']; // Gatii
    $city     = mysqli_real_escape_string($conn, $_POST['textfield']); // Magaalaa
    $desc     = mysqli_real_escape_string($conn, $_POST['textarea']);

    // --- A. SUURAA UPLOAD GOCHUU ---
    $new_img_name = "";
    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0) {
        $img_name = $_FILES['main_image']['name'];
        $img_tmp  = $_FILES['main_image']['tmp_name'];
        $img_ext  = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        
        // Maqaa suuraa jijjiiruu (akka walitti hin buuneef)
        $new_img_name = "IMG_" . time() . "_" . rand(100, 999) . "." . $img_ext;
        $img_destination = "uploads/images/" . $new_img_name;
        
        move_uploaded_file($img_tmp, $img_destination);
    }

    // --- B. VIDEO UPLOAD GOCHUU (YOO JIRAATE) ---
    $new_vid_name = NULL;
    if (isset($_FILES['video_path']) && $_FILES['video_path']['error'] == 0) {
        $vid_name = $_FILES['video_path']['name'];
        $vid_tmp  = $_FILES['video_path']['tmp_name'];
        $vid_ext  = strtolower(pathinfo($vid_name, PATHINFO_EXTENSION));
        
        $new_vid_name = "VID_" . time() . "_" . rand(100, 999) . "." . $vid_ext;
        $vid_destination = "uploads/videos/" . $new_vid_name;
        
        move_uploaded_file($vid_tmp, $vid_destination);
    }

    // --- C. DATABASE-TTI GALMEESSUU ---
    $sql = "INSERT INTO houses (owner_id, title, description, price, city, main_image, video_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    
    // "ississs" -> integer, string, string, integer, string, string, string
    mysqli_stmt_bind_param($stmt, "ississs", $owner_id, $title, $desc, $price, $city, $new_img_name, $new_vid_name);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Manni kee milkaa\'inaan galmaa\'era!'); window.location.href='owner.php';</script>";
    } else {
        echo "Dogoggora uumame: " . mysqli_error($conn);
    }
}
?>