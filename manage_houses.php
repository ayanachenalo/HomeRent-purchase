<?php
session_start();
require_once 'dbconnect.php';

// Username session keessa jiraachuu mirkaneessi (Admin ta'uu isaa)
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: loginadmin.php");
    exit();
}

$search_id = isset($_GET['search_id']) && !empty($_GET['search_id']) ? (int)$_GET['search_id'] : 0;

// Query - Prepared Statement fayyadamnee ijaarra
if ($search_id > 0) {
    $query = "SELECT h.*, u.full_name as owner_name FROM houses h 
              JOIN users u ON h.owner_id = u.id 
              WHERE h.id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $search_id);
} else {
    $query = "SELECT h.*, u.full_name as owner_name FROM houses h 
              JOIN users u ON h.owner_id = u.id ORDER BY h.id DESC";
    $stmt = mysqli_prepare($conn, $query);
}

if ($stmt) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    die("Database execution failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Smart Home Finder - Manage Houses</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<?php include 'admin_nav.php'; ?>

<div class="main-content" style="padding: 20px;">
    <div class="admin-header" style="margin-bottom: 20px;">
        <h3>All Listed Houses</h3>
    </div>

    <table width="100%" border="0" cellpadding="15" style="background: white; border-radius: 10px; border-collapse: collapse; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
        <thead>
            <tr style="background: #f39c12; color: white; text-align: left;">
                <th>Image</th>
                <th>Video</th>
                <th>Title</th>
                <th>Owner</th>
                <th>Price</th>
                <th>City</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($h = mysqli_fetch_assoc($result)): ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td>
                    <?php 
                    // Folder path suuraaf qopheessu
                    $img_raw = trim($h['main_image'] ?? '');
                    $img_src = (strpos($img_raw, 'uploads/') === false && !empty($img_raw)) ? "uploads/images/" . $img_raw : $img_raw;
                    
                    if (empty($img_src)) { $img_src = "uploads/images/default.jpg"; }
                    ?>
                    <img src="<?php echo htmlspecialchars($img_src); ?>" 
                         width="60" height="45" style="border-radius: 5px; cursor: pointer; object-fit: cover;" 
                         onerror="this.src='uploads/images/default.jpg';"
                         onclick="showImage('<?php echo htmlspecialchars($img_src); ?>')">
                </td>
                <td>
                    <?php 
                    // Folder path viidiyoof qopheessu
                    $vid_raw = trim($h['video_path'] ?? '');
                    $vid_src = (strpos($vid_raw, 'uploads/') === false && !empty($vid_raw)) ? "uploads/videos/" . $vid_raw : $vid_raw;
                    
                    if (!empty($vid_raw)): 
                    ?>
                        <div onclick="showVideo('<?php echo htmlspecialchars($vid_src); ?>')" 
                             style="cursor: pointer; position: relative; width: 60px; height: 45px; display: inline-block;">
                            <img src="<?php echo htmlspecialchars($img_src); ?>" width="60" height="45" style="border-radius: 5px; opacity: 0.6; object-fit: cover;" onerror="this.src='uploads/images/default.jpg';">
                            <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 16px; text-shadow: 1px 1px 3px black;">▶</span>
                        </div>
                    <?php else: ?>
                        <span style="font-size: 12px; color: #999;">No Video</span>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($h['title']); ?></td>
                <td><?php echo htmlspecialchars($h['owner_name']); ?></td>
                <td style="color: green; font-weight: bold;"><?php echo htmlspecialchars(number_format($h['price'])); ?> ETB</td>
                <td><?php echo htmlspecialchars($h['city']); ?></td>
                <td>
                    <a href="delete_house.php?id=<?php echo $h['id']; ?>" 
                       onclick="return confirm('Are you sure you want to delete this house?')"
                       style="color: white; background: #e74c3c; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-block;">
                       Delete
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #666; font-weight: bold;">No houses found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div id="mediaModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.9); z-index:9999; justify-content:center; align-items:center;">
    <span onclick="closeModal()" style="position:absolute; top:20px; right:35px; color:white; font-size:40px; font-weight:bold; cursor:pointer; user-select: none;">&times;</span>
    <div id="modalContent" style="max-width: 80%; text-align: center;"></div>
</div>

<script>
function showImage(src) {
    const modal = document.getElementById('mediaModal');
    const content = document.getElementById('modalContent');
    modal.style.display = 'flex';
    content.innerHTML = '<img src="' + src + '" style="max-width:100%; max-height:80vh; border-radius:10px; border: 3px solid white;">';
}

function showVideo(src) {
    const modal = document.getElementById('mediaModal');
    const content = document.getElementById('modalContent');
    modal.style.display = 'flex';
    content.innerHTML = `
        <video width="640" controls autoplay style="max-width:100%; border-radius:10px; border: 3px solid white;">
            <source src="${src}" type="video/mp4">
            Your browser does not support the video tag.
        </video>`;
}

function closeModal() {
    const modal = document.getElementById('mediaModal');
    const content = document.getElementById('modalContent');
    modal.style.display = 'none';
    content.innerHTML = ''; 
}

window.onclick = function(event) {
    const modal = document.getElementById('mediaModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>

</body>
</html>