-- ============================================================
-- Relación muchos-a-muchos entre sermones y categorías
-- Ejecutar en phpMyAdmin → base u690371019_pibg
-- ============================================================

CREATE TABLE IF NOT EXISTS sermon_categorias (
    idsermones INT NOT NULL,
    idcat      INT NOT NULL,
    PRIMARY KEY (idsermones, idcat),
    KEY idx_sc_idcat (idcat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Migrar datos existentes (sermones que ya tenían una categoría asignada)
INSERT IGNORE INTO sermon_categorias (idsermones, idcat)
SELECT idsermones, categoria FROM sermones
WHERE categoria IS NOT NULL AND categoria > 0;
