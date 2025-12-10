-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 10, 2025 at 06:18 PM
-- Server version: 8.0.44
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elephanesamurai_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `Payment`
--

CREATE TABLE `Payment` (
  `reservation_id` int NOT NULL,
  `payment_id` int NOT NULL,
  `status` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `credit_number` varchar(13) COLLATE utf8mb4_general_ci NOT NULL,
  `credit_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `credit_expired_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Payment`
--

INSERT INTO `Payment` (`reservation_id`, `payment_id`, `status`, `credit_number`, `credit_name`, `credit_expired_at`) VALUES
(1, 11, 'cancelled', '4829173056218', 'Liam Carter', '2026-04-14'),
(2, 9, 'pending', '7391058264410', 'Ava Mitchell', '2029-01-20'),
(3, 6, 'completed', '5102987346629', 'Noah Benson', '2027-03-09'),
(4, 8, 'cancelled', '9283745012365', 'Emma Reyes', '2026-06-09'),
(5, 8, 'cancelled', '6039182754401', 'Mason Turner', '2028-01-12'),
(6, 16, 'pending', '8172503694527', 'Chloe Anderson', '2025-12-12'),
(7, 16, 'pending', '2947618305524', 'Ethan Collins', '2026-12-24'),
(8, 19, 'cancelled', '5703492187603', 'Mia Lawson', '2026-06-09'),
(9, 5, 'completed', '8602319475529', 'Lucas Rivera', '2028-01-12'),
(10, 3, 'cancelled', '4318729501664', 'Lily Harper', '2026-06-09'),
(11, 9, 'cancelled', '7925403168821', 'Oliver Brooks', '2026-03-02'),
(12, 13, 'completed', '1568209735448', 'Zoe Ramirez', '2026-01-05'),
(13, 16, 'completed', '9452176033810', 'Henry Kim', '2027-06-19'),
(14, 20, 'cancelled', '3807621590446', 'Sofia Daniels', '2025-01-07'),
(15, 11, 'completed', '2148957306623', 'Jack Morgan', '2029-01-03'),
(16, 6, 'pending', '6735012849920', 'Aria Patel', '2025-11-19'),
(17, 5, 'cancelled', '5089376217441', 'Leo Hughes', '2026-01-30'),
(18, 9, 'completed', '3297604158825', 'Nora Sullivan', '2026-02-05'),
(19, 19, 'completed', '7492016385428', 'Caleb Foster', '2026-04-09'),
(20, 9, 'pending', '6018942377150', 'Isla Bennett', '2025-12-02');

-- --------------------------------------------------------

--
-- Table structure for table `Reservation`
--

CREATE TABLE `Reservation` (
  `reservation_id` int NOT NULL,
  `show_id` int NOT NULL,
  `user_id` int NOT NULL,
  `status` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `ticket_amount` int NOT NULL,
  `ticket_total_price` int NOT NULL,
  `duration` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Reservation`
--

INSERT INTO `Reservation` (`reservation_id`, `show_id`, `user_id`, `status`, `ticket_amount`, `ticket_total_price`, `duration`) VALUES
(1, 10, 13, 'cancelled', 1, 150, '2025-11-09 06:22:05'),
(2, 20, 12, 'pending', 1, 150, '2025-11-22 06:50:09'),
(3, 14, 5, 'completed', 2, 300, '2025-09-22 06:37:30'),
(4, 1, 15, 'cancelled', 1, 150, '2025-06-14 16:24:42'),
(5, 4, 19, 'cancelled', 2, 300, '2025-05-22 23:11:11'),
(6, 16, 2, 'pending', 2, 300, '2025-02-27 14:55:20'),
(7, 5, 5, 'pending', 3, 450, '2025-08-11 10:55:11'),
(8, 19, 3, 'cancelled', 4, 600, '2025-08-14 03:00:11'),
(9, 10, 1, 'completed', 2, 300, '2025-07-20 11:09:05'),
(10, 3, 5, 'cancelled', 2, 300, '2025-08-13 03:16:41'),
(11, 13, 12, 'cancelled', 2, 300, '2025-06-28 09:48:55'),
(12, 9, 14, 'completed', 2, 300, '2025-04-28 06:06:35'),
(13, 18, 20, 'completed', 1, 150, '2025-08-22 17:29:07'),
(14, 2, 4, 'cancelled', 4, 600, '2025-03-28 15:48:18'),
(15, 7, 9, 'completed', 3, 450, '2025-05-29 02:36:32'),
(16, 12, 18, 'pending', 5, 750, '2025-03-11 15:47:10'),
(17, 9, 2, 'cancelled', 1, 150, '2025-03-10 20:11:27'),
(18, 1, 7, 'completed', 2, 300, '2025-04-20 18:17:37'),
(19, 19, 11, 'completed', 2, 300, '2025-08-22 22:33:45'),
(20, 17, 6, 'pending', 2, 300, '2025-06-08 16:41:41'),
(21, 15, 5, 'completed', 1, 150, '2025-06-12 19:51:34'),
(22, 1, 18, 'cancelled', 5, 750, '2025-07-28 07:50:58');

-- --------------------------------------------------------

--
-- Table structure for table `Seat`
--

CREATE TABLE `Seat` (
  `reservation_id` int NOT NULL,
  `seat_id` int NOT NULL,
  `seat_number` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `seat_price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Seat`
--

INSERT INTO `Seat` (`reservation_id`, `seat_id`, `seat_number`, `seat_price`) VALUES
(1, 1, 'A3', 150),
(2, 2, 'F1', 150),
(3, 3, 'A5', 300),
(4, 4, 'A9', 150),
(2, 5, 'F1', 150),
(3, 6, 'A5', 300),
(4, 7, 'A9', 150),
(5, 8, 'A12', 150),
(6, 9, 'B1', 150),
(7, 10, 'H7', 150),
(8, 11, 'B9', 150),
(8, 12, 'B10', 150),
(8, 13, 'B11', 150),
(8, 14, 'B12', 150),
(9, 15, 'C2', 150),
(10, 16, 'D1', 150),
(11, 17, 'D2', 150),
(11, 18, 'D3', 150),
(12, 19, 'F5', 150),
(12, 20, 'F6', 150),
(13, 21, 'G9', 150),
(14, 22, 'M1', 150),
(14, 23, 'M2', 150),
(14, 24, 'M3', 150),
(14, 25, 'M4', 150);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Payment`
--
ALTER TABLE `Payment`
  ADD PRIMARY KEY (`reservation_id`,`payment_id`);

--
-- Indexes for table `Reservation`
--
ALTER TABLE `Reservation`
  ADD PRIMARY KEY (`reservation_id`);

--
-- Indexes for table `Seat`
--
ALTER TABLE `Seat`
  ADD PRIMARY KEY (`seat_id`),
  ADD KEY `Seat` (`reservation_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Payment`
--
ALTER TABLE `Payment`
  MODIFY `reservation_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=258;

--
-- AUTO_INCREMENT for table `Reservation`
--
ALTER TABLE `Reservation`
  MODIFY `reservation_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `Seat`
--
ALTER TABLE `Seat`
  MODIFY `seat_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Payment`
--
ALTER TABLE `Payment`
  ADD CONSTRAINT `Payment` FOREIGN KEY (`reservation_id`) REFERENCES `Reservation` (`reservation_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `Seat`
--
ALTER TABLE `Seat`
  ADD CONSTRAINT `Seat` FOREIGN KEY (`reservation_id`) REFERENCES `Reservation` (`reservation_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
