
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