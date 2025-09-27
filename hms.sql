-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 27, 2025 at 12:23 PM
-- Server version: 8.0.41
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_cred`
--

CREATE TABLE `admin_cred` (
  `sr_no` int NOT NULL,
  `admin_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_pass` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_cred`
--

INSERT INTO `admin_cred` (`sr_no`, `admin_name`, `admin_pass`) VALUES
(1, 'admin', '$2y$10$1zeiFGI.peWQaElaNw0u4eT9MXMQpSYAfBsKFq9LVRg8DwricaNQW');

-- --------------------------------------------------------

--
-- Table structure for table `booking_details`
--

CREATE TABLE `booking_details` (
  `sr_no` int NOT NULL,
  `booking_id` int NOT NULL,
  `room_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `total_pay` int NOT NULL,
  `room_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phonenum` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meal_plan_id` int DEFAULT '0',
  `meal_plan_price` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_details`
--

INSERT INTO `booking_details` (`sr_no`, `booking_id`, `room_name`, `price`, `total_pay`, `room_no`, `user_name`, `phonenum`, `address`, `meal_plan_id`, `meal_plan_price`) VALUES
(167, 167, 'Suite Room', 5000, 5000, '1', 'Pubudu Gunawardhana', '0715507044', '31 / c , Guruwala , Dompe', 177, 0.00),
(168, 168, 'Suite Room', 5400, 5400, NULL, 'Pubudu Gunawardhana', '0715507044', '31 / c , Guruwala , Dompe', 178, 400.00);

-- --------------------------------------------------------

--
-- Table structure for table `booking_order`
--

CREATE TABLE `booking_order` (
  `booking_id` int NOT NULL,
  `user_id` int NOT NULL,
  `room_id` int NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `arrival` int NOT NULL DEFAULT '0',
  `refund` int DEFAULT NULL,
  `booking_status` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `order_id` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trans_id` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trans_amt` int NOT NULL,
  `trans_status` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `trans_resp_msg` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate_review` int DEFAULT NULL,
  `datentime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_order`
--

INSERT INTO `booking_order` (`booking_id`, `user_id`, `room_id`, `check_in`, `check_out`, `arrival`, `refund`, `booking_status`, `order_id`, `trans_id`, `trans_amt`, `trans_status`, `trans_resp_msg`, `rate_review`, `datentime`) VALUES
(166, 44, 18, '2025-09-26', '2025-10-02', 0, NULL, 'booked', 'ORD_441446008', 'ORD_441446008', 36000, 'TXN_SUCCESS', 'Txn Success', NULL, '2025-09-25 10:40:27'),
(167, 45, 16, '2025-09-26', '2025-09-27', 1, NULL, 'booked', 'ORD_454346722', 'ORD_454346722', 5000, 'TXN_SUCCESS', 'Txn Success', 1, '2025-09-25 12:47:07'),
(168, 45, 16, '2025-09-26', '2025-09-27', 0, 1, 'cancelled', 'ORD_458885372', 'ORD_458885372', 5000, 'TXN_SUCCESS', 'Txn Success', NULL, '2025-09-25 12:50:50');

-- --------------------------------------------------------

--
-- Table structure for table `carousel`
--

CREATE TABLE `carousel` (
  `sr_no` int NOT NULL,
  `image` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carousel`
--

INSERT INTO `carousel` (`sr_no`, `image`) VALUES
(38, 'IMG_32891.jpg'),
(39, 'IMG_62468.jpg'),
(40, 'IMG_81190.jpg'),
(41, 'IMG_83549.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `contact_details`
--

CREATE TABLE `contact_details` (
  `sr_no` int NOT NULL,
  `address` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gmap` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pn1` bigint NOT NULL,
  `pn2` bigint NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fb` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `insta` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tw` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iframe` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_details`
--

INSERT INTO `contact_details` (`sr_no`, `address`, `gmap`, `pn1`, `pn2`, `email`, `fb`, `insta`, `tw`, `iframe`) VALUES
(1, 'No 360/6 , kings road , Dabulla', 'https://maps.app.goo.gl/ThnHGHJbhjWYBg7L9', 71555700, 71444400, 'Heritance360@gmail.com', 'https://www.facebook.com/', 'https://www.facebook.com/', 'https://www.facebook.com/', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3968.191188908636!2d80.45893107503566!3d5.968385994016352!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae13f7454a9ba55:0xcfe37ec93c0a68c2!2sMirissa 360!5e0!3m2!1sen!2slk!4v1753295553994!5m2!1sen!2slk');

-- --------------------------------------------------------

--
-- Table structure for table `dine_in_options`
--

CREATE TABLE `dine_in_options` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `open_hours` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `location` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type_of_dine_in` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dine_in_options`
--

INSERT INTO `dine_in_options` (`id`, `name`, `description`, `open_hours`, `image`, `status`, `location`, `type_of_dine_in`, `created_at`, `updated_at`) VALUES
(9, 'Heritage Garden Restaurant', 'Surrounded by lush gardens, this restaurant offers a daily international buffet and traditional Sri Lankan dishes.', '06.30 AM - 10.30 PM', 'dine_in_68d375511e81a.jpg', 1, '2nd Floor (Garden View)', 'Buffet & Dining', '2025-08-10 11:08:38', '2025-09-24 18:30:15'),
(10, 'Lagoon Breeze Café', 'A relaxed beachfront café serving fresh seafood, tropical juices, and light snacks. ', '10.00 AM - 11.00 PM', 'dine_in_68d374716cc15.jpg', 1, 'Ground Floor (Poolside) ', 'Casual Dining', '2025-08-10 11:31:35', '2025-09-24 04:32:49'),
(11, 'Skyview Rooftop Lounge ', 'Rooftop lounge with panoramic city views, signature cocktails, and live music performances.', '07.00 AM - 10.00 P.M', 'dine_in_68d3740cbf689.jpg', 1, 'Rooftop (12th Floor)', 'Lounge & Bar', '2025-08-10 11:31:51', '2025-09-24 18:19:00');

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `id` int NOT NULL,
  `equipment_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `equipment_code` int NOT NULL,
  `quantity` int NOT NULL,
  `description` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `equipment_name`, `equipment_code`, `quantity`, `description`) VALUES
(20, 'Commercial Blender', 1, 6, 'For juice bar &amp; breakfast prep'),
(21, 'Chef Knife Set', 2, 12, 'Stainless steel 8-piece sets');

-- --------------------------------------------------------

--
-- Table structure for table `experience_options`
--

CREATE TABLE `experience_options` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `highlight` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `priceList` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `guide_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `guide_phone` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `experience_options`
--

INSERT INTO `experience_options` (`id`, `name`, `description`, `highlight`, `image`, `priceList`, `status`, `guide_name`, `guide_email`, `guide_phone`, `created_at`, `updated_at`) VALUES
(3, 'The Ancient City of Anuradhapura ', 'The historic Aligala Caves (Elephant Rock) dates back to the 3rd century BC. The name is derived from the likeness of the rock formation to an elephant, and a hike to this prehistoric site takes just 40 minutes. update', 'Time: 7:00 AM | Distance from Hotel: About 77 Km | Duration: Full Day ', 'experience_68d3c524ed5b3.jpg', 'experience_68d3ce6ad6b3a.pdf', 1, 'Saman Weerasinghe ', 'saman.weerasinghe@heritance360.com', '0772345678', '2025-08-06 13:33:44', '2025-09-24 18:28:31'),
(9, 'Dambulla Cave Temples', 'Located just 13 km from Heritance Kandalama, the Dambulla Cave Temples date back to the 1st century BC. This 340m-high ancient rock temple is famous for its five cave shrines, created when King Walagamba, after being defeated in Anuradhapura, sought refuge here for 14 years. When he regained his throne, he turned the caves into a magnificent temple complex. The site includes a 15-meter-long reclining Buddha and over 150 life-size Buddhist statues. Many of the murals and paintings found here date back to the 19th century, making it one of Sri Lanka’s most treasured UNESCO World Heritage Sites.\\\\r\\\\n<br><br>\\\\r\\\\nPrice: Jeep – Rs. 6,500, Van – Rs. 6,000 & Entrance Fee 10 USD (Per Person)', 'Time: 7:00 AM | Distance from Hotel: About 13 Km | Duration: 4 Hours ', 'experience_68d3c6a3a6a54.jpg', 'experience_68d3ce63597ee.pdf', 1, 'Priyantha Bandara', 'priyantha.bandara@heritance360.com', '0714567890', '2025-09-24 10:23:31', '2025-09-24 10:56:35'),
(10, 'The Elephant Gathering in Minneriya & Kaudulla National Parks', 'Witness one of nature’s greatest spectacles at Minneriya and Kaudulla National Parks. Covering 249 square kilometers, this ancient reservoir—built by King Mahasen over 1,500 years ago—has become a thriving sanctuary for elephants. Each year, during the dry season, lush grasses attract hundreds of elephants who gather to feed, bathe, and socialize. At times, more than 200 elephants can be seen together, making it one of the most awe-inspiring wildlife experiences in the world.  ', 'Wildlife Experience – Listed by Lonely Planet as one of the Top Ten Wildlife Spectacles of the World', 'experience_68d3c7dd907c1.jpg', 'experience_68d3ce5a0d161.pdf', 1, 'Nalinda ', 'nalinda@heritancehotels.com', '0715507000', '2025-09-24 10:28:45', '2025-09-24 10:56:26');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int NOT NULL,
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `icon`, `name`, `description`) VALUES
(27, 'IMG_32241.svg', 'Wifi', 'Stay connected throughout your stay with our high-speed Wi-Fi, available in all rooms and public areas. Perfect for work, browsing, or streaming.'),
(28, 'IMG_93313.svg', 'Air Conditioner', 'Enjoy a comfortable indoor climate with individually controlled air conditioning in every room, ensuring a relaxing atmosphere no matter the weather.'),
(29, 'IMG_61733.svg', 'Geyser', 'Enjoy hot showers anytime with our modern geyser systems, providing instant hot water for your convenience.'),
(30, 'IMG_57102.svg', 'Spa', 'Rejuvenate your body and mind at our serene spa, offering a range of massages and wellness treatments tailored to your needs.'),
(31, 'IMG_58601.svg', 'Television', 'Unwind with a wide selection of local and international channels on our flat-screen TVs, available in every guest room.');

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `name`) VALUES
(13, 'bedroom'),
(14, 'balcony'),
(15, 'kitchen'),
(17, 'sofa'),
(19, 'Garden'),
(22, 'text');

-- --------------------------------------------------------

--
-- Table structure for table `meal_plans`
--

CREATE TABLE `meal_plans` (
  `id` int NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meal_plans`
--

INSERT INTO `meal_plans` (`id`, `code`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'RO', 'No Meal Plane', 'Accommodation only without meals', 1, '2025-08-07 02:59:12', '2025-09-23 13:33:11'),
(2, 'BB', 'Bed & Breakfast', 'Accommodation with breakfast included', 1, '2025-08-07 02:59:12', '2025-08-07 02:59:12'),
(3, 'HB', 'Half Board', 'Accommodation with breakfast and dinner', 1, '2025-08-07 02:59:12', '2025-08-07 02:59:12'),
(4, 'FB', 'Full Board', 'Accommodation with breakfast, lunch and dinner', 1, '2025-08-07 02:59:12', '2025-08-07 02:59:12');

-- --------------------------------------------------------

--
-- Table structure for table `rating_review`
--

CREATE TABLE `rating_review` (
  `sr_no` int NOT NULL,
  `booking_id` int NOT NULL,
  `room_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` int NOT NULL,
  `review` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seen` int NOT NULL DEFAULT '0',
  `datentime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rating_review`
--

INSERT INTO `rating_review` (`sr_no`, `booking_id`, `room_id`, `user_id`, `rating`, `review`, `seen`, `datentime`) VALUES
(16, 167, 16, 45, 5, 'good', 1, '2025-09-25 12:50:31');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area` int NOT NULL,
  `price` int NOT NULL,
  `quantity` int NOT NULL,
  `adult` int NOT NULL,
  `children` int NOT NULL,
  `description` varchar(350) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `removed` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `area`, `price`, `quantity`, `adult`, `children`, `description`, `status`, `removed`) VALUES
(4, 'Deluxe Room', 300, 3000, 5, 2, 2, 'Located in the Sigiriya Wing, our Deluxe Rooms provide breathtaking views of the Kandalama Lake, lush jungles and the distant Sigiriya Rock. Featuring teak wood interiors, cultural art, a plush king-sized bed and a standout Jacuzzi, these rooms perfectly blend comfort and elegance.', 1, 1),
(5, 'Luxury Room', 387, 5000, 5, 4, 2, 'Our Luxury Rooms feature expansive jungle or lake vistas, traditional rattan furnishings, and a soothing en-suite bathroom with spectacular views. Relax on your private balcony, perfect for stargazing or wildlife watching. Inside, sink into a plush king-sized bed.', 1, 1),
(6, 'Supreme deluxe room', 500, 900, 12, 9, 10, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dicta quia nisi voluptates impedit perspiciatis, nobis libero culpa error officiis totam?Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dic', 1, 1),
(16, 'Suite Room', 357, 5000, 5, 3, 2, 'Located in our Dambulla and Sigiriya wings, the Kandalama Suites offers sweeping views of the Kandalama Lake and surrounding jungles. Featuring teak interiors, rattan furniture, a king-sized bed, and a 12-jet Jacuzzi perfectly positioned for panoramic nature-watching, these suites blend contemporary comfort with Sri Lankan heritage.', 1, 0),
(17, 'Superior', 344, 6000, 5, 2, 2, 'Featuring serene jungle views, nature-inspired wood paneling and cultural artistic décor, our Superior Room offers private balconies perfect for wildlife spotting or unwinding amidst nature’s beauty. Enjoy contemporary comforts like a king-sized bed, rain shower and an en-suite bathroom with scenic backdrops.', 1, 1),
(18, 'Superior', 230, 6000, 12, 2, 1, 'Featuring serene jungle views, nature-inspired wood paneling and cultural artistic décor, our Superior Room offers private balconies perfect for wildlife spotting or unwinding amidst nature’s beauty. Enjoy contemporary comforts like a king-sized bed, rain shower and an en-suite bathroom with scenic backdrops.', 1, 1),
(19, 'Superior', 344, 6000, 12, 2, 1, 'Featuring serene jungle views, nature-inspired wood paneling and cultural artistic décor, our Superior Room offers private balconies perfect for wildlife spotting or unwinding amidst nature’s beauty. Enjoy contemporary comforts like a king-sized bed, rain shower and an en-suite bathroom with scenic backdrops.', 1, 0),
(20, 'Luxury', 355, 7000, 3, 2, 2, 'Featuring serene jungle views, nature-inspired wood paneling and cultural artistic décor, our Superior Room offers private balconies perfect for wildlife spotting or unwinding amidst nature’s beauty. Enjoy contemporary comforts like a king-sized bed, rain shower and an en-suite bathroom with scenic backdrops.', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `room_facilities`
--

CREATE TABLE `room_facilities` (
  `sr_no` int NOT NULL,
  `room_id` int NOT NULL,
  `facilities_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_facilities`
--

INSERT INTO `room_facilities` (`sr_no`, `room_id`, `facilities_id`) VALUES
(164, 15, 20),
(165, 15, 21),
(166, 15, 22),
(167, 15, 23),
(168, 15, 24),
(169, 15, 25),
(185, 16, 27),
(186, 16, 28),
(187, 16, 31),
(201, 19, 27),
(202, 19, 31),
(207, 20, 27),
(208, 20, 28),
(209, 20, 29),
(210, 20, 31);

-- --------------------------------------------------------

--
-- Table structure for table `room_features`
--

CREATE TABLE `room_features` (
  `sr_no` int NOT NULL,
  `room_id` int NOT NULL,
  `features_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_features`
--

INSERT INTO `room_features` (`sr_no`, `room_id`, `features_id`) VALUES
(161, 15, 13),
(162, 15, 14),
(163, 15, 15),
(164, 15, 17),
(179, 16, 13),
(180, 16, 14),
(181, 16, 17),
(197, 19, 13),
(198, 19, 14),
(199, 19, 19),
(205, 20, 13),
(206, 20, 14),
(207, 20, 15),
(208, 20, 19),
(209, 20, 22);

-- --------------------------------------------------------

--
-- Table structure for table `room_images`
--

CREATE TABLE `room_images` (
  `sr_no` int NOT NULL,
  `room_id` int NOT NULL,
  `image` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumb` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_images`
--

INSERT INTO `room_images` (`sr_no`, `room_id`, `image`, `thumb`) VALUES
(27, 15, 'IMG_80555.jpg', 1),
(30, 16, 'IMG_26159.jpg', 0),
(32, 19, 'IMG_15730.jpg', 0),
(33, 19, 'IMG_34203.jpg', 1),
(34, 16, 'IMG_59494.jpg', 1),
(35, 20, 'IMG_39845.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `room_meal_plans`
--

CREATE TABLE `room_meal_plans` (
  `id` int NOT NULL,
  `room_id` int NOT NULL,
  `meal_plan_id` int NOT NULL,
  `price_modifier` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_meal_plans`
--

INSERT INTO `room_meal_plans` (`id`, `room_id`, `meal_plan_id`, `price_modifier`) VALUES
(41, 7, 1, 0.00),
(42, 7, 2, 200.00),
(43, 7, 3, 400.00),
(44, 7, 4, 800.00),
(45, 8, 1, 0.00),
(46, 8, 2, 0.00),
(47, 8, 3, 0.00),
(48, 8, 4, 0.00),
(53, 9, 1, 0.00),
(54, 9, 2, 1000.00),
(55, 9, 3, 3500.00),
(56, 9, 4, 4750.00),
(69, 10, 2, 100.00),
(70, 10, 3, 200.00),
(71, 10, 4, 300.00),
(72, 11, 2, 100.00),
(73, 11, 3, 200.00),
(74, 11, 4, 300.00),
(82, 12, 2, 100.00),
(83, 12, 3, 200.00),
(84, 12, 4, 500.00),
(85, 13, 2, 300.00),
(86, 13, 3, 600.00),
(87, 13, 4, 1200.00),
(88, 14, 2, 100.00),
(89, 14, 3, 300.00),
(90, 14, 4, 400.00),
(137, 15, 1, 0.00),
(138, 15, 2, 2000.00),
(139, 15, 3, 3000.00),
(140, 15, 4, 5000.00),
(169, 4, 1, 0.00),
(170, 4, 2, 800.00),
(171, 4, 3, 800.00),
(172, 4, 4, 1000.00),
(177, 16, 1, 0.00),
(178, 16, 2, 400.00),
(179, 16, 3, 800.00),
(180, 16, 4, 1000.00),
(181, 5, 1, 0.00),
(182, 5, 2, 500.00),
(183, 5, 3, 1000.00),
(184, 5, 4, 1500.00),
(185, 17, 1, 0.00),
(186, 17, 2, 200.00),
(187, 17, 3, 400.00),
(188, 17, 4, 600.00),
(189, 18, 1, 0.00),
(190, 18, 2, 200.00),
(191, 18, 3, 400.00),
(192, 18, 4, 800.00),
(197, 19, 1, 0.00),
(198, 19, 2, 500.00),
(199, 19, 3, 900.00),
(200, 19, 4, 1500.00),
(205, 20, 1, 0.00),
(206, 20, 2, 400.00),
(207, 20, 3, 700.00),
(208, 20, 4, 1000.00);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `sr_no` int NOT NULL,
  `site_title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_about` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shutdown` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`sr_no`, `site_title`, `site_about`, `shutdown`) VALUES
(1, 'Heritance 360', 'Located in the heart of Dabulla in Sri Lanka, Heritance 360 offers a comfortable and convenient stay for both business and leisure travelers.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_cred`
--

CREATE TABLE `user_cred` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phonenum` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pincode` int DEFAULT '0',
  `dob` date NOT NULL,
  `profile` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` int NOT NULL DEFAULT '0',
  `token` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `t_expire` date DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `datentime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_cred`
--

INSERT INTO `user_cred` (`id`, `name`, `email`, `address`, `phonenum`, `pincode`, `dob`, `profile`, `password`, `is_verified`, `token`, `t_expire`, `status`, `datentime`, `country`, `province`) VALUES
(45, 'Pubudu Gunawardhana', 'example@gmail.com', '31 / c , Guruwala , Dompe', '0715507044', 0, '2025-09-01', 'defaul_user.webp', '$2y$10$xUjcxYDQarA/ccYpXRncd.o.DJsEXXvtmcVpcoZAYW.EA4tN4hcjS', 1, 'd2130c521264c64e840928fa028a5031', '2025-09-25', 1, '2025-09-25 12:45:37', 'Sri Lanka', 'Western');

-- --------------------------------------------------------

--
-- Table structure for table `user_queries`
--

CREATE TABLE `user_queries` (
  `sr_no` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `datentime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `seen` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_queries`
--

INSERT INTO `user_queries` (`sr_no`, `name`, `email`, `subject`, `message`, `datentime`, `seen`) VALUES
(26, 'Pubudu Gunawardhana', 'example@gmail.com', 'Weddings', 'Contact for Crystal Palace Hall', '2025-09-25 13:06:51', 0);

-- --------------------------------------------------------

--
-- Table structure for table `wedding_halls`
--

CREATE TABLE `wedding_halls` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `package_pdf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wedding_halls`
--

INSERT INTO `wedding_halls` (`id`, `name`, `description`, `package_pdf`, `created_at`, `updated_at`) VALUES
(16, 'Banquet Weddings', 'Spacious hall, seats ~500, luxury décor, buffet-ready', 'PDF_31456.pdf', '2025-09-23 22:35:27', '2025-09-23 23:04:31'),
(17, 'Grand Royal Hall', 'Premium stage &amp;amp;amp; LED lighting, seats ~800', 'PDF_51988.pdf', '2025-09-23 22:43:31', '2025-09-23 23:21:22'),
(19, 'Crystal Palace Hall', 'Chandelier lighting, modern sound, dance floor, ~600 capacity', 'PDF_80089.pdf', '2025-09-23 23:24:19', '2025-09-23 23:51:54');

-- --------------------------------------------------------

--
-- Table structure for table `wedding_images`
--

CREATE TABLE `wedding_images` (
  `id` int NOT NULL,
  `wedding_id` int NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wedding_images`
--

INSERT INTO `wedding_images` (`id`, `wedding_id`, `image_path`, `created_at`) VALUES
(17, 16, 'IMG_24665.jpg', '2025-09-23 22:35:27'),
(18, 16, 'IMG_98050.jpg', '2025-09-23 22:39:24'),
(19, 17, 'IMG_55342.jpg', '2025-09-23 22:43:31'),
(20, 17, 'IMG_23740.jpg', '2025-09-23 22:44:49'),
(21, 16, 'IMG_68165.jpg', '2025-09-23 23:04:31'),
(23, 19, 'IMG_26215.jpg', '2025-09-23 23:24:19'),
(24, 19, 'IMG_24302.jpg', '2025-09-23 23:24:30'),
(25, 19, 'IMG_72469.jpg', '2025-09-23 23:51:54');

-- --------------------------------------------------------

--
-- Table structure for table `wedding_inquiries`
--

CREATE TABLE `wedding_inquiries` (
  `id` int NOT NULL,
  `wedding_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wedding_inquiries`
--

INSERT INTO `wedding_inquiries` (`id`, `wedding_id`, `name`, `email`, `message`, `created_at`) VALUES
(19, 19, 'Pubudu Gunawardhana', 'example@gmail.com', 'Weddings', '2025-09-25 12:48:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_cred`
--
ALTER TABLE `admin_cred`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `booking_order`
--
ALTER TABLE `booking_order`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `carousel`
--
ALTER TABLE `carousel`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `contact_details`
--
ALTER TABLE `contact_details`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `dine_in_options`
--
ALTER TABLE `dine_in_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `experience_options`
--
ALTER TABLE `experience_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meal_plans`
--
ALTER TABLE `meal_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_meal_plans_status` (`status`);

--
-- Indexes for table `rating_review`
--
ALTER TABLE `rating_review`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_facilities`
--
ALTER TABLE `room_facilities`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `facilities id` (`facilities_id`),
  ADD KEY `room id` (`room_id`);

--
-- Indexes for table `room_features`
--
ALTER TABLE `room_features`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `features id` (`features_id`),
  ADD KEY `rm id` (`room_id`);

--
-- Indexes for table `room_images`
--
ALTER TABLE `room_images`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `room_meal_plans`
--
ALTER TABLE `room_meal_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_room_meal_plan` (`room_id`,`meal_plan_id`),
  ADD KEY `idx_room_meal_plans_room_id` (`room_id`),
  ADD KEY `idx_room_meal_plans_meal_plan_id` (`meal_plan_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `user_cred`
--
ALTER TABLE `user_cred`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_queries`
--
ALTER TABLE `user_queries`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `wedding_halls`
--
ALTER TABLE `wedding_halls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wedding_images`
--
ALTER TABLE `wedding_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wedding_id` (`wedding_id`);

--
-- Indexes for table `wedding_inquiries`
--
ALTER TABLE `wedding_inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wedding_id` (`wedding_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_cred`
--
ALTER TABLE `admin_cred`
  MODIFY `sr_no` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking_details`
--
ALTER TABLE `booking_details`
  MODIFY `sr_no` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT for table `booking_order`
--
ALTER TABLE `booking_order`
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT for table `carousel`
--
ALTER TABLE `carousel`
  MODIFY `sr_no` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `contact_details`
--
ALTER TABLE `contact_details`
  MODIFY `sr_no` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dine_in_options`
--
ALTER TABLE `dine_in_options`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `experience_options`
--
ALTER TABLE `experience_options`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `meal_plans`
--
ALTER TABLE `meal_plans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rating_review`
--
ALTER TABLE `rating_review`
  MODIFY `sr_no` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `room_facilities`
--
ALTER TABLE `room_facilities`
  MODIFY `sr_no` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `room_features`
--
ALTER TABLE `room_features`
  MODIFY `sr_no` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;

--
-- AUTO_INCREMENT for table `room_images`
--
ALTER TABLE `room_images`
  MODIFY `sr_no` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `room_meal_plans`
--
ALTER TABLE `room_meal_plans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `sr_no` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_cred`
--
ALTER TABLE `user_cred`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `user_queries`
--
ALTER TABLE `user_queries`
  MODIFY `sr_no` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `wedding_halls`
--
ALTER TABLE `wedding_halls`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `wedding_images`
--
ALTER TABLE `wedding_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `wedding_inquiries`
--
ALTER TABLE `wedding_inquiries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD CONSTRAINT `booking_details_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking_order` (`booking_id`);

--
-- Constraints for table `booking_order`
--
ALTER TABLE `booking_order`
  ADD CONSTRAINT `booking_order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_cred` (`id`),
  ADD CONSTRAINT `booking_order_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `rating_review`
--
ALTER TABLE `rating_review`
  ADD CONSTRAINT `rating_review_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking_order` (`booking_id`),
  ADD CONSTRAINT `rating_review_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `rating_review_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user_cred` (`id`);

--
-- Constraints for table `room_facilities`
--
ALTER TABLE `room_facilities`
  ADD CONSTRAINT `facilities id` FOREIGN KEY (`facilities_id`) REFERENCES `facilities` (`id`),
  ADD CONSTRAINT `room id` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `room_features`
--
ALTER TABLE `room_features`
  ADD CONSTRAINT `features id` FOREIGN KEY (`features_id`) REFERENCES `features` (`id`),
  ADD CONSTRAINT `rm id` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `room_images`
--
ALTER TABLE `room_images`
  ADD CONSTRAINT `room_images_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `room_meal_plans`
--
ALTER TABLE `room_meal_plans`
  ADD CONSTRAINT `room_meal_plans_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_meal_plans_ibfk_2` FOREIGN KEY (`meal_plan_id`) REFERENCES `meal_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wedding_images`
--
ALTER TABLE `wedding_images`
  ADD CONSTRAINT `wedding_images_ibfk_1` FOREIGN KEY (`wedding_id`) REFERENCES `wedding_halls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wedding_inquiries`
--
ALTER TABLE `wedding_inquiries`
  ADD CONSTRAINT `wedding_inquiries_ibfk_1` FOREIGN KEY (`wedding_id`) REFERENCES `wedding_halls` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
