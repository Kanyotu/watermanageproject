<?php 
session_start();
include("headerafterlogin.html");
include("database.php");

 if(!isset($_SESSION['username'])){
     header("Location: login.php");
 }

  //ensuring the admin is in the database
  $username = $_SESSION['username'];
  $sql= "SELECT * FROM admin1 WHERE adminname= ?";
  $stmt = $conn->prepare($sql);
  if(!$stmt){
      echo " first Error: " . $conn->error;
  }
  $stmt->bind_param("s", $username);
  
  if(!$stmt ->execute()){
      echo "second Error: " . $stmt->error;
  }
  if($stmt->get_result()->num_rows == 0){
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