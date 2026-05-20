<?php
session_start();
require_once 'dbconnect.php';

// Nageenyaaf: User-ri login gochuu isaa mirkaneessi
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// SQL Injection ittisuuf user_id gara integer jijjiirra
$user_id = (int)$_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query);

// 2. User-ichi login gochuu isaa fi 'role' isaa mirkaneessi
$user_id = $_SESSION['user_id'] ?? 0;

// Role session keessa yoo hin jirre database irraa dubbisi
if (!isset($_SESSION['role']) && $user_id > 0) {
    // ID eegumsa qabu gochuuf integer ta'uu isaa mirkaneessi
    $user_id_clean = (int)$user_id;
    $role_query = "SELECT role FROM users WHERE id = '$user_id_clean'";
    $role_res = mysqli_query($conn, $role_query);
    if ($role_res && $u_data = mysqli_fetch_assoc($role_res)) {
        $_SESSION['role'] = $u_data['role'];
    }
}

// Default role 'tenant' dha
$user_role = $_SESSION['role'] ?? 'tenant'; 

// 3. LOGIC HOME URL: Role irratti hundaa'uun bakka deebii filachuu
if ($user_role === 'owner') {
    $home_url = "owner.php";
} else {
    $home_url = "index.php";
}

// house_id yoo barbaadame qofa (URL irraa)
$house_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Smart Home Finder - My Profile</title>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="stylemedia.css">
<style>
    
    .social-icons a {
        display: block;
        margin-bottom: 5px;
    }
    .social-icons a:hover { color: white; }
    
    .profile-container { max-width: 500px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background: white; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
    .profile-img { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #333; }
    .form-group { text-align: left; margin-bottom: 15px; }
    input[type="text"], input[type="email"] { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
    .btn-save { background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-size: 16px; font-weight: bold; }

    .hoome-link {
    color: white; 
    text-decoration: none;
    font-size: 24px; /* Akkuma PPT keenyaa qubee gurguddaa madaalawaa */
    font-weight: 500;
    padding: 5px 10px;
    border-radius: 5px;
    transition: all 0.3s ease-in-out; /* Hover lallafaa akka ta'uuf */
}
    .hoome-link:hover {
    color: #003366; /* Akkuma diizaayinii Smart House Finder */
    background-color: white; /* Duubee adii uuma */
    transform: translateY(-2px); /* Xiqqoo ol jedha (Animation namatti tolu) */
}
.nav-wrapper { display: flex; align-items: center; gap: 15px; }
</style>
</head>

<body>
<table width="100%" border="0" style="border-collapse: collapse;">
  <tbody>
    <tr bgcolor="#333" style="color:white;">
      <td height="80" style="padding: 0 20px; font-size: 24px;">
        <?php include 'houseicon.php'; ?>
        <strong>Smart Home Finder</strong>
        <span style="float:right;">
             <div class="nav-wrapper">
                <a href="<?php echo $home_url; ?>" class="hoome-link">Home</a>
               <div class="icon-section">
                    <?php include 'icon.php'; ?>
                </div>
             </div>
         </span>   
      </td>
    </tr>
    
    <tr bgcolor="#ECECDE">
      <td>
        <div class="profile-container">
            <h2>My Profile</h2>
            <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                <img src="<?php echo htmlspecialchars($user['profile_photo'] ?? 'default-avatar.png'); ?>" class="profile-img"><br>
                <input type="file" name="profile_photo" style="margin: 10px 0;">
                
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>
                <button type="submit" name="update" class="btn-save">Update Profile</button>
            </form>
            
        </div>
      </td>
    </tr>
      
    <tr bgcolor="#222" style="color:white; font-size:20px;">
      <td>
        <?php include 'footer.php'; ?>

      </td>
    </tr>
  </tbody>
</table>

<script src="nav.js"></script>
</body>
</html>