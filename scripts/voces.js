listar_obras_1();

function listar_obras() {
    document.getElementById("div_busquedas").style.display = "block";
    document.getElementById("div_voces").style.display = "none";
    var nombre = $("#text_nom").val();
    $("#v-spinner-obras").show();
    $("#box_obras").html("");
    $.post("ajax/index.php?op=listar_obras&nombre=" + encodeURIComponent(nombre), function (r) {
        $("#v-spinner-obras").hide();
        $("#box_obras").html(r);
    });
}

function listar_voces(idobra, nombre) {
    $("#v-spinner-voces").show();
    $("#box_voces").html("");
    $.post("ajax/index.php?op=listar_voces&idobra=" + idobra + "&nombre=" + encodeURIComponent(nombre), function (r) {
        $("#v-spinner-voces").hide();
        $("#box_voces").html(r);
        document.getElementById("div_busquedas").style.display = "none";
        document.getElementById("div_voces").style.display = "block";
        $("#p_nom_obra").text(nombre);
    });
}

function listar_obras_1() {
    $("#v-spinner-obras").show();
    $("#box_obras").html("");
    $.post("ajax/index.php?op=listar_obras_1", function (r) {
        $("#v-spinner-obras").hide();
        $("#box_obras").html(r);
        document.getElementById("div_busquedas").style.display = "block";
        document.getElementById("div_voces").style.display = "none";
    });
}

function regresar_obras() {
    document.getElementById("div_busquedas").style.display = "block";
    document.getElementById("div_voces").style.display = "none";
}

function PlaySound(idvoz) {
    var sound = document.getElementById("audio_voz" + idvoz);
    sound.play();
}
function PlaySound2(idvoz) {
    var sound = document.getElementById("audio_voz" + idvoz);
    sound.pause();
}
function PlaySound3(idvoz) {
    var sound = document.getElementById("audio_voz" + idvoz);
    sound.currentTime = 0;
    sound.play();
}