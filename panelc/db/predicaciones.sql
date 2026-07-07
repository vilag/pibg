-- ============================================================
-- Módulo de Predicaciones
-- Ejecutar en Hostinger → phpMyAdmin → base u690371019_pibg
-- ============================================================

-- 1. Series especiales (semanas de conferencias, retiros, etc.)
CREATE TABLE IF NOT EXISTS series_especiales (
  idserie        INT AUTO_INCREMENT PRIMARY KEY,
  nombre         VARCHAR(255) NOT NULL,
  descripcion    TEXT DEFAULT '',
  fecha_inicio   DATE DEFAULT NULL,
  fecha_fin      DATE DEFAULT NULL,
  imagen         VARCHAR(500) DEFAULT '',
  estatus        TINYINT(1) DEFAULT 1,
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Vincular sermones a series (ejecutar si las columnas aún no existen)
ALTER TABLE sermones ADD COLUMN IF NOT EXISTS serie_id    INT DEFAULT NULL;
ALTER TABLE sermones ADD COLUMN IF NOT EXISTS orden_serie INT DEFAULT 0;

-- 3. Soporte para archivo estructurado (Word / PDF) en lugar de transcripción
ALTER TABLE sermones ADD COLUMN IF NOT EXISTS archivo_pred VARCHAR(500) DEFAULT NULL;
