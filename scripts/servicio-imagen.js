'use strict';
const boton_foto = document.querySelector('#btn-foto');
//const imagen = document.querySelector('#user-photo');
let widget_cloudinary = cloudinary.createUploadWidget({
    cloudName: 'dozoneujz',
    uploadPreset: 'preset_pabs'
}, (err, result) => {
    if (!err && result && result.event === 'success') {
        console.log('Audio subida con éxito', result.info);
        console.log(result.info.secure_url);
        // imagen.src = result.info.secure_url;
        // var img = result.info.secure_url;
        // $("#nombre_img").val(img);
        //alert(img);
    }
});

boton_foto.addEventListener('click', () => {
    widget_cloudinary.open();
}, false);
