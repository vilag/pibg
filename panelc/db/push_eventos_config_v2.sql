-- Migración v2: permitir dirigir un evento a un usuario específico del panel
-- (destino='usuario' + idusuario_destino), además de "todos" o "admin"; y
-- agregar el evento de nuevas solicitudes de informes de Academia Coré.
ALTER TABLE push_eventos_config
    MODIFY COLUMN destino ENUM('todos','admin','usuario') NOT NULL DEFAULT 'todos',
    ADD COLUMN idusuario_destino INT NULL AFTER destino;

INSERT INTO push_eventos_config (clave, nombre_legible, activo, destino, titulo, mensaje, variables) VALUES
('academia_solicitud', 'Alguien solicita informes en Academia Coré', 1, 'admin', 'Nueva solicitud de informes: {nombre}', 'Instrumentos: {instrumentos} · Tel: {telefono}', '{nombre}, {telefono}, {instrumentos}')
ON DUPLICATE KEY UPDATE clave = clave;
