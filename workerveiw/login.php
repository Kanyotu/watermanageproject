<?php
  include("database.php");
  include("headerduringlogin.html");
  session_start();
  if(isset($_SESSION['username'])){
      header("Location: index.php");
      exit();
  }
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'];
    $username = filter_var($username, FILTER_SANITIZE_SPECIAL_CHARS);
    $password = $_POST['password'];
    $password = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);
    $query = "SELECT * FROM workers WHERE worker_name = ?";
   
  
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
      $row = $result->fetch_assoc();
      if(password_verify($password, $row['wpassword'])){
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
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
    box-shadow: 1px 5px 5px 5px rgba(46, 64, 91, 0.8);
    border-radius: 10px;
    background-color: rgba(77, 133, 197, 0.98);;
  }
  .contain input{
    width: 70%;
    position: relative;
    left: 10%;
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
    left: 7%;
    margin: 5px;
    border-radius: 5px;

  }
  .bbuttons{
    background-color: rgba(68, 214, 231, 0.8);
    padding: 10px;
    margin: 5px;
    border-radius: 10px;
    border: none;
    text-decoration: none;
    color: black;
    position: relative;
    left: 20%;
    text-align: center;

    
  }
  .bbuttons:hover{
    background-color: rgba(90, 219, 245, 0.8);
  }
  .bbuttons:active{
    background-color: rgba(110, 173, 235, 0.8);
    border:solid 1px black;
  }
  .commen{
    position: relative;
    left: 30%;
  }
  .divbbuttons{
    display: flex;
  }
  @media( max-width: 710px ) {
    .divbbuttons{
      flex-direction: column;
    }
    .bbuttons{
      left: 0;
    }
  }

</style>
<body>
    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ; ?>" method="post">
        <div class="contain">
          <h1><b>Worker Login:</b></h1><br>
          <label for="name" >Username:</label><br>
          <input type="text" name="username" placeholder="username" required><br>
          <label for="password">Password:</label><br>
          <input type="password" name="password" placeholder="password" required><br><br>
          <input type="submit" value="login"><br>
          
          <h4>Login as:</h4><br>
          <div class="divbbuttons">
          <a href="http://localhost:808\2ndyearsem2project\uservieiw\login.php" class="bbuttons">customer</a>
          <a href="http://localhost:808\2ndyearsem2project\managervieiw\login.php" class="bbuttons">manager</a>
          <a href="http://localhost:808\2ndyearsem2project\adminveiw\login.php" class="bbuttons">admin</a>
          <a href="http://localhost:808\2ndyearsem2project\workerveiw\login.php" class="bbuttons">worker</a>
          </div>
        </div>
    </form>

</body>
</html>
<?php

  
  


?>