-- Migración v3: imagen secundaria (logo/icono) en el encabezado de la encuesta
ALTER TABLE encuestas
    ADD COLUMN imagen_secundaria_base64 MEDIUMTEXT DEFAULT NULL AFTER imagen_base64;
