-- Migración v1: texto en portada y video para actividades_destacadas
ALTER TABLE actividades_destacadas
    ADD COLUMN texto_banner VARCHAR(500) DEFAULT NULL,
    ADD COLUMN video_url    VARCHAR(500) DEFAULT NULL;
