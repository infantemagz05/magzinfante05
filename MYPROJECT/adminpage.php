<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <style>
        /* Set background color */
        body {
            background-color: #EDD77E;
            font-family: Arial, sans-serif;
        }

        /* Center the title */
        h1 {
            text-align: center;
            font-size: 36px;
            margin-top: 50px;
            color: #4E2A1D; /* Dark brown color for title */
        }

        /* Style the button container */
        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px; /* Space between buttons */
            margin-top: 40px;
        }

        /* Style the buttons */
        .admin-button {
            background-color: #6B3E26; /* Brown color for buttons */
            color: white;
            border: none;
            padding: 15px 25px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        /* Button hover effect */
        .admin-button:hover {
            background-color: darkgoldenrod; /* Darker brown when hovered */
        }

        /* Add responsiveness */
        @media (max-width: 768px) {
            .admin-button {
                padding: 12px 20px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <h1>Admin Panel</h1>

    <div class="button-container">
        <button class="admin-button" onclick="location.href='dashboard.php'">Dashboard</button>
        <button class="admin-button" onclick="location.href='manage_orders.php'">Manage Orders</button>
        <button class="admin-button" onclick="location.href='item_management.php'">Manage Items</button>
        <!-- Added Home button -->
        <button class="admin-button" onclick="location.href='homepage.php'">Home</button>
    </div>

</body>
</html>
