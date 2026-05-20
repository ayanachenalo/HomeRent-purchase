<?php
// 1. Session jalqabuu
session_start();
require_once 'dbconnect.php';

// 1. ID manaa URL irraa jiraachuu isaa mirkaneessi
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$house_id = (int)$_GET['id']; // 💡 FIX 1: Query gubbaatti waan gargaaramuuf asitti as deebifameera

// 2. User-ichi login gochuu isaa fi 'role' isaa mirkaneessi
$user_id = $_SESSION['user_id'] ?? 0;

// Role session keessa yoo hin jirre database irraa mirkaneessi (Prepared Statement)
if (!isset($_SESSION['role']) && $user_id > 0) {
    $role_query = "SELECT role FROM users WHERE id = ?";
    $stmt_role = mysqli_prepare($conn, $role_query);
    if ($stmt_role) {
        mysqli_stmt_bind_param($stmt_role, "i", $user_id);
        mysqli_stmt_execute($stmt_role);
        $role_res = mysqli_stmt_get_result($stmt_role);
        if ($role_res && $u_data = mysqli_fetch_assoc($role_res)) {
            $_SESSION['role'] = $u_data['role'];
        }
        mysqli_stmt_close($stmt_role);
    }
}

$user_role = $_SESSION['role'] ?? 'tenant'; 

// 3. LOGIC HOME URL: Abbaan manaa yeroo hunda gara owner.php, kireeffataan gara index.php
if ($user_role === 'owner') {
    $home_url = "owner.php";
} else {
    $home_url = "index.php";
}

// 4. ODEEFFANNOO MANAA FIDUU (Prepared Statement)
$sql = "SELECT houses.*, users.full_name, users.phone_number 
        FROM houses 
        INNER JOIN users ON houses.owner_id = users.id 
        WHERE houses.id = ?";

$stmt_house = mysqli_prepare($conn, $sql);
$house = null;

if ($stmt_house) {
    mysqli_stmt_bind_param($stmt_house, "i", $house_id);
    mysqli_stmt_execute($stmt_house);
    $result = mysqli_stmt_get_result($stmt_house);
    $house = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt_house);
}

if (!$house) {
    header("Location: index.php");
    exit();
}

// 5. --- LOGIC CHAT SIRREEFFAME (Prepared Statement) ---
$me = $user_id; 
$owner_id = $house['owner_id'];
$db_house_id = $house['id'];
$other_party_id = 0;

if ($me == $owner_id) {
    // ABBAA MANAA: Nama inni URL irratti filate (tenant_id) fiduu
    $other_party_id = isset($_GET['tenant_id']) ? (int)$_GET['tenant_id'] : 0;
    
    // Yoo URL irratti tenant_id hin jirre, nama dhumarratti message ergeef "Auto-select" godhi
    if ($other_party_id == 0) {
        $find_last_tenant = "SELECT sender_id FROM messages 
                             WHERE house_id = ? AND receiver_id = ? 
                             ORDER BY created_at DESC LIMIT 1";
        $stmt_last = mysqli_prepare($conn, $find_last_tenant);
        if ($stmt_last) {
            mysqli_stmt_bind_param($stmt_last, "ii", $db_house_id, $me);
            mysqli_stmt_execute($stmt_last);
            $last_res = mysqli_stmt_get_result($stmt_last);
            if ($last_res && mysqli_num_rows($last_res) > 0) {
                $last_data = mysqli_fetch_assoc($last_res);
                $other_party_id = $last_data['sender_id'];
            }
            mysqli_stmt_close($stmt_last);
        }
    }

    // Abbaan manaa yoo kireeffataa dhuunfaa sanaa bane qofa 'status' dubbisameera (1) jedhi
    if ($other_party_id > 0) {
        $update_status = "UPDATE messages SET status = 1 
                          WHERE house_id = ? AND receiver_id = ? AND sender_id = ? AND status = 0";
        $stmt_up = mysqli_prepare($conn, $update_status);
        if ($stmt_up) {
            mysqli_stmt_bind_param($stmt_up, "iii", $db_house_id, $me, $other_party_id);
            mysqli_stmt_execute($stmt_up);
            mysqli_stmt_close($stmt_up);
        }
    }
    $chat_title = "Chat with Tenant";

} else {
    // KIREEFFATAA: Inni yeroo hunda abbaa manaatti erga
    $other_party_id = $owner_id;
    $chat_title = "Write a Message to Owner";

    // Kireeffataan yoo fuula kana bane, ergaa abbaan manaa isaaf erge dubbisameera jedhi
    if ($me > 0) {
        $update_status = "UPDATE messages SET status = 1 
                          WHERE house_id = ? AND receiver_id = ? AND sender_id = ? AND status = 0";
        $stmt_up = mysqli_prepare($conn, $update_status);
        if ($stmt_up) {
            mysqli_stmt_bind_param($stmt_up, "iii", $db_house_id, $me, $owner_id);
            mysqli_stmt_execute($stmt_up);
            mysqli_stmt_close($stmt_up);
        }
    }
}

$house_status = strtolower($house['status'] ?? 'available');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo htmlspecialchars($house['title']); ?> - Smart Home Finder</title>
<link rel="stylesheet" type="text/css" href="stylemedia.css">
<link rel="stylesheet" type="text/css" href="style.css">
<style>
    .details-container { padding: 30px; line-height: 1.6; }
    .gallery-img { width: 20%; max-width: 200px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .price-tag { color: #28a745; font-size: 26px; font-weight: bold; margin: 15px 0; }
    .chat-section { background: #63AF94; padding: 20px; border-radius: 8px; border: 1px solid #ccc; margin: 20px auto; width: 100%; max-width: 800px; }
    .msg-area { width: 100%; max-width: 700px; padding: 10px; margin-top: 10px; border-radius: 5px; box-sizing: border-box; }
    .btn-send { background: #273552; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 10px; font-weight: bold; }
    .btn-call { background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-block; margin-bottom: 10px; font-weight: bold; }
    .chat-box {
        width: 100%; 
        max-width: 700px; 
        margin: 20px auto; 
        height: 400px; 
        overflow-y: auto; 
        border: 1px solid #ddd; 
        background: #DBDBC1; 
        padding: 15px; 
        display: flex; 
        flex-direction: column;
        border-radius: 10px; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        box-sizing: border-box;
    }
    .msg { padding: 8px; border-radius: 5px; margin-bottom: 5px; max-width: 70%; font-size: 14px; }
    .sent { background: #dcf8c6; align-self: flex-end; text-align: right; }
    .received { background: #fff; border: 1px solid #eee; align-self: flex-start; }
    
    .nav-wrapper { display: flex; align-items: center; gap: 15px; }
    .home-link { color: white; text-decoration: none; font-weight: bold; padding: 10px; display: inline-flex; align-items: center; }
    .icon-section { display: flex; align-items: center; }
    .message-icon { margin: 0 !important; display: flex; align-items: center; }
        
    .rent-btn {
        background-color: #27ae60; 
        color: white;
        border: none;
        padding: 12px 25px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .rent-btn:hover { background-color: #219150; transform: translateY(-2px); }

    .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.9); }
    .modal-content { margin: auto; display: block; max-width: 80%; max-height: 80%; margin-top: 50px; border: 5px solid white; border-radius: 10px; }
    .close-modal { position: absolute; top: 20px; right: 35px; color: white; font-size: 40px; font-weight: bold; cursor: pointer; }
    .home-link:hover {
    color: #003366; /* Akkuma diizaayinii Smart House Finder */
    background-color: white; /* Duubee adii uuma */
    transform: translateY(-2px); /* Xiqqoo ol jedha (Animation namatti tolu) */
}

</style>
</head>

<body>
<table width="100%" border="0" style="border-collapse: collapse; color: #2A3E5E;">
  <tbody>
    <tr bgcolor="#333" style="color:white;">
      <td height="75" style="padding: 0 20px;">
          <?php include 'houseicon.php'; ?>
        <strong>Smart Home Finder</strong>
        <span style="float:right;">
          <div class="nav-wrapper">
            <a href="<?php echo $home_url; ?>" class="home-link">Home</a>
            <div class="icon-section">
                <?php include 'icon.php'; ?>
            </div>
          </div>
        </span>       
      </td>
    </tr>

    <tr bgcolor="#ECECDE">
       <td class="details-container" style="text-align: center">
           <h1><?php echo htmlspecialchars($house['title']); ?></h1>
           <p>City: <strong><?php echo htmlspecialchars($house['city']); ?></strong></p>

           <div>
            <img src="<?php echo htmlspecialchars($house['main_image']); ?>" 
                 class="gallery-img" 
                 alt="House image" 
                 style="cursor: pointer; width: 300px;" 
                 onclick="openModal('img', this.src)">
           </div>

           <div class="price-tag"><?php echo number_format($house['price']); ?> ETB</div>

           <h3>House Description</h3>
           <p><?php echo nl2br(htmlspecialchars($house['description'])); ?></p>

           <hr style="border:0; height:1px; background:#ccc; margin:20px 0;">
           
           <p><strong>House Owner:</strong> <?php echo htmlspecialchars($house['full_name']); ?></p>
           <a href="tel:<?php echo $house['phone_number']; ?>" class="btn-call">📞 Call Owner: <?php echo htmlspecialchars($house['phone_number']); ?></a>
           
           <div class="owner-controls" style="text-align: center; margin: 20px 0;">
                <?php if ($me != 0 && $me == $owner_id): ?>
                    <div style="padding: 15px; border: 1px solid #ddd; border-radius: 8px; background: #fefefe; display: inline-block;">
                        <h4 style="margin-top: 0; margin-bottom: 10px;">House Management</h4>
                        
                        <?php if ($house_status == 'available'): ?>
                            <form method="POST" action="update_house_status.php">
                                <input type="hidden" name="house_id" value="<?php echo $house['id']; ?>">
                                <button type="submit" name="rent_now" class="rent-btn">
                                    ✅ Mark as Rented
                                </button>
                            </form>
                        <?php else: ?>
                            <div style="background: #e67e22; color: white; padding: 10px; border-radius: 5px; display: inline-block; font-weight: bold;">
                                🔒 This house is registered as rented
                            </div>
                            <p><a href="reset_status.php?id=<?php echo $house['id']; ?>" style="font-size: 12px; color: #007bff; font-weight: bold; text-decoration: none;">Reset Status (Make Available)</a></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
           </div>
           
           <div class="status-banner" style="text-align: center; margin-bottom: 20px;">
                <?php if ($house_status == 'rented'): ?>
                    <div style="background: #fdf2f2; border: 1px solid #f8b4b4; padding: 15px; border-radius: 10px; display: inline-block; max-width: 600px;">
                        <h2 style="color: #c81e1e; margin: 0;">🔒 House Already Rented</h2>
                        <p style="color: #9b1c1c; margin: 5px 0 0;">We are sorry, this house is currently rented out. Please check other available houses.</p>
                    </div>
                    <style> .chat-box, .chat-section form { display: none; } </style>
                <?php endif; ?>
           </div>
           
           <div class="chat-section">
                <h4 style="color: white; margin-top: 0; font-size: 18px;"><?php echo $chat_title; ?></h4>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="chat-box" id="chatbox">
                        <?php
                        if ($other_party_id > 0) {
                            $msg_sql = "SELECT * FROM messages 
                                        WHERE house_id = ? 
                                        AND ((sender_id = ? AND receiver_id = ?) 
                                        OR (sender_id = ? AND receiver_id = ?)) 
                                        ORDER BY created_at ASC";

                            $stmt_msg = mysqli_prepare($conn, $msg_sql);
                            if ($stmt_msg) {
                                mysqli_stmt_bind_param($stmt_msg, "iiiii", $house_id, $me, $other_party_id, $other_party_id, $me);
                                mysqli_stmt_execute($stmt_msg);
                                $msg_res = mysqli_stmt_get_result($stmt_msg);

                                if(mysqli_num_rows($msg_res) > 0) {
                                    while($m = mysqli_fetch_assoc($msg_res)) {
                                        $class = ($m['sender_id'] == $me) ? 'sent' : 'received';
                                        echo "<div class='msg $class'>".htmlspecialchars($m['message'])."<br><small style='font-size:10px; color:#999;'>".$m['created_at']."</small></div>";
                                    }
                                } else {
                                    echo "<p style='text-align:center; color:#777; font-weight: bold;'>Start a conversation...</p>";
                                }
                                mysqli_stmt_close($stmt_msg);
                            }
                        } else {
                            echo "<p style='color:orange; font-weight: bold;'>Waiting for a tenant to send a message.</p>";
                        }
                        ?>
                    </div>

                    <?php if($other_party_id > 0): ?>
                        <form action="send_message.php" method="POST">
                            <input type="hidden" name="receiver_id" value="<?php echo $other_party_id; ?>">
                            <input type="hidden" name="house_id" value="<?php echo $house['id']; ?>">
                            <textarea name="message" class="msg-area" placeholder="Type your message here..." required></textarea><br>
                            <button type="submit" name="send" class="btn-send">Send Message</button>
                            <div style="margin-top: 15px;">
                                <a href="report.php?house_id=<?php echo $house['id']; ?>" style="color: #e74c3c; font-weight: bold; text-decoration: none; font-size: 14px;">⚠️ Report This House</a>
                            </div>
                        </form>
                    <?php endif; ?>
               
                    <script>
                        var cb = document.getElementById("chatbox");
                        if(cb) { cb.scrollTop = cb.scrollHeight; }
                    </script>

                <?php else: ?>
                    <p style="color:#c81e1e; font-weight: bold;">Please <a href="login.php" style="color: #273552;">Login</a> to chat or contact the owner.</p>
                <?php endif; ?>
           </div>
           <div style="margin-top:30px;">
                <h3>House Tour Video</h3>
                <?php if(!empty($house['video_path'])): ?>
                    <div style="cursor: pointer; background: #273552; color: white; display: inline-block; padding: 12px 25px; border-radius: 5px; font-weight: bold;" 
                         onclick="openModal('video', '<?php echo htmlspecialchars($house['video_path']); ?>')">
                         ▶ Play House Video
                    </div>
                <?php else: ?>
                    <p style="color: #777;">No tour video available for this house.</p>
                <?php endif; ?>
           </div>

            <div id="myModal" class="modal">
                <span class="close-modal" onclick="closeModal()">&times;</span>
                <div id="modal-body"></div>
            </div>
            <script src="nav.js"></script>
       </td>
    </tr>

    <tr bgcolor="#333" style="color:white; text-align:center;">
      <td height="100">
          <?php include 'footer.php'; ?>
      </td>
    </tr>
  </tbody>
</table>

<script>
function openModal(type, src) {
    var modal = document.getElementById("myModal");
    var modalBody = document.getElementById("modal-body");
    
    modal.style.display = "block";
    
    if (type === 'img') {
        modalBody.innerHTML = '<img src="' + src + '" class="modal-content">';
    } else if (type === 'video') {
        modalBody.innerHTML = '<video class="modal-content" controls autoplay>' +
                              '<source src="' + src + '" type="video/mp4">' +
                              '</video>';
    }
}

function closeModal() {
    var modal = document.getElementById("myModal");
    var modalBody = document.getElementById("modal-body");
    modal.style.display = "none";
    modalBody.innerHTML = ""; 
}

window.onclick = function(event) {
    var modal = document.getElementById("myModal");
    if (event.target == modal) {
        closeModal();
    }
}
</script>
</body>
</html>