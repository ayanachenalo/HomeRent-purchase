<?php
// 1. Session jalqabuu (Kun baay'ee murteessaa dha)
session_start();
require_once 'dbconnect.php'; // Kallaattiin database connection saagi

// Nageenyaaf: Admin ta'uu isaa fi role isaa guutummaatti mirkaneessi
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginadmin.php");
    exit();
}

// Yaada haquu (DELETE Process) - Prepared Statement fayyadamna
if (isset($_GET['del_feedback'])) {
    $id_to_delete = (int)$_GET['del_feedback']; // Gara integer cast goona
    
    $sql_del = "DELETE FROM feedback WHERE id = ?";
    $stmt_del = mysqli_prepare($conn, $sql_del);
    
    if ($stmt_del) {
        mysqli_stmt_bind_param($stmt_del, "i", $id_to_delete);
        
        if (mysqli_stmt_execute($stmt_del)) {
            mysqli_stmt_close($stmt_del);
            // Erga haqamee booda fuuluma kanatti deebi'i
            header("Location: seefedback.php?status=deleted");
            exit();
        }
        mysqli_stmt_close($stmt_del);
    }
}

/* 💡 BUG FIX: Maqaan column 'user_id' jedhu waan hin jirreef, kallaattiin 
table feedback irraa ragaa 'name' fi 'email' ni fudhanna. Yoo taableen kee 
users waliin walitti hidhamuu qaba ta'e, query kana "f.email = u.email" godhi.
*/
$query = "SELECT * FROM feedback ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

// Yoo query'n sun dogoggore maaliif akka ta'e sitti hima
if (!$result) {
    die("Database SQL Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Home Finder - User Feedback</title>
    <style>
        .feedback-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-left: 5px solid #f39c12;
            text-align: left;
        }
        .user-info {
            font-weight: bold;
            color: #2c3e50;
            display: flex;
            justify-content: space-between;
        }
        .time {
            font-size: 12px;
            color: #7f8c8d;
        }
        .message {
            margin-top: 10px;
            line-height: 1.6;
            color: #34495e;
        }
        .no-data {
            text-align: center;
            padding: 50px;
            color: #7f8c8d;
        }
    </style>
</head>
<body style="background: #f4f7f6; font-family: Arial, sans-serif;">

<?php include 'admin_nav.php'; ?>

<div style="max-width: 900px; margin: 30px auto; padding: 0 20px;">
    <h2>📩 User Feedbacks</h2>
    <hr style="border: 0; height: 1px; background: #ccc; margin-bottom: 20px;">

    <?php if(mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="feedback-card">
                <div class="user-info">
                    <?php 
                    // Column names table feedback keessa jiran gargaaramuuf (Fallback handling)
                    $display_name = $row['full_name'] ?? $row['name'] ?? 'Guest User';
                    $display_email = $row['email'] ?? 'No Email';
                    $created_time = isset($row['created_at']) ? date('M d, Y - h:i A', strtotime($row['created_at'])) : 'Unknown Date';
                    ?>
                    <span>👤 <?php echo htmlspecialchars($display_name); ?> (<?php echo htmlspecialchars($display_email); ?>)</span>
                    <span class="time">📅 <?php echo $created_time; ?></span>
                </div>
                <div class="message">
                    <strong>Feedback Message:</strong><br>
                    <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                </div>
                <div style="margin-top: 10px; text-align: right;">
                    <a href="?del_feedback=<?php echo htmlspecialchars($row['id']); ?>" 
                       onclick="return confirm('Are you sure you want to delete this feedback?')"
                       style="color: #e74c3c; font-size: 13px; text-decoration: none; font-weight: bold;">
                       Delete
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-data">
            <h3>No feedback messages found.</h3>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
<?php mysqli_close($conn); ?>