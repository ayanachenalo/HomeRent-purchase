<?php
session_start();
require_once 'dbconnect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Smart Home Finder</title>
<link rel="stylesheet" type="text/css" href="style.css">
	
	<link rel="stylesheet" type="text/css" href="stylemedia.css">
<style>
    /* Bakka manneen itti mul'atan akka auto-increase ta'uuf */
    .house-container {
        display: flex;
        flex-wrap: wrap; /* Manni yoo baay'ate ofumaan gadi bu'a */
        gap: 20px;
        padding: 20px;
        justify-content: center;
    }
    .house-card {
        width: 300px;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .media-container {
        width: 100%;
        height: 200px;
        background: #eee;
    }
    .media-container img, .media-container video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .card-info { padding: 15px; }
    .btn-details {
        display: block;
        text-align: center;
        background: #273552;
        color: white;
        padding: 10px;
        text-decoration: none;
        border-radius: 4px;
    }
	.footer-column {
        padding: 60px 20px;
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        text-align: left;
		
    }
    .footer-box {
        flex: 1;
        min-width: 250px;
        margin: 10px;
    }
    .footer-box h3 {
        color: #ffc107;
        border-bottom: 2px solid #555;
        padding-bottom: 10px;
        margin-bottom: 15px;
		
    }
    .footer-box p, .footer-box a {
        font-size: 25px;
        color: #ccc;
        text-decoration: none;
        line-height: 1.6;
    }
    .social-icons a {
        display: block;
        margin-bottom: 5px;
    }
    .social-icons a:hover { color: white; }
	
	.profile-container { max-width: 500px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background: white; text-align: center; }
        .profile-img { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #333; }
        .form-group { text-align: left; margin-bottom: 15px; }
        input[type="text"], input[type="email"] { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
        .btn-save { background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
</style>
</head>

<body>
<table width="100%" border="0" style="border-collapse: collapse;">
  <tbody>
    <tr bgcolor="#333" style="color:white;">
      <td height="60" style="padding: 0 20px;">
        <strong>Smart Home Finder</strong>
        
        <div style="float:right; display: flex; align-items: center; gap: 20px;">
            <a href="index.php" style="color:white; text-decoration:none;">Home</a>
            <a href="regist.php" style="color:white; text-decoration:none;">Register</a>
            
            <?php include 'icon.php'; ?>
        </div>
      </td>
    </tr>
	
<tr bgcolor="#ECECDE">
      <td>
	  <div class="profile-container">
        <h2>My Profile</h2>
        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <img src="<?php echo $user['profile_photo'] ?? 'default-avatar.png'; ?>" class="profile-img"><br>
            <input type="file" name="profile_photo" style="margin: 10px 0;">
            
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
            </div>
            <button type="submit" name="update" class="btn-save">Update Profile</button>
        </form>
        <br><a href="index.php">Back to Home</a>
    </div>
	  </td>
    </tr>
	  
 <tr bgcolor="#222" style="color:white;" style=" font-size:20px;">
    <td>
        <div class="footer-column">
            <div class="footer-box">
                <h3>About Us</h3>
                <p>
					Smart Home Finder is a digital platform designed to bridge the gap between house owners and tenants. It allows users to easily search, view, and book rental houses within their preferred city using high-quality images and videos. Our mission is to simplify the house-hunting process by saving time and ensuring a secure connection between parties. We strive to provide a transparent, modern, and hassle-free real estate experience across the region. Find your next dream home with just a few clicks!
                    Smart Home Finder is a modern platform bridging the gap between owners and tenants. 
                    We simplify house hunting through transparent listings and secure connections, 
                    ensuring you find your dream home with ease and speed.
                </p>
            </div>

            <div class="footer-box">
                <h3>Our Team & Location</h3>
                <p><strong>Developed by:</strong> Ayana, Talile, and Werku</p>
                <p><strong>Location:</strong> Bule Hora, Ethiopia</p>
                <p>Working to modernize real estate services across the region.</p>
            </div>

            <div class="footer-box">
                <h3>Contact Us</h3>
                <div class="social-icons">
                    <a href="https://t.me/your_telegram">✈️ Telegram</a>
                    <a href="https://facebook.com/your_page">📘 Facebook</a>
                    <a href="mailto:info@smarthome.com">📧 info@smarthome.com</a>
                </div>
            </div>
        </div>
        
        <div style="text-align:center; padding: 20px; background: #111; font-size: 13px; color: #777;">
            &copy; 2026 Smart Home Finder. All Rights Reserved.
        </div>
    </td>
</tr>
  </tbody>
	<script src="nav.js"></script>
	
</table>

</body>
</html>