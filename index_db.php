<?php
// index_db.php
require_once 'dbconnect.php';

// 1. Ragaa Search irraa dhufe qabachuu
// Akka magaalaan duwwaa yoo ta'e hunda fiduuf '%' fayyadamna
$city = isset($_GET['city']) ? mysqli_real_escape_string($conn, $_GET['city']) : '';
$price = (isset($_GET['price']) && !empty($_GET['price'])) ? (int)$_GET['price'] : 1000000;

// 2. Query Manneen hunda ykn kan calalame fidu
// 'Available' kan ta'an qofa fiduun gaariidha
$sql = "SELECT * FROM houses WHERE city LIKE '%$city%' AND price <= $price AND status = 'Available' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

$houses = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        
        // Ragaa suuraa fi video table 'houses' keessaa fiduu
        // Suuraan yoo hin jirre default kaa'un dirqama
        if (!empty($row['main_image'])) {
            $row['media_path'] = "uploads/images/" . $row['main_image'];
            $row['media_type'] = 'image';
        } 
        // Yoo suuraan hin jirre garuu videon jiraate (filannoo lammataa)
        elseif (!empty($row['video_path'])) {
            $row['media_path'] = "uploads/videos/" . $row['video_path'];
            $row['media_type'] = 'video';
        } 
        // Yoo lamaan hin jirre
        else {
            $row['media_path'] = "uploads/images/default.jpg";
            $row['media_type'] = 'image';
        }
        
        $houses[] = $row;
    }
}
?>