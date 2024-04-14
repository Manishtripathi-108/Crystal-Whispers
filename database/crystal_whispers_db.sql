-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2024 at 08:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crystal_whispers_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `AdminID` int(11) NOT NULL,
  `AdminImage` text NOT NULL DEFAULT 'profile.png',
  `AdminName` varchar(100) NOT NULL,
  `AdminPassword` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`AdminID`, `AdminImage`, `AdminName`, `AdminPassword`) VALUES
(1, 'Display_Picture_2.jpeg', 'Admin', '$2y$10$YT3EV8anDweytG2W42UnTuSMxjau/R2QlQPFGxhGYebaynC6rAaay');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `CartID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryName`) VALUES
(1, 'Rings'),
(2, 'Necklaces'),
(3, 'Earrings'),
(4, 'Bracelets'),
(5, 'Pendants'),
(6, 'Brooches'),
(7, 'Anklets'),
(8, 'Chains'),
(9, 'Bangles');

-- --------------------------------------------------------

--
-- Table structure for table `contactus`
--

CREATE TABLE `contactus` (
  `ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `occasions`
--

CREATE TABLE `occasions` (
  `OccasionID` int(11) NOT NULL,
  `OccasionName` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `occasions`
--

INSERT INTO `occasions` (`OccasionID`, `OccasionName`) VALUES
(1, 'Wedding'),
(2, 'Anniversary'),
(3, 'Birthday'),
(4, 'Graduation'),
(5, 'Valentine\'s Day'),
(6, 'Christmas'),
(7, 'New Year\'s Eve'),
(8, 'Prom'),
(9, 'Date Night');

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `OrderItemsID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `UnitPrice` double NOT NULL,
  `TotalPrice` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `RCVName` varchar(100) NOT NULL,
  `RCVEmail` varchar(100) NOT NULL,
  `RCVPhone` bigint(20) NOT NULL,
  `PayMethod` varchar(100) NOT NULL,
  `PayStatus` varchar(50) NOT NULL DEFAULT 'Pending',
  `ShipAddress` text NOT NULL,
  `MsgSeller` text NOT NULL,
  `TotalAmount` double NOT NULL,
  `OrderStatus` varchar(100) NOT NULL DEFAULT 'Processing',
  `OrderCreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `ProImg1` text NOT NULL DEFAULT 'product.png',
  `ProImg2` text DEFAULT NULL,
  `ProImg3` text DEFAULT NULL,
  `ProductName` varchar(100) NOT NULL,
  `ProductTargetGender` varchar(10) NOT NULL,
  `ProductPrice` int(11) NOT NULL,
  `ProductDiscount` int(11) NOT NULL,
  `ProductMaterial` varchar(100) NOT NULL,
  `ProductWeight` float NOT NULL,
  `ProductColor` varchar(100) NOT NULL,
  `OccasionID` varchar(100) NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `ProductStock` int(11) NOT NULL,
  `ProductUnitsSold` int(11) NOT NULL,
  `ProductCreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `ProductRating` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `ProImg1`, `ProImg2`, `ProImg3`, `ProductName`, `ProductTargetGender`, `ProductPrice`, `ProductDiscount`, `ProductMaterial`, `ProductWeight`, `ProductColor`, `OccasionID`, `CategoryID`, `ProductStock`, `ProductUnitsSold`, `ProductCreatedAt`, `ProductRating`) VALUES
(13, 'Male_White_New_Braclet_0_13_1712943412.jpg', 'Male_White_New_Braclet_1_13_1712943412.jpg', 'Male_White_New_Braclet_2_13_1712943412.jpg', 'Braclet', 'Male', 8980, 50, 'Platinum', 33, 'White', '1', 4, 10, 0, '2024-04-12 23:06:52', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `RatingID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Rating` float NOT NULL,
  `ReviewText` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `reviews`
--
DELIMITER $$
CREATE TRIGGER `update_product_rating` AFTER INSERT ON `reviews` FOR EACH ROW BEGIN
    DECLARE avg_rating DECIMAL(5,2);
    DECLARE product_id INT;
    
    -- Get the product ID for the new review
    SELECT ProductID INTO product_id FROM reviews WHERE RatingID = NEW.RatingID;
    
    -- Calculate the average rating for the product
    SELECT AVG(rating) INTO avg_rating FROM reviews WHERE ProductID = product_id;
    
    -- Update the productrating in the products table
    UPDATE products SET ProductRating = avg_rating WHERE ProductID = product_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) DEFAULT NULL,
  `Gender` varchar(10) DEFAULT NULL,
  `UserImage` text NOT NULL DEFAULT 'profile.png',
  `UserEmail` varchar(150) NOT NULL,
  `UserPassword` varchar(100) NOT NULL,
  `ShopReview` text DEFAULT NULL,
  `Address1` varchar(300) DEFAULT NULL,
  `Address2` varchar(300) DEFAULT NULL,
  `City` varchar(100) DEFAULT NULL,
  `Zip` int(11) DEFAULT NULL,
  `State` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FirstName`, `LastName`, `Gender`, `UserImage`, `UserEmail`, `UserPassword`, `ShopReview`, `Address1`, `Address2`, `City`, `Zip`, `State`) VALUES
(1, 'Manish', NULL, '', '', 'f7e9974f7d702b5cc8b34b79c1ae276a9fa07453d5007fae23407021c9a60f2c', '$2y$10$ymSbCMxEeKlI7.uQXVT5dOLUERES4C1IV0FoZ8Z7jCn37NWdMjIs6', '', '', '', '', 0, ''),
(2, 'Rahul Kumar', NULL, '', '', '5c6551e24bfb8c3a542db4e4d1bf4906288b558766e29233cf18ea022499060d', '$2y$10$xFkVFv/F7F7hvmRejsM5peayT7Iz/ihILZN.KvMtihokq.CLhR906', '', '', '', '', 0, ''),
(3, 'Ashborn', NULL, '', '', 'f498b5701b47ea8b4aa303ca86fd082760dea42b38949fb8c34dfa574ef8a6cf', '$2y$10$rpwPblJX6hAhd2saNoAhAOwMYrQ5c2DJZm0DKhLs5dfx5rFlQckee', '', '', '', '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

CREATE TABLE `workers` (
  `WorkerID` int(11) NOT NULL,
  `WorkerImage` text NOT NULL DEFAULT 'profile.png',
  `WorkerName` varchar(100) NOT NULL,
  `WorkerPosition` varchar(100) NOT NULL,
  `WorkerGender` varchar(50) NOT NULL,
  `WorkerEmail` varchar(100) NOT NULL,
  `WorkerPhone` varchar(50) NOT NULL,
  `WorkerAddress` text NOT NULL,
  `WorkerDescription` text DEFAULT NULL,
  `WorkerSalary` double NOT NULL,
  `HireDate` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workers`
--

INSERT INTO `workers` (`WorkerID`, `WorkerImage`, `WorkerName`, `WorkerPosition`, `WorkerGender`, `WorkerEmail`, `WorkerPhone`, `WorkerAddress`, `WorkerDescription`, `WorkerSalary`, `HireDate`) VALUES
(1, 'Aria_Swiftblade_4_1704316345_.jpg', 'Aria Swiftblade', 'Jeweler', 'Female', 'aria@example.com', '+91 12345 67890', '123 Main St, Mumbai', 'Experienced jeweler specializing in intricate designs.', 50000, '2024-04-13'),
(2, 'Ashborn_Godslayer_10_1704317022_.png', 'Ashborn Godslayer', 'Sales Associate', 'Male', 'ashborn@example.com', '+91 98765 43210', '456 Elm St, Delhi', 'Friendly and outgoing sales professional with a passion for jewelry.', 45000, '2024-04-13'),
(3, 'browncat_themagician_32_1702047417_.jpg', 'Brown Cat', 'Jewelry Designer', 'Female', 'browncat@example.com', '+91 11223 34455', '789 Oak St, Bangalore', 'Innovative designer with a flair for contemporary jewelry pieces.', 55000, '2024-04-13'),
(4, 'Caelum_Frostreaver_7_1704316468_.png', 'Caelum Frostreaver', 'Manager', 'Male', 'caelum@example.com', '+91 65542 18798', '321 Pine St, Hyderabad', 'Experienced manager with a track record of improving operational efficiency.', 70000, '2024-04-13'),
(5, 'Dwarf_Jewelmaker_3_1704316307_.jpg', 'Dwarf Jewelmaker', 'Gemologist', 'Male', 'dwarf@example.com', '+91 98765 43210', '987 Maple St, Chennai', 'Skilled gemologist with a passion for identifying rare gemstones.', 60000, '2024-04-13'),
(6, 'Gideon_Stormforge_5_1704316380_.jpg', 'Gideon Stormforge', 'Appraiser', 'Male', 'gideon@example.com', '+91 78945 61230', '654 Oak St, Kolkata', 'Accurate and detail-oriented jewelry appraiser with extensive knowledge of gem values.', 65000, '2024-04-13'),
(7, 'Himanshu_Verma_2_1704316040_.jpg', 'Himanshu Verma', 'Engraver', 'Male', 'himanshu@example.com', '+91 19876 54321', '123 Elm St, Pune', 'Master engraver specializing in intricate designs on precious metals.', 60000, '2024-04-13'),
(8, 'Isolde_Moonstone_12_1704317120_.jpeg', 'Isolde Moonstone', 'Jeweler', 'Female', 'isolde@example.com', '+91 11223 34455', '789 Pine St, Mumbai', 'Creative jeweler known for elegant and timeless designs.', 55000, '2024-04-13'),
(9, 'Luna_Lovegood_34_1702050693_.jpeg', 'Luna Lovegood', 'Sales Associate', 'Female', 'luna@example.com', '+91 12345 67890', '456 Maple St, Delhi', 'Energetic and enthusiastic sales professional with a passion for helping customers find the perfect piece of jewelry.', 45000, '2024-04-13'),
(10, 'Luna_Silverleaf_6_1704316418_.jpeg', 'Luna Silverleaf', 'Manager', 'Female', 'luna@example.com', '+91 65542 18798', '321 Oak St, Hyderabad', 'Dynamic manager with a proven track record of leading successful teams in the jewelry industry.', 70000, '2024-04-13'),
(11, 'Lyod_Frontera_1_1702049534_.png', 'Lyod Frontera', 'Appraiser', 'Male', 'lyod@example.com', '+91 78945 61230', '654 Pine St, Kolkata', 'Experienced jewelry appraiser known for fair and accurate assessments.', 65000, '2024-04-13'),
(12, 'Lyod_Frontera_1_1704315544_.png', 'Lyod Frontera', 'Goldsmith', 'Male', 'lyod@example.com', '+91 98765 43210', '987 Elm St, Chennai', 'Skilled goldsmith with a passion for crafting intricate and unique pieces.', 60000, '2024-04-13'),
(13, 'Manish_Tripathi_31_1702047229_.jpeg', 'Manish Tripathi', 'Jewelry Designer', 'Male', 'manish@example.com', '+91 19876 54321', '123 Maple St, Pune', 'Innovative designer with a focus on blending traditional and modern jewelry styles.', 55000, '2024-04-13'),
(15, 'Manish_Tripathi_42_1704316813_.jpeg', 'Manish Tripathi', 'Manager', 'Male', 'manish@example.com', '+91 12345 67890', '456 Elm St, Delhi', 'Strategic manager with a focus on driving sales and profitability in the jewelry industry.', 70000, '2024-04-13'),
(19, 'Mohit_Galib_8_1704316860_.png', 'Mohit Galib', 'Sales Associate', 'Male', 'mohit@example.com', '+91 19876 54321', '123 Elm St, Pune', 'Friendly and approachable sales professional with a passion for assisting customers in finding the perfect jewelry piece.', 45000, '2024-04-13'),
(20, 'Narinder_Suthar_13_1704317215_.png', 'Narinder Suthar', 'Jewelry Designer', 'Male', 'narinder@example.com', '+91 11223 34455', '789 Pine St, Mumbai', 'Creative and innovative jewelry designer known for pushing boundaries in design.', 55000, '2024-04-13'),
(21, 'Rahul_Kumar_33_1704317277_.jpg', 'Rahul Kumar', 'Appraiser', 'Male', 'rahul@example.com', '+91 12345 67890', '456 Maple St, Delhi', 'Experienced jewelry appraiser with a keen eye for detail and value assessment.', 65000, '2024-04-13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`CartID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `contactus`
--
ALTER TABLE `contactus`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `occasions`
--
ALTER TABLE `occasions`
  ADD PRIMARY KEY (`OccasionID`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`OrderItemsID`),
  ADD KEY `orderitems_ibfk_2` (`ProductID`),
  ADD KEY `orderitems_ibfk_1` (`OrderID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`RatingID`),
  ADD KEY `reviews_ibfk_1` (`ProductID`),
  ADD KEY `reviews_ibfk_2` (`UserID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`WorkerID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `CartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contactus`
--
ALTER TABLE `contactus`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `occasions`
--
ALTER TABLE `occasions`
  MODIFY `OccasionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `OrderItemsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `RatingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `workers`
--
ALTER TABLE `workers`
  MODIFY `WorkerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
