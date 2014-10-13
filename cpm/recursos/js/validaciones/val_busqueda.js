var ok = false;
function validarPrecio(){
    var valor = document.getElementById("precio").value;
    var patron=/^(\d{0,})(([\.\,]\d{0,}){0,1})$/i;
    if(!patron.test(valor)){
            document.getElementById("error_precio").innerHTML="El precio debe escribirse sin separador de miles.";
            ok = false;
    }else{
            document.getElementById("error_precio").innerHTML="";
    }
}
function validarLocalidad(){
    if(! document.getElementById("buscar_nombre").value.length>0){
        ok = false;
        alert('hola mm');
    }
}

function validarForm(){
	var f=formulario;
	ok=true;
	
        //validarLocalidad();
	
        validarPrecio();

	return ok;
}