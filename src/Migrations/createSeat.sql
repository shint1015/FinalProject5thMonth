CREATE TABLE `Seat` (
  `seat_id` INT NOT NULL AUTO_INCREMENT,
  `reservation_id` INT NOT NULL,
  `seat_number` VARCHAR(3) NOT NULL,
  `seat_price` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`seat_id`),
  FOREIGN KEY (`reservation_id`) REFERENCES `Reservation`(`reservation_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
