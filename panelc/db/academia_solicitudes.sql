-- ============================================================
-- Módulo: Solicitudes de informes - Academia Coré
-- Ejecutar este script una sola vez en la base de datos
-- ============================================================

CREATE TABLE IF NOT EXISTS academia_solicitudes (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(150) NOT NULL,
    correo       VARCHAR(150) NOT NULL,
    telefono     VARCHAR(50)  NOT NULL,
    ip           VARCHAR(45)  DEFAULT NULL,
    instrumentos VARCHAR(500) NOT NULL,
    fecha_hora   DATETIME     DEFAULT CURRENT_TIMESTAMP,
    atendida     TINYINT(1)   DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE academia_solicitudes ADD COLUMN IF NOT EXISTS ip VARCHAR(45) DEFAULT NULL AFTER telefono;
