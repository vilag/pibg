listar_obras();
function listar_obras(){
    $.post("ajax/bach.php?op=listar_obras",function(r){
		$("#div_obras").html(r);					
	});
}

function abrir_modal_nuevo()
{
    document.getElementById("btn_guardar_obra").style.display="block";
    document.getElementById("btn_actualizar_obra").style.display="none";
    $("#nombre").val("");
    $("#autor").val("");
    $("#idobra_update").val("");
}

function guardar_obra()
{
    var nombre = $("#nombre").val();
    var autor = $("#autor").val();

    if (nombre!="" && autor!="") {
        $.post("ajax/bach.php?op=guardar_obra",{nombre:nombre,autor:autor},function(data, status)
        {
            data = JSON.parse(data);

            alert("Obra guardada exitosamente");
            $("#nombre").val("");
            $("#autor").val("");
           
            listar_obras();
            

        });
    }else{
        alert("Es necesario llenar todos los campos");
    }
        
}

function editar_obra(idobra,nombre,autor)
{

    $("#nombre").val(nombre);
    $("#autor").val(autor);
    $("#idobra_update").val(idobra);
    document.getElementById("btn_guardar_obra").style.display="none";
    document.getElementById("btn_actualizar_obra").style.display="block";
        
}

function actualizar_obra(){

    var nombre = $("#nombre").val();
    var autor = $("#autor").val();
    var idobra = $("#idobra_update").val();

        $.post("ajax/bach.php?op=actualizar_obra",{nombre:nombre,autor:autor,idobra:idobra},function(data, status)
        {
            data = JSON.parse(data);

            alert("Obra actualizada exitosamente");

            listar_obras();        

        });

}

function eliminar_obra(idobra){

    var confirmar =  confirm("¿Desea eliminar esta obra?");
    //alert(confirmar);
    if (confirmar==true) {
        $.post("ajax/bach.php?op=eliminar_obra",{idobra:idobra},function(data, status)
        {
            data = JSON.parse(data);

            alert("Obra eliminada exitosamente (Se eliminaron las voces cargadas de la obra)");
            listar_obras();

        });
    }

        

}


function listar_voces(idobra, nombre){
	$.post("ajax/bach.php?op=listar_voces&idobra="+idobra,function(r){
		$("#div_voces").html(r);
		 document.getElementById("tbl_obras").style.display = "none";
		 document.getElementById("tbl_voces").style.display = "block";

         document.getElementById("li_obra").style.display = "none";
		 document.getElementById("li_voz").style.display = "block";
         document.getElementById("btn_back_obras").style.display="block";
		 $("#obra_select").text(nombre);
         $("#idobra").val(idobra);
	});
}

function abrir_modal_nuevo2()
{
    document.getElementById("btn_guardar_voz").style.display="block";
    document.getElementById("btn_actualizar_voz").style.display="none";
    $("#voz").val("");
    $("#archivo_audio").val("");
   
}


function guardar_voz()
{
    
    var voz = $("#voz").val();
    var archivo_audio = $("#archivo_audio").val();
    var idobra = $("#idobra").val();

    if (voz!="" && archivo_audio!="") {
        $.post("ajax/bach.php?op=guardar_voz",{idobra:idobra,voz:voz,archivo_audio:archivo_audio},function(data, status)
        {
            data = JSON.parse(data);

            alert("Voz guardada exitosamente");
            $("#voz").val("");
            $("#archivo_audio").val("");

            $.post("ajax/bach.php?op=listar_voces&idobra="+idobra,function(r){
                $("#div_voces").html(r);

            });
        });
    }else{
        alert("Es necesario llenar todos los campos");
    }
        
}


function editar_voz(idvoz,voz,enlace)
{
    $("#voz").val(voz);
    $("#idvoz").val(idvoz);
    $("#archivo_audio").val(enlace);
    document.getElementById("btn_guardar_voz").style.display="none";
    document.getElementById("btn_actualizar_voz").style.display="block";
        
}

function actualizar_voz(){

    var idvoz = $("#idvoz").val();
    var voz = $("#voz").val();
    var archivo_audio = $("#archivo_audio").val();
    var idobra = $("#idobra").val();

        $.post("ajax/bach.php?op=actualizar_voz",{idvoz:idvoz,voz:voz,archivo_audio:archivo_audio},function(data, status)
        {
            data = JSON.parse(data);

            alert("Voz actualizada exitosamente");

            $.post("ajax/bach.php?op=listar_voces&idobra="+idobra,function(r){
                $("#div_voces").html(r);

            });        

        });

}

function eliminar_voz(idvoz){

    var idobra = $("#idobra").val();

    var confirmar =  confirm("¿Desea eliminar esta voz?");
    //alert(confirmar);
    if (confirmar==true) {
        $.post("ajax/bach.php?op=eliminar_voz",{idvoz:idvoz},function(data, status)
        {
            data = JSON.parse(data);

            alert("Voz eliminada exitosamente");
            $.post("ajax/bach.php?op=listar_voces&idobra="+idobra,function(r){
                $("#div_voces").html(r);

            }); 

        });
    }

        

}

function regresar_a_obras()
{
        document.getElementById("tbl_obras").style.display = "block";
		 document.getElementById("tbl_voces").style.display = "none";

         document.getElementById("li_obra").style.display = "block";
		 document.getElementById("li_voz").style.display = "none";
         document.getElementById("btn_back_obras").style.display="none";
}
