<?php 
 session_start();
 include("headerafterlogin.html");
 if(!isset($_SESSION["username"])){
    header("location:login.php");
    exit();
 }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>