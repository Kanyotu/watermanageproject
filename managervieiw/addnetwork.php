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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Network</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color:rgb(191, 204, 224);
            /* margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; */
        }
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 20px;
        }
        
        form {
            background-color:rgb(181, 200, 202);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        
        h3 {
            color: #2c3e50;
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 24px;
            text-align: center;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #34495e;
            font-weight: 500;
        }
        
        input[type="text"],
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        
        input[type="text"]:focus,
        select:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        input[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }
        
        input[type="submit"]:hover {
            background-color: #2980b9;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .select-group {
            display: flex;
            gap: 20px;
        }
        
        .select-group > div {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="form-container">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method='post'>
        <h3>Add a Network</h3>
        
        <div class="form-group">
            <label for="networkname">Network Name:</label>
            <input type="text" name="networkname" id="networkname" required>
        </div>
        
        <div class="form-group">
            <label for="from">From:</label>
            <input type="text" name="from" id="from" required>
        </div>
        
        <div class="form-group">
            <label for="to">To:</label>
            <input type="text" name="to" id="to" required>
        </div>
        
        <div class="form-group">
            <label for="pipematerial">Pipe Material:</label>
            <select name="pipematerial" id="pipematerial">
                <option value="PVC">PVC</option>
                <option value="Copper">Copper</option>
                <option value="Aluminium">Aluminium</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="pipediameter">Pipe Diameter:</label>
            <input type="text" name="pipediameter" id="pipediameter" required>
        </div>
        
        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        
        <input type="submit" value="Add Network">
    </form>
    </div>
</body>
</html>