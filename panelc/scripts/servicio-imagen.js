'use strict';

const CLOUDINARY_CONFIG = { cloudName: 'dozoneujz', uploadPreset: 'preset_pabs' };

// Widget para el formulario de registro
const boton_foto = document.querySelector('#btn-foto');
let widget_cloudinary = cloudinary.createUploadWidget(CLOUDINARY_CONFIG, (err, result) => {
    if (!err && result && result.event === 'success') {
        $("#archivo_audio").val(result.info.secure_url);
    }
});
boton_foto.addEventListener('click', () => { widget_cloudinary.open(); }, false);

// Widget para el modal de edición (se inicializa cuando existe el botón)
const boton_foto_edit = document.querySelector('#btn-foto-edit');
if (boton_foto_edit) {
    let widget_cloudinary_edit = cloudinary.createUploadWidget(CLOUDINARY_CONFIG, (err, result) => {
        if (!err && result && result.event === 'success') {
            $("#edit_imagen").val(result.info.secure_url);
            $("#edit_preview_img").attr("src", result.info.secure_url);
        }
    });
    boton_foto_edit.addEventListener('click', () => { widget_cloudinary_edit.open(); }, false);
}
