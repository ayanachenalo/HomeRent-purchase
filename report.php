<?php
// 1. Session jalqabuu (Kun baay'ee murteessaa dha)
session_start();
require_once 'dbconnect.php';

// house_id jalqaba irratti URL irraa fudhanna (Gara integer-itti cast goona)
$house_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (isset($_POST['submit_report'])) {
    $email = trim($_POST['email']);
    $reason = trim($_POST['reason']);
    $description = trim($_POST['description']);

    // Validation - Bakka duwwaa calaluu
    if (empty($email) || empty($reason) || empty($description) || $house_id == 0) {
        echo "<script>alert('Please fill in all fields correctly!'); window.history.back();</script>";
        exit();
    }

    // Gabaasa kuusaa ragaatti galmeessuuf Prepared Statement fayyadamna
    $sql = "INSERT INTO reports (house_id, user_email, reason, description) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "isss", $house_id, $email, $reason, $description);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    alert('Your report has been submitted successfully. Admin will review it.'); 
                    window.location.href='view_details.php?id=" . $house_id . "';
                  </script>";
            exit();
        } else {
            echo "Execution Error: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "SQL Error: " . mysqli_error($conn);
    }
}

// 2. User-ichi login gochuu isaa fi 'role' isaa mirkaneessi
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

// Role session keessa yoo hin jirre database irraa dubbisi
if (!isset($_SESSION['role']) && $user_id > 0) {
    $role_query = "SELECT role FROM users WHERE id = ?";
    $role_stmt = mysqli_prepare($conn, $role_query);
    if ($role_stmt) {
        mysqli_stmt_bind_param($role_stmt, "i", $user_id);
        mysqli_stmt_execute($role_stmt);
        $role_res = mysqli_stmt_get_result($role_stmt);
        if ($u_data = mysqli_fetch_assoc($role_res)) {
            $_SESSION['role'] = $u_data['role'];
        }
        mysqli_stmt_close($role_stmt);
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
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Smart Home Finder - Report Issue</title>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="stylemedia.css">

<style>
    .report-box { max-width: 500px; margin: 50px auto; padding: 25px; background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); font-family: sans-serif; text-align: left; }
    input, select, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; font-size: 15px; }
    button { background: #e74c3c; color: white; border: none; padding: 12px 20px; cursor: pointer; border-radius: 5px; width: 100%; font-size: 16px; font-weight: bold; transition: background 0.2s; }
    button:hover { background: #c0392b; }
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
                <a href="<?php echo htmlspecialchars($home_url); ?>" class="home-link">Home</a>
                <div class="icon-section">
                    <?php include 'icon.php'; ?>
                </div>
             </div>
         </span>        
      </td>
    </tr>
      
    <tr bgcolor="#ECECDE">
      <td class="content-area" style="min-height: 500px; vertical-align: top; padding: 20px; text-align: center;">
         <div class="report-box">
            <h2 style="color: #e74c3c; margin-top: 0;">Report a Problem</h2>
            <p style="font-size: 14px; color: #666; margin-bottom: 20px;">Please report any issues or suspicious activity regarding this house listing.</p>
            
            <form action="report.php?id=<?php echo htmlspecialchars($house_id); ?>" method="POST">
                <input type="email" name="email" placeholder="Your Email Address" required>
                
                <select name="reason" required>
                    <option value="">Select a reason...</option>
                    <option value="Scam">Fraud / Scam Listing</option>
                    <option value="Wrong Info">Incorrect Information</option>
                    <option value="Sold">House already rented out / Sold</option>
                    <option value="Other">Other Issues</option>
                </select>
                
                <textarea name="description" rows="5" placeholder="Provide additional details about the issue..." required></textarea>
                
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
<?php mysqli_close($conn); ?>