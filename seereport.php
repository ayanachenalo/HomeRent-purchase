<?php
session_start();
require_once 'dbconnect.php';
// --- Koodii Gabaasa Haquu (Ignore Logic) ---

if (isset($_GET['delete_id'])) {
    $id_to_delete = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_query = "DELETE FROM reports WHERE id = '$id_to_delete'";
    
    if (mysqli_query($conn, $delete_query)) {
        // Erga haqamee booda fuuluma kanatti deebi'i (refresh)
        header("Location: seereport.php?msg=deleted");
        exit();
    }
}
// ------------------------------------------

if (!isset($_SESSION['username'])) {
    header("Location: loginadmin.php");
    exit();
}
// Nageenyaaf: Admin ta'uu isaa mirkaneessi
if (!isset($_SESSION['username'])) {
    header("Location: loginadmin.php");
    exit();
}

// Gabaasaalee hunda maqaa mana (house title) waliin fiduu
// Bakka kana sirriitti kopeessi
$query = "SELECT reports.*, houses.title 
          FROM reports 
          JOIN houses ON reports.house_id = houses.id 
          ORDER BY reports.id DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query'n hojjechuu dideera: " . mysqli_error($conn));
}


?>

<!DOCTYPE html>
<html lang="or">
<head>
    <meta charset="UTF-8">
    <title>Manage Reports - Admin</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; margin: 0; }
        .container { width: 95%; margin: 30px auto; margin-left: 300px;}
        .report-card { 
            background: white; 
            padding: 20px; 
            border-radius: 10px; 
            margin-bottom: 20px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
            border-left: 6px solid #e74c3c; 
        }
        .report-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .house-title { color: #2c3e50; font-size: 18px; font-weight: bold; }
        .reason-tag { background: #ffebee; color: #c62828; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .details { margin: 15px 0; color: #444; line-height: 1.5; }
        .actions { display: flex; gap: 10px; margin-top: 15px; }
        .btn { padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 14px; color: white; }
        .btn-delete-house { background: #e74c3c; }
        .btn-ignore { background: #95a5a6; }
    </style>
</head>
<body>

<?php include 'admin_nav.php'; ?>

<div class="container">
    <h2>📩 User Reports</h2>
    <p>Manneen rakkoo qaban jedhamanii gabaafaman asitti qulqulleessi.</p>
    <hr>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="report-card">
                <div class="report-header">
                    <div>
                        <span class="house-title">🏠 House: <?php echo htmlspecialchars($row['title']); ?></span>
                        <br>
                        <small>Reporter: <?php echo htmlspecialchars($row['user_email']); ?> | 📅 <?php echo $row['created_at']; ?></small>
                    </div>
                    <span class="reason-tag">⚠️ <?php echo htmlspecialchars($row['reason']); ?></span>
                </div>

                <div class="details">
                    <strong>Description:</strong><br>
                    <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                </div>
<!-- Bakka linkii "See House Details" jiru irratti -->
<!-- Bakka linkii "See House Details" jiru irratti -->
<a href="manage_houses.php?search_id=<?php echo $row['house_id']; ?>" 
   class="btn" 
   style="background: #3498db;">
   🔍 See House Details
</a>
    
    <a href="?delete_id=<?php echo $row['id']; ?>" 
       class="btn btn-ignore"
       onclick="return confirm('Gabaasa kana qofa haquu barbaadda?')">
       Ignore Report
    </a>
</div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div style="text-align: center; padding: 50px; color: #7f8c8d;">
            <h3>Gabaasni dhufe hin jiru. 👍</h3>
        </div>
    <?php endif; ?>
</div>

</body>
</html>