<?php
// Start the session
session_start();

$conn = mysqli_connect("localhost", "root", "", "project");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch orders and item details, including the customer name
$sql = "
    SELECT 
        o.order_id, 
        o.item_qty,
        o.date_ordered, 
        o.status,  -- Added order status
        o.cus_id,  -- Added customer name from orders
        i.item_name, 
        i.item_price,
        (o.item_qty * i.item_price) AS total_price
    FROM 
        orders o
    LEFT JOIN 
        items i ON o.item_id = i.item_id  -- Use LEFT JOIN to include orders without matching items
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Check if any orders were found
$orders = [];
$total_amount = 0;

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
    $total_amount += $row['total_price'];
}

$stmt->close();
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
            max-width: 800px;
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

        .order-status {
            font-weight: bold;
            color: #4CAF50; /* Green for completed orders */
        }

        .total {
            font-size: 18px;
            color: #333;
            font-weight: bold;
            margin-top: 20px;
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
        <h1>Your Orders</h1>
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <div class="order">
                    <p><strong>Order ID:</strong> <?= htmlspecialchars($order['order_id']) ?></p>
                    <p><strong>Customer ID:</strong> <?= htmlspecialchars($order['cus_id']) ?></p> <!-- Display customer name -->
                    <p><strong>Item Name:</strong> <?= htmlspecialchars($order['item_name'] ?? 'No item available') ?></p> <!-- Display item name, handle NULLs -->
                    <p><strong>Quantity:</strong> <?= htmlspecialchars($order['item_qty']) ?></p>
                    <p><strong>Price (each):</strong> ₱<?= number_format($order['item_price'], 2) ?></p> <!-- Display price, handle NULLs -->
                    <p><strong>Total Price:</strong> ₱<?= number_format($order['total_price'], 2) ?></p>
                    <p><strong>Date Ordered:</strong> <?= htmlspecialchars($order['date_ordered']) ?></p>
                    <p><strong>Order Status:</strong> 
                        <span class="order-status"><?= htmlspecialchars($order['status']) ?></span>
                    </p>
                </div>
            <?php endforeach; ?>
            <p class="total">Total Amount to Pay: ₱<?= number_format($total_amount, 2) ?></p>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>

        <a href="homepage.php" class="back-button">Back to Homepage</a>
        <a href="items.php" class="back-btn">Back to Shopping</a>
    </div>
</body>
</html>
