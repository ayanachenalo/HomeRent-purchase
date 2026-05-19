<?php
session_start();
require_once 'dbconnect.php';

// Admin login gochuu isaa mirkaneessi
if (!isset($_SESSION['username'])) {
    header("Location: loginadmin.php");
    exit();
}

$admin_user = $_SESSION['username'];
$success_msg = "";
$error_msg = "";

// 1. Odeeffannoo Admin-nichaa Database irraa fiduu
$query = "SELECT * FROM admin WHERE username = '$admin_user'";
$result = mysqli_query($conn, $query);
$admin_data = mysqli_fetch_assoc($result);

// 2. Yoo Button "Update Profile" tuqame
if (isset($_POST['update_profile'])) {
    $new_username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_password = $_POST['new_password'];
    $image_name = $admin_data['profile_pic']; // Default suuraa duraan jiru

    // Suuraa jijjiiruuf yoo filatame
    if (!empty($_FILES['profile_pic']['name'])) {
        $image_name = time() . '_' . $_FILES['profile_pic']['name'];
        $target = "uploads/admin/" . $image_name;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target);
    }

    // Password yoo jijjiiramuu barbaadame
    if (!empty($new_password)) {
        // Password hash gochuun nageenyaaf gaariidha (Optional garuu ni gorfama)
        // $final_password = password_hash($new_password, PASSWORD_DEFAULT);
        $final_password = $new_password; 
        $update_query = "UPDATE admin SET username='$new_username', password='$final_password', profile_pic='$image_name' WHERE id='".$admin_data['id']."'";
    } else {
        $update_query = "UPDATE admin SET username='$new_username', profile_pic='$image_name' WHERE id='".$admin_data['id']."'";
    }

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['username'] = $new_username; // Session up-to-date gochuuf
        $success_msg = "Odeeffannoon kee milkiin jijjiirameera!";
        // Refresh gochuuf
        header("Refresh:2");
    } else {
        $error_msg = "Dogoggora uumame: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="or">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile Settings</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; margin: 0; }
        .settings-container { max-width: 500px; margin: 50px auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-left: 350px;}
        .profile-preview { text-align: center; margin-bottom: 20px; }
        .profile-preview img { width: 120px; height: 120px; border-radius: 50%; border: 4px solid #3498db; object-fit: cover; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        input[type="text"], input[type="password"], input[type="file"] {
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box;
        }
        .btn-update { background: #2c3e50; color: white; border: none; padding: 12px 20px; border-radius: 6px; cursor: pointer; width: 100%; font-size: 16px; }
        .btn-update:hover { background: #1a252f; }
        .msg { padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<?php include 'admin_nav.php'; ?>

<div class="settings-container">
    <h2>👤 Profile Settings</h2>
    <hr>

    <?php if($success_msg) echo "<div class='msg success'>$success_msg</div>"; ?>
    <?php if($error_msg) echo "<div class='msg error'>$error_msg</div>"; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="profile-preview">
            <?php 
                $pic = !empty($admin_data['profile_pic']) ? "uploads/admin/".$admin_data['profile_pic'] : "uploads/admin/default_admin.png";
            ?>
            <img src="<?php echo $pic; ?>" alt="Admin Profile">
            <br>
            <label for="profile_pic" style="cursor:pointer; color:#3498db; margin-top:10px;">Change Photo</label>
            <input type="file" name="profile_pic" id="profile_pic" style="display:none;">
        </div>

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($admin_data['username']); ?>" required>
        </div>

        <div class="form-group">
            <label>New Password (Duwwaa dhiisi yoo jijjiiru hin barbaadne)</label>
            <input type="password" name="new_password" placeholder="Password haaraa...">
        </div>

        <button type="submit" name="update_profile" class="btn-update">Update Profile</button>
    </form>
</div>

<script>
    // Suuraa filatame battalumatti akka agarsiisuuf
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