<?php
include("headerafterlogin.html");
session_start();
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            /* text-align: center;
            margin: 50px; */
        }
        .profile-container {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: auto;
        }
        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #007BFF;
        }
        .upload-form {
            margin-top: 15px;
        }
        input[type="file"] {
            margin: 10px 0;
        }
        button {
            background: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>User Profile</h2>
        <img src="default-profile.jpg" alt="Profile Picture" class="profile-pic" id="profileImage">
        <form class="upload-form" action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="profile_pic" id="fileInput" accept="image/*" required>
            <button type="submit">Update Profile Picture</button>
        </form>
    </div>
</body>
</html>
