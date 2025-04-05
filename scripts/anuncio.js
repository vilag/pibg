var imgposition = 0;
function siguienteimg(){
    if (imgposition==0) {
        $("#img_anuncio1").removeClass("estilo_img1").addClass("estilo_img4");
        $("#img_anuncio2").removeClass("estilo_img2").addClass("estilo_img1");
        $("#img_anuncio3").removeClass("estilo_img3").addClass("estilo_img2");
        $("#img_anuncio4").removeClass("estilo_img4").addClass("estilo_img3");
        imgposition=1;
        return;
    }
    if (imgposition==1) {
        $("#img_anuncio1").removeClass("estilo_img4").addClass("estilo_img3");
        $("#img_anuncio2").removeClass("estilo_img1").addClass("estilo_img4");
        $("#img_anuncio3").removeClass("estilo_img2").addClass("estilo_img1");
        $("#img_anuncio4").removeClass("estilo_img3").addClass("estilo_img2");
        imgposition=2;
        return;
    }
    if (imgposition==2) {
        $("#img_anuncio1").removeClass("estilo_img3").addClass("estilo_img2");
        $("#img_anuncio2").removeClass("estilo_img4").addClass("estilo_img3");
        $("#img_anuncio3").removeClass("estilo_img1").addClass("estilo_img4");
        $("#img_anuncio4").removeClass("estilo_img2").addClass("estilo_img1");
        imgposition=3;
        return;
    }
    if (imgposition==3) {
        $("#img_anuncio1").removeClass("estilo_img2").addClass("estilo_img1");
        $("#img_anuncio2").removeClass("estilo_img3").addClass("estilo_img2");
        $("#img_anuncio3").removeClass("estilo_img4").addClass("estilo_img3");
        $("#img_anuncio4").removeClass("estilo_img1").addClass("estilo_img4");
        imgposition=0;
        return;
    }
    
}