-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2024 at 02:59 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_user`
--

CREATE TABLE `admin_user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_user`
--

INSERT INTO `admin_user` (`id`, `username`, `password`) VALUES
(1, 'magz', 'infante'),
(2, 'rose', 'marie'),
(3, 'tine', 'bande'),
(4, 'jade', 'ondis');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(55) NOT NULL,
  `item_price` decimal(10,2) NOT NULL,
  `item_desc` varchar(255) NOT NULL,
  `item_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `item_price`, `item_desc`, `item_image`) VALUES
(1, 'REDVELVET CAKE', 500.00, 'Soft red velvet layers with tangy cream cheese buttercream.', 'img1.jpg'),
(2, 'VANILLA BEAN SPONGE CAKE', 350.00, 'Light, fluffy vanilla sponge with a delicate, fragrant flavor.', ''),
(3, ' COCONUT CREAM CAKE', 460.00, 'Moist coconut cake with creamy frosting and toasted coconut flakes.', ''),
(4, ' TIRAMISU CAKE', 580.00, 'Espresso-soaked cake layers with mascarpone cream and cocoa powder.', ''),
(5, 'STRAWBERRY SHORTCAKE', 370.00, 'Fluffy cake with sweetened strawberries and fresh whipped cream.', ''),
(6, 'SALTED CARAMEL CAKE ', 400.00, 'Decadent caramel cake with a silky salted caramel drizzle.', ''),
(7, 'LEMON RASPBERRY CAKE', 420.00, 'Zesty lemon cake with fresh raspberries and a tangy glaze.', ''),
(8, 'CHOCOLATE TRUFFLE CAKE', 500.00, 'Rich, moist chocolate cake with smooth ganache and a dusting of cocoa.', ''),
(9, ' MATCHA  CAKE', 600.00, 'Delicate, earthy matcha cake with a light, creamy frosting.', ''),
(10, 'PINEAPPLE CAKE', 360.00, 'Buttery cake topped with caramelized pineapple and a hint of brown sugar.', ''),
(11, 'HONEY BROWN CAKE', 540.00, 'sweet honey cake ', ''),
(12, 'BANANA CAKE', 450.00, 'It is made by banana flavor', '');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_qty` int(11) NOT NULL,
  `cus_id` int(11) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `item_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `date_ordered` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `item_id`, `item_qty`, `cus_id`, `payment_method`, `item_price`, `total_price`, `date_ordered`, `status`) VALUES
(1, 0, 1, 3, 0, 'Credit Card', 500.00, 0.00, '2024-12-16', 'pending'),
(2, 0, 2, 2, 0, 'Cash on Delivery', 350.00, 0.00, '2024-12-16', 'pending'),
(3, 0, 3, 3, 0, 'Cash on Delivery', 460.00, 0.00, '2024-12-16', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `username` varchar(11) NOT NULL,
  `password` varchar(55) NOT NULL,
  `address` varchar(55) NOT NULL,
  `gender` char(1) NOT NULL,
  `contact_no` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `username`, `password`, `address`, `gender`, `contact_no`) VALUES
(1, 'malanie', 'mel', 'lanie', 'sugcad', 'F', '09775873452'),
(2, 'gabriel', 'gab', 'riel', 'basud', 'M', '09587443698'),
(3, 'delia', 'del', 'lia', 'ilaod', 'F', '09775873452');

-- --------------------------------------------------------

--
-- Table structure for table `user_account`
--

CREATE TABLE `user_account` (
  `user_id` int(11) NOT NULL,
  `username` varchar(55) NOT NULL,
  `password` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_account`
--

INSERT INTO `user_account` (`user_id`, `username`, `password`) VALUES
(1, 'mel', 'lanie'),
(2, 'gab', 'riel'),
(3, 'del', 'lia');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_user`
--
ALTER TABLE `admin_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_account`
--
ALTER TABLE `user_account`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_user`
--
ALTER TABLE `admin_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_account`
--
ALTER TABLE `user_account`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
