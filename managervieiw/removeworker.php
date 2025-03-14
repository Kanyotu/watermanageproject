<?php
session_start();
include("headerafterlogin.html");
include("database.php");

if(!isset($_SESSION['username'])){
    header("location: login.php");
}
// preventing none to access this page if got deleted from database after logged in
$username = $_SESSION['username'];
$sql= "SELECT * FROM managers WHERE managername = ?";
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

// after form submission
if($_SERVER['REQUEST_METHOD']=="POST"){
    $workername = filter_var($_POST['name'],FILTER_SANITIZE_SPECIAL_CHARS);

    $sql="DELETE FROM WORKERS WHERE worker_name = ?";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        echo "Error: " . $conn->error;
    }
    $stmt->bind_param("s",$workername);
    if(!$stmt->execute()){
        die("Error: ".$conn->error);

    }
    if ($stmt->affected_rows > 0) {
        header("location:index.php");
        echo"worker removed successfully!";
        
    } else {
        echo "No matching worker found!";
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
    .form-container{
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
    }
    .form {
            background: rgba(11, 176, 222, 0.27);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 35%;
            text-align: center;
            /* position: relative;
            left: 30%; */
        }

        .form label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        .form input {
            width: 90%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form input:hover{
            background-color:rgb(125, 167, 181);
        }
        .form input:focus{
            background-color:rgb(52, 105, 123);
        }

        .form input[type="submit"] {
            background-color:rgb(214, 0, 0);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .form input[type="submit"]:hover {
            background-color:rgb(179, 0, 0);
        }
</style>
<body>
    <div class="form-container">
    <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
        <label for="name">Worker Name:</label><br>
        <input type="text" id="name" name="name" required><br>
        

        <input type="submit" value="Remove Worker">
        
    </form>
    </div>
</body>
</html>