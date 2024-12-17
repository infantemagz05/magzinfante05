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

// Fetch all orders
$query = "SELECT * FROM orders ORDER BY date_ordered DESC";
$result = mysqli_query($conn, $query);

// Check if the query ran successfully
if (!$result) {
    die("Error fetching orders: " . mysqli_error($conn));
}

$total_price = 0; // Variable to store the sum of all total prices
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F3E5AB;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #EDD77E;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .container {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #DDD;
            padding: 10px;
            text-align: center;
        }
        table th {
            background-color: #4CAF50;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #F9F9F9;
        }
        table tr:hover {
            background-color: #F1F1F1;
        }
        .btn {
            text-decoration: none;
            padding: 8px 12px;
            color: white;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn-view {
            background-color: #2196F3;
        }
        .btn-view:hover {
            background-color: #1A7CCC;
        }
        .btn-delete {
            background-color: #F44336;
        }
        .btn-delete:hover {
            background-color: #D32F2F;
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
        .total-price {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
        /* New Back Button */
        .back-btn {
            background-color: brown;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            position: absolute;
            top: 20px;
            left: 20px;
            text-decoration: none;
            font-weight: bold;
        }
        .back-btn:hover {
            background-color: #993333;
        }
    </style>
</head>
<body>
    <!-- Back Button -->
    <a href="adminpage.php" class="back-btn">Back</a>

    <div class="header">
        <h1>Admin Dashboard</h1>
    </div>
    <div class="container">
        <h2>Order Management</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Item ID</th>
                    <th>Item Quantity</th>
                    <th>Item Price</th>
                    <th>Total Price</th> <!-- New column for total price -->
                    <th>Payment Method</th>
                    <th>Order Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): 
                    // Calculate the total price for this order
                    $order_total = $row['item_qty'] * $row['item_price'];
                    $total_price += $order_total; // Add to the overall total price
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['item_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['item_qty']); ?></td>
                        <td>₱<?php echo number_format((float)$row['item_price'], 2, '.', ''); ?></td>
                        <td>₱<?php echo number_format((float)$order_total, 2, '.', ''); ?></td> <!-- Show the total price for this order -->
                        <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                        <td><?php echo htmlspecialchars($row['date_ordered']); ?></td>
                        <td>
                            <a href="view_order.php?order_id=<?php echo urlencode($row['order_id']); ?>" class="btn btn-view">View</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if (mysqli_num_rows($result) === 0): ?>
                    <tr>
                        <td colspan="8">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- Display Total Price -->
        <div class="total-price">
            Total Income: ₱<?php echo number_format((float)$total_price, 2, '.', ''); ?>
        </div>
    </div>

    <!-- Logout Button -->
    <a href="homepage.php" class="logout-btn">Logout</a>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
