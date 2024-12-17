<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$dbname = "project"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']); // Store plain password
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $contact_no = mysqli_real_escape_string($conn, $_POST['contact_no']);

        // Check if the username already exists in the database
        $stmt_check = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $message = "<p class='error-message'>Username already taken. Please choose a different username.</p>";
        } else {
            // If username is not taken, proceed with registration
            $stmt = $conn->prepare("INSERT INTO users (name, username, password, address, gender, contact_no) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $username, $password, $address, $gender, $contact_no);

            if ($stmt->execute()) {
                // Registration successful message
                $message = "<p class='success-message'>Registration successful! You can now log in.</p>";

                // Insert into user_account table
                $stmt_user_account = $conn->prepare("INSERT INTO user_account (username, password) VALUES (?, ?)");
                $stmt_user_account->bind_param("ss", $username, $password); // Storing plain password
                $stmt_user_account->execute();
                $stmt_user_account->close();

                // Redirect to login page after successful registration
                header("Location: homepage.php"); // Redirect to login page
                exit(); // Make sure to stop the script after redirection
            } else {
                $message = "<p class='error-message'>Error: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
        $stmt_check->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #EDD77E;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 120vh;
            margin: 0;
        }
        .container {
            background-color: #F3E5AB;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        .cake-logo {
            font-size: 60px;
            margin-bottom: 20px;
        }
        h2 {
            color: #333;
            margin-bottom: 30px;
        }
        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .input-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .input-group input,
        .input-group select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .submit-container {
            text-align: center;
        }
        .submit-container input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #CB8944;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-container input:hover {
            background-color: #CB8944;
        }
        .success-message {
            color: green;
            font-size: 14px;
            text-align: center;
        }
        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
        }
        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="cake-logo">🍰</div> <!-- Cake Emoji -->
        <h2>Register</h2>
        <form method="POST" action="">
            <div class="input-group">
                <label for="name">Name:</label>
                <input type="text" name="name" required>
            </div>
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="password" required>
                    <span id="eye-icon" class="eye-icon">&#128065;</span> <!-- Eye Icon -->
                </div>
            </div>
            <div class="input-group">
                <label for="address">Address:</label>
                <input type="text" name="address" required>
            </div>
            <div class="input-group">
                <label for="gender">Gender:</label>
                <select name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="input-group">
                <label for="contact_no">Contact No:</label>
                <input type="text" name="contact_no" required>
            </div>
            <div class="submit-container">
                <input type="submit" name="register" value="Register">
            </div>
        </form>

        <?php if (!empty($message)) echo $message; ?>

        <p>Have an account? <a href="homepage.php" class="button">Login now</a></p> <!-- Link to login page -->
    </div>

    <script>
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        eyeIcon.addEventListener('click', function() {
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.innerHTML = '&#128064;'; // Change to closed eye
            } else {
                passwordField.type = 'password';
                eyeIcon.innerHTML = '&#128065;'; // Change to open eye
            }
        });
    </script>
</body>
</html>
