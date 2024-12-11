<?php
// Start the session
session_start();

// Database connection parameters
$servername = "localhost";  // Change to your database server
$username = "root";         // Your database username
$password = "";             // Your database password
$dbname = "project";        // The name of your database

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Order details from query parameters
$order_id = $_GET['order_id'] ?? 'Unknown';
$payment_method = $_GET['payment_method'] ?? 'Unknown';

// Store order details in session
$_SESSION['order_details'] = [
    'order_id' => $order_id,
    'payment_method' => $payment_method
];

// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <style>
        body {
            font-family: Arial;
            background-color: #EDD77E;
            margin: 0;
            padding: 0;
            text-align: center;
            color: black;
            position: relative;
        }
        .container {
            margin-top: 100px;
        }
        .container h1 {
            color: #4CAF50;
        }
        .container p {
            font-size: 18px;
            margin: 20px;
        }
        .btn {
            background-color: #CB8944;
            color: white;
            font-weight: bold;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin: 10px;
        }
        .btn:hover {
            background-color: #B67335;
        }
        .logout-btn {
            background-color: brown;
            color: white;
            font-weight: bold;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            position: fixed;
            bottom: 20px;
            right: 20px;
        }
        .logout-btn:hover {
            background-color: #993333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Placed Successfully!</h1>
        <p>Your order ID is <strong><?php echo htmlspecialchars($order_id); ?></strong>.</p>
        <p>Payment Method: <strong><?php echo htmlspecialchars($payment_method); ?></strong>.</p>
        
        <!-- View Order Button -->
        <p><a href="order_tracking.php" class="btn">View Order</a></p>

        <!-- Continue Shopping Button -->
        <p><a href="items.php" class="btn">Continue Shopping</a></p>
    </div>

    <!-- Logout Button -->
    <a href="homepage.php" class="logout-btn">Logout</a>
</body>
</html>
