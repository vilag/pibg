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
            data = JSON.parse(data);

          // alert(data);
          // var idusuario = data.idusuario;
           //alert(idusuario);
            

            if (data!=null)
            {

                 //alert("valido");
                 $(location).attr("href","calendario.php");
                                
            }
            if (data==null) {

                alert("Usuario y/o Password incorrectos");
                    
            }
        });
}



init();
