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
			<a href="index.php" style="color:white; text-decoration:none;">Home</a>&nbsp;&nbsp;<a href="regist.php" style="color:white; text-decoration:none;">Register</a>
           
        </span>
      </td>
    </tr>
	  
	
    <tr bgcolor="#ECECDE">
      <td height="1000" style="text-align: center"><table width="600" border="0" bgcolor="#B9BFC4">
		  <form action="login_db.php" method="POST">
    <tbody style="font-size: 20px; font-weight: bold;padding: 10px;">
        <tr>
            <td colspan="2" style="font-size: 30px; font-weight: bold; padding: 10px;">Login Here</td>
        </tr>
        <tr>
            <td>Full Name</td>
            <td><input type="text" name="full_name" id="full_name" style="padding: 5px; width: 80%;" required></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="password" name="password" id="password" style="padding: 5px; width: 80%;" required ></td>
        </tr>
		
		
		<tr><td>Login As:</td>
         <td>
         <select name="role" id="role" style="padding: 5px; width: 80%;">
         <option value="tenant" style="padding: 5px; width: 80%;" required>Tenant</option>
         <option value="owner" style="padding: 5px; width: 80%;" required>Owner</option>
         </select>
         </td>
         </tr>
		
        <tr>
            <td height="46" colspan="2" style="text-align: center;">
                <input type="submit" name="submit" id="submit" value="Login" 
                style="border-radius: 4px; color: white; padding: 8px 20px; cursor: pointer; background-color: blue; border: none;">
            </td>
        </tr>
        <tr>
            <td height="57" colspan="2">Create new account? <a href="regist.php">Register</a></td>
        </tr>
    </tbody>
   </form>
      </table>
      <script src="nav.js"></script>
    </tr>
	  
    <tr bgcolor="#333" style="color:white; text-align:center;">
      <td height="200">
      <?php include 'footer.php'; ?>
      </td>
    </tr>
    </tr>
  </tbody>
</table>
</body>
</html>
