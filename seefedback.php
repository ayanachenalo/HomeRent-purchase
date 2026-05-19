<?php
session_start();
require_once 'dbconnect.php'; // Kallaattiin database connection saagi

// Nageenyaaf: Admin qofni akka seenu mirkaneessi
if (!isset($_SESSION['username'])) {
    header("Location: loginadmin.php");
    exit();
}

if (isset($_GET['del_feedback'])) {
    $id_to_delete = mysqli_real_escape_string($conn, $_GET['del_feedback']);
    $sql_del = "DELETE FROM feedback WHERE id = '$id_to_delete'";
    
    if (mysqli_query($conn, $sql_del)) {
        // Erga haqamee booda fuuluma kanatti deebi'i
        header("Location: seefedback.php?status=deleted");
        exit();
    }
}

// Yaada fayyadamtootaa maqaa isaanii waliin fetch gochuuf
$query = "SELECT f.*, u.full_name, u.email 
          FROM feedback 
          JOIN users u ON f.user_id = u.id 
          ORDER BY f.created_at DESC";
$query = "SELECT * FROM feedback ORDER BY id DESC";
$result = mysqli_query($conn, $query);

// Yoo query'n sun dogoggore maaliif akka ta'e sitti hima
if (!$result) {
    die("Dogoggora SQL: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Feedback</title>
    <style>
        .feedback-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-left: 5px solid #f39c12;
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
    <hr>

    <?php if(mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="feedback-card">
                <div class="user-info">
                    <span>👤 <?php echo $row['name']; ?> (<?php echo $row['email']; ?>)</span>
                    <span class="time">📅 <?php echo date('M d, Y - h:i A', strtotime($row['created_at'])); ?></span>
                </div>
                <div class="message">
                    <strong>Yaada:</strong><br>
                    <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                </div>
               <div style="margin-top: 10px; text-align: right;">
    <!-- URL kallaattiin gara fuula kanatti (self) deebi'a -->
    <a href="?del_feedback=<?php echo $row['id']; ?>" 
       onclick="return confirm('Yaada kana haquu barbaaddu?')"
       style="color: #e74c3c; font-size: 13px; text-decoration: none; font-weight: bold;">
       Delete
    </a>
</div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-data">
            <h3>Yaanni (Feedback) ammaaf hin jiru.</h3>
        </div>
    <?php endif; ?>
</div>

</body>
</html>