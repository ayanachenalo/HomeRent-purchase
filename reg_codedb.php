<?php
require_once 'dbconnect.php'; 

if (isset($_POST['submit'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phonenumber']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($full_name) || empty($email) || empty($password)) {
        echo "<script>alert('Maaloo ragaa hunda guuti!'); window.history.back();</script>";
        exit();
    }

    // Password Hash gochuu
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 1. SQL qopheessuu
    $sql = "INSERT INTO users (full_name, email, phone_number, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // 2. Bind Parameters
        mysqli_stmt_bind_param($stmt, "sssss", $full_name, $email, $phone, $hashed_password, $role);

        // 3. Execute gochuu
        if (mysqli_stmt_execute($stmt)) {
            // Galmeen yoo milkaa'e
            echo "<script>
                    alert('Galmeen kee milkaa\'ee jira!'); 
                    window.location.href='login.php';
                  </script>";
            exit(); 
        } else {
            // Email yoo duraan jiraate (Duplicate entry)
            if (mysqli_errno($conn) == 1062) {
                echo "<script>alert('Dogoggora: Imeeyiliin kun duraan galmaa\'ee jira!'); window.history.back();</script>";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "SQL Error: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>