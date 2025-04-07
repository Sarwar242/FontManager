-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 07, 2025 at 07:33 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fontmanager`
--

-- --------------------------------------------------------

--
-- Table structure for table `fonts`
--

CREATE TABLE `fonts` (
  `id` int NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `font_family` varchar(100) DEFAULT NULL,
  `font_style` varchar(50) DEFAULT NULL,
  `upload_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `fonts`
--

INSERT INTO `fonts` (`id`, `filename`, `original_name`, `font_family`, `font_style`, `upload_path`, `created_at`) VALUES
(10, '67f2adb68ae9b.ttf', 'Roboto-Italic.ttf', 'Unknown', 'Regular', './uploads/fonts/fonts/67f2adb68ae9b.ttf', '2025-04-06 16:37:10'),
(12, '67f2bd34d8c87.ttf', 'Roboto Italic', 'Roboto', 'Regular', './uploads/fonts/fonts/67f2bd34d8c87.ttf', '2025-04-06 17:43:16'),
(13, '67f2bd4344c37.ttf', 'Winky Sans Italic', 'Winky Sans', 'Regular', './uploads/fonts/fonts/67f2bd4344c37.ttf', '2025-04-06 17:43:31'),
(14, '67f37dcf88af4.ttf', 'Winky Sans Italic', 'Winky Sans', 'Regular', './uploads/fonts/fonts/67f37dcf88af4.ttf', '2025-04-07 07:25:03'),
(15, '67f37dd5ce60a.ttf', 'Winky Sans Regular', 'Winky Sans', 'Regular', './uploads/fonts/fonts/67f37dd5ce60a.ttf', '2025-04-07 07:25:09');

-- --------------------------------------------------------

--
-- Table structure for table `font_groups`
--

CREATE TABLE `font_groups` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `font_groups`
--

INSERT INTO `font_groups` (`id`, `name`, `created_at`) VALUES
(5, 'test', '2025-04-07 06:38:50'),
(6, 'test', '2025-04-07 06:51:54'),
(7, 'sarwar2', '2025-04-07 07:26:02');

-- --------------------------------------------------------

--
-- Table structure for table `font_group_fonts`
--

CREATE TABLE `font_group_fonts` (
  `group_id` int NOT NULL,
  `font_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `font_group_fonts`
--

INSERT INTO `font_group_fonts` (`group_id`, `font_id`) VALUES
(5, 10),
(5, 12),
(6, 12),
(6, 13),
(7, 13),
(7, 14),
(7, 15);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fonts`
--
ALTER TABLE `fonts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `font_groups`
--
ALTER TABLE `font_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `font_group_fonts`
--
ALTER TABLE `font_group_fonts`
  ADD PRIMARY KEY (`group_id`,`font_id`),
  ADD KEY `font_id` (`font_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fonts`
--
ALTER TABLE `fonts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `font_groups`
--
ALTER TABLE `font_groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `font_group_fonts`
--
ALTER TABLE `font_group_fonts`
  ADD CONSTRAINT `font_group_fonts_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `font_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `font_group_fonts_ibfk_2` FOREIGN KEY (`font_id`) REFERENCES `fonts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
