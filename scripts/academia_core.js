function academia_enviar_solicitud() {
    var nombre    = $("#aca_nombre").val().trim();
    var correo    = $("#aca_correo").val().trim();
    var telefono  = $("#aca_telefono").val().trim();
    var instrumentos = [];
    $("input[name='aca_instrumento']:checked").each(function () {
        instrumentos.push($(this).val());
    });

    var resultado = $("#aca_form_resultado");
    resultado.removeClass('aca-form__resultado--error').text('');

    if (!nombre || !correo || !telefono) {
        resultado.addClass('aca-form__resultado--error').text('Completa nombre, correo y teléfono.');
        return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
        resultado.addClass('aca-form__resultado--error').text('Ingresa un correo válido.');
        return;
    }
    if (!instrumentos.length) {
        resultado.addClass('aca-form__resultado--error').text('Selecciona al menos un instrumento.');
        return;
    }

    var btn = $("#aca_form_btn");
    btn.prop('disabled', true).html('Enviando…');

    $.post('ajax/academia_core.php?op=solicitar_informes', {
        nombre: nombre,
        correo: correo,
        telefono: telefono,
        instrumentos: instrumentos,
        aca_campo_extra: $("#aca_campo_extra").val()
    }, function (r) {
        var resp = typeof r === 'string' ? JSON.parse(r) : r;
        btn.prop('disabled', false).html('<i class="fa fa-paper-plane"></i> Solicitar informes');
        if (resp.ok) {
            resultado.text('¡Listo! Te enviamos un correo de confirmación. Pronto nos comunicaremos contigo.');
            $("#aca_nombre, #aca_correo, #aca_telefono").val('');
            $("input[name='aca_instrumento']").prop('checked', false);
        } else {
            resultado.addClass('aca-form__resultado--error').text(resp.msg || 'Ocurrió un error, intenta de nuevo.');
        }
    }).fail(function () {
        btn.prop('disabled', false).html('<i class="fa fa-paper-plane"></i> Solicitar informes');
        resultado.addClass('aca-form__resultado--error').text('Ocurrió un error de conexión, intenta de nuevo.');
    });
}
