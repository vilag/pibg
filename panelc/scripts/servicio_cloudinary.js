'use strict';
const boton_foto = document.querySelector('#btn-foto_lumbrera');
//const imagen = document.querySelector('#user-photo');
let widget_cloudinary = cloudinary.createUploadWidget({
    cloudName: 'dmtvvrw4s',
    uploadPreset: 'upload_lumbrera'
}, (err, result) => {
    if (!err && result && result.event === 'success') {
        console.log('Audio subida con éxito', result.info);
        console.log(result.info.secure_url);
        $("#archivo_audio").val(result.info.secure_url);
        // imagen.src = result.info.secure_url;
        // var img = result.info.secure_url;
        // $("#nombre_img").val(img);
        //alert(img);
    }
});

boton_foto.addEventListener('click', () => {
    widget_cloudinary.open();
}, false);
