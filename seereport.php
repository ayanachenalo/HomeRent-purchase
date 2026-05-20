<?php
// 1. Session jalqabuu (Kun baay'ee murteessaa dha)
session_start();
require_once 'dbconnect.php';

// Nageenyaaf: Admin ta'uu isaa fi role isaa guutummaatti mirkaneessi
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginadmin.php");
    exit();
}

// --- Koodii Gabaasa Haquu (Ignore Logic) - Prepared Statement fayyadamna ---
if (isset($_GET['delete_id'])) {
    $id_to_delete = (int)$_GET['delete_id']; // Gara integer cast goona
    
    $delete_query = "DELETE FROM reports WHERE id = ?";
    $stmt_del = mysqli_prepare($conn, $delete_query);
    
    if ($stmt_del) {
        mysqli_stmt_bind_param($stmt_del, "i", $id_to_delete);
        
        if (mysqli_stmt_execute($stmt_del)) {
            mysqli_stmt_close($stmt_del);
            // Erga haqamee booda fuuluma kanatti deebi'i (refresh)
            header("Location: seereport.php?msg=deleted");
            exit();
        }
        mysqli_stmt_close($stmt_del);
    }
}
// --------------------------------------------------------------------------

// Gabaasaalee hunda maqaa mana (house title) waliin JOIN gochuun fiduu
$query = "SELECT reports.*, houses.title 
          FROM reports 
          JOIN houses ON reports.house_id = houses.id 
          ORDER BY reports.id DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Home Finder - Manage Reports</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; margin: 0; }
        .container { width: 95%; max-width: 1000px; margin: 30px auto; margin-left: 300px; }
        .report-card { 
            background: white; 
            padding: 20px; 
            border-radius: 10px; 
            margin-bottom: 20px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
            border-left: 6px solid #e74c3c; 
            text-align: left;
        }
        .report-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .house-title { color: #2c3e50; font-size: 18px; font-weight: bold; }
        .reason-tag { background: #ffebee; color: #c62828; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .details { margin: 15px 0; color: #444; line-height: 1.5; }
        .actions { display: flex; gap: 10px; margin-top: 15px; }
        .btn { padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 14px; color: white; display: inline-flex; align-items: center; font-weight: bold; }
        .btn-ignore { background: #95a5a6; }
        .btn-ignore:hover { background: #7f8c8d; }
    </style>
</head>
<body>

<?php include 'admin_nav.php'; ?>

<div class="container">
    <h2>📩 User Reports</h2>
    <p style="color: #666;">Review and manage reported problems and issues regarding house listings.</p>
    <hr style="border: 0; height: 1px; background: #ccc; margin-bottom: 25px;">

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="report-card">
                <div class="report-header">
                    <div>
                        <span class="house-title">🏠 House: <?php echo htmlspecialchars($row['title']); ?></span>
                        <br>
                        <small style="color: #7f8c8d;">Reporter: <?php echo htmlspecialchars($row['user_email']); ?> | 📅 <?php echo htmlspecialchars($row['created_at']); ?></small>
                    </div>
                    <span class="reason-tag">⚠️ <?php echo htmlspecialchars($row['reason']); ?></span>
                </div>

                <div class="details">
                    <strong>Description:</strong><br>
                    <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                </div>

                <div class="actions">
                    <a href="manage_houses.php?search_id=<?php echo htmlspecialchars($row['house_id']); ?>" 
                       class="btn" 
                       style="background: #3498db;">
                       🔍 See House Details
                    </a>
                    
                    <a href="?delete_id=<?php echo htmlspecialchars($row['id']); ?>" 
                       class="btn btn-ignore"
                       onclick="return confirm('Are you sure you want to ignore and delete this report?')">
                       Ignore Report
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div style="text-align: center; padding: 50px; color: #7f8c8d;">
            <h3>No reports submitted by users. 👍</h3>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
<?php mysqli_close($conn); ?>