<?php
// 1. Session jalqabuu (Kun baay'ee murteessaa dha)
session_start();
require_once 'dbconnect.php';

$message_sent = false;

$house_id = isset($_GET['house_id']) ? $_GET['house_id'] : 0;

if (isset($_POST['submit_report'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "INSERT INTO reports (house_id, user_email, reason, description) 
            VALUES ('$house_id', '$email', '$reason', '$description')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Gabaasni kee milkiin ergameera. Admin ni qulqulleessa.'); window.location.href='view_details.php';</script>";
    } else {
        echo "Dogoggora: " . mysqli_error($conn);
    }
}

// 2. User-ichi login gochuu isaa fi 'role' isaa mirkaneessi
$user_id = $_SESSION['user_id'] ?? 0;

// Role session keessa yoo hin jirre database irraa dubbisi
if (!isset($_SESSION['role']) && $user_id > 0) {
    $role_query = "SELECT role FROM users WHERE id = '$user_id'";
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
$house_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Smart Home Finder - Feedback</title>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="stylemedia.css">

<style>
        .report-box { max-width: 500px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); font-family: sans-serif; }
        input, select, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #e74c3c; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; width: 100%; }
	.nav-wrapper { display: flex; align-items: center; gap: 15px; }
    .home-link { color: white; text-decoration: none; font-weight: bold; padding: 10px; display: inline-flex; align-items: center; }
    </style>
</head>

<body>
<table width="100%" border="0" style="border-collapse: collapse;">
  <tbody>
    <tr bgcolor="#333" style="color:white;">
      <td height="75" style="padding: 0 20px;">
         <?php include 'houseicon.php'; ?>
         <strong>Smart Home Finder</strong>
         <span style="float:right;">
             <div class="nav-wrapper">
                <!-- Linkii Home kallaattiin role adda baasa -->
                <a href="<?php echo $home_url; ?>" class="home-link">Home</a>
                <div class="icon-section">
                    <?php include 'icon.php'; ?>
                </div>
             </div>
         </span>        
      </td>
    </tr>
      
    <tr bgcolor="#ECECDE">
      <td class="content-area" style="min-height: 500px; vertical-align: top; padding: 20px;">
         <div class="report-box">
    <h2 style="color: #e74c3c;">Report a Problem</h2>
    <p style="font-size: 14px; color: #666;">Mana kireeffamu kana irratti rakkoo argite sitti himi.</p>
    
    <form action="" method="POST">
        <input type="email" name="email" placeholder="Email keessan" required>
        
        <select name="reason" required>
            <option value="">Sababa filadhu...</option>
            <option value="Scam">Gowwoomsaa (Scam)</option>
            <option value="Wrong Info">Odeeffannoo dogoggoraa</option>
            <option value="Sold">Manichi kireeffameera</option>
            <option value="Other">Kan biraa</option>
        </select>
        
        <textarea name="description" rows="5" placeholder="Ibsa dabalataa..." required></textarea>
        
        <button type="submit" name="submit_report">Submit Report</button>
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