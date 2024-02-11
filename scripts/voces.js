listar_obras_1();

function PlaySound(idvoz) {
    var sound = document.getElementById("audio_voz"+idvoz);
    sound.play();
    document.getElementById("btn_play"+idvoz).style.display = "none";
    document.getElementById("btn_pause"+idvoz).style.display = "block";
}
function PlaySound2(idvoz) {
    var sound = document.getElementById("audio_voz"+idvoz);
    sound.pause();
    document.getElementById("btn_play"+idvoz).style.display = "block";
    document.getElementById("btn_pause"+idvoz).style.display = "none";
}
function PlaySound3(idvoz) {
    var sound = document.getElementById("audio_voz"+idvoz);
    sound.currentTime = 0
	sound.play();
    document.getElementById("btn_play"+idvoz).style.display = "block";
    document.getElementById("btn_pause"+idvoz).style.display = "none";
}

function listar_obras(){
	document.getElementById("div_busquedas").style.display = "block";
	document.getElementById("div_voces").style.display = "none";
	var nombre = $("#text_nom").val();

	$.post("ajax/index.php?op=listar_obras&nombre="+nombre,function(r){
		$("#box_obras").html(r);
	});
}


function listar_voces(idobra, nombre){
	$.post("ajax/index.php?op=listar_voces&idobra="+idobra,function(r){
		$("#box_voces").html(r);
		document.getElementById("div_busquedas").style.display = "none";
		document.getElementById("div_voces").style.display = "block";
		$("#p_nom_obra").text(nombre);
	});
}
function listar_obras_1(){
	$.post("ajax/index.php?op=listar_obras_1",function(r){
		$("#box_obras").html(r);
	});
}