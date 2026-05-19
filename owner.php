<?php include 'index_db.php'; ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Smart Home Finder</title>
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
    }
    .media-container {
        width: 100%;
        height: 200px;
        background: #eee;
    }
    .media-container img, .media-container video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .card-info { padding: 15px; }
    .btn-details {
        display: block;
        text-align: center;
        background: #273552;
        color: white;
        padding: 10px;
        text-decoration: none;
        border-radius: 4px;
    }
</style>
</head>

<body>
<table width="100%" border="0" style="border-collapse: collapse;">
  <tbody>
    <tr bgcolor="#333" style="color:white;">
      <td height="60" style="padding: 0 20px;">
		  <?php include 'houseicon.php'; ?>
        <strong>Smart Home Finder</strong>
        
        <div style="float:right; display: flex; align-items: center; gap: 15px;">
            <a href="owner.php" style="color:white; text-decoration:none;">Home</a>
            <a href="addhouse.php" style="color:white; text-decoration:none;">Add house</a>
             
            <?php include 'icon.php'; ?>
        </div>
      </td>
    </tr>
	  
	  
	  
	  

    <tr bgcolor="#606266">
      <td height="100" align="center"style="font-size:20px;color:white">
          <form action="index.php" method="GET">
              <strong>Maqaa magaala galchuun barbaadi:</strong> 
              <input type="text" name="city" placeholder="Fkn: Bule Hora..." style="padding:8px 8px 4px 8px;width:850px;">
              <strong>Gatii:</strong>
              <select name="price" style="padding:8px;">
                <option value="1000000">Hunda</option>
                <option value="5000">5,000 ETB</option>
                <option value="10000">10,000 ETB</option>
              </select>
              <button type="submit" style="padding:8px 20px; background:#28a745; color:white; border:none; cursor:pointer;">Barbaadi</button>
          </form>
      </td>
    </tr>

    <tr bgcolor="#ECECDE">
      <td class="content-area" style="min-height: 500px; vertical-align: top; padding: 20px;">
        <h2 style="text-align: center;">Manneen Amma Kiraa Irra Jiran</h2>
        
        <div class="house-container">
            <?php if(!empty($houses)): ?>
                <?php foreach($houses as $h): ?>
                <div class="house-card">
                    <div class="media-container">
                        <?php if($h['media_type'] == 'video'): ?>
                            <video controls>
                                <source src="<?php echo $h['media_path']; ?>" type="video/mp4">
                            </video>
                        <?php else: ?>
                            <img src="<?php echo $h['media_path']; ?>" alt="House Image">
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-info">
                        <h3><?php echo htmlspecialchars($h['title']); ?></h3>
                        <p style="color:green; font-weight:bold;"><?php echo number_format($h['price']); ?> ETB / Ji'atti</p>
                        <p style="font-size:13px; color:#666;">📍 <?php echo htmlspecialchars($h['city']); ?></p>
                        
                        <a href="view_details.php?id=<?php echo $h['id']; ?>" class="btn-details">Description</a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="width:100%; text-align:center; padding:100px;">
                    <p>Manni barbaadame hin argamne. Maaloo irra deebi'ii yaali.</p>
                </div>
            <?php endif; ?>
        </div>
		  <script src="nav.js"></script>
      </td>
    </tr>

     <tr bgcolor="#222" style="color:white;" style=" font-size:20px;">
    <td>
        <?php include 'footer.php'; ?>
    </td>
</tr>
  </tbody>
</table>
</body>
</html>