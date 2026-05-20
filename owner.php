<?php include 'index_db.php'; ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Smart Home Finder - Owner Dashboard</title>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="stylemedia.css">
<style>
    /* Bakka manneen itti mul'atan akka auto-increase ta'uuf */
    .house-container {
        display: flex;
        flex-wrap: wrap; /* Manni yoo baay'ate ofumaan gadi bu'a */
        gap: 20px;
        padding: 20px;
        justify-content: center;
    }
    .house-card {
        width: 300px;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
    }
    .media-container {
        width: 100%;
        height: 200px;
        background: #eee;
        position: relative;
    }
    .media-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .video-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(39, 53, 82, 0.9);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: bold;
    }
    .card-info { padding: 15px; text-align: left; }
    .btn-details {
        display: block;
        text-align: center;
        background: #273552;
        color: white;
        padding: 10px;
        text-decoration: none;
        border-radius: 4px;
        font-weight: bold;
        margin-top: 10px;
    }
    /* Haala duraan qabdi ture fi lallaafina transition itti dabaluu */
    .nav-link {
        color: white; 
        text-decoration: none;
        font-size: 20px; /* Qubee gurguddaa madaalawaa */
        font-weight: bold;
        padding: 5px 15px;
        border-radius: 5px;
        transition: all 0.3s ease-in-out; /* Hover lallafaa akka ta'uuf */
    }

    /* Yeroo mouse irra kaayan (Hover Effect) */
    .nav-link:hover {
        color: #00cbc6; /* Gosa cuquliisa bareedaa (Cyan) */
        background-color: rgba(255, 255, 255, 0.1); /* Duubee xiqqoo calaqqisu */
        transform: translateY(-2px); /* Xiqqoo ol jedha */
    }
</style>
</head>

<body>
<table width="100%" border="0" style="border-collapse: collapse;">
  <tbody>
    <tr bgcolor="#333" style="color:white;">
      <td height="80" style="padding: 0 20px; font-size: 24px;">
          <?php include 'houseicon.php'; ?>
        <strong>Smart Home Finder</strong>
        
        <div style="float:right; display: flex; align-items: center; gap: 15px;">
            <a href="owner.php" class="nav-link">Home</a>
            <a href="addhouse.php" class="nav-link">Add House</a>
                 
            <?php include 'icon.php'; ?>
        </div>
      </td>
    </tr>

    <tr bgcolor="#606266">
      <td height="100" align="center" style="font-size:20px; color:white;">
          <form action="owner.php" method="GET">
              <strong>Search by City Name:</strong> 
              <input type="text" name="city" value="<?php echo htmlspecialchars($city ?? ''); ?>" placeholder="e.g., Bule Hora..." style="padding:8px 8px 4px 8px; width:600px; border-radius: 4px; border: none;">
              <strong>Price:</strong>
              <select name="price" style="padding:8px; border-radius: 4px;">
                <option value="1000000" <?php echo (isset($price) && $price == 1000000) ? 'selected' : ''; ?>>All</option>
                <option value="5000" <?php echo (isset($price) && $price == 5000) ? 'selected' : ''; ?>>5,000 ETB</option>
                <option value="10000" <?php echo (isset($price) && $price == 10000) ? 'selected' : ''; ?>>10,000 ETB</option>
              </select>
              <button type="submit" style="padding:8px 20px; background:#28a745; color:white; border:none; cursor:pointer; font-weight:bold; border-radius: 4px;">Search</button>
          </form>
      </td>
    </tr>

    <tr bgcolor="#ECECDE">
      <td class="content-area" style="min-height: 500px; vertical-align: top; padding: 20px;">
        <h2 style="text-align: center; color: #2A3E5E;">My Listed Houses</h2>
        
        <div class="house-container">
            <?php if(!empty($houses)): ?>
                <?php foreach($houses as $h): ?>
                <div class="house-card">
                    <div class="media-container">
                        <?php 
                        // 💡 FIX 2: Column maqaa database wajjin wal simsiisuu ($h['main_image'])
                        $img_src = $h['main_image'] ?? $h['media_path'] ?? '';
                        $video_file = $h['video_path'] ?? '';
                        ?>

                        <img src="<?php echo htmlspecialchars($img_src); ?>" alt="House Image" onerror="this.src='uploads/images/default.jpg';">
                        
                        <?php if(!empty($video_file)): ?>
                            <div class="video-badge">🎬 Has Video</div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-info">
                        <h3><?php echo htmlspecialchars($h['title']); ?></h3>
                        <p style="color:green; font-weight:bold;"><?php echo htmlspecialchars(number_format($h['price'])); ?> ETB / Month</p>
                        <p style="font-size:13px; color:#666; font-weight: bold;">📍 City: <?php echo htmlspecialchars($h['city']); ?></p>
                        
                        <a href="view_details.php?id=<?php echo $h['id']; ?>" class="btn-details">View Details</a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="width:100%; text-align:center; padding:100px;">
                    <p style="font-weight: bold; font-size: 18px; color: #721c24;">No houses found matching your criteria. Please try again.</p>
                </div>
            <?php endif; ?>
        </div>
        <script src="nav.js"></script>
      </td>
    </tr>

    <tr bgcolor="#222" style="color:white; font-size:20px; text-align: center;">
        <td>
            <?php include 'footer.php'; ?>
        </td>
    </tr>
  </tbody>
</table>
</body>
</html>