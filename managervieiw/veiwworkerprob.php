<?php
session_start();
include("database.php");

$mname = $_SESSION['username'];
$sql = "SELECT * FROM managers WHERE managername = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("First Error: " . $conn->error);
}
$stmt->bind_param("s", $mname);
if (!$stmt->execute()) {
    die("Second Error: " . $stmt->error);
}
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$managerid = $row['manager_id'];

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $problemid = intval($_POST['problemid']);
    

  // make the cstatus from pending to active and add the managerwho selected it to the table 
    $sql = "UPDATE workerproblems SET cstatus = 'active', manager_assigned = ? WHERE problemid= ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("ii", $managerid, $problemid);
    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }
    echo "Problem selected successfully";
    header("location:index.php");
    exit();
}  