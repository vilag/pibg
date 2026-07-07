-- ============================================================
-- Módulo de Peticiones de Oración
-- Ejecutar en phpMyAdmin → base u690371019_pibg
-- ============================================================

CREATE TABLE IF NOT EXISTS motivos_oracion (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nombre     VARCHAR(255) NOT NULL,
    telefono   VARCHAR(50)  DEFAULT '',
    motivo     TEXT         NOT NULL,
    fecha_hora DATETIME     DEFAULT CURRENT_TIMESTAMP,
    atendida   TINYINT(1)   DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Si la tabla ya existía, agregar la columna atendida
ALTER TABLE motivos_oracion ADD COLUMN IF NOT EXISTS atendida TINYINT(1) DEFAULT 0;
