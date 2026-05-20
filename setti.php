<?php 
session_start();
include 'dbconnect.php'; // Kallaattiin database connection qulqulluun saagi
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
<title>Smart Home Finder - Settings</title>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="stylemedia.css">
<style>
    .social-icons a {
        display: block;
        margin-bottom: 5px;
    }
    .social-icons a:hover { color: white; }
    
    body.dark-mode { background-color: #1a1a1a; color: white; }
    body.dark-mode .settings-box { border-color: #444; background: #222; color: white; }
    body.dark-mode input { background: #333; color: white; border: 1px solid #555; }
    body.dark-mode .footer-box p { color: #bbb; }
        
    .settings-box { max-width: 450px; margin: 50px auto; padding: 25px; border: 1px solid #ddd; border-radius: 10px; font-family: Arial, sans-serif; background: white; text-align: left;}
    .toggle-container { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #eee; margin-bottom: 20px; }
    
    .password-section { margin-top: 20px; padding-top: 10px; }
    .form-group { margin-bottom: 15px; }
    label { display: block; font-size: 14px; margin-bottom: 5px; font-weight: bold; }
    input { width: 95%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
    
    .btn-change { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; width: 100%; margin-top: 10px; font-weight: bold; font-size: 16px; }
    .btn-change:hover { background: #0056b3; }
    
    #error-msg { color: #e74c3c; font-size: 13px; margin-top: 5px; display: none; font-weight: bold; }
    .hoomes-link {
    color: white; 
    text-decoration: none;
    font-size: 24px; /* Akkuma PPT keenyaa qubee gurguddaa madaalawaa */
    font-weight: 500;
    padding: 5px 10px;
    border-radius: 5px;
    transition: all 0.3s ease-in-out; /* Hover lallafaa akka ta'uuf */
}
    .hoomes-link:hover {
    color: #003366; /* Akkuma diizaayinii Smart House Finder */
    background-color: white; /* Duubee adii uuma */
    transform: translateY(-2px); /* Xiqqoo ol jedha (Animation namatti tolu) */
}
.nav-wrapper { display: flex; align-items: center; gap: 15px; }
</style>
</head>

<body>
<table width="100%" border="0" style="border-collapse: collapse; color: #2A3E5E;">
  <tbody>
    <tr bgcolor="#333" style="color:white;">
     <td height="80" style="padding: 0 20px; font-size: 24px;">
        <?php include 'houseicon.php'; ?>
        <strong>Smart Home Finder</strong>
        <span style="float:right;">
             <div class="nav-wrapper">
                <a href="<?php echo $home_url; ?>" class="hoomes-link">Home</a>
                <div class="icon-section">
                    <?php include 'icon.php'; ?>
                </div>
             </div>
         </span> 
      </td>
    </tr>
      
    <tr bgcolor="#ECECDE">
      <td style="text-align: center; vertical-align: top; padding: 20px;">
        <div class="settings-box">
          <h2 style="margin-top: 0; color: #2A3E5E;">App Settings</h2>
        
          <div class="toggle-container">
            <span style="font-weight: bold; font-size: 16px;">Dark Mode</span>
            <button onclick="toggleDarkMode()" id="modeBtn" style="padding: 6px 20px; cursor:pointer; font-weight: bold; border-radius: 4px; border: 1px solid #ccc; background: #f8f9fa;">Enable</button>
          </div>

          <div class="password-section">
            <h3 style="color: #2A3E5E; margin-bottom: 15px;">Change Password</h3>
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
                    <div id="error-msg">⚠️ Passwords do not match!</div>
                </div>
                
                <button type="submit" name="change_pwd" class="btn-change">Change Password</button>
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
        var confirmInput = document.getElementById("confirm_password");

        if (new_pwd !== confirm_pwd) {
            errorMsg.style.display = "block"; // Ergaa agarsiisi
            confirmInput.style.borderColor = "#e74c3c";
            return false; // Form-ichi akka hin deemne dhorga
        }
        
        errorMsg.style.display = "none";
        confirmInput.style.borderColor = "#ccc";
        return true; 
    }
</script>
<script src="nav.js"></script>
</body>
</html>