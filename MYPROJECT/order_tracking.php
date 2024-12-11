<?php
// Start the session
session_start();

$conn = mysqli_connect("localhost", "root", "", "project");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch orders from the database
$sql = "SELECT * FROM orders WHERE cus_id = 0";
$result = mysqli_query($conn, $sql);

// Retrieve order details from session
$order_details = $_SESSION['order_details'] ?? null;

if (!$order_details) {
    $error = "No order details found. Please place an order first.";
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #EDD77E;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #F3E5AB;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .order {
            background-color: #fff;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: left;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .order p {
            margin: 5px 0;
            color: #555;
        }

        .order-details {
            display: inline-block;
            text-align: left;
            background-color: #FFF;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .order-details h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .order-details p {
            margin: 10px 0;
            font-size: 16px;
        }

        .back-button, .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #CB8944;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .back-button:hover, .back-btn:hover {
            background-color: #a56632;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if (isset($order_details)) {
            echo "<div class='order-details'>";
            echo "<h2>Order Details</h2>";
            echo "<p><strong>Order ID:</strong> " . htmlspecialchars($order_details['order_id']) . "</p>";
            echo "<p><strong>Payment Method:</strong> " . htmlspecialchars($order_details['payment_method']) . "</p>";
            echo "</div>";
        } else {
            echo "<p style='color: red;'>" . htmlspecialchars($error) . "</p>";
        }
        ?>

        <h1>Your Orders</h1>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='order'>";
                echo "<p><strong>Order ID:</strong> " . $row['order_id'] . "</p>";
                echo "<p><strong>Item ID:</strong> " . $row['item_id'] . "</p>";
                echo "<p><strong>Status:</strong> " . $row['status'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No orders found.</p>";
        }
        ?>

        <a href="homepage.php" class="back-button">Back to Homepage</a>
        <a href="items.php" class="back-btn">Back to Shopping</a>
    </div>
</body>
</html>