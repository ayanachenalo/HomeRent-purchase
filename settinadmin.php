<?php
// 1. Session jalqabuu (Kun baay'ee murteessaa dha)
session_start();
require_once 'dbconnect.php';

// Admin login gochuu isaa fi role isaa guutummaatti mirkaneessi
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginadmin.php");
    exit();
}

$admin_user = $_SESSION['username'];
$success_msg = "";
$error_msg = "";

// 1. Odeeffannoo Admin-nichaa Database irraa fiduu (Prepared Statement fayyadamna)
$query = "SELECT * FROM admin WHERE username = ?";
$stmt = mysqli_prepare($conn, $query);
$admin_data = [];

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $admin_user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $admin_data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

// Admin-ni database keessaa yoo dhabame deebisi
if (!$admin_data) {
    session_destroy();
    header("Location: loginadmin.php");
    exit();
}

// 2. Yoo Button "Update Profile" tuqame
if (isset($_POST['update_profile'])) {
    $new_username = trim($_POST['username']);
    $new_password = $_POST['new_password'];
    $image_name = $admin_data['profile_pic']; // Default suuraa duraan jiru

    // Suuraa jijjiiruuf yoo filatame
    if (!empty($_FILES['profile_pic']['name'])) {
        $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        // Nageenyaaf maqaa random goona koodiin summii qabu akka hin lixne
        $image_name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $target = "uploads/admin/" . $image_name;
        
        // Folder-ri uploads jiraachuu isaa mirkaneessi
        if (!is_dir('uploads/admin/')) {
            mkdir('uploads/admin/', 0777, true);
        }
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target);
    }

    // Password yoo jijjiiramuu barbaadame (Password Hash gochuu)
    if (!empty($new_password)) {
        // Nageenya piroojektichaa agarsiisuuf password_hash gaariidha
        $final_password = password_hash($new_password, PASSWORD_DEFAULT); 
        
        $update_query = "UPDATE admin SET username = ?, password = ?, profile_pic = ? WHERE id = ?";
        $stmt_up = mysqli_prepare($conn, $update_query);
        if ($stmt_up) {
            mysqli_stmt_bind_param($stmt_up, "sssi", $new_username, $final_password, $image_name, $admin_data['id']);
            $execute_status = mysqli_stmt_execute($stmt_up);
            mysqli_stmt_close($stmt_up);
        }
    } else {
        $update_query = "UPDATE admin SET username = ?, profile_pic = ? WHERE id = ?";
        $stmt_up = mysqli_prepare($conn, $update_query);
        if ($stmt_up) {
            mysqli_stmt_bind_param($stmt_up, "ssi", $new_username, $image_name, $admin_data['id']);
            $execute_status = mysqli_stmt_execute($stmt_up);
            mysqli_stmt_close($stmt_up);
        }
    }

    if (isset($execute_status) && $execute_status) {
        $_SESSION['username'] = $new_username; // Session up-to-date gochuuf
        $success_msg = "Your profile information has been updated successfully!";
        // Refresh gochuuf
        header("Refresh:2");
    } else {
        $error_msg = "An error occurred while updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Home Finder - Admin Profile</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; margin: 0; }
        .settings-container { max-width: 500px; margin: 50px auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-left: 350px; text-align: left; }
        .profile-preview { text-align: center; margin-bottom: 20px; }
        .profile-preview img { width: 120px; height: 120px; border-radius: 50%; border: 4px solid #3498db; object-fit: cover; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        input[type="text"], input[type="password"] {
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 15px;
        }
        .btn-update { background: #2c3e50; color: white; border: none; padding: 12px 20px; border-radius: 6px; cursor: pointer; width: 100%; font-size: 16px; font-weight: bold; transition: background 0.2s; }
        .btn-update:hover { background: #1a252f; }
        .msg { padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center; font-weight: bold; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

<?php include 'admin_nav.php'; ?>

<div class="settings-container">
    <h2>👤 Admin Profile Settings</h2>
    <hr style="border: 0; height: 1px; background: #eee; margin-bottom: 20px;">

    <?php if($success_msg) echo "<div class='msg success'>$success_msg</div>"; ?>
    <?php if($error_msg) echo "<div class='msg error'>$error_msg</div>"; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="profile-preview">
            <?php 
                $pic = !empty($admin_data['profile_pic']) ? "uploads/admin/".$admin_data['profile_pic'] : "uploads/admin/default_admin.png";
            ?>
            <img src="<?php echo htmlspecialchars($pic); ?>" alt="Admin Profile">
            <br>
            <label for="profile_pic" style="cursor:pointer; color:#3498db; margin-top:10px; display: inline-block;">Change Profile Photo</label>
            <input type="file" name="profile_pic" id="profile_pic" accept="image/*" style="display:none;">
        </div>

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($admin_data['username']); ?>" required>
        </div>

        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" placeholder="Leave blank if you don't want to change it">
        </div>

        <button type="submit" name="update_profile" class="btn-update">Update Profile</button>
    </form>
</div>

<script>
    // Suuraa filatame battalumatti (Client-side) akka agarsiisuuf
    document.getElementById('profile_pic').onchange = function (evt) {
        var tgt = evt.target || window.event.srcElement,
            files = tgt.files;
        if (FileReader && files && files.length) {
            var fr = new FileReader();
            fr.onload = function () {
                document.querySelector(".profile-preview img").src = fr.result;
            }
            fr.readAsDataURL(files[0]);
        }
    }
</script>

</body>
</html>