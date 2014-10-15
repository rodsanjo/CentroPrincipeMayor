var ok = false;
var f = formulario

function validarNum_portal(){
    //var nombre_via = f.nombre_via.value;
    var num_portal = f.num_portal.value;
    //alert(num_min_jug+" - "+num_max_jug);
    var patron=/^\d{0,}$/;
    if(!patron.test(num_portal)){
        document.getElementById("error_num_portal").innerHTML="Debe escribir solo números, 0 en caso de s/n, almacena el número de portal o el numero de plaza de garaje o el numero de local.";                
        ok = false;
    }else{
        document.getElementById("error_num_portal").innerHTML="";
    }
}
function validarNombre_via(){
    var valor = document.getElementById("nombre_via").value;
    var patron=/[\wñ]{1,}/i;
    if(!patron.test(valor)){
        document.getElementById("error_nombre_via").innerHTML="Este campo debe contener al menos 1 carácter.";
        ok = false;
    }else{
        document.getElementById("error_nombre_via").innerHTML="";
    }                      
}

function validarForm(){
    ok=true;

    validarNombre_via();
    validarNum_portal();

    //ok=false;	//Si devolvemos false, no se envia el formulario
    return ok;
}


