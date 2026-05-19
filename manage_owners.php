<?php
session_start();
require_once 'dbconnect.php';

// Nageenyaaf: Admin ta'uu isaa mirkaneessi
// $_SESSION['username'] akka jiru check goona
if (!isset($_SESSION['username'])) {
    header("Location: loginadmin.php"); // Gara login deebisi
    exit();
}

// User-oota role isaanii 'owner' ta'e fiduu
$query = "SELECT id, full_name, email, created_at FROM users WHERE role = 'owner' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Owners</title>
</head>
<body>

<?php include 'admin_nav.php'; ?>

<div class="main-content">
    <div class="admin-header">
        <h3>Manage Owners</h3>
    </div>

    <table width="100%" border="0" cellpadding="15" style="background: white; border-radius: 10px; border-collapse: collapse;">
        <tr style="background: #34495e; color: white; text-align: left;">
            <th>ID</th>
            <th>Full name</th>
            <th>Email</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr style="border-bottom: 1px solid #eee;">
            <td>#<?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
                <a href="delete_user.php?id=<?php echo $row['id']; ?>" 
                   onclick="return confirm('Are you sure you want to delete this owner?')"
                   style="color: white; background: #e74c3c; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 12px;">
                   Delete
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>