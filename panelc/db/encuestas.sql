CREATE TABLE IF NOT EXISTS encuestas (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    titulo          VARCHAR(255)  NOT NULL,
    descripcion     TEXT,
    fecha_inicio    DATE,
    fecha_fin       DATE,
    es_publica      TINYINT(1)   NOT NULL DEFAULT 0,
    token_publico   VARCHAR(64)  DEFAULT NULL,
    activa          TINYINT(1)   NOT NULL DEFAULT 1,
    fecha_creacion  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS encuesta_preguntas (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    encuesta_id     INT          NOT NULL,
    orden           INT          NOT NULL DEFAULT 1,
    tipo            VARCHAR(30)  NOT NULL,
    pregunta        TEXT         NOT NULL,
    opciones        TEXT         DEFAULT NULL,
    imagen_base64   MEDIUMTEXT   DEFAULT NULL,
    requerida       TINYINT(1)   NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS encuesta_respuestas (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    encuesta_id     INT          NOT NULL,
    sesion_id       VARCHAR(64)  DEFAULT NULL,
    ip              VARCHAR(45)  DEFAULT NULL,
    fecha_respuesta TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS encuesta_respuesta_detalles (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    respuesta_id    INT          NOT NULL,
    pregunta_id     INT          NOT NULL,
    valor           TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
