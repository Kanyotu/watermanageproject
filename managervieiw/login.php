<?php

 
  include("database.php");
  include("headerduringlogin.html");
  session_start();
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
  .contain{
    margin: 0 auto;
    width: 40%;
    padding: 10px;
    box-shadow: 1px 5px 5px 5px rgba(23, 59, 81, 0.8);
    background-color: rgba(33, 154, 219, 0.8);
    border-radius: 10px;
  }
  .contain input{
    width: 70%;
    position: relative;
    left: 20%;
    padding: 10px;
    margin: 5px;
    border-radius: 10px;
    border: none;
  }
  .contain input:hover{
    background-color: rgba(90, 180, 234, 0.8);
  }
  .contain input:focus{
    background-color: rgba(28, 118, 136, 0.8);
    border:solid 1px black;
  }
  .contain label{
    position: relative;
    left: 18%;
    margin: 5px;
    border-radius: 5px;
  }
  .bbut{
    background-color: rgba(68, 214, 231, 0.8);
    padding: 10px;
    margin: 5px;
    border-radius: 10px;
    border: none;
    text-decoration: none;
    color: black;
    position: relative;
    left: 20%;

    
  }
  .bbut:hover{
    background-color: rgba(90, 219, 245, 0.8);
  }
  .bbut:active{
    background-color: rgba(110, 173, 235, 0.8);
    border:solid 1px black;
  }
  .commen{
    position: relative;
    left: 20%;
  }
</style>
<body>
    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ; ?>" method="post">
        <div class="contain">
          <h1><b>Login:</b></h1><br>
          <label for="name" >Username:</label><br>
          <input type="text" name="username" placeholder="username" required><br>
          <label for="password">Password:</label><br>
          <input type="password" name="password" placeholder="password" required><br><br>
          <input type="submit" value="login"><br>
          <!-- <h6>Have no account?<a href="register.php">register</a></h6> -->
          <h4 class="commen">Wrong selection?? Login as:</h4><br>
          <a href="http://localhost:808\2ndyearsem2project\uservieiw\login.php" class="bbut">customer</a>
          <a href="http://localhost:808\2ndyearsem2project\managervieiw\login.php" class="bbut">manager</a>
          <a href="http://localhost:808\2ndyearsem2project\adminveiw\login.php" class="bbut">admin</a>
          <a href="http://localhost:808\2ndyearsem2project\workerveiw\login.php" class="bbut">worker</a>
        </div>
    </form>

</body>
</html>
<?php

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'];
    $username = filter_var($username, FILTER_SANITIZE_SPECIAL_CHARS);
    $password = $_POST['password'];
    $password = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);
    $query = "SELECT * FROM managers WHERE managername = ?";
   
  
    $stmt = $conn->prepare($query);
    if(!$stmt){
      die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
      $row = $result->fetch_assoc();
      if(password_verify($password, $row['managerpassword'])){
        $_SESSION['username'] = $username;
        header("Location: index.php");
      }
      else{
        echo "Invalid password";
      }
    }
    else{
      echo "Invalid username";
    }
    
    
  
}
  


?>