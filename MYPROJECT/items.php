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

// Add item to cart if button is pressed
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Default to 1 if not specified

    // Check if the item already exists in the cart
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$item_id] = ['quantity' => $quantity];
    }
}

// Remove item from cart
if (isset($_POST['remove_from_cart'])) {
    $item_id = $_POST['item_id'];
    unset($_SESSION['cart'][$item_id]);
}

// Clear the cart
if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
}

// Handle search query
$search_query = "";
if (isset($_POST['search']) && !empty($_POST['search'])) {
    $search_query = $_POST['search'];
}

// Fetch items from the database based on the search query
$sql = "SELECT item_id, item_name, item_desc, item_price, item_image FROM items WHERE item_name LIKE ?";
$stmt = $conn->prepare($sql);
$search_term = "%" . $search_query . "%";
$stmt->bind_param("s", $search_term);
$stmt->execute();
$result = $stmt->get_result();

// Helper function to fetch item details
function getItemDetails($item_id, $conn) {
    $stmt = $conn->prepare("SELECT item_name, item_price, item_image FROM items WHERE item_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart System</title>
    <style>
        body {
            font-family: Arial;
            background-color: #EDD77E;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 30px auto;
            background-color: #F3E5AB;
            padding: 20px;
            padding-left: 30px; /* Adds more space to the left side */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: black;
        }
        .items-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 40px;
            margin-top: 20px;
        }
        .item-card, .cart-item {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: 30%;
            box-sizing: border-box;
            transition: transform 0.3s ease-in-out;
        }
        .item-card:hover {
            transform: translateY(-5px);
        }
        .item-card h3, .cart-item h3 {
            margin: 0;
            color: black;
        }
        .item-card p, .cart-item p {
            color: #555;
        }
        .item-card .price, .cart-item .price {
            color: #ff6347;
            font-weight: bold;
        }
        .item-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .item-card button, .cart-item button {
            background-color: #CB8944;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
            width: 100%;
        }
        .item-card button:hover, .cart-item button:hover {
            background-color: #af6d33;
        }
        .cart-btn {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        .cart-btn a {
            background-color: #4CAF50;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
        }
        .cart-btn a:hover {
            background-color: #45a049;
        }
        /* Styling for Home Button */
        .header-container {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin-left 20px;
            margin-bottom: 20px;
            margin-top: 50px; /* Moves the header lower */
        }
        .home-btn {
            position: absolute;
            left: 0;
            background-color: brown;
            color: white;
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .home-btn:hover {
            background-color: darkgoldenrod;
        }

        /* Styling for the Search Bar */
        .search-bar {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }
        .search-bar input {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 300px;
        }
        .search-bar button {
            padding: 10px 20px;
            background-color: #CB8944;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .search-bar button:hover {
            background-color: #CB8944;
        }
    </style>
</head>
<body>

<!-- Header with Home Button and Search Bar -->
<div class="header-container">
    <a href="homepage.php" class="home-btn">Home</a>
    <div class="search-bar">
        <form method="POST" action="">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search for items...">
            <button type="submit">Search</button>
        </form>
    </div>
</div>

<div class="container">
    <h2>CAKEYLICIOUS</h2>
    <div class="items-wrapper">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $formatted_price = "₱" . number_format($row["item_price"], 2);
                echo "<div class='item-card'>
                        <img src='" . htmlspecialchars($row["item_image"]) . "' alt='" . htmlspecialchars($row["item_name"]) . "'>
                        <h3>" . $row["item_name"] . "</h3>
                        <p>" . $row["item_desc"] . "</p>
                        <p class='price'>" . $formatted_price . "</p>
                        <form method='POST' action=''>
                            <input type='hidden' name='item_id' value='" . $row["item_id"] . "'>
                            <button type='submit' name='add_to_cart'>Add to Cart</button>
                        </form>
                      </div>";
            }
        } else {
            echo "<p>No items available at the moment.</p>";
        }
        ?>
    </div>
</div>

<div class="container">
    <h2>Your Cart</h2>
    <?php if (!empty($_SESSION['cart'])): ?>
        <div class="items-wrapper">
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item_id => $cart_item) {
                $item_details = getItemDetails($item_id, $conn);
                $subtotal = $item_details['item_price'] * $cart_item['quantity'];
                $total += $subtotal;

                echo "<div class='cart-item'>
                        <h3>" . $item_details['item_name'] . "</h3>
                        <p>Quantity: " . $cart_item['quantity'] . "</p>
                        <p class='price'>Subtotal: ₱" . number_format($subtotal, 2) . "</p>
                        <form method='POST' action=''>
                            <input type='hidden' name='item_id' value='" . $item_id . "'>
                            <button type='submit' name='remove_from_cart'>Remove</button>
                        </form>
                      </div>";
            }
            ?>
        </div>
        <h3>Total: ₱<?php echo number_format($total, 2); ?></h3>
        <form method="POST" action="checkout.php">
            <div class="cart-btn">
                <button type="submit">Proceed to Checkout</button>
            </div>
        </form>
        <form method="POST" action="">
            <div class="cart-btn">
                <button type="submit" name="clear_cart" style="background-color: red;">Clear Cart</button>
            </div>
        </form>
    <?php else: ?>
        <p>Your cart is empty. Add items to your cart to proceed to checkout.</p>
    <?php endif; ?>
</div>

</body>
</html>