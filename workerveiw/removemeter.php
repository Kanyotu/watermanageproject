<?php
include("database.php");
include("headerafterlogin.html");
session_start();
if (!isset($_SESSION["username"])){
    header("location:login.php");
    exit();
}


$username = $_SESSION["username"];
$sql= "SELECT * FROM workers WHERE worker_name = ?";
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


if($_SERVER["REQUEST_METHOD"]=="POST"){
   $user=filter_var($_POST["usersname"],FILTER_SANITIZE_SPECIAL_CHARS);

   $sql= "SELECT * FROM customers WHERE customer_name = ?";
   $stmt = $conn->prepare($sql);
   if (!$stmt){
    echo "Error: " . $conn->error;
   }

   $stmt->bind_param("s", $user);

   if (!$stmt->execute()){
    echo "Error: " . $stmt->error;
   }
   $result = $stmt->get_result();
   $row = $result->fetch_assoc();
   $userid= $row ["customer_id"] ;


   $sql='DELETE FROM meter WHERE consumerid = ?';
   $stmt = $conn->prepare($sql);
   if (!$stmt){
    echo "Error: " . $conn->error;
   }
   $stmt->bind_param("i", $userid);
   if( !$stmt->execute()){
    echo "Error: " . $stmt->error;
   }
   header("index.php");
   echo "Meter deleted successfully";

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
            background-color: #007BFF;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .form input[type="submit"]:hover {
            background-color: #0056b3;
        }
</style>
<body>
    <div class="form-container">
    <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
        <label for="name">Users Name:</label><br>
        <input type="text" id="name" name="usersname" required><br>
        <input type="submit" value="Add Worker">
        
    </form>
    </div>
</body>
</html>