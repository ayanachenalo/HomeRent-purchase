<style>
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; display: flex; background: #f4f7f6; }
    .sidebar { width: 285px; background: #2c3e50; color: white; height: 100vh; position: fixed; padding-top: 20px; }
    .sidebar h2 { text-align: center; font-size: 20px; border-bottom: 10px solid #34495e; padding-bottom: 20px; }
    .sidebar a { display: block; color: #bdc3c7; padding: 15px 25px; text-decoration: none; transition: 0.3s; }
    .sidebar a:hover { background: #34495e; color: white; padding-left: 35px; }
    .sidebar a.active { background: #3498db; color: white; border-left: 5px solid #fff; }
    .main-content { margin-left: 260px; flex-grow: 1; padding: 30px; }
    .admin-header { background: white; padding: 15px 30px; margin: -30px -30px 30px -30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
</style>

<div class="sidebar">
	<?php
// Ikonii mana bifa suuraatiin (SVG)
echo '<img src="https://cdn-icons-png.flaticon.com/512/25/25694.png" width="60" height="60" style="vertical-align: middle; filter: invert(1); padding-right:20px;">';
?>
    <strong style="font-size: 20;">Smart Home Admin</strong>
    <a href="adminpanel.php">Dashboard</a>
    <a href="manage_owners.php">Manage Owners</a>
    <a href="manage_tenants.php">Manage Tenants</a>
    <a href="manage_houses.php">Manage Houses</a>
    <a href="seefedback.php">Feedback</a>
	 <a href="seereport.php">Report</a>
	 <a href="settinadmin.php">Settings</a>
    <a href="adminlogout.php" style="color: #e74c3c;">Logout</a>
</div>