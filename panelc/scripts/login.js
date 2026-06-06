function init()
{

 // alert("entra");
}


function login()
{
   // alert("entra");

    var logina = $("#logina").val();
    var clavea = $("#clavea").val();

    //alert(logina);
    //alert(clavea);


         $.post("ajax/usuario.php?op=verificar",
            {"logina":logina,"clavea":clavea},
            function(data)
        {
            var parsed;
            try { parsed = typeof data === 'string' ? JSON.parse(data) : data; }
            catch(e) { alert("Error inesperado del servidor. Intente más tarde."); return; }

            if (parsed && parsed.error === 'db') {
                alert("No se pudo conectar a la base de datos. Intente más tarde.");
                return;
            }

            if (parsed != null) {
                $(location).attr("href","calendario.php");
            } else {
                alert("Usuario y/o Password incorrectos");
            }
        });
}



document.addEventListener("DOMContentLoaded", function() { init(); });
