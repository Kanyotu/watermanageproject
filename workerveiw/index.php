<?php 
session_start();
include("database.php");
include("headerafterlogin.html");
if (!isset($_SESSION['username'])) {
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
    $result = $stmt->get_result();
    if($result->num_rows == 0){
        header("location:login.php");
        exit();
    }
    //get the manager id of the manager assigned to the worker
    $row = $result->fetch_assoc();
    $managerid = $row['manager_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    table {
        width: 100%;
        text-align: center;
        border :1px solid black;
    }
    th {
        background-color:rgb(34, 54, 97);
        height : 50px;
        border :1px solid black;
        color:rgb(129, 201, 217);
    }
    td {
        height : 30px;
        border :1px solid black;
    }
    h2 {
        text-align: center;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        font-size: 40px;
        text-decoration: underline;
        color:rgb(34, 54, 97);  
    }
    input[type=submit] {
        background-color: rgb(12, 62, 142);

        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        float: right;
        width: 100%;
    }
    #nowork {
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        font-size: 30px;
        color:rgb(77, 77, 77);
    }
    
</style>
<body>
    <h2>Work to do this week</h2> <br><br>
</body>
</html>
<?php


//this displays the complaits choosrn by the manager
$sql = "SELECT * FROM customercomplaints where cstatus = 'active' AND manager_assigned = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("2Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $managerid);
if (!$stmt->execute()) {
    die("2Error executing query: " . $stmt->error);
}
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo "<h3 id=\"nowork\">No work to do at the moment</h3>";
    exit();
}
while ($row = $result->fetch_assoc()) {

    $customerid = $row['customerid'];
    $sql = "SELECT * FROM customers WHERE customer_id = ?";
    $stmt2 = $conn->prepare($sql);
    if (!$stmt2) {
        die("3Error preparing statement: " . $conn->error);
    }
    $stmt2->bind_param("i", $customerid);
    if (!$stmt2->execute()) {
        die("3Error executing query: " . $stmt2->error);
    }
    $res= $stmt2->get_result();
    $ro= $res->fetch_assoc();
    $customername = $ro['customername'];
    $customeradress = $ro['customeraddress']; 
    $customerphoneno = $ro['customerphoneno'];
    $customertype = $ro['customertype'];
    echo "<table>";
    echo "<tr><th>Customer Name:</th>";
    echo "<th>Customer Address:</th>";
    echo "<th>Customer Phone Number:</th>";
    echo "<th>Customer Type:</th>";
    echo "<th>Issue:</th>";
    echo "<th> issue Description:</th>";
    echo "<th>select if done</th></tr>";

    echo "<tr>";
    echo "<td>" . $customername . "</td>";
    echo "<td>" . $customeradress . "</td>";
    echo "<td>" . $customerphoneno . "</td>";
    echo "<td>" . $customertype . "</td>";
    echo "<td>" . $row['issue'] . "</td>";
    echo "<td>" . $row['cdescription'] . "</td>";
    echo "<td><form action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" method='post'><input type='hidden' name='complaintid' value='" . $row['complaint_id'] . "'><input type='submit' value='done'></form></td>";
    echo "</tr>";
    echo "</table>";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //update the status of the complaint to done
        $complaintid = intval($_POST['complaintid']);
        $sql = "UPDATE customercomplaints SET cstatus = 'done' WHERE complaint_id = ?";
        $stmt1 = $conn->prepare($sql);
        if (!$stmt1) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt1->bind_param("i", $complaintid);
        if (!$stmt1->execute()) {
            die("4Error executing query: " . $stmt1->error);
        }
        if ($stmt1->affected_rows > 0) {
            header("Location: index.php?success=1"); // Pass a success flag
            exit();
        } else {
            echo "<h3>No record was updated.</h3>";
        }
    }
    
}
?>