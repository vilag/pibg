-- Configuracion editable desde el panel para cada evento que dispara push
-- automaticos: si esta activo, a quien llega (todos los suscriptores o solo
-- el admin) y el texto (titulo/mensaje) con variables tipo {nombre}.
-- El conjunto de "claves" es fijo — corresponde a los puntos del codigo que
-- ya llaman a push_disparar_evento(); el panel no puede crear eventos
-- nuevos, solo configurar el comportamiento de los que ya existen.
CREATE TABLE IF NOT EXISTS push_eventos_config (
    clave VARCHAR(50) PRIMARY KEY,
    nombre_legible VARCHAR(150) NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    destino ENUM('todos','admin') NOT NULL DEFAULT 'todos',
    titulo VARCHAR(150) NOT NULL,
    mensaje VARCHAR(255) NOT NULL,
    variables VARCHAR(255) NULL,
    fecha_actualizacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO push_eventos_config (clave, nombre_legible, activo, destino, titulo, mensaje, variables) VALUES
('predicacion_nueva', 'Se publica una nueva predicación', 1, 'todos', 'Nueva predicación: {nombre_sermon}', 'Predicador: {predicador}', '{nombre_sermon}, {predicador}'),
('peticion_oracion', 'Alguien envía una petición de oración desde el sitio', 1, 'admin', 'Nueva petición de oración', '{nombre}: {motivo}', '{nombre}, {motivo}')
ON DUPLICATE KEY UPDATE clave = clave;
