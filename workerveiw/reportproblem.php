<?php

// use function PHPSTORM_META\type;
session_start();
include("headerafterlogin.html");
include("database.php");

if (!isset($_SESSION["username"])){
    header("Location: login.php");
    exit;
    }

 
$username = $_SESSION["username"];
$sql= "SELECT * FROM workers WHERE worker_name = ?";
$stmt = $conn->prepare($sql);
if(!$stmt){
    die(" first Error: " . $conn->error);
}
$stmt->bind_param("s", $username);

if(!$stmt ->execute()){
    die("second Error: " . $stmt->error);
}
$result = $stmt->get_result();

if($result->num_rows == 0){
    header("location:login.php");
    exit();
}
$row = $result->fetch_assoc();
$workerid = $row["worker_id"];


if($_SERVER["REQUEST_METHOD"]=="POST"){
    $type = filter_var($_POST["type"],FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_var($_POST["description"],FILTER_SANITIZE_SPECIAL_CHARS);
    $problem = "";
    $pumpnumber = 0;
    $networkname = "";
    $tankno = 0;
    $pnonnatno = null;
    $pstatus = "pending";

    if($type == "3"){
        $problem = filter_var($_POST["problem"],FILTER_SANITIZE_SPECIAL_CHARS);
        $type = $problem;
    } else if($type == "pump"){
        $pumpnumber = filter_var($_POST["pumpnumber"],FILTER_SANITIZE_NUMBER_INT);
        $pnonnatno = $pumpnumber;
    } else if($type == "network"){
        $networkname = filter_var($_POST["networkname"],FILTER_SANITIZE_SPECIAL_CHARS);
        $pnonnatno = $networkname;
    } else if($type == "tank"){
        $tankno = filter_var($_POST["tankno"],FILTER_SANITIZE_NUMBER_INT);
        $pnonnatno = $tankno;
    }

    $sql = "INSERT INTO workerproblems (worker_id, ptype, pdescription,pnonnatno,pstatus) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        die("Error: " . $conn->error);
    }
    $stmt->bind_param("isssss", $workerid, $type, $description, $problem ,$pnonnatno ,$pstatus);
    if(!$stmt->execute()){
        die("Error: " . $stmt->error);
    }
    echo "Problem reported successfully";
    header("location:index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script>
        function toggleTextarea() {
            var selectBox = document.getElementById("type");
            var textareaDiv = document.getElementById("problemDiv");
            var input = document.getElementById("pumpselect");
            var net = document.getElementById("netselect");
            var tank = document.getElementById("tankselect");

            // Hide all divs initially
            textareaDiv.style.display = "none";
            input.style.display = "none";
            net.style.display = "none";
            tank.style.display = "none";

            // Show the appropriate div based on the selected value
            if (selectBox.value == "network") {
                net.style.display = "block";
            } else if (selectBox.value == "tank") {
                tank.style.display = "block";
            } else if (selectBox.value == "pump") {
                input.style.display = "block";
            } else if (selectBox.value == "3") {
                textareaDiv.style.display = "block";
            }
        }

        // Ensure the correct div is shown on page load
        window.onload = function() {
            toggleTextarea();
        };
    </script>
</head>
<style>
    .form-container {
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
    }

    .form label {
        display: block;
        text-align: left;
        margin: 10px 0 5px;
        font-weight: bold;
    }

    .form input,
    .form select {
        width: 90%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .form textarea {
        height: 80px;
        width: 90%;
        resize: none;
    }

    .form textarea:focus {
        background-color: rgb(111, 205, 222);
    }

    .form input:hover {
        background-color: rgb(125, 167, 181);
    }

    .form input:focus {
        background-color: rgb(52, 105, 123);
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
        <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="type">Problem Type:</label>
            <select name="type" id="type" onchange="toggleTextarea()">
                <option value="pump" selected>Pump</option>
                <option value="network">Network</option>
                <option value="tank">Tank</option>
                <option value="3">Others</option>
            </select>

            <div id="problemDiv" style="display: none;">
                <label for="problem">Problem:</label><br>
                <textarea name="problem" id="problem"></textarea><br>
            </div>
            <div id="pumpselect" style="display: none;">
                <label for="pumpnumber">Pump number:</label><br>
                <input type="number" name="pumpnumber" id="pumpnumber"><br>
            </div>
            <div id="netselect" style="display: none;">
                <label for="networkname">Network name:</label><br>
                <input type="text" name="networkname" id="netname"><br>
            </div>
            <div id="tankselect" style="display: none;">
                <label for="tankno">Tank no:</label><br>
                <input type="number" name="tankno" id="tankno"><br>
            </div>

            <br>
            <label for="description">Description:</label>
            <textarea name="description" id="description"></textarea><br><br>
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>