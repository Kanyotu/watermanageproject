<?php 
include("database.php");
include("headerafterlogin.html");
session_start();
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}
//function to calculate the bill
function calculateBill($units) {
    $rate = 0.5; // Example rate per unit
    $fixedCharge = 10; // Example fixed charge
    return ($units * $rate) + $fixedCharge;
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
$result = $stmt->get_result();
if($result->num_rows == 0){
    header("location:login.php");
    exit();
}
$row = $result->fetch_assoc();
$customer_id = $row['customer_id'];
$meter_id = $row['meter_id'];

if($_SERVER['REQUEST_METHOD'] == "POST"){
    // Get the meter reading from the form
     $meter_reading = filter_var($_POST['meterreading'], FILTER_SANITIZE_NUMBER_INT);
     $status = "pending";

    //get last meter reading from the database
    $sql = "SELECT * FROM meters WHERE meter_id = ?";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        echo " first Error: " . $conn->error;
        }
    $stmt->bind_param("i", $meter_id);
    if(!$stmt ->execute()){
        echo "second Error: " . $stmt->error;
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $lastmeterreading = $row['lastmeterreading'];
    if ($lastmeterreading == null) {
        $lastmeterreading = 0; // Set to 0 if no previous reading exists
    }

    //minus the last meter reading from the current one to get the units consumed
    if ($meter_reading < $lastmeterreading) {
        echo '<script>alert("Error: Current meter reading cannot be less than the last reading.");</script>';
        exit();
    }
    if ($meter_reading == $lastmeterreading) {
        echo '<script>alert("Error: Current meter reading cannot be equal to the last reading.");</script>';
        exit();
    }
    $units = $meter_reading - $lastmeterreading;
    $billamount = calculateBill($units);

    $dueDate = date('Y-m-d', strtotime('+14 days'));
    $_SESSION['dueDate'] = $dueDate; // Store due date in session for later use
    $_SESSION['billamount'] = $billamount; // Store bill amount in session for later use
    

    // Insert the meter reading into the database
    $sql = "UPDATE meters SET lastmeterreading = ? WHERE meter_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt){
        echo "Error: " . $conn->error;
    }
    $stmt->bind_param("ii", $customer_id, $meter_reading);
    
    if (!$stmt->execute()){
        echo "Error: " . $stmt->error;
    } 
    // Insert the bill details into the database
    $sql = "INSERT INTO bills (customerid, meter_id, ammount, duepaydate,bstatus) VALUES (?, ?, ?, ?,?)";
    $stmt = $conn->prepare($sql);   
    if (!$stmt){
        echo "Error: " . $conn->error;
    }
    $stmt->bind_param("iisss", $customer_id, $meter_id, $billamount, $dueDate, $status);
    if (!$stmt->execute()){
        echo "Error: " . $stmt->error;
    }
    echo '<script>alert("Meter reading submitted successfully!");</script>';
        echo '<script>alert("Your bill amount is: ' . $billamount . ' and the due date is: ' . $dueDate . '");</script>';
        header("Refresh: 2; url=index.php");
        echo 'Redirecting to home page in 2 seconds...';
        exit();
    $stmt->close();


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meter Reading Submission</title>
    <style>
        /* Form container styling */
        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 2rem;
            font-family: 'Segoe UI', system-ui, sans-serif;
            background-color: #f9f9f9;
            width: 50%;
        }

        /* Input container with modern floating label effect */
        .input-container {
            position: relative;
            margin-bottom: 2rem;
        }

        label {
            position: absolute;
            top: 1rem;
            left: 0.5rem;
            color: #666;
            font-size: 1rem;
            transition: all 0.3s ease;
            pointer-events: none;
            background: white;
            padding: 0 0.5rem;
        }

        input[type="number"] {
            width: 100%;
            padding: 1rem 0.75rem;
            font-size: 1rem;
            border: 2px solid #ddd;
            border-radius: 4px;
            transition: all 0.3s ease;
            background-color: transparent;
        }

        input[type="number"]:focus {
            border-color: #4a90e2;
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
        }

        /* Floating label effect */
        input[type="number"]:focus + label,
        input[type="number"]:not(:placeholder-shown) + label {
            top: -0.5rem;
            left: 0.8rem;
            font-size: 0.8rem;
            color: #4a90e2;
        }

        /* Animated underline effect */
        .bar {
            position: relative;
            display: block;
            width: 100%;
        }

        .bar:before, .bar:after {
            content: '';
            height: 2px;
            width: 0;
            bottom: 0;
            position: absolute;
            background: #4a90e2;
            transition: all 0.3s ease;
        }

        .bar:before {
            left: 50%;
        }

        .bar:after {
            right: 50%;
        }

        input[type="number"]:focus ~ .bar:before,
        input[type="number"]:focus ~ .bar:after {
            width: 50%;
        }

        /* Submit button styling */
        input[type="submit"] {
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #357abD;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        input[type="submit"]:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
        <div class="input-container">
            <input type="number" id="meterreading" name="meterreading" placeholder=" " required>
            <label for="meterreading">Enter your meter reading</label>
            <span class="bar"></span><br><br>
            <input type="submit" value="Submit">
        </div>
    </form>
</body>
</html>