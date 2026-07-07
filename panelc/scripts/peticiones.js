var filtro_activo = 'todas';

document.addEventListener("DOMContentLoaded", function () {
    cargar_badge();
});

/* ── Autenticación ──────────────────────────────────────────── */
function verificar_clave() {
    var clave = $("#input_clave_pet").val().trim();
    if (!clave) return;
    $("#btn_acceder_pet").prop("disabled", true).text("Verificando...");
    $.post("ajax/peticiones.php?op=verificar_clave", { clave: clave }, function (r) {
        var data = typeof r === "string" ? JSON.parse(r) : r;
        if (data.ok) {
            $("#panel_clave_pet").hide();
            $("#panel_peticiones").show();
            listar_peticiones();
        } else {
            $("#error_clave_pet").text(data.msg).show();
            $("#input_clave_pet").val("").focus();
            $("#btn_acceder_pet").prop("disabled", false).text("Acceder");
        }
    });
}

/* ── Lista ──────────────────────────────────────────────────── */
function set_filtro(f) {
    filtro_activo = f;
    $(".btn-filtro-pet").css({ background: "#f0f0f0", color: "#333", fontWeight: "normal" });
    $("#btn_filtro_" + f).css({ background: "#042C49", color: "#fff", fontWeight: "600" });
    listar_peticiones();
}

function listar_peticiones() {
    $("#tabla_peticiones").html("<tr><td colspan='6' style='text-align:center;padding:20px;color:#aaa;'>Cargando...</td></tr>");
    $.get("ajax/peticiones.php?op=listar&filtro=" + filtro_activo, function (html) {
        $("#tabla_peticiones").html(html);
        cargar_badge();
    });
}

function toggle_atendida(id, valor) {
    $.post("ajax/peticiones.php?op=toggle_atendida", { id: id, valor: valor }, function (r) {
        var data = typeof r === "string" ? JSON.parse(r) : r;
        if (data.ok) { listar_peticiones(); }
    });
}

function eliminar_peticion(id) {
    bootbox.confirm({
        message: "¿Eliminar esta petición? Esta acción no se puede deshacer.",
        buttons: {
            confirm: { label: "Sí, eliminar", className: "btn-danger" },
            cancel:  { label: "Cancelar",     className: "btn-secondary" }
        },
        callback: function (result) {
            if (result) {
                $.post("ajax/peticiones.php?op=eliminar", { id: id }, function () {
                    listar_peticiones();
                });
            }
        }
    });
}

function toggleMotivo(link) {
    var $link = $(link);
    var $td   = $link.closest("td");
    $td.find(".motivo-prev, .motivo-full").toggle();
}

/* ── Badge sidebar ──────────────────────────────────────────── */
function cargar_badge() {
    $.getJSON("ajax/peticiones.php?op=contar", function (data) {
        if (data.n > 0) {
            $("#badge_peticiones").text(data.n).show();
        } else {
            $("#badge_peticiones").hide();
        }
    });
}

/* Enter en campo de clave */
$(document).on("keydown", "#input_clave_pet", function (e) {
    if (e.key === "Enter") verificar_clave();
});
