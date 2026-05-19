<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Smart Home Finder</title>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="stylemedia.css">
</head>
	

<body>
<table width="100%" border="0" style="border-collapse: collapse; color: #2A3E5E;">
  <tbody>
    <tr bgcolor="#333" style="color:white;">
      <td height="60" style="padding: 0 20px; font-size: 18px;">
		<?php include 'houseicon.php'; ?>
        <strong>Smart Home Finder</strong>
        <span style="float:right; margin-top: 20px;">
		 <a href="owner.php" style="color:white; text-decoration:none;">Home</a>
        <a href="addhouse.php" style="color:white; text-decoration:none;">Add house</a>
       </span>
      </td>
    </tr>
	  
	  
    <tr bgcolor="#ECECDE"  style="color: black; font-size: 18px;">
      <td height="786"><h2>Mana galmeessi</h2>
	 <form action="addhouse_db.php" method="POST" enctype="multipart/form-data">
        <table width="400" border="0" bgcolor="#AEBAD4">
			
          <tbody>
            <tr>
              <td width="124"><p>Maqa mana</p></td>
              <td width="260"><input type="text" name="textfield2" id="textfield2" style="font-size: 10px; height: 20px; width: 600px;"></td>
            </tr>
            <tr>
              <td style="width: 150px;">Gatii</td>
              <td><input type="number" name="number" id="number" style="font-size: 10px; height: 20px; width: 600px;"></td>
            </tr>
            <tr>
              <td width="150" >Maqaa magaala</td>
              <td><input type="text" name="textfield" id="textfield" style="font-size: 10px; height: 20px; width: 600px;"></td>
            </tr>
            <tr>
              <td style="width: 150px;">Description</td>
              <td><textarea name="textarea" id="textarea" style="font-size: 10px; height: 200px; width: 600px;"></textarea></td>
            </tr>
			 <tr>
              <td colspan="2">&nbsp;
			<p>Suura mana</p>
        <p> <input type="file" name="main_image" id="choosefile" value="Choose File"></p>
        <p>video manaa</p>
        <p><input type="file" name="video_path" id="choosefile"  value="Choose File"></p>
        <p><input type="submit" name="submit" id="submit" value="Add" style="font-size: 20px; height: 30px; width: 80px; background-color:deepskyblue; color: aliceblue"></p>
		</td>
         </tr>
		 </tbody>
		 </table>
          </form> 
      
      <p>&nbsp;</p>
      <p>&nbsp;</p>
		</td>
    </tr>
    <tr>
     <script src="nav.js"></script>
    </tr>
    <tr>
      <tr bgcolor="#333" style="color:white; text-align:center;">
      <td height="100">
        <?php include 'footer.php'; ?>
      </td>
    </tr>
    </tr>
    </tr>
  </tbody>
</table>
</body>
</html>
