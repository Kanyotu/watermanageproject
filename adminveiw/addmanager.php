<?php
session_start();
include("database.php");
include("headerafterlogin.html");

// checking if the user is already logged in and kicking him out if he is not
 if(!isset($_SESSION['username'])){
    header("Location: login.php");
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

 // after submitting the form
 if($_SERVER["REQUEST_METHOD"]=="POST"){
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
    $email = $_POST['email'];
    $email = filter_var($email,FILTER_SANITIZE_EMAIL);
    $phone = $_POST['phone'];
    $phone = filter_var($phone,FILTER_SANITIZE_NUMBER_INT);
    $emailcon = "SELECT * FROM managers WHERE manager_email = ?";
    $stmt = $conn->prepare($emailcon);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if($stmt->get_result()->num_rows > 0){
       die("email already exists");
    }

    $phonecon = "SELECT * FROM managers WHERE phoneno = ?";
    $stmt = $conn->prepare($phonecon);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    if($stmt->get_result()->num_rows >0){
       die("phone number already exists");
    } 
    $namecon = "SELECT * FROM managers WHERE managername = ?";
    $stmt = $conn->prepare($namecon);
    $stmt->bind_param("s",$name);
    $stmt->execute();
    if($stmt->get_result()->num_rows > 0){
       die("name already exists");
    }
  
    $department = $_POST['department'];
    $department = filter_var($department,FILTER_SANITIZE_SPECIAL_CHARS);
    $querydep = "SELECT * FROM department WHERE departmentname = ?";
    $stmt = $conn->prepare($querydep);
       $stmt->bind_param("s",$department);
       $stmt->execute();
       $result = $stmt->get_result();


       if($result->num_rows === 0){
          die( "department does not exist");
           exit();
       }
       else{
           $departmentData = $result->fetch_assoc();
           $departmentid = $departmentData['department_id'];

       }
       $adminname = $_SESSION['username']; //this is the name of the admin who is adding the manager
       $query = "SELECT * FROM admin1 WHERE adminname = ?";
       $stmt = $conn->prepare($query);
       $stmt->bind_param("s",$adminname);
       $stmt->execute();
       $result = $stmt->get_result();
       $row = $result->fetch_assoc();
       $adminid = $row['admin_id']; //this is the id of the admin who is adding the manager
    if($_POST['password'] == $_POST['password2']){
        $password = $_POST['password'];
        $password = filter_var($password,FILTER_SANITIZE_SPECIAL_CHARS);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO managers(manager_email,managerpassword,phoneno,managername,admin_id,department_id) VALUES(?,?,?,?,?,?)";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
           die("Query preparation failed: " . $conn->error);
       }
        $stmt->bind_param("ssssii",$email,$password,$phone,$name,$adminid,$departmentid);
        if(!$stmt->execute()){
            die("Error executing statement: " . $stmt->error);
        }
        header("Location: index.php");
        die("manager added successfully");
        exit();
        $stmt-> close();
        $conn-> close();
    }
    else{
        die("passwords do not match");
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
        background-color:rgba(119, 146, 154, 0.8) ;
        padding: 5px;
        border: none;
        width: 55%;
    }
    .form input:hover{
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
        <h1>Fill the following form to add a manager</h1>
        <label for="name">Name:</label><br>
        <input type="text" name="name" id="name" required><br>
        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" required><br>
        <label for="password">Password:</label><br>
        <input type="password" name="password" id="password" required><br>
        <label for="password">Confirm password:</label><br>
        <input type="password" name="password2" id="password2" required><br>
        <label for="phone">Phone number:</label><br>
        <input type="text" name="phone" id="phone" required><br>
        <label for="department">Department:</label><br>
        <input type="text" name="department" id="department" required><br><br>
        <input type="submit" value="add manager">

    </form>
    
</body>
</html>