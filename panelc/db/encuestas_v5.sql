-- Migración v5: preservar el historial de preguntas editadas.
--
-- Antes, guardar_preguntas() borraba TODAS las preguntas de la encuesta y
-- las volvia a insertar en cada guardado (incluso si solo se corregia el
-- texto de una), generando ids nuevos por el AUTO_INCREMENT. Las respuestas
-- ya capturadas en encuesta_respuesta_detalles seguian apuntando al id
-- viejo (ahora borrado), asi que desaparecian de los resultados.
--
-- Con estas columnas, al corregir el texto de una pregunta la fila anterior
-- se conserva (activo=0) en vez de borrarse, y la nueva fila queda
-- enlazada a ella via reemplaza_a. Las preguntas que no cambian conservan
-- su id de siempre.

ALTER TABLE encuesta_preguntas
    ADD COLUMN activo TINYINT(1) NOT NULL DEFAULT 1,
    ADD COLUMN reemplaza_a INT DEFAULT NULL;
