<?php
session_start();
include("database.php");
include("headerafterlogin.html");

// checking if session username is set or not
if (!isset($_SESSION["username"])) {
    header("location:login.php");
    exit();
}

// Ensuring the manager exists in the database
$username = $_SESSION['username'];
$sql = "SELECT * FROM managers WHERE managername = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("First Error: " . $conn->error);
}
$stmt->bind_param("s", $username);
if (!$stmt->execute()) {
    die("Second Error: " . $stmt->error);
}
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    header("location:login.php");
    exit();
}
$row = $result->fetch_assoc();
//getting manager id to use later
$managerid = $row['manager_id'];

// Fetch customer complaints
$sql = "SELECT * FROM customercomplaints where cstatus != 'active' and cstatus != 'done'";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}
$result = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $complaintid = intval($_POST['complaintid']);
    

  // make the cstatus from pending to active and add the managerwho selected it to the table 
    $sql = "UPDATE customercomplaints SET cstatus = 'active', manager_assigned = ? WHERE complaint_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("ii", $managerid, $complaintid);
    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }

    header("refresh:2; url=index.php");
    echo "<script>alert('Problem selected successfully');</script>";
    echo "Redirecting to home page in 2 seconds...";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Complaints</title>
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
</style>
<body>
    <h2><b>Problems written by customers</b></h2><br>

    <table >
        <tr>
            <th>Customer ID</th>
            <th>Date Filed</th>
            <th>Issue</th>
            <th>Description</th>
            <th>Select</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
            
            <tr>
                <td><?= htmlspecialchars($row['customerid']) ?></td>
                <td><?= htmlspecialchars($row['datefilled']) ?></td>
                <td><?= htmlspecialchars($row['issue']) ?></td>
                <td><?= htmlspecialchars($row['cdescription']) ?></td>

                <td>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                        <input type="hidden" name="complaintid" value="<?= htmlspecialchars($row['complaint_id']) ?>">
                       
                        <input type="submit" value="Select Problem">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table><br>

    <h2>Problems written by workers</h2>
    <table>
    <tr>
        <th> Worker ID</th>
        <th> Date Filed</th>
        <th> Issue</th>
        <th> Description</th>
        <th>inactive device no</th>
        <th> Select</th>
    </tr>


    <?php
    $sql = "SELECT * FROM workerproblems where pstatus != 'active' and pstatus != 'done'";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) : ?>
    <tr>
        <td><?= htmlspecialchars($row['workerid']) ?></td>
        <td><?= htmlspecialchars($row['dateadded']) ?></td>
        <td><?= htmlspecialchars($row['ptype']) ?></td>
        <td><?= htmlspecialchars($row['pdescription']) ?></td>
        <td> <?htmlspecialchars($row ['pnonnatno'])?></td>
        <td>
            <form action="veiwworkerprob.php" method="post">
                <input type="hidden" name="problemid" value="<?= htmlspecialchars($row['problemid']) ?>">
                <input type="submit" value="Select Problem">
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
    </table>
    <br>
    <a href="index.php">Back to Home</a>


</body>
</html>
