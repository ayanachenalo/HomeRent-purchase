<?php
session_start();

if (isset($_GET['lang'])) {
    // trim() fayyadamuun bakka duwwaa (space) akka hin galle ittisa
    $selected = trim($_GET['lang']); 
    $_SESSION['lang'] = $selected;
    
    // Gara index.php deebisi
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Language</title>
    <style>
        .lang-btn { text-decoration: none; padding: 10px 20px; background: #333; color: white; border-radius: 5px; margin: 5px; display: inline-block; font-family: sans-serif; }
        .lang-btn:hover { background: blue; }
    </style>
</head>
<body>
    <div style="text-align:center; margin-top:100px; font-family: sans-serif;">
        <h2>Select Language / Afaan Filadhu</h2>
        <br>
        <a href="language.php?lang=en" class="lang-btn">English</a>
        <a href="language.php?lang=or" class="lang-btn">Afaan Oromoo</a>
        <a href="language.php?lang=am" class="lang-btn">አማርኛ</a>
    </div>
</body>
</html>