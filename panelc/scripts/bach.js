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
    // console.log("Listar voces");
    // console.log(idobra);
    // console.log(nombre);
	$.post("ajax/bach.php?op=listar_voces&idobra="+idobra+"&nombre="+nombre,function(r){
		$("#div_voces").html(r);
		 document.getElementById("tbl_obras").style.display = "none";
		 document.getElementById("tbl_voces").style.display = "block";

         document.getElementById("li_obra").style.display = "none";
		 document.getElementById("li_voz").style.display = "flex";
         document.getElementById("btn_back_obras").style.display="block";
         $("#nom_obra_voces").text(nombre);
		 $("#obra_select").text(nombre);
         $("#idobra").val(idobra);
         $("#idobra_refresh").val(idobra);

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

         document.getElementById("li_obra").style.display = "flex";
		 document.getElementById("li_voz").style.display = "none";
         document.getElementById("btn_back_obras").style.display="none";
}

function subir_doc_voz(){
    var parametros = new FormData($("#formulario_partituras")[0]);
    // console.log(parametros);
	$.ajax({

		data: parametros,
		url: "../panelc/ajax/bach.php?op=subir_doc_voz",
		type: "POST",
		contentType: false,
		processData: false,
		beforesend: function(){
		},
		success: function(data, status){
			data = JSON.parse(data);

            var nombre = $("#nom_obra_voces").text();
            var idobra = $("#idobra_refresh").val();

            listar_voces(idobra, nombre);
            var idvoz = $("#idvoz_upload").val();
            $.post("ajax/bach.php?op=lista_partituras&idvoz="+idvoz+"&nombre_obra="+nombre,function(r){
                $("#lista_partituras").html(r);

            }); 
			//listar_documentos_cargados_lic();													
		}
	});
}

function subir_doc_voz_modal(idvoz,voz){
    $("#idvoz_upload").val(idvoz);
    var nombre_obra = $("#nom_obra_voces").text();
    $("#nombre_obre_modal_partituras").text(nombre_obra);
    $("#nom_voz_upload").val(nombre_obra);
    $("#nombre_voz_modal_part").text(voz);

    var fecha=moment().format('YYYY-MM-DD');
	var hora=moment().format('HH:mm:ss');
	//var fecha_hora=fecha+" "+hora;
    //fecha_hora.tz('America/Mexico_City').format('YYYY-MM-DD HH:mm:ss');

    $("#fecha_reg_part").val(fecha);
    var nombre_obra = $("#nombre_obre_modal_partituras").text();

    $.post("ajax/bach.php?op=lista_partituras&idvoz="+idvoz+"&nombre_obra="+nombre_obra,function(r){
        $("#lista_partituras").html(r);

    }); 
}

function subir_doc_voz_content(){
    var idvoz = $("#idvoz_upload").val();
    if (idvoz>0) {
        document.getElementById("content_list_partituras").style.display="none";
        document.getElementById("content_reg_part").style.display="block";
        document.getElementById("span_back").style.display="inline";
    }else{
        alert("Regrese a la lista de voces y vuelva a interntar.");
    }
    
}
function back_list_part(){
    document.getElementById("content_list_partituras").style.display="block";
    document.getElementById("content_reg_part").style.display="none";
    document.getElementById("span_back").style.display="none";
}

function eliminar_partitura(idpartitura, nombre_obra, nombre_archivo){
    
    var confirmar =  confirm("¿Desea eliminar esta partitura?");
    //alert(confirmar);
    if (confirmar==true) {
        $.post("ajax/bach.php?op=eliminar_partitura",{idpartitura:idpartitura,nombre_obra:nombre_obra,nombre_archivo:nombre_archivo},function(data, status)
        {
            data = JSON.parse(data);

            var idobra = $("#idobra_refresh").val();
            var nombre = nombre_obra;
            listar_voces(idobra, nombre);
            alert("Partitura eliminada exitosamente");
           

        });
    }

        
}
