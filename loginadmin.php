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
        background: #007bff;
        color: white;
        padding: 10px;
        text-decoration: none;
        border-radius: 4px;
    }
</style>
</head>

<body>
<table width="100%"  height="100%"border="0" style="border-collapse: collapse; font-size: 24px; ">
  <tbody>
    <tr bgcolor="#333" style="color:white;">
      <td height="78" style="padding: 0 20px; font-size: 24px;">
	 <?php include 'houseicon.php'; ?>
        <strong>Smart Home Finder</strong>
        <span style="text-align: justify"></span><span style="float:right;">  <a href="loginadmin.php" style="color:white; text-decoration:none;">Login</a>
            </span>
      </td>
    </tr>
	  
	   <td height="800" style="text-align: center"><table width="466" border="0" bgcolor="#B9BFC4">
		  <form action="loginadmin_code.php" method="POST">
    <tbody>
        <tr>
            <td colspan="2" style="font-size: 20px; font-weight: bold; padding: 10px;">Login Here</td>
        </tr>
        <tr>
            <td>Username</td>
            <td><input type="text" name="username" id="username" style="padding: 5px; width: 80%;" required></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="password" name="password" id="password" style="padding: 5px; width: 80%;" required></td>
        </tr>
		
	 <tr>
            <td height="46" colspan="2" style="text-align: center;">
                <input type="submit" name="submit" id="submit" value="Login" 
                style="border-radius: 4px; color: white; padding: 8px 20px; cursor: pointer; background-color: blue; border: none;">
            </td>
        </tr>
       
    </tbody>
   </form>
      </table>
      
    </tr>

   <tr bgcolor="#333" style="color:white; text-align:center;">
      <td height="200">
        <p>&copy; 2026 Smart Home Finder developed by Ayana Talile and Werqu </p>
      </td>
    </tr>
  </tbody>
</table>
</body>
</html>