<?php
session_start();
$current_lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
?>
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
      <td height="82" style="padding: 0 20px; font-size: 24px;">
		  <?php include 'houseicon.php'; ?>
        <strong>Smart Home Finder</strong>
        <span style="float:right;">
			<a href="index.php" style="color:white; text-decoration:none;">Home</a>
			&nbsp;&nbsp;<a href="login.php" style="color:white; text-decoration:none;">Login</a>
           
        </span>
      </td>
    </tr>
	  
	
    <tr bgcolor="#ECECDE">
      <td height="1000" ><table width="600" border="0" bgcolor="#B9BFC4">
		  <form action="reg_codedb.php" method="POST">
    <tbody style="font-size: 20px; font-weight: bold; padding: 10px;">
        <tr>
            <td colspan="2" style="font-size: 40px; font-weight: bold; padding: 10px;">Registration</td>
        </tr>
        <tr>
            <td>Full Name</td>
            <td><input type="text" name="full_name" id="full_name" style=" width: 80%; padding: 5px;" required autocomplete="off"></td>
        </tr>
		
        <tr>
            <td>Password</td>
            <td><input type="password" name="password" id="password" style=" width: 80%; padding: 5px;" required ></td>
		</tr>
		<tr>
            <td>PhoneNumber</td>
            <td><input type="number" name="phonenumber" id="phonenumber" style="width: 80%; padding: 5px;" required ></td>
        </tr>
		
		<tr>
            <td>Email</td>
            <td><input type="email" name="email" id="email" style="padding: 5px; width: 80%;" required ></td>
        </tr>
		
		
		<tr><td>Role:</td>
         <td>
         <select name="role" id="role" style="padding: 5px; width: 80%;">
         <option value="tenant" style="padding: 5px; width: 80%;" required>Tenant</option>
         <option value="owner" style="padding: 5px; width: 80%;" required>Owner</option>
         </select>
         </td>
         </tr>
		
        <tr>
            <td height="46" colspan="2" style="text-align: center;">
                <input type="submit" name="submit" id="submit" value="Register" 
                style="border-radius: 4px; color: white; padding: 8px 20px; cursor: pointer; background-color: blue; border: none;">
            </td>
        </tr>
        <tr>
            <td height="57" colspan="2">Have you account? <a href="login.php">Login</a></td>
        </tr>
    </tbody>
   </form>
      </table>
      <p>&nbsp;</p></td>
    </tr>
	  <script src="nav.js"></script>
	  
   <tr bgcolor="#222" style="color:white;" style=" font-size:20px;">
    <td>
        <?php include 'footer.php'; ?>
    </td>
</tr>
    
  </tbody>
	
</table>
</body>
</html>

