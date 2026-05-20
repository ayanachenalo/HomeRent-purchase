<?php include 'index_db.php'; ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Smart Home Finder</title>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="stylemedia.css">

<style>
    /* Card-ichaa fi Suuraan bifa sirriin akka mul'ataniif */
    .house-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        padding: 20px 0;
    }
    .house-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        width: 300px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .media-container {
        width: 100%;
        height: 200px;
        background: #ddd;
        position: relative;
    }
    .media-container img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Suuraan utuu hin dabiin bifa sirriin akka guutu */
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
    .card-info {
        padding: 15px;
        text-align: left;
    }
    .btn-details {
        display: block;
        text-align: center;
        background: #273552;
        color: white;
        padding: 10px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        margin-top: 10px;
    }
    .nad-link {
    color: white; 
    text-decoration: none;
    font-size: 24px; /* Akkuma PPT keenyaa qubee gurguddaa madaalawaa */
    font-weight: 500;
    padding: 5px 10px;
    border-radius: 5px;
    transition: all 0.3s ease-in-out; /* Hover lallafaa akka ta'uuf */
}
    .nad-link:hover {
    color: #003366; /* Akkuma diizaayinii Smart House Finder */
    background-color: white; /* Duubee adii uuma */
    transform: translateY(-2px); /* Xiqqoo ol jedha (Animation namatti tolu) */
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
        
        <div style="float:right; display: flex; align-items: center; gap: 15px; font-weight:bold;">
            <a href="index.php"  class="nad-link">Home</a>
            <a href="regist.php" class="nad-link">Register</a>
            
            <?php include 'icon.php'; ?>
        </div>
    
      </td>
    </tr>
    
    <tr bgcolor="#606266">
      <td height="100" align="center" style="font-size:20px; color:white;">
          <form action="index.php" method="GET">
              <strong>Search by City Name:</strong> 
              <input type="text" name="city" value="<?php echo htmlspecialchars($city ?? ''); ?>" placeholder="e.g., Bule Hora..." style="padding:8px 8px 4px 8px; width:600px; border-radius: 4px; border: none;">
              <strong>Price:</strong>
              <select name="price" style="padding:8px; border-radius: 4px;">
                <option value="1000000" <?php echo ($price == 1000000) ? 'selected' : ''; ?>>All</option>
                <option value="5000" <?php echo ($price == 5000) ? 'selected' : ''; ?>>5,000 ETB</option>
                <option value="10000" <?php echo ($price == 10000) ? 'selected' : ''; ?>>10,000 ETB</option>
              </select>
              <button type="submit" style="padding:8px 20px; background:#28a745; color:white; border:none; cursor:pointer; font-weight:bold; border-radius: 4px;">Search</button>
          </form>
      </td>
    </tr>

    <tr bgcolor="#ECECDE">
      <td class="content-area" style="min-height: 500px; vertical-align: top; padding: 20px;">
        <h2 style="text-align: center; color: #2A3E5E;">Available Houses for Rent</h2>
        
        <div class="house-container">
            
            <?php if(!empty($houses)): ?>
                <?php foreach($houses as $h): ?>
                <div class="house-card">
                    <div class="media-container">
                        <?php 
                        // 💡 FIX 1: index_db.php irratti path guutuu waan ijaarruuf, asirratti kallaattiin fudhanna
                        $img_src = $h['main_image']; 
                        $video_file = $h['video_path'] ?? '';
                        ?>

                        <img src="<?php echo htmlspecialchars($img_src); ?>" alt="House Image" onerror="this.src='uploads/images/default.jpg';">
                        
                        <?php if(!empty($video_file)): ?>
                            <div class="video-badge">🎬 Video Tour</div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-info">
                        <h3><?php echo htmlspecialchars($h['title']); ?></h3>
                        <p style="color:green; font-weight:bold;"><?php echo htmlspecialchars(number_format($h['price'])); ?> ETB / Month</p>
                        <p style="font-size:13px; color:#666; font-weight: bold;">📍 City: <?php echo htmlspecialchars($h['city']); ?></p>
                        
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="view_details.php?id=<?php echo $h['id']; ?>" class="btn-details">View Details</a>
                        <?php else: ?>
                            <a href="login.php?redirect=view_details.php?id=<?php echo $h['id']; ?>" class="btn-details" style="background: #ffc107; color: black;">Login to View Details</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="width:100%; text-align:center; padding:100px;">
                    <p style="font-weight: bold; font-size: 18px; color: #721c24;">No houses found matching your criteria. Please try again.</p>
                </div>
            <?php endif; ?>
        </div>
      </td>
    </tr>
    
    <tr bgcolor="#222" style="color:white; font-size:20px; text-align: center;">
      <td>
         <?php include 'footer.php'; ?>
      </td>
    </tr>
  </tbody>
</table>
<script src="nav.js"></script>
</body>
</html>