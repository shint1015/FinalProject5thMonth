CREATE TABLE `Reservation` (
  `reservation_id` INT NOT NULL AUTO_INCREMENT,
  `show_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `status` VARCHAR(100) NOT NULL DEFAULT 'pending',
  `ticket_amount` INT NOT NULL,
  `ticket_total_price` DECIMAL(10,2) NOT NULL,
  `duration` DATETIME NOT NULL,
  PRIMARY KEY (`reservation_id`),
  FOREIGN KEY (`show_id`) REFERENCES `ShowTable`(`show_id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `UserTable`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
