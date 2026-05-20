<?php
session_start();
include 'dbconnect.php';

// 1. Session irraa eenyummaa koo mirkaneessi
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Session expired. Please login again."]);
    exit();
}

$me = (int)$_SESSION['user_id']; // Nageenyaaf gara integer-atti geeddaruu

// 2. Data URL irraa dhufe qulqulleessi (Security)
$them = isset($_GET['receiver_id']) ? (int)$_GET['receiver_id'] : 0;
$h_id = isset($_GET['house_id']) ? (int)$_GET['house_id'] : 0;

// Yoo ragaan barbaachisu hin jirre deebii duwwaa ergi
if ($them === 0 || $h_id === 0) {
    header('Content-Type: application/json');
    echo json_encode([]); 
    exit();
}

// 3. Query - Haasawa namoota lamaan gidduu mana tokko qofa irratti fida (Prepared Statement fayyadamna)
$query = "SELECT m.*, u.full_name as sender_name 
          FROM messages m
          JOIN users u ON m.sender_id = u.id
          WHERE (
                (m.sender_id = ? AND m.receiver_id = ?) 
             OR (m.sender_id = ? AND m.receiver_id = ?)
          )
          AND m.house_id = ? 
          ORDER BY m.created_at ASC";

$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    // Bind parameters: "iiiii" (hunduu integer dha)
    mysqli_stmt_bind_param($stmt, "iiiii", $me, $them, $them, $me, $h_id);
    
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    // 4. Data fidiitii JSON-dhaan ergi
    $messages = mysqli_fetch_all($res, MYSQLI_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($messages);
    
    mysqli_stmt_close($stmt);
} else {
    header('Content-Type: application/json');
    echo json_encode(["error" => "Database query statement error."]);
}

mysqli_close($conn);
?>