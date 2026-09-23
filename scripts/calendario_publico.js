var MESES_PUB = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

var cal_pub_hoy = new Date();
var cal_pub_estado = {
	mes: cal_pub_hoy.getMonth() + 1,
	anio: cal_pub_hoy.getFullYear(),
	dia_sel: null,
	eventos_por_dia: {}
};

document.addEventListener('DOMContentLoaded', function () {
	cal_pub_poblar_selects();
	cal_pub_cargar_anios();

	document.getElementById('cal_pub_prev').addEventListener('click', function () { cal_pub_mover_mes(-1); });
	document.getElementById('cal_pub_next').addEventListener('click', function () { cal_pub_mover_mes(1); });
	document.getElementById('cal_pub_hoy').addEventListener('click', function () {
		var h = new Date();
		cal_pub_estado.mes = h.getMonth() + 1;
		cal_pub_estado.anio = h.getFullYear();
		cal_pub_estado.dia_sel = null;
		cal_pub_cargar_mes(function () { cal_pub_seleccionar_dia(h.getDate()); });
	});
	document.getElementById('cal_pub_sel_mes').addEventListener('change', function () {
		cal_pub_estado.mes = parseInt(this.value, 10);
		cal_pub_estado.dia_sel = null;
		cal_pub_cargar_mes();
	});
	document.getElementById('cal_pub_sel_anio').addEventListener('change', function () {
		cal_pub_estado.anio = parseInt(this.value, 10);
		cal_pub_estado.dia_sel = null;
		cal_pub_cargar_mes();
	});

	cal_pub_cargar_mes(function () {
		var h = cal_pub_hoy;
		if (cal_pub_estado.anio === h.getFullYear() && cal_pub_estado.mes === h.getMonth() + 1) {
			cal_pub_seleccionar_dia(h.getDate());
		}
	});
});

function cal_pub_poblar_selects() {
	var selMes = document.getElementById('cal_pub_sel_mes');
	selMes.innerHTML = MESES_PUB.map(function (nombre, i) {
		return '<option value="' + (i + 1) + '">' + nombre + '</option>';
	}).join('');
	selMes.value = cal_pub_estado.mes;
}

function cal_pub_cargar_anios() {
	$.get('ajax/index.php?op=listar_calendario_anios', function (res) {
		if (!res || !res.ok) return;
		var selAnio = document.getElementById('cal_pub_sel_anio');
		selAnio.innerHTML = res.anios.map(function (a) {
			return '<option value="' + a + '">' + a + '</option>';
		}).join('');
		selAnio.value = cal_pub_estado.anio;
	}, 'json').fail(function () {
		// Si falla, al menos deja seleccionar el año actual y el siguiente.
		var selAnio = document.getElementById('cal_pub_sel_anio');
		var actual = cal_pub_estado.anio;
		selAnio.innerHTML = '<option value="' + actual + '">' + actual + '</option><option value="' + (actual + 1) + '">' + (actual + 1) + '</option>';
	});
}

function cal_pub_mover_mes(delta, despues_de_cargar) {
	cal_pub_estado.mes += delta;
	if (cal_pub_estado.mes > 12) { cal_pub_estado.mes = 1; cal_pub_estado.anio++; }
	if (cal_pub_estado.mes < 1) { cal_pub_estado.mes = 12; cal_pub_estado.anio--; }
	cal_pub_estado.dia_sel = null;
	cal_pub_cargar_mes(despues_de_cargar);
}

function cal_pub_cargar_mes(despues_de_cargar) {
	document.getElementById('cal_pub_sel_mes').value = cal_pub_estado.mes;
	document.getElementById('cal_pub_sel_anio').value = cal_pub_estado.anio;
	document.getElementById('cal_pub_titulo').textContent = MESES_PUB[cal_pub_estado.mes - 1] + ' ' + cal_pub_estado.anio;

	var grid = document.getElementById('cal_pub_grid');
	grid.classList.add('cal_pub_cargando');

	$.get('ajax/index.php?op=listar_calendario_mes&mes=' + cal_pub_estado.mes + '&anio=' + cal_pub_estado.anio, function (res) {
		grid.classList.remove('cal_pub_cargando');
		if (!res || !res.ok) {
			cal_pub_estado.eventos_por_dia = {};
			cal_pub_render_grid();
			return;
		}
		var porDia = {};
		res.dias.forEach(function (ev) {
			var dia = parseInt(ev.dia, 10);
			if (!porDia[dia]) porDia[dia] = [];
			porDia[dia].push(ev);
		});
		cal_pub_estado.eventos_por_dia = porDia;
		cal_pub_render_grid();

		if (despues_de_cargar) despues_de_cargar();
	}, 'json').fail(function () {
		grid.classList.remove('cal_pub_cargando');
		grid.innerHTML = '<div class="cal_pub_error">No se pudo cargar el calendario. Intenta recargar la página.</div>';
	});
}

function cal_pub_render_grid() {
	var grid = document.getElementById('cal_pub_grid');
	var anio = cal_pub_estado.anio, mes = cal_pub_estado.mes;

	var diasEnMes = new Date(anio, mes, 0).getDate();
	var diasMesAnterior = new Date(anio, mes - 1, 0).getDate();
	var primerDiaSemana = new Date(anio, mes - 1, 1).getDay(); // 0=domingo
	var offsetInicial = (primerDiaSemana + 6) % 7; // convertir a lunes=0

	var celdas = [];
	for (var i = 0; i < offsetInicial; i++) {
		celdas.push({ dia: diasMesAnterior - offsetInicial + 1 + i, fuera: true });
	}
	for (var d = 1; d <= diasEnMes; d++) {
		celdas.push({ dia: d, fuera: false });
	}
	while (celdas.length % 7 !== 0) {
		celdas.push({ dia: celdas.length - offsetInicial - diasEnMes + 1, fuera: true, siguiente: true });
	}

	var esMesActual = (anio === cal_pub_hoy.getFullYear() && mes === cal_pub_hoy.getMonth() + 1);

	var html = celdas.map(function (c) {
		var clases = ['cal_pub_celda'];
		if (c.fuera) clases.push('cal_pub_fuera');
		if (!c.fuera && esMesActual && c.dia === cal_pub_hoy.getDate()) clases.push('cal_pub_hoy_celda');
		if (!c.fuera && cal_pub_estado.dia_sel === c.dia) clases.push('cal_pub_seleccionada');

		var eventosHtml = '';
		if (!c.fuera && cal_pub_estado.eventos_por_dia[c.dia]) {
			var eventos = cal_pub_estado.eventos_por_dia[c.dia];
			var visibles = eventos.slice(0, 2);
			eventosHtml = visibles.map(function (ev) {
				var enVivo = ev.tipo == 1 ? '<span class="cal_pub_punto"></span>' : '';
				var hora = (ev.hora || '').substring(0, 5);
				var titulo = (hora + ' ' + (ev.nom_activ || '')).trim();
				return '<div class="cal_pub_chip" title="' + cal_pub_escapar(titulo) + '">' + enVivo + cal_pub_escapar(titulo) + '</div>';
			}).join('');
			if (eventos.length > 2) {
				eventosHtml += '<div class="cal_pub_mas">+' + (eventos.length - 2) + ' más</div>';
			}
		}

		var onclick = c.fuera
			? "cal_pub_ir_a_mes_vecino(" + (c.siguiente ? 1 : -1) + "," + c.dia + ")"
			: "cal_pub_seleccionar_dia(" + c.dia + ")";

		return '<div class="' + clases.join(' ') + '" onclick="' + onclick + '">' +
			'<div class="cal_pub_celda_num">' + c.dia + '</div>' +
			'<div class="cal_pub_celda_eventos">' + eventosHtml + '</div>' +
			'</div>';
	}).join('');

	grid.innerHTML = html;
}

function cal_pub_escapar(s) {
	return (s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function cal_pub_ir_a_mes_vecino(delta, dia) {
	cal_pub_mover_mes(delta, function () { cal_pub_seleccionar_dia(dia); });
}

function cal_pub_seleccionar_dia(dia) {
	cal_pub_estado.dia_sel = dia;
	cal_pub_render_grid();

	var titulo = document.getElementById('cal_pub_detalle_titulo');
	var lista = document.getElementById('cal_pub_detalle_lista');
	var esHoy = (cal_pub_estado.anio === cal_pub_hoy.getFullYear() && cal_pub_estado.mes === cal_pub_hoy.getMonth() + 1 && dia === cal_pub_hoy.getDate());

	titulo.textContent = (esHoy ? 'Hoy, ' : '') + dia + ' de ' + MESES_PUB[cal_pub_estado.mes - 1] + ' de ' + cal_pub_estado.anio;

	var eventos = cal_pub_estado.eventos_por_dia[dia];
	if (!eventos || !eventos.length) {
		lista.innerHTML = '<div class="cal_pub_detalle_vacio">No hay actividades registradas este día.</div>';
		return;
	}

	lista.innerHTML = eventos.map(function (ev) {
		var enVivo = ev.tipo == 1 ? '<span class="cal_pub_punto"></span> ' : '';
		var hora = (ev.hora || '').substring(0, 5);
		var tema = ev.tema ? '<div class="cal_pub_ev_tema">' + cal_pub_escapar(ev.tema) + '</div>' : '';
		return '<div class="cal_pub_ev">' +
			'<div class="cal_pub_ev_hora">' + cal_pub_escapar(hora) + '</div>' +
			'<div class="cal_pub_ev_body">' +
				'<div class="cal_pub_ev_nombre">' + enVivo + cal_pub_escapar(ev.nom_activ || '') + '</div>' +
				tema +
			'</div>' +
		'</div>';
	}).join('');
}
