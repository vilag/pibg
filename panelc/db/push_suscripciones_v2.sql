-- Migración v2: distinguir la suscripción push del administrador de las
-- suscripciones públicas del sitio, para poder avisarle solo a él sobre
-- eventos internos (ej. nuevas peticiones de oración) sin transmitirlos
-- a los demás suscriptores.
ALTER TABLE push_suscripciones
    ADD COLUMN es_admin TINYINT(1) NOT NULL DEFAULT 0 AFTER activo;
