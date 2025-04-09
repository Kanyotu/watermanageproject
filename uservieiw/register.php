<?php
ob_start();
include("database.php");
include("headerduringlogin.html");

if($_SERVER["REQUEST_METHOD"] == 'POST'){
    $name = $_POST['username'];
    $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
    $email = $_POST['email'];
    $email = filter_var($email,FILTER_SANITIZE_EMAIL);
    $phone = $_POST['phone'];
    $phone = filter_var($phone,FILTER_SANITIZE_NUMBER_INT);
    $location = $_POST['location'];
    $location = filter_var($location,FILTER_SANITIZE_SPECIAL_CHARS);
    $type = $_POST['customertype'];
   //update database dont forget to hash password using password_hash() function PASSWORD_DEFAULT  as the second parameter
   if($_POST['password'] == $_POST['password2']){
        $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
        $emalcon = "SELECT * FROM customers WHERE customeremail = '$email'";
        $conts = "SELECT * FROM customers WHERE customerphoneno = '$phone'";
        $userna = "SELECT * FROM customers WHERE customername = '$name'";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
           die("invalid email");}
        elseif(mysqli_num_rows(mysqli_query($conn,$emalcon)) > 0){
           die("email already exists");
           }
        elseif(mysqli_num_rows(mysqli_query($conn,$userna)) > 0){
               die("username already exists");
           }
        elseif(mysqli_num_rows(mysqli_query($conn,$conts)) > 0){
               die("phone number already exists");
           }
        elseif(empty($name) || empty($email) || empty($phone) || empty($location) || empty($type)){
             die("fill all fields");  
       }else{
           $sql = "INSERT INTO customers (customername,customeraddress,customeremail,customerpassword,customerphoneno,customertype) VALUES(?,?,?,?,?,?)";
           $stmt = mysqli_stmt_init($conn);
               if (!mysqli_stmt_prepare($stmt, $sql)) {
                   die("<h1>sql error " . mysqli_error($conn) . "</h1>");
               }else{
                   mysqli_stmt_bind_param($stmt,"ssssss",$name,$location,$email,$password,$phone,$type);
                   mysqli_stmt_execute($stmt);
                   ob_end_clean();
                   header("Refresh: 2; url=login.php");

                    // Then output your content
                    echo '<script>alert("signup successful");</script>';
                    echo "Redirecting to login page in 3 seconds...";
                    exit();
                  
                   exit();
               }
               
           
       } 
 }else {
       die("passwords do not match");
   }
  
   mysqli_close($conn);
   }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="—Pngtree—water tap icon for your_4852220.png">
</head>
<style>
   
    .innercont{
        margin: 0 auto;
        width: 50%;
        padding: 10px;
        box-shadow: 1px 5px 5px 5px rgba(15, 76, 113, 0.8);
        background-color: rgba(76, 109, 129, 0.8);
        border-radius: 10px;
        /* background-image: url("—Pngtree—water tap icon for your_4852220.png"); */
    }
    
    .innercont input{
        width: 50%;
        position: relative;
        left: 20%;
        padding: 10px;
        margin: 5px;
        height : 30px;
        border-radius: 10px;
        border: none;
    }
    .innercont input:hover{
        background-color: rgba(90, 180, 234, 0.8);
    }
    .innercont input:focus{
        background-color: rgba(28, 118, 136, 0.8);
        border:solid 1px black;
        
    }
    
    .innercont select{
        width: 50%;
        position: relative;
        left: 20%;
        padding: 10px;
        margin: 5px;
        border-radius: 10px;
        height: 60px;
    }
    .innercont label{
        position: relative;
        left: 18%;
        margin: 5px;
        border-radius: 5px;
        font-size: 20px;
        
    }
    
    .reg{
        position: relative;
        left: 12%;
    }
    input[type="submit"]{
        width: 50%;
        position: relative;
        left: 20%;
        padding: 10px;
        margin: 5px;
        height : 60px;
        border-radius: 10px;
        border: none;
        
    }



</style>
<body>
    <div class="cont">
        
    <form class="innercont" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <h1 class="reg">Register:</h1><br>
        <label for="name" >Name:</label><br>
        <input type="text" name="username" placeholder="username" required><br>
        <label for="email">Email:</label><br>
        <input type="email" name="email" placeholder="email@123" required><br>
        <label for="password">Password:</label><br>
        <input type="password" name="password" placeholder="password" required><br>
        <label for="password">Confirm password:</label><br>
        <input type="password" name="password2" placeholder="confirm password" required><br>
        <label for="phone">Phone number:</label><br>
        <input type="text" name="phone" placeholder="phone number" required><br>
        <label for="customertype">Customer type:</label><br>
        <select name="customertype" id="customertype" required>
            <option value="residential">residential</option>
            <option value="commercial">commercial</option>
            <option value="industrial">industrial</option>
        </select><br>
        <label for="location">Location:</label><br>
        <input type="text" name="location" placeholder="location" required><br> <br>
        <input type="submit" value="register"><br>
    </form>
    </div>
</body>
</html>
