<?php
// index_db.php
require_once 'dbconnect.php';

// 1. Ragaa Search irraa dhufe qabachuu
$city = isset($_GET['city']) ? trim($_GET['city']) : '';
$price = (isset($_GET['price']) && !empty($_GET['price'])) ? (int)$_GET['price'] : 1000000;

// Like constraint akka hojjetuuf % asitti daballa
$city_search = "%" . $city . "%";

// 2. Query Manneen hunda ykn kan calalame fidu (Prepared Statements fayyadamna)
$sql = "SELECT * FROM houses WHERE city LIKE ? AND price <= ? AND (status = 'Available' OR status = 'available') ORDER BY id DESC";
$stmt = mysqli_prepare($conn, $sql);

$houses = [];

if ($stmt) {
    // Bind parameters: "si" -> string ($city_search), integer ($price)
    mysqli_stmt_bind_param($stmt, "si", $city_search, $price);
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            
            $main_img = trim($row['main_image'] ?? '');
            $vid_path = trim($row['video_path'] ?? '');

            // 💡 FIX 1: Suuraadhaaf folder path sirreessuu
            if (!empty($main_img)) {
                // Yoo database keessatti kallaattiin maqaa faayilii qofa kuusame ta'e 'uploads/images/' itti dabalna
                if (strpos($main_img, 'uploads/') === false) {
                    $row['main_image'] = "uploads/images/" . $main_img;
                }
            } else {
                // Yoo suuraan dhabame default kuusna
                $row['main_image'] = "uploads/images/default.jpg";
            }

            // 💡 FIX 2: Viidiyoodhaafis haaluma kanaan addatti check goona (elseif dhiifne)
            if (!empty($vid_path)) {
                // Yoo database keessatti 'uploads/' hin jirre ta'e 'uploads/videos/' itti dabalna
                if (strpos($vid_path, 'uploads/') === false) {
                    $row['video_path'] = "uploads/videos/" . $vid_path;
                }
            } else {
                $row['video_path'] = NULL;
            }
            
            $houses[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
} else {
    echo "SQL Error: " . mysqli_error($conn);
}
?>