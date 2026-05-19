<?php
session_start();
require_once 'dbconnect.php';

// 1. ID manaa URL irraa jiraachuu isaa mirkaneessi
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// 2. User-ichi login gochuu isaa fi 'role' isaa mirkaneessi
$user_id = $_SESSION['user_id'] ?? 0;

// Role session keessa yoo hin jirre database irraa mirkaneessi (Professionalism-f)
if (!isset($_SESSION['role']) && $user_id > 0) {
    $role_query = "SELECT role FROM users WHERE id = '$user_id'";
    $role_res = mysqli_query($conn, $role_query);
    if ($role_res && $u_data = mysqli_fetch_assoc($role_res)) {
        $_SESSION['role'] = $u_data['role'];
    }
}

$user_role = $_SESSION['role'] ?? 'tenant'; 

// 3. LOGIC HOME URL: Abbaan manaa yeroo hunda gara owner.php, kireeffataan gara index.php
if ($user_role === 'owner') {
    $home_url = "owner.php";
} else {
    $home_url = "index.php";
}

$house_id = mysqli_real_escape_string($conn, $_GET['id']);

// 4. ODEEFFANNOO MANAA FIDUU
$sql = "SELECT houses.*, users.full_name, users.phone_number 
        FROM houses 
        INNER JOIN users ON houses.owner_id = users.id 
        WHERE houses.id = '$house_id'";

$result = mysqli_query($conn, $sql);
$house = mysqli_fetch_assoc($result);

if (!$house) {
    header("Location: index.php");
    exit();
}

// 5. --- LOGIC CHAT SIRREEFFAME (PROFESSIONAL VERSION) ---
$me = $user_id; // Session irraa kan dhufe
$owner_id = $house['owner_id'];
$db_house_id = $house['id'];

if ($me == $owner_id) {
    // ABBAA MANAA: Nama inni URL irratti filate (tenant_id) fiduu
    $other_party_id = isset($_GET['tenant_id']) ? mysqli_real_escape_string($conn, $_GET['tenant_id']) : 0;
    
    // Yoo URL irratti tenant_id hin jirre, nama dhumarratti message ergeef "Auto-select" godhi
    if ($other_party_id == 0) {
        $find_last_tenant = "SELECT sender_id FROM messages 
                            WHERE house_id = '$db_house_id' AND receiver_id = '$me' 
                            ORDER BY created_at DESC LIMIT 1";
        $last_res = mysqli_query($conn, $find_last_tenant);
        if ($last_res && mysqli_num_rows($last_res) > 0) {
            $last_data = mysqli_fetch_assoc($last_res);
            $other_party_id = $last_data['sender_id'];
        }
    }

    // Abbaan manaa yoo kireeffataa dhuunfaa sanaa bane qofa 'status' dubbisameera (1) jedhi
    if ($other_party_id > 0) {
        $update_status = "UPDATE messages SET status = 1 
                          WHERE house_id = '$db_house_id' 
                          AND receiver_id = '$me' 
                          AND sender_id = '$other_party_id'
                          AND status = 0";
        mysqli_query($conn, $update_status);
    }
    $chat_title = "Kireeffataa wajjin haasa'i";

} else {
    // KIREEFFATAA: Inni yeroo hunda abbaa manaatti erga
    $other_party_id = $owner_id;
    $chat_title = "Abbaa Manaaf Ergaa Barreessi";

    // Kireeffataan yoo fuula kana bane, ergaa abbaan manaa isaaf erge dubbisameera jedhi
    if ($me > 0) {
        $update_status = "UPDATE messages SET status = 1 
                          WHERE house_id = '$db_house_id' 
                          AND receiver_id = '$me' 
                          AND sender_id = '$owner_id'
                          AND status = 0";
        mysqli_query($conn, $update_status);
    }
}
// ... koodii database houses fidu ...
$result = mysqli_query($conn, $sql);
$house = mysqli_fetch_assoc($result);

// Variables kanaan gadii asitti mirkaneessi
$me = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; 
$owner_id = $house['owner_id']; 
$house_status = strtolower($house['status'] ?? 'available'); // status yoo jiraachuu baate default 'available'


?>

<!-- HTML irratti 'Home' link kee bifa kanaan fayyadami -->
<!-- <a href="<?php echo $home_url; ?>">Home</a> -->

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $house['title']; ?> - Smart Home Finder</title>
<link rel="stylesheet" type="text/css" href="stylemedia.css">
<link rel="stylesheet" type="text/css" href="style.css">
<style>
    .details-container { padding: 30px; line-height: 1.6; }
    .gallery-img { width: 20%; max-width: 200px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .price-tag { color: #28a745; font-size: 26px; font-weight: bold; margin: 15px 0; }
    .chat-section { background: #63AF94; padding: 20px; border-radius: 8px; border: 1px solid #ccc;  margin: 20px auto;; width: 100%;  max-width: 800px;  }
    .msg-area {  width: 100%;  max-width: 700px;  padding: 10px; margin-top: 10px; border-radius: 5px; }
    .btn-send { background: #273552; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 10px; }
    .btn-call { background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-block; margin-bottom: 10px; }
    .chat-box {
	  /* 1. Bal'ina isaa flexible gochuu */
    width: 100%; 
    max-width: 700px; /* Bal'ina inni qabaachuu danda'u kan dhumaa */
    
    /* 2. Gidduu akka galu gochuuf */
    margin: 20px auto; 
    
    /* 3. Haala keessaa */
    height: 400px; 
    overflow-y: auto; 
    border: 1px solid #ddd; 
    background: #DBDBC1; 
    padding: 15px; 
    display: flex; 
    flex-direction: column;
    border-radius: 10px; /* Qarqara isaa xiqqoo laaffisuuf */
    box-shadow: 0 4px 10px rgba(0,0,0,0.1); /* Miidhaginaaf */
 }
    .msg { padding: 8px; border-radius: 5px; margin-bottom: 5px; max-width: 70%; font-size: 14px; }
    .sent { background: #dcf8c6; align-self: flex-end; text-align: right; }
    .received { background: #fff; border: 1px solid #eee; align-self: flex-start; }
	
	/*--kun icon page kana qofaafi--*/
.nav-wrapper {
    display: flex;             /* Sarara tokko irratti isaan fida */
    align-items: center;       /* Gidduu (vertically) wal qixxeessa */
    gap: 15px;                 /* Gidduu Home fi Icon fageenya kenna */
}

.home-link {
    color: white; 
    text-decoration: none; 
    font-weight: bold; 
    padding: 10px;
    display: inline-flex;      /* Akka icon-nii wajjin wal simu */
    align-items: center;
}

.icon-section {
    display: flex;
    align-items: center;
}

/* Yoo icon-ni sun gadi bu'eera ta'e (margin correction) */
.message-icon {
    margin: 0 !important;      /* Margin dabalataa yoo jiraate balleessa */
    display: flex;
    align-items: center;
}
	
	.rent-btn {
    background-color: #27ae60; /* Halluu magariisa */
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

.rent-btn:hover {
    background-color: #219150;
    transform: translateY(-2px); /* Yeroo irra deeman xiqqoo ol jedha */
}
	/* Popup (Modal) background */
.modal {
    display: none; 
    position: fixed; 
    z-index: 2000; 
    left: 0; top: 0;
    width: 100%; height: 100%;
    background-color: rgba(0,0,0,0.9); /* Gurraacha xiqqoo calaqqisu */
}

/* Meeshaa Popup keessa jiru (Image/Video) */
.modal-content {
    margin: auto;
    display: block;
    max-width: 80%;
    max-height: 80%;
    margin-top: 50px;
    border: 5px solid white;
    border-radius: 10px;
}

/* Button cufuuf gargaaru */
.close-modal {
    position: absolute;
    top: 20px; right: 35px;
    color: white; font-size: 40px;
    font-weight: bold; cursor: pointer;
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
    <!-- Linkii Home -->
    <a href="<?php echo $home_url; ?>" class="home-link">Home</a>

    <!-- Icon Messages (icon.php) -->
    <div class="icon-section">
        <?php include 'icon.php'; ?>
    </div>
</div>
		  </span>		
      </td>
    </tr>

    <tr bgcolor="#ECECDE">
       <td class="details-container" style="text-align: center"><h1><?php echo $house['title']; ?></h1>
           <p>Magaalaa: <strong><?php echo $house['city']; ?></strong></p>

           <!-- Suuraa -->
      <div>
    <img src="uploads/images/<?php echo $house['main_image']; ?>" 
         class="gallery-img" 
         alt="House image" 
         style="cursor: pointer; width: 300px;" 
         onclick="openModal('img', this.src)">
      </div>

           <div class="price-tag"><?php echo number_format($house['price']); ?> ETB</div>

           <h3> House description</h3>
           <p><?php echo nl2br($house['description']); ?></p>

           <hr>
           
           <p><strong>Abbaa Manaa:</strong> <?php echo $house['full_name']; ?></p>
		 
         <a href="tel:<?php echo $house['phone_number']; ?>" class="btn-call">Bilbili: <?php echo $house['phone_number']; ?></a>
		   
		 
		   <!-- Akkuma gubbaatti ilaalle, Chat Section-naan ala gubbaa irratti gala -->
<div class="owner-controls" style="text-align: center; margin: 20px 0;">
    <?php if ($me != 0 && $me == $owner_id): ?>
        <div style="padding: 15px; border: 1px solid #ddd; border-radius: 8px; background: #fefefe;">
            <h4 style="margin-bottom: 10px;">Bulchiinsa Manaa</h4>
            
            <?php if ($house_status == 'available'): ?>
                <!-- MANNI KUN AMMAYYUU NI JIRA YOO TA'E BUTTON KUN MUL'ATA -->
                <form method="POST" action="update_house_status.php">
                    <input type="hidden" name="house_id" value="<?php echo $house['id']; ?>">
                    <button type="submit" name="rent_now" class="rent-btn" style="background: #27ae60; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
                        ✅ Manni kun kireeffameera jedhi
                    </button>
                </form>
            <?php else: ?>
                <!-- YOO MANNI KUN KANAAN DURA RENTED TA'E KANATU MUL'ATA -->
                <div style="background: #e67e22; color: white; padding: 10px; border-radius: 5px; display: inline-block; font-weight: bold;">
                    🔒 Manni kun kireeffameera jedhamee galmaa'eera
                </div>
                <!-- Yoo dogoggoraan kireeffameera jettee deebisuu barbaadde, link gadii kanaan Reset godhi -->
                <p><a href="reset_status.php?id=<?php echo $house['id']; ?>" style="font-size: 11px; color: blue;">Deebisi (Available godhi)</a></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
		   
 <div class="status-banner" style="text-align: center; margin-bottom: 20px;">
    <?php if ($house_status == 'rented'): ?>
        <!-- Kireeffataa fi Nama kamiyyuu dabalatee kan mul'atu -->
        <div style="background: #fdf2f2; border: 1px solid #f8b4b4; padding: 15px; border-radius: 10px;">
            <h2 style="color: #c81e1e; margin: 0;">🔒 Manni Kun Kireeffameera</h2>
            <p style="color: #9b1c1c; margin: 5px 0 0;">Dihifama, manni kun ammaan tana kireeffameera. Kan biraa barbaadi.</p>
        </div>
        
        <!-- Yoo kireeffataan mana kireeffame sana kallaattiin arge, chat box dhowwuuf: -->
        <style> .chat-box, .message-form { display: none; } </style>
    <?php endif; ?>
</div>
		   
		 <!-- Fuula manneen tarreeffaman irratti -->
 
		   <!-- --- CHAT SECTION SIRREEFFAME --- -->
           <div class="chat-section">
			   
                <h4><?php echo $chat_title; ?></h4>
                <?php if(isset($_SESSION['user_id'])): ?>
			     <div class="chat-box" id="chatbox">
                        <?php
                        if ($other_party_id > 0) {
                            $msg_sql = "SELECT * FROM messages 
                                        WHERE house_id = '$house_id' 
                                        AND ((sender_id = '$me' AND receiver_id = '$other_party_id') 
                                        OR (sender_id = '$other_party_id' AND receiver_id = '$me')) 
                                        ORDER BY created_at ASC";

                            $msg_res = mysqli_query($conn, $msg_sql);

                            if(mysqli_num_rows($msg_res) > 0) {
                                while($m = mysqli_fetch_assoc($msg_res)) {
                                    $class = ($m['sender_id'] == $me) ? 'sent' : 'received';
                                    echo "<div class='msg $class'>".htmlspecialchars($m['message'])."<br><small style='font-size:10px; color:#999;'>".$m['created_at']."</small></div>";
                                }
                            } else {
                                echo "<p style='text-align:center; color:#ccc;'>Haasawa jalqabaa...</p>";
                            }
                        } else {
                            echo "<p style='color:orange;'>Kireeffataan tokko ergaa akka siif ergu eegi.</p>";
                        }
                        ?>
                    </div>

                    <?php if($other_party_id > 0): ?>
                    <form action="send_message.php" method="POST">
                        <input type="hidden" name="receiver_id" value="<?php echo $other_party_id; ?>">
                        <input type="hidden" name="house_id" value="<?php echo $house['id']; ?>">
                        <textarea name="message" class="msg-area" placeholder="Ergaa asitti barreessi..." required></textarea><br>
                        <button type="submit" name="send" class="btn-send">Ergaa Ergi</button>
						<!-- Bakka $h['id'] jiru sana $house['id'] godhi -->
              <a href="report.php?house_id=<?php echo $house['id']; ?>" style="color: red; font-weight: bold; text-decoration: none;">⚠️ Report This House</a>
						
                    </form>
			   
                    <?php endif; ?>
			   
			      <script>
                        var cb = document.getElementById("chatbox");
                        cb.scrollTop = cb.scrollHeight;
                    </script>

                <?php else: ?>
                    <p style="color:red;">Ergaa barreessuuf dura <a href="login.php">Login</a></p>
                <?php endif; ?>
			   
			
			  </div>
           <!-- --- END CHAT SECTION --- -->
		  
		   
           <!-- Viidiyoo -->
            <div style="margin-top:30px;">
                   <h3>Viidiyoo Manaa</h3>
                     <?php if(!empty($house['video_path'])): ?>
                  <div style="cursor: pointer; background: #333; color: white; display: inline-block; padding: 10px; border-radius: 5px;" 
                onclick="openModal('video', 'uploads/videos/<?php echo $house['video_path']; ?>')">
                ▶ Viidiyoo Taphaachisi
                 </div>
                  <?php else: ?>
               <p>Viidiyoon hin jiru.</p>
             <?php endif; ?>
              </div>

<!-- KAN KANA GADIITTII: Meeshaa Popup-ichaaf qophaa'e -->
<div id="myModal" class="modal">
    <span class="close-modal" onclick="closeModal()">&times;</span>
    <div id="modal-body">
        <!-- Suuraan ykn Viidiyoon asitti kallaattiin JavaScript-iin galama -->
    </div>
</div>
		   <script src="nav.js"></script>
       </td>
    </tr>

    <tr bgcolor="#333" style="color:white; text-align:center;">
      <td height="100">
		  <?php include 'footer.php' ?>
       
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
    modalBody.innerHTML = ""; // Viidiyoon akka dhiibuuf
}

// Yoo bakka duwwaa cuqaasan akka cufamuuf
window.onclick = function(event) {
    var modal = document.getElementById("myModal");
    if (event.target == modal) {
        closeModal();
    }
}
</script>
</body>
</html>