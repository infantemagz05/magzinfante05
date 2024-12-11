<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "project");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check for a POST request to update the status
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    if (!empty($_POST['order_id']) && !empty($_POST['status'])) {
        $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);

        // Prepare and execute the SQL query using prepared statements
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
        $stmt->bind_param("si", $status, $order_id); // "si" means string and integer
        if ($stmt->execute()) {
            echo "<p>Status updated successfully for Order ID: $order_id .</p>";
        } else {
            echo "<p>Error updating status: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Error: Order ID or Status is missing.</p>";
    }
}

// Fetch all orders from the database
$sql = "SELECT * FROM orders";
$result = mysqli_query($conn, $sql);

// Display orders and a form for updating their status
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F3E5AB;
        }

        /* Header */
        h1 {
            text-align: center;
            margin-top: 50px;
            color: #4CAF50;
        }

        /* Back Button */
        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: brown;
            color: white;
            font-weight: bold;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: #993333;
        }

        /* Order Container */
        .order-container {
            background-color: #fff;
            padding: 20px;
            margin: 20px auto;
            width: 80%;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .order-container p {
            font-size: 18px;
            margin: 10px 0;
        }

        /* Form */
        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
        }

        form label {
            font-weight: bold;
            margin-bottom: 8px;
        }

        form select, form input[type="submit"] {
            padding: 8px;
            font-size: 16px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        form input[type="submit"] {
            background-color: brown;
            color: white;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #993333;
        }

        /* Status Dropdown */
        select {
            width: 200px;
        }
    </style>
</head>
<body>

    <!-- Back Button -->
    <a href="adminpage.php" class="back-btn">Back</a>

    <h1>Order Management</h1>

    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <div class="order-container">
            <p><strong>Order ID:</strong> <?php echo $row['order_id']; ?></p>
            <p><strong>Current Status:</strong> <?php echo $row['status']; ?></p>
            
            <form method="POST">
                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                <label for="status">Change Status:</label>
                <select name="status">
                    <option value="Pending" <?php echo ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="Shipped" <?php echo ($row['status'] == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                    <option value="Delivered" <?php echo ($row['status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                </select>
                <input type="submit" value="Update">
            </form>
        </div>
    <?php endwhile; ?>

    <?php 
    // Close the database connection
    mysqli_close($conn);
    ?>
</body>
</html>
