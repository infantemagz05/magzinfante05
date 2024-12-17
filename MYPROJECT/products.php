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
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #EDD77E;
            margin: 0;
            padding: 0;
        }

        /* Home Button */
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
            margin-bottom: 20px;
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
            background-color: #af6d33;
        }

        /* Main Container */
        .container {
            width: 90%;
            margin: 30px auto;
            background-color: #F3E5AB;
            padding: 20px;
            padding-left: 30px; /* Adds more space to the left side */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Items Wrapper Section */
        .items-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 40px;
            margin-top: 20px;
        }

        /* Item Card */
        .item-card {
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

        .item-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .item-card h3 {
            margin: 0;
            color: black;
        }

        .item-card p {
            color: #555;
        }

        .item-card .price {
            color: #ff6347;
            font-weight: bold;
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

<!-- Items Display Section -->
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
                      </div>";
            }
        } else {
            echo "<p>No items available at the moment.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>