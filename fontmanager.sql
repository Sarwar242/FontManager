-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 19, 2025 at 10:06 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.20

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
(20, '687b4f9c053b8.ttf', 'Bitcount Regular', 'Bitcount', 'Regular', './uploads/fonts/fonts/687b4f9c053b8.ttf', '2025-07-19 07:56:12'),
(21, '687b50a5247b4.ttf', 'Roboto Condensed Bold', 'Roboto Condensed', 'Regular', './uploads/fonts/fonts/687b50a5247b4.ttf', '2025-07-19 08:00:37'),
(23, '687b50cb6dffb.ttf', 'Roboto SemiCondensed Thin Italic', 'Roboto SemiCondensed Thin', 'Regular', './uploads/fonts/fonts/687b50cb6dffb.ttf', '2025-07-19 08:01:15'),
(24, '687b5141036f3.ttf', 'Hind Siliguri Bold', 'Hind Siliguri', 'Regular', './uploads/fonts/fonts/687b5141036f3.ttf', '2025-07-19 08:03:13'),
(25, '687b5203100a1.ttf', 'Montserrat Thin', 'Montserrat Thin', 'Regular', './uploads/fonts/fonts/687b5203100a1.ttf', '2025-07-19 08:06:27'),
(26, '687b522846bcf.ttf', 'Poppins Italic', 'Poppins', 'Regular', './uploads/fonts/fonts/687b522846bcf.ttf', '2025-07-19 08:07:04'),
(27, '687b52436e8d3.ttf', 'Poppins Bold', 'Poppins', 'Regular', './uploads/fonts/fonts/687b52436e8d3.ttf', '2025-07-19 08:07:31'),
(28, '687b529f707b6.ttf', 'Roboto Slab Regular', 'Roboto Slab', 'Regular', './uploads/fonts/fonts/687b529f707b6.ttf', '2025-07-19 08:09:03'),
(29, '687b530ed6564.ttf', 'Boldonse Regular', 'Boldonse', 'Regular', './uploads/fonts/fonts/687b530ed6564.ttf', '2025-07-19 08:10:54');

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
(12, 's2', '2025-07-19 09:20:49'),
(13, 's1', '2025-07-19 09:20:57'),
(15, 'D2', '2025-07-19 09:54:49');

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
(12, 21),
(12, 23),
(15, 26),
(13, 27),
(15, 27),
(12, 28),
(13, 29);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `font_groups`
--
ALTER TABLE `font_groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
