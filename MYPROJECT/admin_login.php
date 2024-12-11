<?php
// Database connection parameters
$servername = "localhost";  // Change to your database server
$username = "root";         // Your database username
$password = "";             // Your database password
$dbname = "project";        // The name of your database

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get admin username and password from the form
    $adminUsername = $_POST['username'];
    $adminPassword = $_POST['password'];

    // Sanitize input to prevent SQL injection
    $adminUsername = mysqli_real_escape_string($conn, $adminUsername);
    $adminPassword = mysqli_real_escape_string($conn, $adminPassword);

    // Insert the admin user into the database
    $sql = "INSERT INTO admin_user (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $adminUsername, $adminPassword);

    if ($stmt->execute()) {
        // Redirect to adminpage.php after successful insertion
        header("Location: adminpage.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!-- HTML Form for Adding Admin User -->
<!DOCTYPE html>
<html>
<head>
    <title>Add Admin User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #EDD77E;
        }

        .container {
            width: 100%;
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #F3E5AB;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .cake-logo {
            font-size: 60px;
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .submit-container {
            text-align: center;
        }

        .submit-container button {
            width: 100%;
            padding: 10px;
            background-color: #CB8944;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-container button:hover {
            background-color: #CB8944;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="cake-logo">🍰</div>
        <h2>Add Admin User</h2>
        <form method="POST" action="">
            <div class="input-group">
                <label for="username">Admin Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Admin Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="submit-container">
                <button type="submit">Add Admin User</button>
            </div>
        </form>
    </div>
</body>
</html>