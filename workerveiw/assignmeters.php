<?php
include("database.php");
include("headerafterlogin.html");
session_start();

$_SESSION['customername'] = null; // Clear the session variable
// Check if user is logged in
if(!isset($_SESSION['username'])){
    header("location:login.php");
    exit;
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM workers WHERE worker_name = ?";
$stmt = $conn->prepare($sql);
if(!$stmt){
    die("Error: " . $conn->error);
}
$stmt->bind_param("s", $username);

if(!$stmt->execute()){
    die("Error: " . $stmt->error);
}

if($stmt->get_result()->num_rows == 0){
    header("location:login.php");
    exit();
}
$stmt->close();

// Get customers without meter IDs
$sql = "SELECT * FROM customers WHERE meter_id IS NULL";
$stmt = $conn->prepare($sql);
if(!$stmt){
    die("Error: " . $conn->error);
}
$stmt->execute();
$result = $stmt->get_result();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $customer_id = filter_var($_POST['customer_id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM customers WHERE customer_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Error: " . $conn->error);
    }
    $stmt->bind_param("i", $customer_id);
    if (!$stmt->execute()) {
        die("Error: " . $stmt->error);
    }
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        die("Error: Customer not found.");
    }
    $row = $result->fetch_assoc();
    $customername = $row['customername'];

    //set a variable to be used in the adddmeter.php page
    $_SESSION['customername'] = $customername;
    
    header("location: addmeters.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers Without Meters</title>
    <style>
        table {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: rgb(34, 54, 97);
            height: 50px;
            color: rgb(129, 201, 217);
        }
        td {
            height: 30px;
        }
        h2 {
            text-align: center;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            font-size: 40px;
            text-decoration: underline;
            color: rgb(34, 54, 97);  
        }
        .action-cell {
            width: 150px;
            
        }
    </style>
</head>
<body>
    <h2>Customers Without Meters</h2>
    <table>
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Customer ID</th>
                <th>Customer Address</th>
                <th>Customer Phone</th>
                <th>Customer Email</th>
                <th>Customer Type</th>
                <th class="action-cell">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['customername']) ?></td>
                <td><?= htmlspecialchars($row['customer_id']) ?></td>
                <td><?= htmlspecialchars($row['customeraddress']) ?></td>
                <td><?= htmlspecialchars($row['customerphoneno']) ?></td>
                <td><?= htmlspecialchars($row['customeremail']) ?></td>
                <td><?= htmlspecialchars($row['customertype']) ?></td>
                <td>
                    <form action="assignmeters.php" method="post">
                        <input type="hidden" name="customer_id" value="<?= $row['customer_id'] ?>">
                        <input type="submit" value="Assign Meter">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>