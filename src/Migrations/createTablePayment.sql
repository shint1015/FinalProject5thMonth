CREATE TABLE `Payment` (
  `payment_id` INT NOT NULL AUTO_INCREMENT,
  `reservation_id` INT NOT NULL,
  `status` VARCHAR(100) NOT NULL DEFAULT 'pending',
  `credit_number` VARCHAR(13) NOT NULL,
  `credit_name` VARCHAR(100) NOT NULL,
  `credit_expired_at` DATE NOT NULL,
  PRIMARY KEY (`payment_id`),
  FOREIGN KEY (`reservation_id`) REFERENCES `Reservation`(`reservation_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
