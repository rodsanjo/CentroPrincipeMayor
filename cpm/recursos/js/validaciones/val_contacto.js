var ok = false;    
function validarNombre(){
	var valor = document.getElementById("nombre").value;
	var patron=/[\w]{3,}/i;
	if(!patron.test(valor)){
		document.getElementById("error_nombre").innerHTML="Debe contener al menos 3 caracteres";
		//document.getElementById("nombre").value="";
		ok = false;
	}else{
		document.getElementById("error_nombre").innerHTML="";
	}                      
}

function validarEmail(){
	var valor = document.getElementById("email").value;
        //alert(valor);
	var patron= /^([a-z\d]{1,})(([\.|\_\-]{1}[a-z\d]{1,}){1,}){0,}@([a-z\d]{0,})(([\.|\_\-]{1}[a-z\d]{1,}){1,}){0,}(\.[a-z]{2,4})$/i;
	if(!patron.test(valor)){
		document.getElementById("error_email").innerHTML="El email es incorrecto. Formato cuenta@servidor.net";
		ok = false;
	}else{
		document.getElementById("error_email").innerHTML="";
	}
}

function validarAsunto(){
	var valor = document.getElementById("asunto").value;
        //alert(valor);
	var patron= /([\w]{1,})/i;
	if(!patron.test(valor)){
		document.getElementById("error_asunto").innerHTML="Debe contener al menos un caracter válido";
		ok = false;
	}else{
		document.getElementById("error_asunto").innerHTML="";
	}
}
function validarMensaje(){
	var valor = document.getElementById("mensaje").value;
        //alert(valor);
	var patron= /([\w]{4,})/i;
	if(!patron.test(valor)){
		document.getElementById("error_mensaje").innerHTML="La longitud del mensaje es insuficiente";
		ok = false;
	}else{
		document.getElementById("error_mensaje").innerHTML="";
	}
}

function validarForm(){
	var f=formulario;
	ok=true;
	
	validarNombre();
	validarEmail();
	validarAsunto();
	validarMensaje()

	return ok;
}

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
            	url: 'index.php?module=contact&action=send',
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