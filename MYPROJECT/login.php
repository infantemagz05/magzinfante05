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
    if (isset($_POST['login'])) {
        $login_username = mysqli_real_escape_string($conn, $_POST['login_username']);
        $login_password = mysqli_real_escape_string($conn, $_POST['login_password']);

        // Fetch user details from the user_account table
        $stmt = $conn->prepare("SELECT password FROM user_account WHERE username = ?");
        $stmt->bind_param("s", $login_username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($stored_password);
            $stmt->fetch();

            // Compare the entered password with the stored plain password
            if ($login_password === $stored_password) {
                // Start session and store user information
                session_start();
                $_SESSION['username'] = $login_username;

                // Redirect to items page
                header("Location: items.php");
                exit();
            } else {
                $message = "<p class='error-message'>Invalid username or password.</p>";
            }
        } else {
            $message = "<p class='error-message'>Invalid username or password.</p>";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Basic styles for the container */
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
            text-align: center; /* Keep center alignment for overall content */
        }

        .cake-logo {
            font-size: 60px; /* Adjust size as needed */
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left; /* Align the label and input to the left */
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            text-align: left; /* Ensure label is aligned left */
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            text-align: left; /* Align the text inside the input fields to the left */
        }

        .submit-container {
            text-align: center;
        }

        .submit-container input {
            width: 100%;
            padding: 10px;
            background-color:#CB8944;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-container input:hover {
            background-color: #CB8944;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
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
    <div class="cake-logo">🍰</div>
    <h2>Login</h2>
    <form method="POST" action="">
        <div class="input-group">
            <label for="login_username">Username:</label>
            <input type="text" name="login_username" required>
        </div>
        <div class="input-group">
            <label for="login_password">Password:</label>
            <div style="position: relative;">
                <input type="password" name="login_password" id="login_password" required>
                <span id="eye-icon" class="eye-icon">&#128065;</span>
            </div>
        </div>
        <div class="submit-container">
            <input type="submit" name="login" value="Login">
        </div>
    </form>

    <?php if (!empty($message)) echo $message; ?>

    <!-- Registration link -->
    <div class="register-link">
        <p>Not registered yet? <a href="registration.php">Register now</a></p>
    </div>
</div>


    <script>
        const passwordField = document.getElementById('login_password');
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
