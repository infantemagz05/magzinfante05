<?php
// Start the session to track the cart
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$dbname = "project"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle checkout when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    // Get customer ID (if using a logged-in user system) and payment method
    $cus_id = $_POST['cus_id'] ?? 'guest'; // Use 'guest' for non-logged-in users
    $payment_method = $_POST['payment_method'];

    // Generate a unique order ID
    $order_id = uniqid('ORD-', true);

    // Get the current date and time
    $date_ordered = date('Y-m-d H:i:s');

    // Check if cart is not empty
    if (!empty($_SESSION['cart'])) {
        // Insert order details into the database
        foreach ($_SESSION['cart'] as $item_id => $details) {
            $item_qty = $details['quantity'];

            // Retrieve the item price from the database
            $sql_price = "SELECT item_price FROM items WHERE item_id = '$item_id'";
            $result_price = $conn->query($sql_price);
            $item = $result_price->fetch_assoc();
            $item_price = $item['item_price'];

            // Insert order data into the orders table
            $sql = "INSERT INTO orders (order_id, item_id, item_qty, cus_id, item_price, date_ordered, payment_method)
                    VALUES ('$order_id', '$item_id', '$item_qty', '$cus_id', '$item_price', '$date_ordered', '$payment_method')";

            if ($conn->query($sql) !== TRUE) {
                die("Error: " . $sql . "<br>" . $conn->error);
            }
        }

        // Clear the cart after checkout
        unset($_SESSION['cart']);

        // Redirect to a success page with order confirmation
        header("Location: order_success.php?order_id=$order_id&payment_method=$payment_method");
        exit;
    } else {
        echo "<p>Your cart is empty. Please add items to your cart before proceeding to checkout.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial;
            background-color: #EDD77E;
            margin: 0;
            padding: 0;
        }

        .back-button {
            background-color: #CB8944;
            color: white;
            cursor: pointer;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
            display: inline-block;
            padding: 15px 15px;
            font-size: 14px;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: #CB8944;
        }

        .container {
            width: 70%;
            margin: 30px auto;
            background-color: #F3E5AB;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            text-align: center;
            color: black;
        }

        .form-group {
            margin-bottom: 20px;
            width: 100%;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group select,
        .form-group input[type="text"],
        .form-group input[type="submit"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input[type="submit"] {
            background-color: #CB8944;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        .form-group input[type="submit"]:hover {
            background-color: #CB8944s;
        }

        .cart-items {
            margin-bottom: 20px;
            width: 100%;
        }

        .cart-items ul {
            list-style-type: none;
            padding: 0;
        }

        .cart-items li {
            background-color: #fff;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cart-items h3 {
            margin-bottom: 10px;
            font-size: 1.2em;
        }
    </style>
</head>
<body>

    <!-- Back Button (Placed outside the container) -->
    <a href="items.php" class="back-button">&lt; Back to Items</a>

    <div class="container">
        <h2>Checkout</h2>

        <!-- Display Cart Items -->
        <div class="cart-items">
            <h3>Your Cart</h3>
            <?php if (!empty($_SESSION['cart'])): ?>
                <ul>
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $item_id => $details) {
                        $sql = "SELECT item_name, item_price FROM items WHERE item_id = '$item_id'";
                        $result = $conn->query($sql);
                        $item = $result->fetch_assoc();
                        $subtotal = $item['item_price'] * $details['quantity'];
                        $total += $subtotal;
                        echo "<li>
                                <strong>" . $item['item_name'] . "</strong><br>
                                Quantity: " . $details['quantity'] . "<br>
                                Price: ₱" . number_format($item['item_price'], 2) . "<br>
                                Subtotal: ₱" . number_format($subtotal, 2) . "
                              </li>";
                    }
                    ?>
                </ul>
                <h4>Total: ₱<?php echo number_format($total, 2); ?></h4>
            <?php else: ?>
                <p>Your cart is empty. Please add items to your cart before proceeding to checkout.</p>
            <?php endif; ?>
        </div>

        <!-- Checkout Form -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="cus_id">Customer ID (if applicable, otherwise leave blank):</label>
                <input type="text" name="cus_id" id="cus_id" placeholder="Enter your customer ID">
            </div>
            <div class="form-group">
                <label for="payment_method">Select Payment Method:</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="" disabled selected>Select payment method</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Cash on Delivery">Cash on Delivery</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" name="checkout" value="Place Order">
            </div>
        </form>
    </div>

</body>
</html>