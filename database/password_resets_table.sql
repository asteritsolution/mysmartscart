-- Password Reset OTP Table
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `otp` VARCHAR(6) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `used` TINYINT(1) DEFAULT 0 COMMENT '1=Used, 0=Not Used',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `otp` (`otp`),
  KEY `expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

