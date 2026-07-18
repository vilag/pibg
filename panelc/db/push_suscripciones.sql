CREATE TABLE IF NOT EXISTS push_suscripciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('fcm','webpush') NOT NULL,
    fcm_token VARCHAR(255) NULL,
    endpoint TEXT NULL,
    p256dh VARCHAR(255) NULL,
    auth VARCHAR(255) NULL,
    user_agent VARCHAR(255) NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_fcm_token (fcm_token),
    UNIQUE KEY uq_endpoint (endpoint(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS push_notificaciones_enviadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    mensaje VARCHAR(255) NOT NULL,
    url VARCHAR(255) NULL,
    total_destinatarios INT NOT NULL DEFAULT 0,
    total_exitosos INT NOT NULL DEFAULT 0,
    fecha_envio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
