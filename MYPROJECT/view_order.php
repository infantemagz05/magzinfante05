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

// Retrieve order details from session
$order_details = $_SESSION['order_details'] ?? null;

if (!$order_details) {
    $error = "No order details found. Please place an order first.";
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order</title>
    <style>
        body {
            font-family: Arial;
            background-color: #F7F7F7;
            margin: 0;
            padding: 0;
            color: #333;
            text-align: center;
        }
        .container {
            margin-top: 50px;
            padding: 20px;
        }
        .order-details {
            display: inline-block;
            text-align: left;
            background-color: #FFF;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .order-details h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }
        .order-details p {
            margin: 10px 0;
            font-size: 16px;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #CB8944;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
        }
        .back-btn:hover {
            background-color: #B67335;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($order_details)): ?>
            <div class="order-details">
                <h2>Order Details</h2>
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_details['order_id']); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order_details['payment_method']); ?></p>
            </div>
        <?php else: ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Back Button -->
        <a href="items.php" class="back-btn">Back to Shopping</a>
    </div>
</body>
</html>
