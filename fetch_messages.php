<?php
session_start();
include 'dbconnect.php';

// 1. Session irraa eenyummaa koo mirkaneessi
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Session expired. Please login."]);
    exit();
}

$me = $_SESSION['user_id'];

// 2. Data URL irraa dhufe qulqulleessi (Security)
$them = isset($_GET['receiver_id']) ? mysqli_real_escape_string($conn, $_GET['receiver_id']) : null;
$h_id = isset($_GET['house_id']) ? mysqli_real_escape_string($conn, $_GET['house_id']) : null;

// Yoo ragaan barbaachisu hin jirre deebii duwwaa ergi
if (!$them || !$h_id) {
    echo json_encode([]); 
    exit();
}

// 3. Query - Haasawa namoota lamaan gidduu mana tokko qofa irratti fida
$query = "SELECT m.*, u.full_name as sender_name 
          FROM messages m
          JOIN users u ON m.sender_id = u.id
          WHERE (
                (m.sender_id = '$me' AND m.receiver_id = '$them') 
             OR (m.sender_id = '$them' AND m.receiver_id = '$me')
          )
          AND m.house_id = '$h_id' 
          ORDER BY m.created_at ASC";

$res = mysqli_query($conn, $query);

if (!$res) {
    echo json_encode(["error" => mysqli_error($conn)]);
    exit();
}

// 4. Data fidiitii JSON-dhaan ergi
$messages = mysqli_fetch_all($res, MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($messages);
?>