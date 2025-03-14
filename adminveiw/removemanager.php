<?php
include("database.php");
include("headerafterlogin.html");
session_start();
if(!isset($_SESSION["username"])){
   header("location: login.php") ;
   exit;
}

 //ensuring the admin is in the database
 $username = $_SESSION['username'];
 $sql= "SELECT * FROM admin1 WHERE adminname = ?";
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


if($_SERVER['REQUEST_METHOD']== "POST"){
    $name= filter_var($_POST['name'],FILTER_SANITIZE_SPECIAL_CHARS);
    $sql="DELETE FROM managers WHERE managername= ?";
    $stmt= $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("s",$name);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    if ($stmt->affected_rows > 0) {
        die("Manager deleted successfully!");
        header("location: index.php");
    } else {
        die( "No matching manager found!");
    }
    $stmt->close();
    $conn->close();
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
     .contai{
        position: relative;
        margin: 0 ;
        
        left: 30%;
        width: 40%;
        border-radius: 10px;
        background-color:rgba(147, 185, 195, 0.8); 
        box-shadow: 3px 4px 3px 2px rgba(60, 40, 40, 0.2);

    }
    .contai input{
        position: relative;
        left: 15%;
        border-radius: 5px;
        background-color:rgba(85, 134, 164, 0.8) ;
        padding: 5px;
        width: 50%;
        border: none;
        height: 30px;
    }
    .contai h3{
        position: relative;
        left: 7%;
    }
    .contai label{
        position: relative;
        left: 10%;
    }
    .contai input:hover{
        background-color:rgba(134, 173, 197, 0.9) ;
    }
    .contai input:focus{
        background-color:rgba(132, 160, 176, 0.9) ;
    }
    .contai input[type="submit"]{
        position: relative;
        left: 15%;
        background-color:rgba(230, 9, 9, 0.8) ;
        width: 45% ;
    
    }
    .contai input[type="submit"]:hover{
        background-color:rgba(236, 54, 54, 0.9) ;
    }
</style>
<body>
<form class="contai" action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
        <h3><u>Enter the manager name</u></h3>
        <label for="name">Manager name:</label><br>
        <input type="text" name="name" placeholder="manager name" required><br><br>
        <input type="submit" name="submit" value="remove"><br>
    </form>
    
</body>
</html>