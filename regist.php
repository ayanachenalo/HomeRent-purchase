<?php
session_start();
$current_lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Smart Home Finder - Registration</title>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="stylemedia.css">
<style>
    .register-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 0;
    }
    /* Input fields akka dizaayiniin isaanii miidhaguuf */
    .form-input {
        width: 95%; 
        padding: 10px; 
        border: 1px solid #ccc; 
        border-radius: 5px;
        font-size: 16px;
    }
    .form-input:focus {
        border-color: #28a745;
        outline: none;
    }
</style>
</head>

<body>
<table width="100%" border="0" style="border-collapse: collapse; color: #2A3E5E;">
  <tbody>
    <tr bgcolor="#333" style="color:white;">
      <td height="82" style="padding: 0 20px; font-size: 24px;">
          <?php include 'houseicon.php'; ?>
        <strong>Smart Home Finder</strong>
        <span style="float:right; font-size: 18px; font-weight: bold; display: flex; gap: 20px;">
            <a href="index.php" style="color:white; text-decoration:none;">Home</a>
            <a href="login.php" style="color:white; text-decoration:none;">Login</a>
        </span>
      </td>
    </tr>
      
    <tr bgcolor="#ECECDE">
      <td height="600" style="text-align: center; vertical-align: middle; padding: 40px 0;">
          <div class="register-wrapper">
              <form action="reg_codedb.php" method="POST">
                  <table width="550" border="0" bgcolor="#B9BFC4" style="margin: 0 auto; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); text-align: left;">
                    <tbody style="font-size: 18px; font-weight: bold;">
                        <tr>
                            <td colspan="2" style="font-size: 32px; font-weight: bold; padding-bottom: 20px; color: #2A3E5E; text-align: center;">Registration</td>
                        </tr>
                        <tr>
                            <td width="30%" style="padding: 12px 0;">Full Name</td>
                            <td><input type="text" name="full_name" id="full_name" class="form-input" required autocomplete="off"></td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0;">Password</td>
                            <td><input type="password" name="password" id="password" class="form-input" required></td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0;">Phone Number</td>
                            <td><input type="text" name="phonenumber" id="phonenumber" class="form-input" required></td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0;">Email</td>
                            <td><input type="email" name="email" id="email" class="form-input" required></td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0;">Role:</td>
                            <td>
                                <select name="role" id="role" class="form-input" style="width: 100%;">
                                    <option value="tenant">Tenant</option>
                                    <option value="owner">Owner</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td height="60" colspan="2" style="text-align: center; padding-top: 20px;">
                                <input type="submit" name="submit" id="submit" value="Register" 
                                style="border-radius: 5px; color: white; padding: 12px; cursor: pointer; background-color: #28a745; border: none; font-size: 18px; font-weight: bold; width: 100%; transition: background 0.2s;">
                            </td>
                        </tr>
                        <tr>
                            <td height="40" colspan="2" style="font-size: 15px; font-weight: normal; padding-top: 15px; text-align: center;">
                                Already have an account? <a href="login.php" style="color: blue; text-decoration: none; font-weight: bold;">Login</a>
                            </td>
                        </tr>
                    </tbody>
                  </table>
              </form>
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