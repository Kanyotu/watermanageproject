<?php
session_start();
include("database.php");
include("headerafterlogin.html");

if(!isset($_SESSION['username'])){
    header('location:login.php');
    exit();

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


//after submiting form 
 if($_SERVER['REQUEST_METHOD']=='POST'){
    if (!isset($conn)) {
        die("Database connection failed: " . mysqli_connect_error());
    }
    $department = filter_var($_POST['department'], FILTER_SANITIZE_SPECIAL_CHARS);

    $sql= "SELECT * FROM `department` WHERE `departmentname` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $department);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0){
        die("Department already exists");
        }
         else {
            $sql = "INSERT INTO `department` (`departmentname`) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $department);
            $stmt->execute();
            header("Location: index.php");
            exit();
            die("Department added successfully");
            
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
        background-color:rgba(187, 207, 205, 0.8); 
        box-shadow: 3px 4px 3px 2px rgba(60, 40, 40, 0.2);

    }
    .contai input{
        position: relative;
        left: 15%;
        border-radius: 5px;
        background-color:rgba(129, 164, 186, 0.8) ;
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
        background-color:rgba(144, 188, 189, 0.9) ;
    }
    .contai input:focus{
        background-color:rgba(151, 173, 187, 0.9) ;
    }
    .contai input[type="submit"]{
        position: relative;
        left: 15%;
        background-color:rgba(154, 177, 192, 0.8) ;
        width: 45% ;
    
    }
    .contai input[type="submit"]:hover{
        background-color:rgba(32, 125, 187, 0.9) ;
    }

</style>
<body>
    <form class="contai" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
        <h3><u>Enter the department</u></h3>
        <label for="department">Department:</label><br>
        <input type="text" name="department" placeholder="department name"><br><br>
        <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>