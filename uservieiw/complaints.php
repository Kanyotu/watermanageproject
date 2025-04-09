<?php
include("database.php");
include("headerafterlogin.html");
session_start();
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}
//ensuring the user is in the database
$username = $_SESSION['username'];
$sql= "SELECT * FROM customers WHERE customername = ?";
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
if ($_SERVER['REQUEST_METHOD']=="POST"){
    $name = $_SESSION['username'];
    $sql = "SELECT * FROM customers WHERE customername = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt){
        echo "Error: " . $conn->error;
    }
    $stmt->bind_param("s", $name);
    if (!$stmt->execute()){
        echo "Error: " . $stmt->error;
    }
    $result = $stmt->get_result();
    if ($result->num_rows == 0){
        die("Error: customer does not exist");
    }
    $row = $result->fetch_assoc();
    $customer_id = $row['customer_id'];
    $type = filter_var($_POST['type'], FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
    $status = "pending";

    if ($type == "other"){
        $problem = filter_var($_POST['problem'], FILTER_SANITIZE_SPECIAL_CHARS);
    } else {
        $problem = $type;
    }

    $sql = "INSERT INTO customercomplaints (customerid, issue, cstatus, cdescription) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt){
        echo "Error: " . $conn->error;
    }
    $stmt->bind_param("isss", $customer_id, $problem, $status, $description);
    if (!$stmt->execute()){
        echo "Error: " . $stmt->error;
    }
    header("Refresh: 2; url=index.php");

                    // Then output your content
    echo '<script>alert("complaint submitted successfully");</script>';
    echo "Redirecting to home page in 3 seconds...";
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
    <script>
        function toggleTextarea() {
            var selectBox = document.getElementById("type");
            var textareaDiv = document.getElementById("problemDiv");

            if (selectBox.value == "other") {
                textareaDiv.style.display = "block"; // Show textarea
            } else {
                textareaDiv.style.display = "none"; // Hide textarea
            }
        }
    </script>
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    /* background-color: #f4f4f4; */
    /* display: flex; */
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.form {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    width: 45%;
    text-align: left;
    position: relative;
    left: 30% ;
}

.form label {
    font-weight: bold;
    display: block;
    margin: 10px 0 5px;
}

.form select, textarea, input[type="submit"] {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
}

.form select {
    cursor: pointer;
}

.form textarea {
    height: 80px;
    resize: none;
}
.form textarea:focus{
    background-color:rgb(111, 205, 222);
}

input[type="submit"] {
    background-color: #007BFF;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 18px;
    margin-top: 15px;
}

.form input[type="submit"]:hover {
    background-color: #0056b3;
}

#problemDiv {
    display: none;
}

</style>
<body>
    <form class="form" action="" method="post">  
        <label for ="type">Problem type:</label><br>
        <select name="type" id="type" onchange="toggleTextarea()">
            <option value ="meter issues" default>meter issues</option>
            <option value="other">other</option>
        </select><br>

        <div id="problemDiv" style="display: none;">
            <label for="problem">Problem:</label><br>
            <textarea name="problem" id="problem"></textarea><br>
        </div>

        <label for="description">Description</label><br>
        <textarea name ="description" id="description"></textarea><br>
        <input type="submit" name="submit" value="submit">
    </form>
</body>
</html>
