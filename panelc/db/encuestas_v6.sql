-- Permite compartir un enlace público de solo lectura para ver los resultados
-- de una encuesta en tiempo real (sin necesidad de iniciar sesión como
-- administrador). Es independiente del enlace para responder la encuesta
-- (es_publica / token_publico): una encuesta puede aceptar respuestas sin
-- exponer resultados, o exponer resultados sin aceptar más respuestas.
ALTER TABLE encuestas
    ADD COLUMN resultados_publicos TINYINT(1) NOT NULL DEFAULT 0,
    ADD COLUMN token_resultados    VARCHAR(64) DEFAULT NULL;
