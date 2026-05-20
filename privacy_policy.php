<?php
// 1. Session jalqabuu
session_start();
require_once 'dbconnect.php';

// Role session keessa yoo jiraate dhimma bahuuf
$user_role = $_SESSION['role'] ?? 'tenant'; 
if ($user_role === 'owner') {
    $home_url = "owner.php";
} else {
    $home_url = "index.php";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Privacy Policy - Smart Home Finder</title>
    <link rel="stylesheet" type="text/css" href="stylemedia.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; margin: 0; color: #2A3E5E; }
        .policy-container { max-width: 800px; margin: 50px auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: left; line-height: 1.7; }
        h1 { color: #273552; border-bottom: 2px solid #63AF94; padding-bottom: 10px; }
        h3 { color: #2c3e50; margin-top: 25px; }
        p, li { color: #555; font-size: 15px; }
        ul { padding-left: 20px; }
        .nav-wrapper { display: flex; align-items: center; gap: 15px; }
        .home-link { color: white; text-decoration: none; font-weight: bold; padding: 10px; display: inline-flex; align-items: center; }
    </style>
</head>
<body>

<table width="100%" border="0" style="border-collapse: collapse; color: white;">
  <tbody>
    <tr bgcolor="#333">
      <td height="75" style="padding: 0 20px;">
        <?php include 'houseicon.php'; ?>
        <strong>Smart Home Finder</strong>
        <span style="float:right;">
          <div class="nav-wrapper">
            <a href="<?php echo $home_url; ?>" class="home-link">Home</a>
            <?php include 'icon.php'; ?>
          </div>
        </span>       
      </td>
    </tr>
  </tbody>
</table>

<div class="policy-container">
    <h1>Privacy Policy</h1>
    <p>Welcome to <strong>Smart Home Finder</strong>. Your privacy is important to us. This Privacy Policy explains how we collect, use, and protect your personal information when you use our web application.</p>
    
    <hr style="border:0; height:1px; background:#eee; margin:20px 0;">

    <h3>1. Information We Collect</h3>
    <p>To provide our home rental services, we collect basic user account information including:</p>
    <ul>
        <li><strong>Account Details:</strong> Full Name, Username, Phone Number, and Password (secured via encryption).</li>
        <li><strong>Property Information:</strong> House details, prices, images, and tour videos uploaded by Owners.</li>
        <li><strong>Communication:</strong> Private chat messages exchanged securely between Tenants and Owners.</li>
    </ul>

    <h3>2. How We Use Your Information</h3>
    <p>The information we collect is strictly used to maximize the user experience:</p>
    <ul>
        <li>To connect Tenants directly with House Owners via integrated chat and phone numbers.</li>
        <li>To manage and showcase verified rental listings on the dashboard.</li>
        <li>To maintain system security and prevent fraudulent property listings.</li>
    </ul>

    <h3>3. Data Protection and Security</h3>
    <p>We implement professional security measures to keep your data safe. All user passwords are encrypted using modern hashing algorithms ($password\_hash()$). Your direct messages are handled securely within our database architecture to prevent unauthorized access.</p>

    <h3>4. Information Sharing</h3>
    <p>We do not sell, rent, or trade your personal information to third parties. Your phone number is only displayed to logged-in users to facilitate legitimate booking inquiries.</p>

    <h3>5. Contact Us</h3>
    <p>If you have any questions or concerns regarding this Privacy Policy, please reach out to our system administration team.</p>
</div>

<table width="100%" border="0" style="border-collapse: collapse; color: white; text-align: center;">
  <tbody>
    <tr bgcolor="#333">
      <td height="100">
          <?php include 'footer.php'; ?>
      </td>
    </tr>
  </tbody>
</table>
<script src="nav.js"></script>
</body>
</html>