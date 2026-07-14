-- Liga una encuesta a un registro de "Semanas Especiales" (actividades_destacadas)
-- para poder usarla como formulario de registro de ese evento.
ALTER TABLE encuestas
    ADD COLUMN idactiv_relacionada INT DEFAULT NULL AFTER id;
