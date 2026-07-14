-- Liga un código QR a un registro de "Semanas Especiales" (actividades_destacadas)
-- cuando el QR apunta al formulario de registro de ese evento.
ALTER TABLE qr_codes
    ADD COLUMN idactiv_relacionada INT DEFAULT NULL AFTER id;
