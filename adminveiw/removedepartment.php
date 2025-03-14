<?php 
include("headerafterlogin.html");
include('database.php');
session_start();
if(!isset($_SESSION['username'])){
    header("location:login.php");
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
 

    if($_SERVER['REQUEST_METHOD']=="POST"){
        $department=filter_var($_POST['department'],FILTER_SANITIZE_SPECIAL_CHARS);
        $sql="DELETE FROM department WHERE department_name=?";
        $stmt=$conn->prepare($sql);
        if(!$stmt){
            die("Error: ".$conn->error);
        }
        $stmt->bind_param("s",$department);
        if(!$stmt->execute()){
            die("Error: ".$conn->error);

        }
        if ($stmt->affected_rows > 0) {
            die("department deleted successfully!");
        } else {
            die( "No matching department found!");
        }
        $stmt->close();
        $conn->close();
        header("location:index.php");    
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
        background-color:rgba(158, 193, 205, 0.8); 
        box-shadow: 3px 4px 3px 2px rgba(60, 40, 40, 0.2);

    }
    .contai input{
        position: relative;
        left: 15%;
        border-radius: 5px;
        background-color:rgba(82, 139, 155, 0.8) ;
        padding: 5px;
        width: 50%;
        height: 30px;
        border: none;
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
        background-color:rgba(55, 138, 189, 0.9) ;
    }
    .contai input:focus{
        background-color:rgba(82, 138, 173, 0.9) ;
    }
    .contai input[type="submit"]{
        position: relative;
        left: 15%;
        background-color:rgba(220, 119, 119, 0.8) ;
        width: 45% ;
    
    }
    .contai input[type="submit"]:hover{
        background-color:rgba(136, 58, 58, 0.9) ;
    }
</style>
<body>
<form class="contai" action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
        <h3><u>Enter the department</u></h3>
        <label for="name">Department name:</label><br>
        <input type="text" name="department" placeholder="department name" required><br><br>
        <input type="submit" name="submit" value="remove">
    </form>
    
</body>
</html>
