listar_obras();
function listar_obras(){
    $.post("ajax/bach.php?op=listar_obras",function(r){
		$("#div_obras").html(r);					
	});
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

function listar_voces(idobra, nombre){
	$.post("ajax/bach.php?op=listar_voces&idobra="+idobra,function(r){
		$("#div_voces").html(r);
		 document.getElementById("tbl_obras").style.display = "none";
		 document.getElementById("tbl_voces").style.display = "block";

         document.getElementById("form_obras").style.display = "none";
		 document.getElementById("form_voces").style.display = "block";
		 $("#obra_select").text(nombre);
         $("#idobra").val(idobra);
	});
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


bootbox.confirm('This is the default confirm!',
                                function(result) {
                                console.log('This was logged in the callback: ' + result);
                                });