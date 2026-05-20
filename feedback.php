<?php
// 1. Session jalqabuu (Kun baay'ee murteessaa dha)
session_start();
require_once 'dbconnect.php';

$message_sent = false;

// Feedback galmeessuu
if (isset($_POST['submit_feedback'])) {
    $name = trim($_POST['name']);
    $email = strtolower(trim($_POST['email']));
    $message = trim($_POST['message']);

    // SQL Injection ittisuuf Prepared Statements fayyadamna
    $sql = "INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $message);
        
        if (mysqli_stmt_execute($stmt)) {
            $message_sent = true;
        } else {
            echo "Error: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "SQL Error: " . mysqli_error($conn);
    }
}

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
<title>Smart Home Finder - Feedback</title>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="stylemedia.css">

<style>
    body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
    .feedback-container { max-width: 600px; margin: 50px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    h2 { color: #2c3e50; text-align: center; }
    input, textarea { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
    button { width: 100%; padding: 12px; background-color: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold; }
    button:hover { background-color: #219150; }
    .success-msg { background-color: #d4edda; color: #155724; padding: 15px; border-radius: 4px; text-align: center; margin-bottom: 20px; }
    
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
          <div class="feedback-container" style="background-color: cadetblue;">
            <h2>📩 Send Us Your Feedback</h2>
            <p style="text-align: center; color: #E4EEFF;">Share your thoughts with us to help improve our service.</p>

            <?php if ($message_sent): ?>
                <div class="success-msg">
                    Thank you! Your feedback has been successfully submitted.
                </div>
            <?php endif; ?>

            <form action="feedback.php" method="POST">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Enter your name..." required>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email..." required>

                <label for="message">Your Message</label>
                <textarea name="message" id="message" rows="6" placeholder="Write your feedback or questions here..." required></textarea>

                <button type="submit" name="submit_feedback">Send Feedback</button>
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