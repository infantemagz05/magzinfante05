<?php
// Start output buffering to prevent premature output
ob_start();

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
        session_start(); // Start session here before any output

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
                $_SESSION['username'] = $login_username;

                // Redirect to items.php
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
    <title>CAKEYLICIOUS</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #EDD77E, #F3E5AB, #CB8944);
            color: #fff;
            height: 100vh;
            overflow: hidden; /* Prevent scrolling */
        }

        header {
            position: absolute;
            bottom: 40px; /* Adjusted to lower the title */
            left: 20px;
            text-align: left;
        }

        h1 {
            font-size: 5rem;
            margin: 0;
            font-family: Gothic;
            color: brown;
        }

        nav {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            font-size: 1rem;
            background: brown;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .button:hover {
            background: darkgoldenrod;
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #F3E5AB;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: absolute;
            top: 120px; /* Adjusted to place below navigation buttons */
            right: 50px; /* Align to the right side */
            transform: translateX(-130%);
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

        .submit-container input {
            width: 100%;
            padding: 10px;
            background-color: #CB8944;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-container input:hover {
            background-color: darkgoldenrod;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .register-link {
            margin-top: 15px;
        }

        .register-link a {
            color: #CB8944;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>CAKEYLICIOUS</h1>
        <p>"Where Every Slice Brings Delight!"</p>
    </header>

    <nav>
        <a href="homepage.php" class="button">Home</a>
        <a href="products.php" class="button">Products</a>
        <a href="services.php" class="button">Services</a>
        <a href="admin_login.php" class="button"> Admin</a>
    </nav>

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
                eyeIcon.innerHTML = '&#128064;';
            } else {
                passwordField.type = 'password';
                eyeIcon.innerHTML = '&#128065;';
            }
        });
    </script>
</body>
</html>