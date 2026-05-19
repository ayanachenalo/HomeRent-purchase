<?php
session_start();
require_once 'dbconnect.php';

if (!isset($_SESSION['username'])) {
    header("Location: loginadmin.php");
    exit();
}

$search_id = isset($_GET['search_id']) ? $_GET['search_id'] : '';

if ($search_id != '') {
    $query = "SELECT h.*, u.full_name as owner_name FROM houses h 
              JOIN users u ON h.owner_id = u.id 
              WHERE h.id = '$search_id'";
} else {
    $query = "SELECT h.*, u.full_name as owner_name FROM houses h 
              JOIN users u ON h.owner_id = u.id ORDER BY h.id DESC";
}

// *** SIRREEFFAMA: Koodii kana dabaladhu ***
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query'n hojjechuu dideera: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Houses</title>
</head>
<body>

<?php include 'admin_nav.php'; ?>

<div class="main-content">
    <div class="admin-header">
        <h3>All Listed Houses</h3>
    </div>

    <table width="100%" border="0" cellpadding="15" style="background: white; border-radius: 10px; border-collapse: collapse;">
        <tr style="background: #f39c12; color: white; text-align: left;">
            <th>Image</th>
			<th>Video</th>
            <th>Title</th>
            <th>Owner</th>
            <th>Price</th>
            <th>City</th>
            <th>Action</th>
        </tr>
        
        <!-- Table kee keessa jiru kanaan bakka buusi -->
<?php while($h = mysqli_fetch_assoc($result)): ?>
<tr style="border-bottom: 1px solid #eee;">
    <!-- Suuraa: Yoo cuqaasan ni guddata -->
    <td>
        <img src="uploads/images/<?php echo $h['main_image']; ?>" 
             width="60" style="border-radius: 5px; cursor: pointer;" 
             onclick="showImage('uploads/images/<?php echo $h['main_image']; ?>')">
    </td>
    <!-- Viidiyoo: Yoo cuqaasan bakkuma sanatti taphata -->
    <td>
        <div onclick="showVideo('uploads/videos/<?php echo $h['video_path']; ?>')" 
             style="cursor: pointer; position: relative; width: 60px;">
            <img src="uploads/images/<?php echo $h['main_image']; ?>" width="60" style="border-radius: 5px; opacity: 0.6;">
            <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 20px;">▶</span>
        </div>
    </td>
    <td><?php echo $h['title']; ?></td>
    <td><?php echo $h['owner_name']; ?></td>
    <td><?php echo number_format($h['price']); ?> ETB</td>
    <td><?php echo $h['city']; ?></td>
    <td>
        <a href="delete_house.php?id=<?php echo $h['id']; ?>" 
           onclick="return confirm('Are you sure to delete this house?')"
           style="color: white; background: #e74c3c; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 12px;">
           Delete
        </a>
    </td>
</tr>
<?php endwhile; ?>

<!-- Pop-up Modal (Saanduqa Media) -->
<div id="mediaModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.9); z-index:9999; justify-content:center; align-items:center;">
    <span onclick="closeModal()" style="position:absolute; top:20px; right:35px; color:white; font-size:40px; font-weight:bold; cursor:pointer;">&times;</span>
    <div id="modalContent" style="max-width: 80%; text-align: center;">
        <!-- Suuraan ykn Viidiyoon as keessatti uumama -->
    </div>
</div>
</table>
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
    content.innerHTML = ''; // Viidiyoon akka dhaabbatuuf koodii isaa keessaa haqa
}

// Yoo bakka duwwaa (shashii gurraacha) cuqaasan akka cufamuuf
window.onclick = function(event) {
    const modal = document.getElementById('mediaModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>

</body>
</html>