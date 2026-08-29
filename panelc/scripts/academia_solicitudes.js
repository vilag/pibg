var filtro_academia_activo = 'todas';

function set_filtro_academia(f) {
    filtro_academia_activo = f;
    $(".btn-filtro-aca").css({ background: "#f0f0f0", color: "#333", fontWeight: "normal" });
    $("#btn_filtro_" + f).css({ background: "#042C49", color: "#fff", fontWeight: "600" });
    listar_academia_solicitudes();
}

function listar_academia_solicitudes() {
    $("#tabla_academia_solicitudes").html("<tr><td colspan='7' style='text-align:center;padding:20px;color:#aaa;'>Cargando...</td></tr>");
    $.get("ajax/academia_solicitudes.php?op=listar&filtro=" + filtro_academia_activo, function (html) {
        $("#tabla_academia_solicitudes").html(html);
    });
}

function toggle_atendida_academia(id, valor) {
    $.post("ajax/academia_solicitudes.php?op=toggle_atendida", { id: id, valor: valor }, function (r) {
        var data = typeof r === "string" ? JSON.parse(r) : r;
        if (data.ok) { listar_academia_solicitudes(); }
    });
}

function eliminar_solicitud_academia(id) {
    bootbox.confirm({
        message: "¿Eliminar esta solicitud? Esta acción no se puede deshacer.",
        buttons: {
            confirm: { label: "Sí, eliminar", className: "btn-danger" },
            cancel:  { label: "Cancelar",     className: "btn-secondary" }
        },
        callback: function (result) {
            if (result) {
                $.post("ajax/academia_solicitudes.php?op=eliminar", { id: id }, function () {
                    listar_academia_solicitudes();
                });
            }
        }
    });
}

function cargar_correos_academia() {
    $.getJSON("ajax/academia_solicitudes.php?op=obtener_config", function (data) {
        if (data.ok) { $("#aca_correos_notif").val(data.correos); }
    });
}

function guardar_correos_academia() {
    var correos = $("#aca_correos_notif").val().trim();
    var resultado = $("#aca_config_resultado");
    resultado.css("color", "").text("Guardando…");
    $.post("ajax/academia_solicitudes.php?op=guardar_config", { correos: correos }, function (r) {
        var data = typeof r === "string" ? JSON.parse(r) : r;
        if (data.ok) {
            resultado.css("color", "#28a745").text("✓ Guardado.");
        } else {
            resultado.css("color", "#c00").text(data.msg || "Ocurrió un error.");
        }
    });
}
