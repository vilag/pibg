CREATE TABLE IF NOT EXISTS `biografias` (
  `idbiografia` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(255) NOT NULL,
  `cargo` VARCHAR(255) DEFAULT '',
  `biografia` LONGTEXT,
  `imagen` VARCHAR(500) DEFAULT '',
  `fecha_registro` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `estatus` TINYINT(1) DEFAULT 1
);
