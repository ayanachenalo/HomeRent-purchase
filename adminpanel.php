<script src="script.js"></script>
<?php
session_start();
require_once 'dbconnect.php';

// Akka jiru check goona (Admin login gochuu isaa mirkaneessuuf)
if (!isset($_SESSION['username'])) {
    header("Location: loginadmin.php"); // Gara login deebisi
    exit();
}

// Ragaalee Dashboard-if barbaachisan fiduu
$total_users = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users"));
$total_owners = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role='owner'"));
$total_tenants = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role='tenant'"));
$total_houses = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM houses"));
$latest_houses = mysqli_query($conn, "SELECT title, city, price FROM houses ORDER BY id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
<?php include 'admin_nav.php'; ?>
<div class="main-content">
    <div class="admin-header">
        <h3>Dashboard</h3>
        <span><strong>Admin</strong></span>
    </div>

    <style>
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .stat-card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; }
        .stat-card h1 { margin: 10px 0; color: #2c3e50; }
        .stat-card p { color: #7f8c8d; font-weight: bold; }
    </style>

    <div class="stats-grid">
        <div class="stat-card" style="border-top: 5px solid #3498db;">
            <p>TOTAL USERS</p>
            <h1><?php echo $total_users; ?></h1>
        </div>
        <div class="stat-card" style="border-top: 5px solid #e67e22;">
            <p>OWNERS</p>
            <h1><?php echo $total_owners; ?></h1>
        </div>
        <div class="stat-card" style="border-top: 5px solid #2ecc71;">
            <p>TENANTS</p>
            <h1><?php echo $total_tenants; ?></h1>
        </div>
        <div class="stat-card" style="border-top: 5px solid #9b59b6;">
            <p>HOUSES</p>
            <h1><?php echo $total_houses; ?></h1>
        </div>
    </div>

    <div style="margin-top: 40px; background: white; padding: 20px; border-radius: 10px;">
        <h4>Recently Registered Houses</h4>
        <table width="100%" border="0" cellpadding="10" style="border-collapse: collapse;">
            <tr style="background: #f8f9fa; text-align: left;">
                <th>House Name</th>
                <th>City</th>
                <th>Price (ETB)</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($latest_houses)): ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['city']; ?></td>
                <td><?php echo number_format($row['price']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
</body>
</html>