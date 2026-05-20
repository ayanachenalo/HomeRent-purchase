<?php
session_start();
require_once 'dbconnect.php'; 

if (isset($_POST['submit'])) {
    $full_name = trim($_POST['full_name']);
    $email = strtolower(trim($_POST['email'])); // Email gara lowercase-itti jijjiiruu
    $phone = trim($_POST['phonenumber']);
    $password = $_POST['password'];
    $role = trim($_POST['role']);

    // Validation - Bakka duwwaa ta'uu isaanii calaluu
    if (empty($full_name) || empty($email) || empty($password) || empty($role)) {
        echo "<script>alert('Please fill in all the required fields!'); window.history.back();</script>";
        exit();
    }

    // Email sirrii ta'uu isaa mirkaneessuu
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Please enter a valid email address!'); window.history.back();</script>";
        exit();
    }

    // 1. Dursee check gochuu: Email-iin kun kanaan dura galmaa'ee jiraa?
    $check_email_sql = "SELECT id FROM users WHERE email = ?";
    $check_stmt = mysqli_prepare($conn, $check_email_sql);
    if ($check_stmt) {
        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            echo "<script>alert('Error: This email address is already registered!'); window.history.back();</script>";
            mysqli_stmt_close($check_stmt);
            exit();
        }
        mysqli_stmt_close($check_stmt);
    }

    // 2. Password Hash gochuu (Nageenya cimsuuf)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 3. Data haaraa database-itti galmeessuu
    $sql = "INSERT INTO users (full_name, email, phone_number, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssss", $full_name, $email, $phone, $hashed_password, $role);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    alert('Registration successful!'); 
                    window.location.href='login.php';
                  </script>";
            exit(); 
        } else {
            echo "Execution Error: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "SQL Error: " . mysqli_error($conn);
    }
    mysqli_close($conn);
} else {
    header("Location: regist.php");
    exit();
}
?>