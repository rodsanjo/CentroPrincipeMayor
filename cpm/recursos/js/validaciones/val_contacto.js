var ok = false;    
function validarNombre(){
	var valor = document.getElementById("nombre").value;
	var patron=/[\w]{3,}/i;
	if(valor == 'Nombre' || !patron.test(valor)){
                document.getElementById("error_nombre").innerHTML="El nombre debe contener al menos 3 caracteres.";
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
		//document.getElementById("error_email").innerHTML="El email es incorrecto. Formato cuenta@servidor.net";
                document.getElementById("error_emailPhone").innerHTML="El email es incorrecto. Formato cuenta@servidor.net";
		ok = false;
	}else{
		//document.getElementById("error_email").innerHTML="";
                ok=true;    //ponemos ok aqui porque va conjunto con el email-telefono
	}
        return ok;
}

function validarPhone(){
	var valor = document.getElementById("phone").value;
        //alert(valor);
	var patron= /^[+]{0,1}([\d]{0,2})([\d]{9,})$/i;
	if(!patron.test(valor)){
		//document.getElementById("error_phone").innerHTML="Debe escribir el número sin espacios en blanco con al menos 9 dígitos";
		ok = false;
	}else{
		//document.getElementById("error_phone").innerHTML="";ç
                ok=true;    //ponemos ok aqui porque va conjunto con el email-telefono
	}
        return ok;
}

function validarEmailPhone(){
    var email = validarEmail();
    var phone = validarPhone();
    var valorEmail = document.getElementById("email").value;
    var valorPhone = document.getElementById("phone").value;
    //alert(email+', '+phone+', '+valorEmail+', '+valorPhone);
        if ( (email && phone) || ( email && valorPhone == '' ) || ( valorEmail == '' && phone ) ){
            document.getElementById("error_emailPhone").innerHTML="";
            ok = true;
        }else if(( valorPhone == '' ) && ( valorEmail == '' ) ){
            document.getElementById("error_emailPhone").innerHTML="Al menos debe facilitar un medio de contacto";
            ok = false;
        }else if( !email ){
            document.getElementById("error_emailPhone").innerHTML="El email es incorrecto. Formato cuenta@servidor.net";
            ok = false;
        }else if( !phone){
            document.getElementById("error_emailPhone").innerHTML="El teléfono debe contener un número sin espacios en blanco con al menos 9 dígitos";
            ok = false;
        }
}

function validarAsunto(){
	var valor = document.getElementById("asunto").value;
        //alert(valor);
	var patron= /([\w]{1,})/i;
	if(valor == 'Asunto' || !patron.test(valor)){
		document.getElementById("error_asunto").innerHTML="El asunto bebe contener al menos un caracter válido.";
		ok = false;
	}else{
		document.getElementById("error_asunto").innerHTML="";
	}
}
function validarMensaje(){
	var valor = document.getElementById("mensaje").value;
        //alert(valor);
	var patron= /([\w]{4,})/i;
	if(valor == 'Mensaje' || !patron.test(valor)){
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
	
        //Debe estar el email o el telefono
	validarEmailPhone();
        
        if(document.formulario.recibirCopia.checked){
            validarEmail();
        }
        
	validarAsunto();
	validarMensaje()

	return ok;
}
