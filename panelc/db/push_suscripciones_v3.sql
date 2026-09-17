-- Migración v3: vincular cada suscripción push a la cuenta del panel que la
-- activó (idusuario), para poder dirigir avisos a un usuario específico y no
-- solo a "todos" o "cualquier admin". NULL para suscriptores públicos del
-- sitio, que no tienen cuenta en el panel.
ALTER TABLE push_suscripciones
    ADD COLUMN idusuario INT NULL AFTER es_admin;
