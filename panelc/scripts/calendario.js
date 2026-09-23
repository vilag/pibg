document.addEventListener("DOMContentLoaded", function() {
    cal_cargar_filtro_anios();
    listar_activ_sem();
});

var modo_edicion_cal = false;
var idcal_editar = 0;

function cal_cargar_filtro_anios() {
	$.post("ajax/calendario.php?op=listar_anios_disponibles", function (res) {
		if (!res || !res.ok) { listar_dias(); return; }
		var selectAnio = document.getElementById('cal_filtro_anio');
		selectAnio.innerHTML = '<option value="">Todos</option>' + res.anios.map(function (a) {
			return '<option value="' + a + '">' + a + '</option>';
		}).join('');
		selectAnio.value = res.anio_actual;
		document.getElementById('cal_filtro_mes').value = res.mes_actual;
		listar_dias();
	}, 'json');
}

function cal_filtro_limpiar() {
	document.getElementById('cal_filtro_mes').value = '';
	document.getElementById('cal_filtro_anio').value = '';
	listar_dias();
}

// Recuerda en que pagina esta el admin para no regresarlo a la 1 cada vez
// que guarda, edita o borra un registro (ver los listar_dias(cal_pagina_actual)
// mas abajo).
var cal_pagina_actual = 1;

function listar_dias(pagina)
{
	var mes = document.getElementById('cal_filtro_mes') ? document.getElementById('cal_filtro_mes').value : '';
	var anio = document.getElementById('cal_filtro_anio') ? document.getElementById('cal_filtro_anio').value : '';
	pagina = pagina || 1;
	cal_pagina_actual = pagina;
	$.post("ajax/calendario.php?op=listar_dias", { mes: mes, anio: anio, pagina: pagina }, function(res){
		if (!res || !res.ok) { $("#dias_calendario").html(''); cal_render_paginacion(1, 1); return; }
		$("#dias_calendario").html(res.html);
		// El servidor puede recortar la pagina pedida (por ejemplo si un
		// borrado la dejo fuera de rango), asi que se toma su valor final.
		cal_pagina_actual = res.pagina;
		cal_render_paginacion(res.pagina, res.total_paginas);
	}, 'json');
}

// La consulta viene paginada (50 registros por pagina) para no traer de
// golpe todos los registros guardados, sobre todo al dar "Ver todo".
function cal_render_paginacion(pagina, totalPaginas) {
	var cont = document.getElementById('cal_paginacion');
	if (!cont) return;
	if (totalPaginas <= 1) { cont.innerHTML = ''; return; }
	cont.innerHTML =
		'<button type="button" class="btn btn-outline-secondary btn-sm" ' + (pagina <= 1 ? 'disabled' : 'onclick="listar_dias(' + (pagina - 1) + ')"') + '>&laquo; Anterior</button>' +
		'<span style="margin: 0 12px; font-size: 13px; color: #6c757d;">Página ' + pagina + ' de ' + totalPaginas + '</span>' +
		'<button type="button" class="btn btn-outline-secondary btn-sm" ' + (pagina >= totalPaginas ? 'disabled' : 'onclick="listar_dias(' + (pagina + 1) + ')"') + '>Siguiente &raquo;</button>';
}

function mostrar_dia()
{
    var fecha_actividad = $("#fecha_actividad").val();
    //alert(fecha_actividad);

	var fecha=moment().format('YYYY-MM-DD');
	var hora=moment().format('HH:mm:ss');
	var fecha_hora=fecha+" "+hora;

	var dia = moment(fecha_actividad).format('dddd');

   // alert(dia);

	if (dia=="Monday") {dia="Lunes";}
	if (dia=="Tuesday") {dia="Martes";}
	if (dia=="Wednesday") {dia="Miercoles";}
	if (dia=="Thursday") {dia="Jueves";}
	if (dia=="Friday") {dia="Viernes";}
	if (dia=="Saturday") {dia="Sabado";}
	if (dia=="Sunday") {dia="Domingo";}

	$("#dia").val(dia);
}

// function mostrar_horas_capt(){
// 	document.getElementById("horas_capt").style.display="block";

// 	$.post("ajax/calendario.php?op=listar_horas",function(r){
// 		$("#horas_capt").html(r);					
// 	});
// }

function set_hora(idcal,hora){
	//alert(hora)
	$("#hora_actividad").val(hora);
	document.getElementById("horas_capt").style.display="none";
}

// function mostrar_nombre_capt(){
// 	document.getElementById("nombre_act_capt").style.display="block";

// 	$.post("ajax/calendario.php?op=listar_nombres",function(r){
// 		$("#nombre_act_capt").html(r);					
// 	});
// }

function set_nombre(idcal,nom_activ){
	//alert(hora)
	$("#nom_actividad").val(nom_activ);
	document.getElementById("nombre_act_capt").style.display="none";
}

function listar_activ_sem(){

	$.post("ajax/calendario.php?op=listar_activ_sem",function(r){
		$("#box_act_sem").html(r);					
	});
}

function set_dia_sem(idactiv, nombre, hora){
	$("#hora_actividad").val(hora);
	$("#nom_actividad").val(nombre);
}

function guardar_dia_calendario()
{
	var fecha_actividad = $("#fecha_actividad").val();
	var hora_actividad = $("#hora_actividad").val();
	var dia = $("#dia").val();
	var nom_actividad = $("#nom_actividad").val();
	var tema_actividad = $("#tema_actividad").val();
	var tipo_actividad = $('input[name="tipo_actividad"]:checked').val();
	var tipo_act = 0;

	if (tipo_actividad=="Si" || tipo_actividad=="No") {
		if (tipo_actividad=="Si") {
			tipo_act = 1;
		}else{
			tipo_act = 0;
		}
	}else{
		alert("Es necesario seleccionar el estatus de transmisión");
		return;
	}

	var fecha_hora = fecha_actividad+" "+hora_actividad;
	var editando = modo_edicion_cal;
	// Al editar no se exige que la hora sea distinta de "00:00:00": ese valor
	// es válido para un registro ya guardado (medianoche, o una actividad
	// importada del PDF sin hora capturada) y no debe bloquear la edición de
	// los demás campos de un registro existente.
	var hora_valida = editando || hora_actividad != "00:00:00";

	if (fecha_actividad!="" && hora_valida && nom_actividad!="") {
		var datos = {fecha_hora:fecha_hora,dia:dia,nom_actividad:nom_actividad,tema_actividad:tema_actividad,tipo_act:tipo_act};
		var op = editando ? "actualizar_dia_calendario" : "guardar_dia_calendario";
		if (editando) { datos.idcal = idcal_editar; }

		$.post("ajax/calendario.php?op=" + op, datos, function(data, status)
		{
			// op=actualizar_dia_calendario responde JSON (jQuery ya lo parsea
			// por el header Content-Type que manda ese endpoint) con {ok:bool};
			// op=guardar_dia_calendario responde el booleano crudo como texto,
			// por eso solo ese caso necesita JSON.parse.
			if (!editando) { data = JSON.parse(data); }

			var exito = editando ? !!(data && data.ok) : !!data;
			if (!exito) {
				var msg = (data && data.msg) ? data.msg : (editando ? "No se pudo actualizar el registro." : "No se pudo guardar el registro.");
				bootbox.alert(msg);
				return;
			}

			cancelar_edicion_calendario();
			listar_dias(cal_pagina_actual);
			bootbox.alert(editando ? "Registro actualizado exitosamente" : "Registro guardado exitosamente");

		});
	}else{
		bootbox.alert("Es necesario capturar los datos obligatorios: fecha, hora y nombre de actividad.");
	}
}

function editar_dia_calendario(idcal)
{
	$.post("ajax/calendario.php?op=obtener_dia", { idcal: idcal }, function (data) {
		// El endpoint responde JSON con header Content-Type: application/json,
		// asi que jQuery ya entrega "data" parseado como objeto.
		if (!data.ok || !data.dia) { bootbox.alert(data.msg || "No se encontró el registro."); return; }
		var d = data.dia;

		$("#fecha_actividad").val(d.fecha);
		$("#hora_actividad").val(d.hora);
		$("#dia").val(d.dia_nom);
		$("#nom_actividad").val(d.nom_activ);
		$("#tema_actividad").val(d.tema);
		$('input[name="tipo_actividad"]').prop('checked', false);
		$(d.tipo == 1 ? "#activ1" : "#activ2").prop('checked', true);

		modo_edicion_cal = true;
		idcal_editar = idcal;
		$("#cal_form_titulo").text("Editar registro de calendario");
		$("#cal_btn_guardar").text("Actualizar");
		$("#cal_editando_msg").show();

		window.scrollTo({ top: 0, behavior: 'smooth' });
	});
}

function cancelar_edicion_calendario()
{
	modo_edicion_cal = false;
	idcal_editar = 0;
	$("#cal_form_titulo").text("Registrar en calendario");
	$("#cal_btn_guardar").text("Guardar");
	$("#cal_editando_msg").hide();
	$("#fecha_actividad").val("");
	$("#hora_actividad").val("00:00:00");
	$("#dia").val("");
	$("#nom_actividad").val("");
	$("#tema_actividad").val("");
	$('input[name="tipo_actividad"]').prop('checked', false);
}

var cal_pdf_eventos = [];

// Nombres (normalizados) de actividades que el usuario ya marco alguna vez
// como que no transmiten, para aplicarlos como Transmisión=No automatica en
// futuros analisis de PDF. Se cargan al analizar cada PDF (ver
// cal_analizar_pdf) para no depender de que ya hayan cargado al abrir la
// pagina.
var cal_pdf_no_transmite_conocidas = [];

function cal_analizar_pdf() {
	var input = document.getElementById('cal_pdf_input');
	if (!input.files || !input.files[0]) {
		bootbox.alert('Selecciona primero un archivo PDF.');
		return;
	}

	var fd = new FormData();
	fd.append('pdf', input.files[0]);

	document.getElementById('cal_pdf_btn_analizar').disabled = true;
	document.getElementById('cal_pdf_estado').textContent = 'Analizando PDF, esto puede tardar unos segundos…';

	// La lista de nombres conocidos es solo un plus para los valores por
	// defecto: si falla, se sigue con una lista vacia en vez de tirar todo
	// el analisis del PDF (que es la parte que de verdad importa).
	var peticionConocidas = $.get('ajax/calendario.php?op=listar_no_transmite', null, null, 'json')
		.then(function (r) { return (r && r.ok) ? r.nombres : []; })
		.catch(function () { return []; });

	$.ajax({
		url: 'ajax/calendario.php?op=analizar_pdf',
		type: 'POST',
		data: fd,
		processData: false,
		contentType: false,
		dataType: 'json',
		success: function (res) {
			document.getElementById('cal_pdf_btn_analizar').disabled = false;
			if (!res || !res.ok) {
				document.getElementById('cal_pdf_estado').textContent = '';
				bootbox.alert((res && res.msg) ? res.msg : 'No se pudo analizar el PDF.');
				return;
			}
			peticionConocidas.then(function (nombres) {
				cal_pdf_no_transmite_conocidas = nombres;
				cal_pdf_eventos = res.eventos.map(function (e) {
					e.seleccionado = false;
					e.tipo = cal_pdf_tipo_por_defecto(e.nom_activ);
					return e;
				});
				document.getElementById('cal_pdf_estado').textContent = 'Se encontraron ' + cal_pdf_eventos.length + ' actividades (año ' + res.anio + '). Marca con el check las que quieras registrar (o usa "Marcar todas").';
				cal_pdf_poblar_filtro_anios();
				cal_pdf_render_tabla();
			});
		},
		error: function () {
			document.getElementById('cal_pdf_btn_analizar').disabled = false;
			document.getElementById('cal_pdf_estado').textContent = '';
			bootbox.alert('Ocurrió un error al subir o analizar el PDF.');
		}
	});
}

function cal_pdf_poblar_filtro_anios() {
	var anios = Array.from(new Set(cal_pdf_eventos.map(function (e) { return (e.fecha || '').substring(0, 4); }))).filter(Boolean).sort();
	var select = document.getElementById('cal_pdf_filtro_anio');
	select.innerHTML = '<option value="">Todos</option>' + anios.map(function (a) {
		return '<option value="' + a + '">' + a + '</option>';
	}).join('');
}

function cal_pdf_visibles() {
	var mes = document.getElementById('cal_pdf_filtro_mes').value;
	var anio = document.getElementById('cal_pdf_filtro_anio').value;
	var out = [];
	cal_pdf_eventos.forEach(function (e, i) {
		if (mes && parseInt(e.fecha.substring(5, 7), 10) !== parseInt(mes, 10)) return;
		if (anio && e.fecha.substring(0, 4) !== anio) return;
		out.push({ e: e, i: i });
	});
	return out;
}

// Resaltado de filas cuyo nombre de actividad coincida (aunque sea de
// forma aproximada) con estas categorias recurrentes del calendario, que
// normalmente no se transmiten en vivo. La comparacion ignora
// mayusculas/acentos.
function cal_pdf_quitar_acentos(s) {
	return (s || '').normalize('NFD').replace(/[̀-ͯ]/g, '');
}

// CAL_PDF_CATEGORIAS (bg/borde por categoria) lo define calendario.php justo
// antes de cargar este script, a partir del mismo arreglo que usa para la
// leyenda, para que ambos no se puedan desincronizar.

function cal_pdf_categoria(nom_activ) {
	var t = cal_pdf_quitar_acentos(nom_activ).toLowerCase();
	if ((t.indexOf('cena') !== -1 && t.indexOf('senor') !== -1) || t.indexOf('santa cena') !== -1) return 'cena'; // Cena del Señor / Santa Cena
	if (t.indexOf('negocios') !== -1) return 'negocios'; // Sesion(es) de negocios
	if (t.indexOf('convivi') !== -1 || t.indexOf('comida') !== -1 || t.indexOf('conas') !== -1) return 'convivio'; // Comidas y convivios
	return '';
}

// Ademas de las categorias de arriba, se recuerdan (en la tabla
// calendario_no_transmite via ajax) los nombres exactos de actividades que
// el usuario haya marcado manualmente como "No" en Transmisión, para
// aplicarles ese mismo valor por defecto en futuros PDFs.
function cal_pdf_normalizar_nombre(s) {
	return cal_pdf_quitar_acentos(s).toLowerCase().trim();
}

function cal_pdf_es_no_transmite_conocida(nom_activ) {
	return cal_pdf_no_transmite_conocidas.indexOf(cal_pdf_normalizar_nombre(nom_activ)) !== -1;
}

function cal_pdf_tipo_por_defecto(nom_activ) {
	return (cal_pdf_categoria(nom_activ) || cal_pdf_es_no_transmite_conocida(nom_activ)) ? 0 : 1;
}

function cal_pdf_marcar_no_transmite(nom_activ) {
	if (!nom_activ) return;
	$.post('ajax/calendario.php?op=marcar_no_transmite', { nom_activ: nom_activ });
}

function cal_pdf_desmarcar_no_transmite(nom_activ) {
	if (!nom_activ) return;
	$.post('ajax/calendario.php?op=desmarcar_no_transmite', { nom_activ: nom_activ });
}

// Si el usuario cambia Transmisión varias veces seguidas en la misma fila
// (por ejemplo se arrepiente al toque), las peticiones a marcar/desmarcar
// podrian llegar al servidor desordenadas y dejar guardado lo contrario de
// la ultima seleccion. Se espera un momento sin cambios en esa fila antes
// de mandar una sola peticion con el valor final.
var cal_pdf_no_transmite_timers = {};

function cal_pdf_programar_no_transmite(i, nom_activ, marcar) {
	if (cal_pdf_no_transmite_timers[i]) clearTimeout(cal_pdf_no_transmite_timers[i]);
	cal_pdf_no_transmite_timers[i] = setTimeout(function () {
		delete cal_pdf_no_transmite_timers[i];
		if (marcar) { cal_pdf_marcar_no_transmite(nom_activ); } else { cal_pdf_desmarcar_no_transmite(nom_activ); }
	}, 500);
}

function cal_pdf_render_tabla() {
	document.getElementById('cal_pdf_revision').style.display = cal_pdf_eventos.length ? 'block' : 'none';
	var tbody = document.getElementById('cal_pdf_tabla');
	var html = cal_pdf_visibles().map(function (par) {
		var e = par.e, i = par.i;
		var estilo = CAL_PDF_CATEGORIAS[cal_pdf_categoria(e.nom_activ)];
		var trStyle = estilo ? ' style="background-color:' + estilo.bg + ';"' : '';
		var campoStyle = estilo ? 'background-color:' + estilo.bg + ';' : '';
		var primeraCeldaStyle = estilo ? 'border-left:5px solid ' + estilo.borde + ';' : '';
		return '<tr' + trStyle + '>' +
			'<td style="' + primeraCeldaStyle + '"><input type="checkbox" ' + (e.seleccionado ? 'checked' : '') + ' onchange="cal_pdf_actualizar(' + i + ',\'seleccionado\',this.checked)"></td>' +
			'<td><input type="date" class="form-control form-control-sm" style="' + campoStyle + '" value="' + e.fecha + '" onchange="cal_pdf_actualizar(' + i + ',\'fecha\',this.value)"></td>' +
			'<td><input type="text" class="form-control form-control-sm" style="width:80px;' + campoStyle + '" value="' + e.hora + '" onchange="cal_pdf_actualizar(' + i + ',\'hora\',this.value)"></td>' +
			'<td><input type="text" class="form-control form-control-sm" style="width:100px;' + campoStyle + '" value="' + cal_pdf_escapar(e.dia_nom) + '" onchange="cal_pdf_actualizar(' + i + ',\'dia_nom\',this.value)"></td>' +
			'<td><input type="text" class="form-control form-control-sm" style="' + campoStyle + '" value="' + cal_pdf_escapar(e.nom_activ) + '" onchange="cal_pdf_actualizar(' + i + ',\'nom_activ\',this.value)"></td>' +
			'<td><input type="text" class="form-control form-control-sm" style="' + campoStyle + '" value="' + cal_pdf_escapar(e.tema) + '" onchange="cal_pdf_actualizar(' + i + ',\'tema\',this.value)"></td>' +
			'<td><select class="form-control form-control-sm" style="' + campoStyle + '" onchange="cal_pdf_actualizar(' + i + ',\'tipo\',this.value)">' +
				'<option value="0" ' + (e.tipo == 0 ? 'selected' : '') + '>No</option>' +
				'<option value="1" ' + (e.tipo == 1 ? 'selected' : '') + '>Si</option>' +
			'</select></td>' +
			'<td><button type="button" class="btn btn-sm btn-outline-danger" onclick="cal_pdf_quitar_fila(' + i + ')">&times;</button></td>' +
			'</tr>';
	}).join('');
	tbody.innerHTML = html;
	cal_pdf_actualizar_contador();
}

function cal_pdf_escapar(s) {
	return (s || '').replace(/"/g, '&quot;');
}

function cal_pdf_actualizar(i, campo, valor) {
	cal_pdf_eventos[i][campo] = valor;
	if (campo === 'fecha') {
		var dia = moment(valor).format('dddd');
		var mapa = { Monday: 'Lunes', Tuesday: 'Martes', Wednesday: 'Miercoles', Thursday: 'Jueves', Friday: 'Viernes', Saturday: 'Sabado', Sunday: 'Domingo' };
		cal_pdf_eventos[i].dia_nom = mapa[dia] || dia;
	}
	if (campo === 'seleccionado') cal_pdf_actualizar_contador();
	if (campo === 'tipo') {
		// El usuario cambio Transmisión a mano: ya no se debe pisar con el
		// valor por defecto de la categoria si luego edita el nombre. Ademas
		// se recuerda (o se olvida) el nombre para que futuros PDFs con esta
		// misma actividad tomen el mismo valor automaticamente.
		cal_pdf_eventos[i]._tipo_manual = true;
		cal_pdf_programar_no_transmite(i, cal_pdf_eventos[i].nom_activ, valor == '0');
	}
	if (campo === 'nom_activ') {
		// El nombre editado puede ahora coincidir (o dejar de coincidir) con
		// alguna categoria o actividad conocida: se actualiza la transmision
		// por defecto (solo si el usuario no la cambio a mano) y se
		// re-renderiza para reflejar el color y el select de Transmisión.
		if (!cal_pdf_eventos[i]._tipo_manual) {
			cal_pdf_eventos[i].tipo = cal_pdf_tipo_por_defecto(valor);
		}
		cal_pdf_render_tabla();
	}
}

function cal_pdf_actualizar_contador() {
	var total = cal_pdf_eventos.filter(function (e) { return e.seleccionado; }).length;
	document.getElementById('cal_pdf_contador').textContent = total;
}

function cal_pdf_quitar_fila(i) {
	cal_pdf_eventos.splice(i, 1);
	cal_pdf_render_tabla();
}

function cal_pdf_agregar_fila() {
	var mes = document.getElementById('cal_pdf_filtro_mes').value;
	var anio = document.getElementById('cal_pdf_filtro_anio').value;
	var fecha = moment().format('YYYY-MM-DD');
	if (mes || anio) {
		var a = anio || moment().format('YYYY');
		var m = mes ? ('0' + mes).slice(-2) : '01';
		fecha = a + '-' + m + '-01';
	}
	cal_pdf_eventos.push({
		fecha: fecha,
		hora: '12:00:00',
		dia_nom: '',
		nom_activ: '',
		tema: '',
		tipo: 1,
		seleccionado: true
	});
	cal_pdf_render_tabla();
}

// Los botones de marcar/desmarcar solo afectan las filas visibles segun
// el filtro de mes/año activo (no todo el conjunto de datos).
function cal_pdf_marcar_todas(valor) {
	cal_pdf_visibles().forEach(function (par) { par.e.seleccionado = valor; });
	cal_pdf_render_tabla();
}

function cal_pdf_marcar_desde_hoy() {
	var hoy = moment().format('YYYY-MM-DD');
	cal_pdf_visibles().forEach(function (par) { par.e.seleccionado = par.e.fecha >= hoy; });
	cal_pdf_render_tabla();
}

var cal_pdf_guardando = false;

function cal_pdf_registrar_seleccionadas() {
	if (cal_pdf_guardando) return; // evita doble envio por clics repetidos

	var seleccionados = cal_pdf_eventos.filter(function (e) { return e.seleccionado; });
	if (!seleccionados.length) {
		bootbox.alert('No has seleccionado ninguna actividad.');
		return;
	}
	var vacias = seleccionados.filter(function (e) { return !e.fecha || !e.nom_activ; });
	if (vacias.length) {
		bootbox.alert('Hay actividades seleccionadas sin fecha o sin nombre. Corrígelas o desmárcalas.');
		return;
	}

	var btn = document.getElementById('cal_pdf_btn_registrar');

	bootbox.confirm({
		message: '¿Registrar ' + seleccionados.length + ' actividades en el calendario?',
		buttons: { confirm: { label: 'Si', className: 'btn-success' }, cancel: { label: 'No', className: 'btn-danger' } },
		callback: function (result) {
			if (!result || cal_pdf_guardando) return;
			cal_pdf_guardando = true;
			if (btn) btn.disabled = true;

			$.post('ajax/calendario.php?op=guardar_multiples', { eventos: JSON.stringify(seleccionados) }, function (data) {
				cal_pdf_guardando = false;
				if (btn) btn.disabled = false;
				if (data && data.ok) {
					var msg = 'Se registraron ' + data.guardados + ' actividades.';
					if (data.omitidos > 0) {
						msg += ' (' + data.omitidos + ' ya existían con la misma fecha, hora y nombre, así que no se duplicaron.)';
					}
					bootbox.alert(msg);
					cal_pdf_eventos = cal_pdf_eventos.filter(function (e) { return !e.seleccionado; });
					cal_pdf_render_tabla();
					listar_dias(cal_pagina_actual);
				} else {
					bootbox.alert((data && data.msg) ? data.msg : 'No se pudieron guardar las actividades.');
				}
			}).fail(function () {
				cal_pdf_guardando = false;
				if (btn) btn.disabled = false;
				bootbox.alert('Ocurrió un error de conexión al guardar.');
			});
		}
	});
}

function borrar_dia(idcal){
    bootbox.confirm({
        message: "¿Confirmar eliminacion de registro?",
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
                $.post("ajax/calendario.php?op=borrar_dia",{idcal:idcal},function(data, status)
                {
                    data = JSON.parse(data);

                    listar_dias(cal_pagina_actual);
                    bootbox.alert("Registro eliminado exitosamente");

                });
            }
        }
    });
}