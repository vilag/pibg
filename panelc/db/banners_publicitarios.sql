-- Banners Publicitarios
-- Editor de banners (imagen de fondo, imagenes secundarias, textos, contacto/direccion)
-- diseno_json guarda el canvas.toJSON() completo de Fabric.js (todo embebido, incluidas las imagenes en base64)
-- imagen_final_base64 guarda el PNG aplanado para miniatura en la galeria y descarga rapida

CREATE TABLE banners_publicitarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    ancho_px INT NOT NULL,
    alto_px INT NOT NULL,
    unidad_original ENUM('px','cm') DEFAULT 'px',
    ancho_original DECIMAL(10,2) DEFAULT NULL,
    alto_original DECIMAL(10,2) DEFAULT NULL,
    diseno_json LONGTEXT NOT NULL,
    imagen_final_base64 MEDIUMTEXT DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
