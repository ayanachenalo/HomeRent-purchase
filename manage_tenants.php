<?php
session_start();
require_once 'dbconnect.php';

// Nageenyaaf: Admin ta'uu isaa fi role isaa guutummaatti mirkaneessi
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginadmin.php"); // Gara login deebisi
    exit();
}

// User-oota role isaanii 'tenant' ta'e fiduu (Prepared Statement fayyadamna)
$query = "SELECT id, full_name, email, created_at FROM users WHERE role = 'tenant' ORDER BY id DESC";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    die("Database execution failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Smart Home Finder - Manage Tenants</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<?php include 'admin_nav.php'; ?>

<div class="main-content" style="padding: 20px;">
    <div class="admin-header">
        <h3>Manage Tenants</h3>
    </div>

    <table width="100%" border="0" cellpadding="15" style="background: white; border-radius: 10px; border-collapse: collapse; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
        <thead>
            <tr style="background: #27ae60; color: white; text-align: left;">
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Date Registered</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td>#<?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                <td>
                    <a href="delete_user.php?id=<?php echo htmlspecialchars($row['id']); ?>" 
                       onclick="return confirm('Are you sure you want to delete this tenant?')"
                       style="color: white; background: #e74c3c; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 12px; font-weight: bold;">
                       Delete
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px; color: #666;">No tenants found registered.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
<?php 
if (isset($stmt)) { mysqli_stmt_close($stmt); }
mysqli_close($conn);
?>