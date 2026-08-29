-- ============================================================
-- Módulo: Configuración de Academia Coré (correos de notificación)
-- Ejecutar este script una sola vez en la base de datos
-- ============================================================

CREATE TABLE IF NOT EXISTS academia_config (
    id                    INT NOT NULL PRIMARY KEY,
    correos_notificacion  VARCHAR(500) NOT NULL DEFAULT 'pibgdlar@gmail.com'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO academia_config (id, correos_notificacion) VALUES (1, 'pibgdlar@gmail.com');
