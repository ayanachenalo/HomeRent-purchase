<?php
ob_start();
session_start();
require_once 'dbconnect.php';

if (isset($_POST['submit'])) {
    // Prepared Statement waan fayyadamnuuf real_escape_string asirratti nu hin barbaachisu
    $full_name = trim($_POST['full_name']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE full_name = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $full_name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            
            // Ragaa session keessatti kuusuu
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];

            $current_role = strtolower(trim($user['role']));

            // 1. Admin yoo ta'e
            if ($current_role == 'admin') {
                header("Location: adminpanel.php");
                exit();
            } 
            // 2. Abbaa Manaa (Owner) yoo ta'e
            elseif ($current_role == 'owner') {
                header("Location: owner.php"); 
                exit();
            } 
            // 3. Kireeffataa (Tenant) yoo ta'e
            else {
                // Yoo "Redirect" URL irraa dhufe qabaate (view_details irraa)
                if (isset($_GET['redirect'])) {
                    // Nageenya URL eeguuf htmlspecialchars itti daballeera
                    header("Location: " . htmlspecialchars($_GET['redirect']));
                } else {
                    header("Location: index.php");
                }
                exit();
            }

        } else {
            echo "<script>alert('Incorrect password! Please try again.'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('This username is not registered!'); window.location.href='login.php';</script>";
    }
}
ob_end_flush();
?>