<?php
session_start();
require_once 'dbconnect.php';

if (isset($_POST['submit'])) {
    // Prepared Statement waan fayyadamnuuf real_escape_string nu hin barbaachisu
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Table admin jedhamu keessaa barbaaduu (Prepared Statement fayyadamnee)
        $sql = "SELECT * FROM admin WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($row = mysqli_fetch_assoc($result)) {
                // Password plain-text fi hash ta'e lameeninuu akka hojjetu gochuu
                if (password_verify($password, $row['password']) || $password === $row['password']) {
                    
                    // Session set gochuu
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['role'] = 'admin'; // Adminpanel irratti kanaan check goona
                    
                    header('Location: adminpanel.php');
                    exit();
                } else {
                    echo "<script>alert('Invalid username or password!'); window.location.href='adminlogin.php';</script>";
                    exit();
                }
            } else {
                echo "<script>alert('Invalid username or password!'); window.location.href='adminlogin.php';</script>";
                exit();
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "SQL Statement Error.";
        }
    } else {
        echo "Please enter both username and password.";
    }
} else {
    header("Location: adminlogin.php");
    exit();
}
?>