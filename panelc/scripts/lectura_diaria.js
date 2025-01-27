var idlecturav = 0;
var url_api_consul = "";
var tipov = 0;
function init()
{
    listar_lecturas();
}

function listar_lecturas()
{
	$.post("ajax/lectura_diaria.php?op=listar_lecturas",function(r){
		$("#div_textos_guardados").html(r);					
	});
}

function guardar_lectura()
{
    var fecha = $("#fecha").val();
    var cita_biblica = $("#cita_biblica").val();
    var link = $("#link").val();

    if (fecha!="" && cita_biblica!="" && link!="") {
        $.post("ajax/lectura_diaria.php?op=guardar_lectura",{fecha:fecha,cita_biblica:cita_biblica,link:link},function(data, status)
        {
            data = JSON.parse(data);

            alert("Lectura guardada exitosamente");
            $("#fecha").val("");
            $("#cita_biblica").val("");
            $("#link").val("");
            listar_lecturas();

        });
    }else{
        alert("Es necesario llenar todos los campos");
    }
    
}

function seleccionar_tipo(){
    var tipo_cita_biblica = $("#tipo_cita_biblica").val();
    if (tipo_cita_biblica==1) {
        document.getElementById("vers1_cita_biblica").disabled = true;
        document.getElementById("vers2_cita_biblica").disabled = true;
    }
    if (tipo_cita_biblica==2) {
        document.getElementById("vers1_cita_biblica").disabled = false;
        document.getElementById("vers2_cita_biblica").disabled = true;
    }
    if (tipo_cita_biblica==3) {
        document.getElementById("vers1_cita_biblica").disabled = false;
        document.getElementById("vers2_cita_biblica").disabled = false;
    }
    limpiar_textos();
    document.getElementById("btn_confirm_cita").style.display="none";
}


var url_api = "";
var tipo_api = 0;
function buscar_cita(){  
    // var validacion = false;
    var tipo_cita_biblica = $("#tipo_cita_biblica").val();
    tipo_api = tipo_cita_biblica;
    var libro_cita_biblica = $("#libro_cita_biblica").val();
    var capitulo_cita_biblica = $("#capitulo_cita_biblica").val();
    var vers1_cita_biblica = $("#vers1_cita_biblica").val();
    var vers2_cita_biblica = $("#vers2_cita_biblica").val();
    if (tipo_cita_biblica==1 && libro_cita_biblica!="" && capitulo_cita_biblica!="") {
        // validacion = true;
        url_api = 'https://bible-api.deno.dev/api/read/rv1960/'+libro_cita_biblica+'/'+capitulo_cita_biblica+'';
        buscar_cita_resp()
    }else{
        if (tipo_cita_biblica==2 && libro_cita_biblica!="" && capitulo_cita_biblica>0 && vers1_cita_biblica>0) {
            // validacion = true; 
            url_api = 'https://bible-api.deno.dev/api/read/rv1960/'+libro_cita_biblica+'/'+capitulo_cita_biblica+'/'+vers1_cita_biblica+'';
            buscar_cita_resp()
        }else{
            if (tipo_cita_biblica==3 && libro_cita_biblica!="" && capitulo_cita_biblica>0 && vers1_cita_biblica>0 && vers2_cita_biblica>0) {
                // validacion = true;
                url_api = 'https://bible-api.deno.dev/api/read/rv1960/'+libro_cita_biblica+'/'+capitulo_cita_biblica+'/'+vers1_cita_biblica+'-'+vers2_cita_biblica+'';
                buscar_cita_resp()
            }else{
                // validacion = false;
                bootbox.alert("Por favor llene los campos activos");
                return; 
            }
        }
    }

    limpiar_textos();

}

async function buscar_cita_resp(){

	try {
		const apiUrl=url_api;
		const response = await fetch(apiUrl);
		const resultado = await response.json();
		console.log(resultado);

        setTimeout(() => {
            if (tipo_api==1) {
                if (resultado.vers.length>0) {
                    document.getElementById("btn_confirm_cita").style.display="block";
                    var cont= 0;
                    for (let index = 0; index < resultado.vers.length; index++) {
                        var fila='<div id="fila'+cont+'" style="margin-bottom: 10px;">'+					
                            '<p style="color: #000; font-size: 18px; line-height: 27px; font-weight: 300;">'+resultado.vers[index].number+'. '+resultado.vers[index].verse+'</p>'+
                        '</div>';
                        $('#text_confirm_div').append(fila);
                        cont++;
                        
                    }
                }
                
            }

            if (tipo_api==2) {
                if (resultado.verse != "undefined. undefined") {
                    document.getElementById("btn_confirm_cita").style.display="block";
                    var fila='<div id="fila'+cont+'" style="margin-bottom: 10px;">'+					
                        '<p style="color: #000; font-size: 18px; line-height: 27px; font-weight: 300;">'+resultado.number+'. '+resultado.verse+'</p>'+
                    '</div>';
                    $('#text_confirm_div').append(fila);
                }
                    
            }

            if (tipo_api==3) {
                if (resultado.length>0) {
                    document.getElementById("btn_confirm_cita").style.display="block";
                    for (let index = 0; index < resultado.length; index++) {
                        var fila='<div id="fila'+cont+'" style="margin-bottom: 10px;">'+					
                            '<p style="color: #000; font-size: 18px; line-height: 27px; font-weight: 300;">'+resultado[index].number+'. '+resultado[index].verse+'</p>'+
                        '</div>';
                        $('#text_confirm_div').append(fila);
                        cont++;
                        
                    }
                }
                
            }

        }, 1000);

        

	} catch (error) {
		console.error(error);
        //bootbox.alert(error);
	}
}

function guardar_cita(){
    var obj = document.getElementById('text_confirm_div');
    var versiculos = obj.getElementsByTagName('div').length;
    var fecha = $("#fecha").val();
    if (fecha!="") {
        if (versiculos>0) {

            var fecha = $("#fecha").val();
            var tipo_cita_biblica = $("#tipo_cita_biblica").val();
            var libro_cita_biblica = $("#libro_cita_biblica").val();
            var capitulo_cita_biblica = $("#capitulo_cita_biblica").val();
            var vers1_cita_biblica = $("#vers1_cita_biblica").val();
            var vers2_cita_biblica = $("#vers2_cita_biblica").val();

            $.post("ajax/lectura_diaria.php?op=guardar_cita_send",{
                fecha:fecha,
                tipo_cita_biblica:tipo_cita_biblica,
                libro_cita_biblica:libro_cita_biblica,
                capitulo_cita_biblica:capitulo_cita_biblica,
                vers1_cita_biblica:vers1_cita_biblica,
                vers2_cita_biblica:vers2_cita_biblica
            },function(data, status)
            {
                    data = JSON.parse(data);

                    $("#fecha").val("");
                    $("#tipo_cita_biblica").val("");
                    $("#libro_cita_biblica").val("");
                    $("#capitulo_cita_biblica").val("");
                    $("#vers1_cita_biblica").val("");
                    $("#vers2_cita_biblica").val("");
                    url_api = "";
                    tipo_api = 0;
                    limpiar_textos();
                    bootbox.alert("Cita biblica guardada exitosamente");
                    listar_lecturas();

            });
            
        }else{
            bootbox.alert("Por favor verifique la cita y vuelva a intentar");
        }
    }else{
        bootbox.alert("Por favor captura la fecha");
    }
    
    //alert(versiculos);
}

function limpiar_textos(){
    var element = document.getElementById("text_confirm_div");
    while (element.firstChild) {
        element.removeChild(element.firstChild);
    }
}

function limpiar_textos_reg(){
    var element = document.getElementById("text_confirm_div");
    while (element.firstChild) {
        element.removeChild(element.firstChild);
    }
}

function ver_textos_reg(idlectura, libro, capitulo, versiculo1, versiculo2, tipo){
    idlecturav = idlectura;
    tipov = tipo;
    var valview = $("#input_text_regis"+idlectura).val();
    
    
    if (valview==0) {
        document.getElementById("texto_regis"+idlectura).style.height=200+"px";
        $("#input_text_regis"+idlectura).val(1);

        if (tipo==1) {
            // validacion = true;
            url_api_consul = 'https://bible-api.deno.dev/api/read/rv1960/'+libro+'/'+capitulo+'';
            buscar_cita_resp_view();
        }else{
            if (tipo==2) {
                // validacion = true; 
                url_api_consul = 'https://bible-api.deno.dev/api/read/rv1960/'+libro+'/'+capitulo+'/'+versiculo1+'';
                buscar_cita_resp_view();
            }else{
                if (tipo==3) {
                    // validacion = true;
                    url_api_consul = 'https://bible-api.deno.dev/api/read/rv1960/'+libro+'/'+capitulo+'/'+versiculo1+'-'+versiculo2+'';
                    buscar_cita_resp_view();
                }else{
                    // validacion = false;
                    bootbox.alert("Por favor llene los campos activos");
                    return; 
                }
            }
        }

        
    }
    if (valview==1) {
        document.getElementById("texto_regis"+idlectura).style.height=0+"px";
        $("#input_text_regis"+idlectura).val(0);
    }
    
}

async function buscar_cita_resp_view(){

	try {
		const apiUrl=url_api_consul;
		const response = await fetch(apiUrl);
		const resultado = await response.json();
		//console.log(resultado);

        var element = document.getElementById("texto_regis"+idlecturav);
        while (element.firstChild) {
            element.removeChild(element.firstChild);
        }

        setTimeout(() => {
            if (tipov==1) {
                if (resultado.vers.length>0) {
                    // document.getElementById("btn_confirm_cita").style.display="block";
                    var cont= 0;
                    for (let index = 0; index < resultado.vers.length; index++) {
                        var fila='<div id="fila'+cont+'" style="margin-bottom: 10px;">'+					
                            '<p style="color: #000; font-size: 18px; line-height: 27px; font-weight: 300;">'+resultado.vers[index].number+'. '+resultado.vers[index].verse+'</p>'+
                        '</div>';
                        $('#texto_regis'+idlecturav).append(fila);
                        cont++;
                        
                    }
                }
                
            }

            if (tipov==2) {
                if (resultado.verse != "undefined. undefined") {
                    // document.getElementById("btn_confirm_cita").style.display="block";
                    var cont= 0;
                    var fila='<div id="fila'+cont+'" style="margin-bottom: 10px;">'+					
                        '<p style="color: #000; font-size: 18px; line-height: 27px; font-weight: 300;">'+resultado.number+'. '+resultado.verse+'</p>'+
                    '</div>';
                    $('#texto_regis'+idlecturav).append(fila);
                }
                    
            }

            if (tipov==3) {
                if (resultado.length>0) {
                    // document.getElementById("btn_confirm_cita").style.display="block";
                    var cont= 0;
                    for (let index = 0; index < resultado.length; index++) {
                        var fila='<div id="fila'+cont+'" style="margin-bottom: 10px;">'+					
                            '<p style="color: #000; font-size: 18px; line-height: 27px; font-weight: 300;">'+resultado[index].number+'. '+resultado[index].verse+'</p>'+
                        '</div>';
                        $('#texto_regis'+idlecturav).append(fila);
                        cont++;
                        
                    }
                }
                
            }

        }, 100);

        

	} catch (error) {
		console.error(error);
        //bootbox.alert(error);
	}
}

function borrar_lectura(idlectura){
    bootbox.confirm({
        message: "¿Confirmar eliminacion de lectura bíblica?",
        buttons: {
            confirm: {
                label: 'Si',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            // console.log('This was logged in the callback: ' + result);
            //alert(result);
            if (result) {
                // alert(idlectura);
                // return;
                $.post("ajax/lectura_diaria.php?op=borrar_lectura",{idlectura:idlectura},function(data, status)
                {
                    data = JSON.parse(data);

                    listar_lecturas();
                    bootbox.alert("Lectura borrada exitosamente");

                });
            }
        }
    });
}


init();