function validarForm(){
	var f=formulario;
	ok=true;
	
        var error_form="";
        if ( $('#nombre').val()=='Nombre' ){ error_form=error_form+'- Nombre\r\n'; }
        if ( $('#phone').val()=='Teléfono' ) { error_form=error_form+'- Teléfono\r\n'; }
        if ( $('#email').val()=='Email' )  { error_form=error_form+'- Correo Electrónico\r\n'; }
        if ( $('#comentario').val()=='' || $('#comentario').val()=='Comentarios') { error_form=error_form+'- Comentario\r\n'; }
        if (error_form!="") {
            error_form="Debes rellenar los siguientes campos\r\n"+"__________________________________________\r\n\r\n"+error_form;
            alert(error_form);
            ok = false;
        }

	return ok;
}
/*
$(document).ready(function() {
    $('#form_comentario').submit(function() {
        var error="";
        if ( $('#nombre').val()=='' ){ error=error+'- Nombre\r\n'; }
        if ( $('#phone').val()=='' ) { error=error+'- Teléfono\r\n'; }
        if ( $('#email').val()=='' )  { error=error+'- Correo Electrónico\r\n'; }
        if ( $('#comentario').val()=='') { error=error+'- Comentario\r\n'; }
        if (error!="") {
            error="Debes rellenar los siguientes campos\r\n"+"__________________________________________\r\n\r\n"+error;
            alert(error);
        } else {
            $.ajax({
            	type: 'POST',
            	url: 'http://localhost/web/CentroPrincipeMayor/cpm/contacto/enviar_comentario',
            	data: $('#form_comentario').serialize(),
            	success:function(msj){	
            		//alert(msj);
            		if ( msj == 1 ){
            			alert("Se ha enviado la información al anunciante");	
            		}else{
            			alert("No se ha podido enviar la información al anunciante");
            		}
            	},
            	error:function(){
            		alert("Error interno. Inténtelo de nuevo más tarde.");
            	}
            });
        }	
        return false;  
    });
    initialize();
});
*/

