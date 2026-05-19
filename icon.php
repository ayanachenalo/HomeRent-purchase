<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'dbconnect.php'; 

$full_name = $_SESSION['full_name'] ?? "Guest";
$user_id = $_SESSION['user_id'] ?? 0;

// --- A. INITIALS QOPHEESSUU ---
$words = explode(" ", trim($full_name));
$initials = "";
if (count($words) > 0 && !empty($words[0])) {
    foreach ($words as $w) {
        if(isset($w[0])) { $initials .= $w[0]; }
    }
} else {
    $initials = "GU";
}
$initials = substr(strtoupper($initials), 0, 2);

// --- B. ERGAALLAA HAARAA DATABASE IRRAA FIDUU ---
$unread_count = 0;
$messages_html = "";

if ($user_id > 0) {
    // 1. Lakkoofsa ergaa hin dubbifamne (status = 0) qofa fida
    $count_query = "SELECT COUNT(*) as total FROM messages WHERE receiver_id = '$user_id' AND status = 0";
    $count_res = mysqli_query($conn, $count_query);
    if ($count_res) {
        $count_data = mysqli_fetch_assoc($count_res);
        $unread_count = $count_data['total'];
    }

    // 2. Ergaa 20 dhumarratti dhufe fida (status 0 dura dursa)
    $msg_query = "SELECT m.*, u.full_name as sender_name 
                  FROM messages m 
                  JOIN users u ON m.sender_id = u.id 
                  WHERE m.receiver_id = '$user_id' 
                  ORDER BY m.status ASC, m.created_at DESC LIMIT 20";
                  
    $msg_res = mysqli_query($conn, $msg_query);

    if ($msg_res && mysqli_num_rows($msg_res) > 0) {
        while ($m = mysqli_fetch_assoc($msg_res)) {
            $link = "view_details.php?id=" . $m['house_id'] . "&tenant_id=" . $m['sender_id'];
            
            // Yoo ergaan hin dubbifamne ta'e halluu bultii (blue tint) kennaaf
            $unread_style = ($m['status'] == 0) ? 'background-color: #f0f7ff; font-weight: bold;' : '';
            
            $messages_html .= '<a href="' . $link . '" class="inbox-item" style="' . $unread_style . '">';
            $messages_html .= '<strong>' . htmlspecialchars($m['sender_name']) . ':</strong> ';
            $messages_html .= substr(htmlspecialchars($m['message']), 0, 45) . '...';
            $messages_html .= '</a>';
        }
    } else {
        $messages_html = '<p style="padding:20px; font-size:12px; color:#999; text-align:center;">Ergaan hin jiru.</p>';
    }
}

?>

<style>
/* Akka Language jalatti gadi dhangala'u */
.nested-dropdown {
    width: 100%;
}

.lang-submenu {
    display: none; 
    background-color: #fcfcfc; /* Halluu xiqqoo adda ta'e */
    min-width: 100%;
    
    /* Afaan 2-3 qofa mul'isee akka scroll ta'uuf */
    max-height: 100px; 
    overflow-y: auto;
    
    border-left: 3px solid #3498db; /* Halluu bultii bitaatti dabalaniif */
    margin-top: 5px;
    z-index: 100;
}

/* Yeroo klik ta'u ni banama */
.lang-submenu.show-lang {
    display: block !important;
}

.lang-submenu a {
    padding: 8px 25px !important; /* Xiqqoo gara mirgaatti dhiibuuf */
    font-size: 13px;
    color: #555;
    display: block;
    text-decoration: none;
    border-bottom: 1px solid #f1f1f1;
}

.lang-submenu a:hover {
    background-color: #f1f1f1;
    color: #000;
}

/* Scrollbar akka bareeduuf */
.lang-submenu::-webkit-scrollbar { width: 4px; }
.lang-submenu::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
</style>

<!-- HTML fi JS akkuma kanaan duraatti itti fufa... -->

<!-- --- HTML DISPLAY --- -->
<div style="display: flex; align-items: center; gap: 20px;">

    <!-- 1. Notification Area -->
    <div class="notification-container">
        <div class="message-icon" onclick="toggleInbox()">
           ✉️<?php if($unread_count > 0): ?>
                <span class="badge"><?php echo $unread_count; ?></span>
            <?php endif; ?>
        </div>
        
        <div id="inbox-dropdown" class="inbox-dropdown">
            <div class="inbox-header">Ergaa Haaraa</div>
            <?php echo $messages_html; ?>
           
        </div>
    </div>

    <!-- 2. Profile Area -->
    <div class="user-profile" onclick="toggleMenu()">
        <div class="avatar-circle">
            <?php echo $initials; ?>
        </div>

        <div class="dropdown-menu" id="myDropdown">
            <div style="padding: 10px 15px; border-bottom: 1px solid #eee; font-weight: bold; color: #333; font-size:13px;">
                <?php echo htmlspecialchars($full_name); ?>
            </div>
            <a href="pro.php">Profile</a>
            <a href="setti.php">Settings</a>
            <a href="change_password.php">Change Password</a>
            <a href="privacy_policy.php">Privacy Policy</a>
			<a href="feedback.php">Feedback</a>
			<!-- Bakka linkii language duraanii kanaan bakka buusi -->
     <div class="nested-dropdown">
        <a href="javascript:void(0)" onclick="toggleLangMenu(event)" style="display: flex; justify-content: space-between; align-items: center;">
            Language <span style="font-size: 25px;">▾</span>
        </a>
        
        <div id="langDropdown" class="lang-submenu">
            <a href="#">Afaan Oromoo</a>
            <a href="#">English</a>
            <a href="#">አማርኛ</a>
            <a href="#">French</a>
            <a href="#">Arabic</a>
        </div>
    </div>
            <hr style="margin:0; border: 0; border-top: 1px solid #eee;">
            <a href="logout.php" style="color: #e74c3c;">Logout</a>
        </div>
    </div>

</div>


