<?php
session_start();
include("headerafterlogin.html");
include ("database.php");

if (!isset($_SESSION["username"])){
    header("location: login.php");
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

//add the pump to db
if($_SERVER['REQUEST_METHOD']=="POST"){

    $sourcename=filter_var($_POST["sourcename"],FILTER_SANITIZE_SPECIAL_CHARS);
    $dateins=$_POST["dateinstalled"];
    $electricityinwatts=filter_var($_POST["electricityinwatts"],FILTER_SANITIZE_NUMBER_FLOAT);
    $status=filter_var($_POST['status'],FILTER_SANITIZE_SPECIAL_CHARS);

    $sql="SELECT FROM watersource WHERE sourcename = ?";
    $stmt=$conn->prepare();
    if(!$stmt){
        echo "Error :".$conn->error;
    }
    $stmt->bind_param("s",$sourcename);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        $row=$result->fetch_assoc();
        $sourceid=$row["source_id"];
    }
    else {
       echo"!!invalid sourcename";
    } 
    
    
    $sql="INSERT INTO pumps (sourceid,dateinstalled,electricityconsumingwatts,pstatus) VALUES(?,?,?,?) ";
    $stmt= $conn->prepare($sql);
    if(!$stmt){
        echo "Error: " . $conn->error;
    }
    $stmt->bind_param("isds",$sourceid,$dateins,$electricityinwatts,$status);
    if(!$stmt->execute()){
        echo "Error: " . $stmt->error;
    }
    else{
        echo "Pump added successfully";
        header("location: index.php");
    }

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
    .form{
        
        width: 50%;
        position: relative;
        left: 5%;
        margin: 0 auto;
        width: 40%;
        padding: 10px;
        box-shadow: 1px 5px 5px 5px rgba(118, 122, 125, 0.8);
        border-radius: 10px;
        background-color: rgba(181, 207, 223, 0.8);
    }
    .form input{
        position: relative;
        left: 15%;
        border-radius: 7px;
        height: 30px;
        background-color:rgba(35, 75, 100, 0.8) ;
        padding: 5px;
        border: none;
        width: 55%;
    }
    .form input:hover{
        background-color:rgba(138, 152, 161, 0.9) ;

    }
    .form select{
        position: relative;
        left: 15%;
        border-radius: 1px;
        background-color:rgba(35, 75, 100, 0.8) ;
        padding: 5px;
        border: none;
        width: 55%;
        height: 35px;
    }
    .form select:hover{
        background-color:rgba(138, 152, 161, 0.9) ;
    }
    .form input:focus{
        background-color:rgba(89, 173, 225, 0.9) ;
    }
    .form label{
        position: relative;
        left:10% ;
    }
    .form input[type="submit"]{
        margin-top: 5px;
        background-color: rgba(45, 92, 173, 0.8) ;
        width: 50%;
    }
    .form input[type="submit"]:hover{
        background-color: rgba(6, 120, 190, 0.8) ;
        }
</style>
<body>
    <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
        <h3><u>Enter the pump details</u></h3><br>
        
        <label for="sourcename">Sourcename:</label><br>
        <input type="text" name="sourcename" placeholder="Source name " required><br><br>
        <label for="dateinstalled">Date Installed:</label><br>
        <input type="date" name="dateinstalled" required><br><br>
        <label for="electricityinwatts">Electricity consuming watts:</label><br>
        <input type="number" name="electricityinwatts" placeholder="Electricity consuming watts" required><br><br>
        <label for="status">Status:</label><br>
        <select name="status" id="" required>
            <option value="working">Working</option>
            <option value="not working">Not working</option>
        </select><br><br>

        <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>