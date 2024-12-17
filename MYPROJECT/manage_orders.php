<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize messages
$success_message = $error_message = "";

// Handling POST requests for status update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['order_id']) && !empty($_POST['status'])) {
        $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);

        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
        $stmt->bind_param("si", $status, $order_id);
        if ($stmt->execute()) {
            $success_message = "Status updated successfully for Order ID: $order_id.";
        } else {
            $error_message = "Error updating status: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Error: Order ID or Status is missing.";
    }
}

// Query to fetch orders and related user information, ensuring user_id and order_id match
$sql = "
    SELECT 
        o.order_id, 
        o.item_qty, 
        o.date_ordered, 
        i.item_name, 
        i.item_price, 
        (o.item_qty * i.item_price) AS total_price,
        o.status,
        u.user_id,
        u.name,
        u.contact_no,
        u.address
    FROM 
        orders o
    LEFT JOIN 
        items i ON o.item_id = i.item_id
    LEFT JOIN
        users u ON u.user_id = u.user_id
    WHERE 
        o.order_id = u.user_id  -- Ensures that order_id and user_id match
    ORDER BY 
        o.order_id
";

// Fetch results
$result = mysqli_query($conn, $sql);

// Check if any rows were returned
if (!$result) {
    $error_message = "Query failed: " . mysqli_error($conn);
} elseif (mysqli_num_rows($result) == 0) {
    $error_message = "No orders found.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>

    <!-- CSS Styles -->
    <style>
        body { font-family: Arial, sans-serif; background-color: #F3E5AB; }
        h1 { text-align: center; color: #4CAF50; margin-top: 20px; }
        table { width: 90%; margin: 20px auto; border-collapse: collapse; }
        th, td { padding: 10px; text-align: center; border: 1px solid #ddd; }
        th { background-color: #4CAF50; color: white; }
        td { background-color: #FFF9C4; }
        form { display: inline-block; }
        select, input[type="submit"] { padding: 5px 10px; margin: 2px; border-radius: 4px; border: 1px solid #ccc; }
        input[type="submit"] { background-color: brown; color: white; cursor: pointer; }
        input[type="submit"]:hover { background-color: #993333; }
        .back-btn { text-decoration: none; padding: 10px 20px; background-color: brown; color: white; border-radius: 5px; position: fixed; top: 10px; left: 10px; }
        .back-btn:hover { background-color: #993333; }
    </style>

</head>
<body>

    <a href="adminpage.php" class="back-btn">Back</a>
    <h1>Order Management</h1>

    <?php if ($success_message): ?>
        <div class="alert success"><?php echo $success_message; ?></div>
    <?php elseif ($error_message): ?>
        <div class="alert error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if (!$error_message && $result): ?>
        <div class="order-container">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Customer Name</th>
                        <th>Contact No.</th>
                        <th>Address</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price (Each)</th>
                        <th>Total Price</th>
                        <th>Date Ordered</th>
                        <th>Current Status</th>
                        <th>Change Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo $row['order_id']; ?></td>
                            <td><?php echo $row['user_id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['contact_no']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                            <td><?php echo $row['item_name']; ?></td>
                            <td><?php echo $row['item_qty']; ?></td>
                            <td>₱<?php echo number_format($row['item_price'], 2); ?></td>
                            <td>₱<?php echo number_format($row['total_price'], 2); ?></td>
                            <td><?php echo $row['date_ordered']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                    <select name="status" required>
                                        <option value="Pending" <?php echo ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Shipped" <?php echo ($row['status'] == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="Delivered" <?php echo ($row['status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                                    </select>
                                    <input type="submit" value="Update">
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php mysqli_close($conn); ?>
</body>
</html>
