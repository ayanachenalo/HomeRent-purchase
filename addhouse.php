<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Smart House Finder</title>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="stylemedia.css">
<style>
/* Haala duraan qabdi ture fi lallaafina transition itti dabaluu */
.nav-link {
    color: white; 
    text-decoration: none;
    font-size: 24px; /* Akkuma PPT keenyaa qubee gurguddaa madaalawaa */
    font-weight: 500;
    padding: 5px 10px;
    border-radius: 5px;
    transition: all 0.3s ease-in-out; /* Hover lallafaa akka ta'uuf */
}

/* Yeroo mouse irra kaayan (Hover Effect) */
.nav-link:hover {
    color: #003366; /* Akkuma diizaayinii Smart House Finder */
    background-color: white; /* Duubee adii uuma */
    transform: translateY(-2px); /* Xiqqoo ol jedha (Animation namatti tolu) */
}

.house-add{
   align-items: center;
   display: table;
}
</style>

</head>
  

<body>
<table width="100%" border="0" style="border-collapse: collapse; color: #2A3E5E;">
  <tbody>
    <tr bgcolor="#333" style="color:white;">
      <td height="60" style="padding: 0 20px; font-size: 20px;">
    <?php include 'houseicon.php'; ?>
        <strong style="font-size: 25px;">Smart House Finder</strong>
        <span style="float:right; margin-top: 20px;">
      <div style="float:right; display: flex; align-items: center; gap: 15px;">
    <a href="owner.php" class="nav-link">Home</a>
    <a href="addhouse.php" class="nav-link">Add house</a>
    
</div>
        </span>
      </td>
    </tr>
    
    
    <tr bgcolor="#ECECDE"  style="color: black; font-size: 18px;">
      <td align-items="center"  height="786">
    <h2>Register House</h2>
   <form action="addhouse_db.php" method="POST" enctype="multipart/form-data">
        <table width="400" border="0" bgcolor="#AEBAD4">
      
          <tbody>
            <tr>
              <td width="300"><p>House Type</p></td>
              <td><input type="text" name="textfield2" id="textfield2" style="font-size: 15px; height: 20px; width: 600px;"></td>
            </tr>
            <tr>
              <td width="300">Price</td>
              <td><input type="number" name="number" id="number" style="font-size: 15px; height: 20px; width: 600px;"></td>
            </tr>
            <tr>
              <td width="300" >City Name</td>
              <td><input type="text" name="textfield" id="textfield" style="font-size: 15px; height: 20px; width: 600px;"></td>
            </tr>
            <tr>
              <td width="300">Description</td>
              <td><textarea name="textarea" id="textarea" style="font-size: 15px; height: 200px; width: 600px;"></textarea></td>
            </tr>
    
            <tr>
              <td colspan="2">&nbsp;
      <p>House Image</p>
        <p> <input type="file" name="main_image" id="choosefile" value="Choose File"></p>
        <p>House Video</p>
        <p><input type="file" name="video_path" id="choosefile"  value="Choose File"></p>
        <p><input type="submit" name="submit" id="submit" value="Add" style="font-size: 20px; height: 30px; width: 80px; background-color:deepskyblue; color: aliceblue"></p>
            </td>
           </tr>
         </tbody>
       </table>
     </form> 
    
    </td>
    </tr>
    <tr bgcolor="#333" style="color:white; text-align:center;">
      <td height="100">
        <?php include 'footer.php'; ?>
      </td>
    </tr>
    
  </tbody>
</table>
 <script src="nav.js"></script>
</body>
</html>