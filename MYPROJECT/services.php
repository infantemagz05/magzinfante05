<?php
// services.php

// Example data for the services (this could be fetched from a database)
$services = [
    [
        'title' => 'Custom Cake Design',
        'description' => 'Get personalized cakes for all occasions, from birthdays to weddings. Choose your flavors, and design.',
    ],
    [
        'title' => 'Wedding Cakes',
        'description' => 'Design your dream wedding cake with us. We offer consultation and cake delivery services for your big day.',
    ],
    [
        'title' => 'Cupcakes & Mini Cakes',
        'description' => 'Perfect for smaller events or gifts. We offer a variety of flavors and designs to make your event memorable.',
    ],
    [
        'title' => 'Cake Delivery',
        'description' => 'Order online and have your cake delivered fresh to your doorstep. We offer fast and reliable delivery services.',
    ],
    [
        'title' => 'Cake Decorating Classes',
        'description' => 'Learn the art of cake decorating. Our classes will teach you everything from basic frosting to advanced designs.',
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cakeylicious Services</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #EDD77E;
            padding: 20px;
            text-align: center;
            color: white;
            position: relative;
        }
        .home-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 15px;
            background-color: brown;
            text-decoration: none;
            border-radius: 5px;
            color: white; /* Changed text color of the home button to white */
        }
        .home-btn:hover {
            background-color: darkgoldenrod;
        }
        h1 {
            color: black; /* Changed color of the "Cakeylicious Services" heading to black */
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 20px;
            
        }
        .service-card {
            background-color: #F3E5AB;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 30%;
            margin: 15px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .service-card img {
            width: 100%;
            border-radius: 8px 8px 0 0;
        }
        .service-card h3 {
            margin: 15px 0;
            font-size: 1.5em;
        }
        .service-card p {
            padding: 0 15px;
            font-size: 1.1em;
        }
    </style>
</head>
<body>

<header>
    <a href="homepage.php" class="home-btn">Home</a>
    <h1>Cakeylicious Services</h1>
    <p>"Explore our custom cake offerings and more!"</p>
</header>

<div class="container">
    <?php foreach ($services as $service): ?>
        <div class="service-card">
            <h3><?php echo $service['title']; ?></h3>
            <p><?php echo $service['description']; ?></p>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
