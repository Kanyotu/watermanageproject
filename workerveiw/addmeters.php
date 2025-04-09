<?php
include("database.php");
include("headerafterlogin.html");
session_start();


// Check if user is logged in
if(!isset($_SESSION['username'])){
    header("location:login.php");
    exit;

}

$username = $_SESSION['username'];
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



if ($_SERVER['REQUEST_METHOD']=="POST"){
    $meterno = filter_var($_POST['meterno'],FILTER_SANITIZE_SPECIAL_CHARS);
    $installdate =$_POST['installdate'];
    $status = $_POST['status'];
    $lastmeterreading= filter_var( $_POST['lastmeterreading'], FILTER_SANITIZE_NUMBER_FLOAT);
    $networkname= filter_var( $_POST['networkname'], FILTER_SANITIZE_SPECIAL_CHARS);
    $consumername = filter_var( $_POST['consumername'], FILTER_SANITIZE_SPECIAL_CHARS);

    //check if meter number already exists in database table meter 
    $que= "SELECT FROM meter WHERE meterno = ?";
    $stmt = $conn->prepare($que);
    if(!$stmt){
        echo "GETTING METER NO Error: unable to prepare SQL statement: " . $conn->error;
    }
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        echo"this meter number is already exist";
        }
    
     //getting thhe consumer id from the database customers table
    $sql = "SELECT FROM customers WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        echo "GETTING CUSTOMER ID Error: unable to prepare SQL statement: " . $conn->error;
    }
    $stmt->bind_param("s",$consumername);
    if(!$stmt->execute()){
        echo "AFTER BINDING CONSUMERNAME Error: unable to execute SQL statement: " . $stmt->error;
    }
    $result = $stmt->get_result();
    if ($result->num_rows == 0){
        echo"invalid customername name";
    }
    $row = $result->fetch_assoc();
    $consumerid = $row['customer_id'];

    

    //getting network id from the database network table using network name
    $sql = "SELECT FROM network WHERE netname= ?";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        echo " GETTING NETWORK ID Error: unable to prepare SQL statement: " . $conn->error;
        }
    $stmt->bind_param("s",$networkname);
    if(!$stmt->execute()){
        echo "AFTER BINDING NETWORKNAME Error: unable to execute SQL statement: " . $stmt->error;
    }
    $result = $stmt->get_result();
    if ($result->num_rows == 0){
        echo"invalid network name";
    }
    $row = $result->fetch_assoc();
    $networkid = $row['netid'];

    // inserting data into the meter table
    $sql = "INSERT INTO meter (meterno, installationdate, mstatus, lastmeterreading, networkid) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        echo " INSERTING METER DATA Error: unable to prepare SQL statement: " . $conn->error;
        }
    $stmt->bind_param("sssdi", $meterno, $installdate, $status, $lastmeterreading, $networkid);
    if( !$stmt->execute()){
        echo "AFTER BINDING ALL DATA Error: unable to execute SQL statement: " . $stmt->error;
        }


    //getting the meter id from the database table meter
    $sql = "SELECT FROM meter WHERE meterno = ?";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        echo "GETTING METER ID Error: unable to prepare SQL statement: " . $conn->error;
        }
    $stmt->bind_param("s",$meterno);
    if(!$stmt->execute()){
        echo "AFTER BINDING METERNO Error: unable to execute SQL statement: " . $stmt->error;
        }
    $result = $stmt->get_result();
    if ($result->num_rows == 0){
        echo"invalid meter number";
    }
    $row = $result->fetch_assoc();
    $meterid = $row['meter_id'];



    //updating the customer table with the meter id
    $sql = "UPDATE customers SET meter_id = ? WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        echo "UPDATING CUSTOMER TABLE Error: unable to prepare SQL statement: " . $conn->error;
        }
    $stmt->bind_param("ii",$meterid,$consumerid);
    if(!$stmt->execute()){
        echo "AFTER BINDING CUSTOMER ID Error: unable to execute SQL statement: " . $stmt->error;
        }
    header("Refresh: 2; url=index.php");
    echo '<script>alert("meter added successfully");</script>';
    $stmt-> close();
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
        .form select{
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
    <label for="meterno"> meter no</label><br>
    <input type="text" name="meterno" required><br>
    <label for="date" >installation date:</label><br>
    <input type="date" name="installdate" required><br>
    <label for="status">status:</label><br>
    <select name="status" id="status">
        <option value="active" default>active</option>
        <option value="inactive"> inactive</option>
    </select><br>
    <label for="lastmeterreading">lastmeterreading</label><br>
    <input type="number" name="lastmeterreading" required><br>
    <label for="networkname">network name:</label><br>
    <input type="text" name="networkname" required><br>
    <label for="consumername ">consumername</label><br>
    <input type="text" name="consumername" value="<?php echo $_SESSION['customername'] ?>" readonly><br>
    <input type="submit" value="submit">
    </form>
    </div>
</body>
</html>