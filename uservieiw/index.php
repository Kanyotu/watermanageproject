<?php
session_start();
include("database.php");
// Prevent users from using the "Back" button to return to login.php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// If user is not logged in, redirect them to login.php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
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
include("headerafterlogin.html");
if(isset($_SESSION['username'])){
    echo "<h1 class=\"welcome\">Welcome " . $_SESSION['username'] . "</h1>";
}
else{
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Water - Dashboard</title>
    <style>
        /* General Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color:rgb(189, 208, 230);
            color: #333;
            /* padding: 20px; */
        }

        .container {
            max-width: 900px;
            margin: auto;
            text-align: center;
        }

        /* Header Section */
        .header {
            background-color: #0077b6;
            color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 36px;
            text-transform: uppercase;
        }

        .header p {
            font-size: 18px;
            margin-top: 10px;
        }

        .header a {
            color: #ffcc00;
            font-weight: bold;
            text-decoration: none;
        }

        .header a:hover {
            text-decoration: underline;
        }

        /* Water Status Sections */
        .status-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 20px;
        }

        .status-box {
            width: 48%;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.2s ease-in-out;
        }

        .status-box:hover {
            transform: scale(1.04);
        }

        .progress-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .progress-text {
            font-size: 18px;
            font-weight: bold;
        }

        .progress-bar {
            width: 70%;
            height: 12px;
            background: #ccc;
            border-radius: 6px;
            overflow: hidden;
        }

        .progress-fill {
            width: 0%;
            height: 100%;
            background: #0077b6;
            transition: width 1s ease-in-out;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .status-container {
                flex-direction: column;
                align-items: center;
            }
            .status-box {
                width: 90%;
                margin-bottom: 15px;
            }
        }
    </style>
    <script>
        //if a user has a meter no display a atag at bottom left to let him enter meter reading and display his/her bill to pay and due date
        //if a user has no meter no display a button to let him enter meter reading and display
       
    </script>
</head>
<body>

    <div class="container">
        <!-- Header Section -->
         
        <div class="header">
            <h1>Save Water, Save Life</h1>
            <p>To report water leakages, click <a href="#">here</a>. Always ensure that taps are turned off when not in use and fix leaks in your home. <strong>We are not responsible for home leaks!</strong></p>
        </div>

        <!-- Water Usability Status -->
        <div class="status-container">
            <div class="status-box">
                <h2>Water Usability Status</h2>
                <div class="progress-container">
                    <span class="progress-text">0%</span>
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                </div>
            </div>

            <div class="status-box">
                <h2>Water Usability Status</h2>
                <div class="progress-container">
                    <span class="progress-text">0%</span>
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
