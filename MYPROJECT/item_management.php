<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "project");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to escape output to prevent XSS
function escape_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Handle adding a new item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item'])) {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $item_price = mysqli_real_escape_string($conn, $_POST['item_price']);
    $item_desc = mysqli_real_escape_string($conn, $_POST['item_desc']);

    if (!empty($item_name) && is_numeric($item_price) && !empty($item_desc)) {
        $sql = "INSERT INTO items (item_name, item_price, item_desc) 
                VALUES ('$item_name', $item_price, '$item_desc')";
        if (mysqli_query($conn, $sql)) {
            echo "<p>Item added successfully!</p>";
        } else {
            error_log("Error adding item: " . mysqli_error($conn));
            echo "<p>Error adding item. Please try again later.</p>";
        }
    } else {
        echo "<p>Error: Invalid input!</p>";
    }
}

// Handle updating an item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_item'])) {
    $item_id = (int)$_POST['item_id'];
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $item_price = mysqli_real_escape_string($conn, $_POST['item_price']);
    $item_desc = mysqli_real_escape_string($conn, $_POST['item_desc']);

    if ($item_id > 0 && !empty($item_name) && is_numeric($item_price) && !empty($item_desc)) {
        $sql = "UPDATE items 
                SET item_name = '$item_name', item_price = $item_price, 
                    item_desc = '$item_desc' 
                WHERE item_id = $item_id";
        if (mysqli_query($conn, $sql)) {
            echo "<p>Item updated successfully!</p>";
        } else {
            error_log("Error updating item: " . mysqli_error($conn));
            echo "<p>Error updating item. Please try again later.</p>";
        }
    } else {
        echo "<p>Error: Invalid input!</p>";
    }
}

// Handle deleting an item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_item'])) {
    $item_id = (int)$_POST['item_id'];

    if ($item_id > 0) {
        $sql = "DELETE FROM items WHERE item_id = $item_id";
        if (mysqli_query($conn, $sql)) {
            echo "<p>Item deleted successfully!</p>";
        } else {
            error_log("Error deleting item: " . mysqli_error($conn));
            echo "<p>Error deleting item. Please try again later.</p>";
        }
    } else {
        echo "<p>Error: Invalid ID!</p>";
    }
}

// Fetch all items from the database
$sql = "SELECT * FROM items";
$result = mysqli_query($conn, $sql);

// Display items and forms
echo "<h1>Item Management</h1>";

// Add Item Form
echo "<h2>Add New Item</h2>";
echo "<form method='POST'>";
echo "<label for='item_name'>Item Name:</label>";
echo "<input type='text' name='item_name' required>";
echo "<label for='item_price'>Price:</label>";
echo "<input type='number' step='0.01' name='item_price' required>";
echo "<label for='item_desc'>Description:</label>";
echo "<textarea name='item_desc' required></textarea>";
echo "<input type='submit' name='add_item' value='Add Item'>";
echo "</form>";

// Display Items
echo "<h2>Items</h2>";
while ($row = mysqli_fetch_assoc($result)) {
    $escaped_name = escape_output($row['item_name']);
    $escaped_price = escape_output($row['item_price']);
    $escaped_desc = isset($row['item_desc']) ? escape_output($row['item_desc']) : 'No description available';
    $escaped_id = escape_output($row['item_id']);

    echo "<div class='item-container'>";
    echo "<h3>$escaped_name</h3>";
    echo "<p>Price: $$escaped_price</p>";
    echo "<p>Description: $escaped_desc</p>";

    // Update Item Form
    echo "<form method='POST'>";
    echo "<input type='hidden' name='item_id' value='$escaped_id'>";
    echo "<label for='item_name'>Name:</label>";
    echo "<input type='text' name='item_name' value='$escaped_name' required>";
    echo "<label for='item_price'>Price:</label>";
    echo "<input type='number' step='0.01' name='item_price' value='$escaped_price' required>";
    echo "<label for='item_desc'>Description:</label>";
    echo "<textarea name='item_desc' required>$escaped_desc</textarea>";
    echo "<input type='submit' name='update_item' value='Update'>";
    echo "</form>";

    // Delete Item Form
    echo "<form method='POST'>";
    echo "<input type='hidden' name='item_id' value='$escaped_id'>";
    echo "<input type='submit' name='delete_item' value='Delete'>";
    echo "</form>";
    echo "</div>";
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color:#F3E5AB;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

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

        .item-container {
            width: 100%; /* Adjust this value as needed */
            max-width: 600px; /* Maximum width for larger screens */
            margin: 20px auto; /* Centers the container */
            padding: 15px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .item-container h3 {
            margin-top: 0;
        }

        form {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        form input, form textarea {
            padding: 8px;
            font-size: 16px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form input[type="submit"] {
            background-color: brown;
            color: white;
            border: none;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #993333;
        }

        .item-container form input[type="submit"]:first-child {
            margin-right: 10px;
        }
    </style>
</head>
<body>

    <!-- Back Button -->
    <a href="adminpage.php" class="back-btn">Back</a>

</body>
</html>
