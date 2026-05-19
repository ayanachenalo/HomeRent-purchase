<?php
session_start();
require_once 'dbconnect.php';

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (!empty($username) && !empty($password)) {
        // Table admin jedhamu keessaa barbaaduu
        $query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
        
        if (mysqli_num_rows($query) > 0) {
            $row = mysqli_fetch_assoc($query);
            
            // Session set gochuu
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = 'admin'; // Adminpanel irratti kanaan check goona
            
            header('Location: adminpanel.php');
            exit();
        } else {
            echo "<script>alert('Username ykn Password dogoggora!'); window.location.href='adminlogin.php';</script>";
        }
    } else {
        echo "Maaloo username fi password galchi.";
    }
} else {
    header("Location: adminlogin.php");
}
?>