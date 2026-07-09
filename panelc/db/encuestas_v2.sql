-- Migración v2: imagen de encabezado en la encuesta (no por pregunta)
ALTER TABLE encuestas
    ADD COLUMN imagen_base64 MEDIUMTEXT DEFAULT NULL AFTER fecha_fin;
