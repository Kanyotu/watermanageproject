<?php
include("database.php");
session_start();
include("headerafterlogin.html");
// checking if session username is set or not
if (!isset($_SESSION["username"])){
    header("location:login.php");
    exit();
}
//ensuring the manager is in the database
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


//code runs after submitting the form
if ($_SERVER['REQUEST_METHOD']=="POST"){
    $name = filter_var($_POST['name'],FILTER_SANITIZE_SPECIAL_CHARS);
    $email= filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
    $phonenumber=filter_var($_POST['phonenumber'],FILTER_SANITIZE_SPECIAL_CHARS);
    $tobepaid=filter_var($_POST['ammount'],FILTER_SANITIZE_NUMBER_INT);
    $password= filter_var($_POST['password'],FILTER_SANITIZE_SPECIAL_CHARS);
    $password2= filter_var($_POST['password2'],FILTER_SANITIZE_SPECIAL_CHARS);

    //checking if the password and password2 are the same

    if(!($password==$password2)){
        die("Passwords dont match!!");
    }
    //hashing password
    $password = password_hash($password, PASSWORD_DEFAULT);

    //checking if email exists in database
    $que= "SELECT * FROM workers WHERE worker_email= ?";
    $stmt = $conn->prepare($que);
    if(!$stmt){
        die("Query preparation failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    if( !$stmt->execute() ){
        die("Query execution failed: " . $conn->error);
    }
    if($stmt->get_result()->num_rows > 0){
        die("email already exists!!");
     }

     //checking if phone no exists in table workers

     $que= "SELECT * FROM workers WHERE phoneno= ?";
     $stmt = $conn->prepare($que);
     if(!$stmt){
         die("!!Query preparation failed: " . $conn->error);
     }
     $stmt->bind_param("s", $phonenumber);
     if( !$stmt->execute() ){
         die("!!Query execution failed: " . $conn->error);
     }
     if($stmt->get_result()->num_rows > 0){
         die("phonenumber already exists!!");
      }

      // checking if worker_name exists in table workers

    $que= "SELECT * FROM workers WHERE worker_name= ?";
     $stmt = $conn->prepare($que);
     if(!$stmt){
         die("!!Query preparation failed: " . $conn->error);
     }
     $stmt->bind_param("s", $name);
     if( !$stmt->execute() ){
         die("!!Query execution failed: " . $stmt->error);
     }
     if($stmt->get_result()->num_rows > 0){
         die("Name already exists!!");
      }
     //getting managers id who is adding the worker and the department id of the department the worker is being added to same as the manager
    $sql= "SELECT * FROM managers WHERE managername=?";
    $stmt=$conn->prepare($sql);
    if(!$stmt){
        die("Query preparation failed: " . $conn->error);
    }
    $stmt->bind_param("s",$_SESSION['username']);
    if( !$stmt->execute() ){
        die("Query execution failed: " . $stmt->error);
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $managerid = $row['manager_id'];
    $departmentid = $row['department_id'];

    // inserting into the table worker_details
    $sql="INSERT INTO workers (worker_name,manager_id,worker_email,wpassword,phoneno,department_id,ammountpaidpermonth) VALUES (?,?,?,?,?,?,?)";
    $stmt= $conn->prepare($sql);
    if(!$stmt){
        die("!!!Query preparation failed: " . $conn->error);
        }
    $stmt->bind_param("sisssid", $name, $managerid, $email,$password,$phonenumber,$departmentid,$tobepaid);
    if(!$stmt->execute()){
        die("!!Query execution failed: " . $stmt->error);

    }
    //displaying a success message and redirecting to the index page
    header("location: index.php");
    die('worker added sucessfully!');
    
    exit();
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
        <label for="name">Worker Name:</label><br>
        <input type="text" id="name" name="name" required><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <label for="password2">Confirm Password</label><br>
        <input type="password" id="password2" name="password2" required><br>
        <label for="phonenumber">Phone Number:</label><br>
        <input type="number" id="phonenumber" name="phonenumber" required><br>
        <label for="ammount ">Amount To be paid:</label><br>
        <input type="number" id="ammount" name="ammount" required><br>

        <input type="submit" value="Add Worker">
        
    </form>
    </div>
</body>
</html>