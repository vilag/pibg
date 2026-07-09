CREATE TABLE IF NOT EXISTS qr_codes (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(255)  NOT NULL,
    contenido       TEXT          NOT NULL,
    color_frente    VARCHAR(7)    NOT NULL DEFAULT '#000000',
    color_fondo     VARCHAR(7)    NOT NULL DEFAULT '#ffffff',
    estilo_puntos   VARCHAR(30)   NOT NULL DEFAULT 'square',
    nivel_correccion CHAR(1)      NOT NULL DEFAULT 'M',
    imagen_base64   MEDIUMTEXT    NOT NULL,
    fecha_creacion  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
