-- Tabla de transcripciones guardadas desde el módulo de Groq Whisper
-- Ejecutar en la base de datos: u690371019_pibg

CREATE TABLE IF NOT EXISTS transcripcion (
  id_transcripcion INT          AUTO_INCREMENT PRIMARY KEY,
  nombre_archivo   VARCHAR(255) NOT NULL,
  idioma           VARCHAR(10)  DEFAULT 'es',
  texto            LONGTEXT     NOT NULL,
  num_palabras     INT          DEFAULT 0,
  fecha_creacion   DATETIME     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
