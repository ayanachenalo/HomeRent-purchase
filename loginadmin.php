<?php include 'index_db.php'; ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Smart Home Finder - Admin Login</title>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="stylemedia.css">
<style>
    /* Fuula login qofa waan ta'eef style-onni manneenii asirratti hin barbaachisne */
    .login-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 0;
    }
</style>
</head>

<body>
<table width="100%" height="100%" border="0" style="border-collapse: collapse; font-size: 24px;">
  <tbody>
    <tr bgcolor="#333" style="color:white;">
      <td height="78" style="padding: 0 20px; font-size: 24px;">
         <?php include 'houseicon.php'; ?>
         <strong>Smart Home Finder</strong>
         <span style="float:right;">
             <a href="loginadmin.php" style="color:white; text-decoration:none;">Login</a>
         </span>
      </td>
    </tr>
      
    <tr bgcolor="#ECECDE">
      <td height="800" style="text-align: center; vertical-align: middle;">
          <div class="login-wrapper">
              <form action="loginadmin_code.php" method="POST">
                  <table width="466" border="0" bgcolor="#B9BFC4" style="margin: 0 auto; padding: 20px; border-radius: 8px;">
                    <tbody>
                        <tr>
                            <td colspan="2" style="font-size: 20px; font-weight: bold; padding: 10px; text-align: left;">Login Here</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; padding: 10px;">Username</td>
                            <td><input type="text" name="username" id="username" style="padding: 5px; width: 80%;" required></td>
                        </tr>
                        <tr>
                            <td style="text-align: left; padding: 10px;">Password</td>
                            <td><input type="password" name="password" id="password" style="padding: 5px; width: 80%;" required></td>
                        </tr>
                        <tr>
                            <td height="46" colspan="2" style="text-align: center; padding-top: 15px;">
                                <input type="submit" name="submit" id="submit" value="Login" 
                                style="border-radius: 4px; color: white; padding: 8px 20px; cursor: pointer; background-color: blue; border: none; font-size: 18px; font-weight: bold;">
                            </td>
                        </tr>
                    </tbody>
                  </table>
              </form>
          </div>
      </td>
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