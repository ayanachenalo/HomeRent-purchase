<?php include 'index_db.php'; ?>
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
	
	body.dark-mode { background-color: #1a1a1a; color: white; }
        body.dark-mode .settings-box { border-color: #444; background: #222; }
        body.dark-mode input { background: #333; color: white; border: 1px solid #555; }
        
        .settings-box { max-width: 450px; margin: 50px auto; padding: 25px; border: 1px solid #ddd; border-radius: 10px; font-family: Arial, sans-serif;}
        .toggle-container { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #eee; margin-bottom: 20px; }
        
        .password-section { margin-top: 20px; padding-top: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-size: 14px; margin-bottom: 5px; }
        input { width: 95%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        
        .btn-change { background: #007bff; color: white; border: none; padding: 5px 5px; border-radius: 10px; cursor: pointer; width: 100px; margin-top: 10px; }
        .btn-change:hover { background: #0056b3; }
        
        #error-msg { color: #e74c3c; font-size: 13px; margin-top: 5px; display: none; }
</style>
</head>

<body>
<table width="100%" border="0" style="border-collapse: collapse;">
  <tbody>
    <tr bgcolor="#333" style="color:white;">
      <td height="60" style="padding: 0 20px;">
		<?php include 'houseicon.php'; ?>
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
        <div class="settings-box">
		  <h2>App Settings</h2>
        
        <div class="toggle-container">
            <span>Dark Mode</span>
            <button onclick="toggleDarkMode()" id="modeBtn" style="padding: 5px 15px; cursor:pointer;">Enable</button>
        </div>

        <div class="password-section">
            <h3>Change Password</h3>
            <form action="update_password_db.php" method="POST" onsubmit="return validatePassword()">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required>
                </div>
                
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <div id="error-msg">Password must be the same</div>
                </div>
                
                <button type="submit" name="change_pwd" class="btn-change">Change</button>
            </form>
        </div>

        <br><hr>
        <a href="index.php" style="text-decoration: none; color: #666; font-size: 14px;">← Back to Home</a>
		  </div>
      </td>
    </tr>
	  
	  <script>
        // 1. Dark Mode Logic
        function toggleDarkMode() {
            var body = document.body;
            body.classList.toggle("dark-mode");
            var btn = document.getElementById("modeBtn");
            if (body.classList.contains("dark-mode")) {
                btn.innerText = "Disable";
                localStorage.setItem("theme", "dark");
            } else {
                btn.innerText = "Enable";
                localStorage.setItem("theme", "light");
            }
        }

        if (localStorage.getItem("theme") === "dark") {
            document.body.classList.add("dark-mode");
            document.getElementById("modeBtn").innerText = "Disable";
        }

        // 2. Password Validation Logic
        function validatePassword() {
            var new_pwd = document.getElementById("new_password").value;
            var confirm_pwd = document.getElementById("confirm_password").value;
            var errorMsg = document.getElementById("error-msg");

            if (new_pwd !== confirm_pwd) {
                errorMsg.style.display = "block"; // Ergaa diimaa agarsiisi
                document.getElementById("confirm_password").style.borderColor = "#e74c3c";
                return false; // Form-ichi gara database akka hin deemne dhorga
            }
            
            errorMsg.style.display = "none";
            return true; // Yoo wal-fakkaate itti fufa
        }
    </script>
	  

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