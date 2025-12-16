CREATE TABLE `audit_logs` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `action` VARCHAR(50) NOT NULL,
    `entity` VARCHAR(50) NOT NULL,
    `entity_id` INT NOT NULL,
    `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `ip` VARCHAR(45),
    `details` TEXT,
    PRIMARY KEY (`id`)
)