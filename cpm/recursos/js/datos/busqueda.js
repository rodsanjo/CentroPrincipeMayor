//JSON
var tipo_inmueble = [
        { 'origen':'vivienda' , 'texto':['Habitaciones','1 habitación','2 habitaciones','3 habitaciones','4 habitaciones'] , 'value':['','1','2','3','4'] }, 
        { 'origen':'garaje' , 'texto':['superficie mínima','6 m2','9 m2','12 m2','15 m2'], 'value':['','6','9','12','15'] }, 
        { 'origen':'local' , 'texto':['superficie mínima','50 m2','100 m2', '200 m2'], 'value':['superficie','50','100','200'] }
    ];
var tipo_transacion = [
        { 'origen':'comprar' , 'texto':['Precio','Hasta 50.000 €','Hasta 100.000 €','Hasta 150.000 €','Hasta 200.000 €','Hasta 300.000 €','Hasta 400.000 €'],'value':['','50000','100000','150000','200000','300000','400000'] }, 
        { 'origen':'alquilar' , 'texto':['Precio','Hasta 150 €','Hasta 300 €','Hasta 500 €','Hasta 700 €','Hasta 1.000 €'], 'value':['','150','300','500','700','1000'] }, 
    ];

function borrarDestinos(id){
    var destinos = document.getElementById(id);
    var nOption = destinos.children.length
    //alert(nOption);
    for(var i=0; i<nOption; i++){              
        destinos.removeChild(destinos.lastChild);   //Poniendo: (destinos.childNodes[0]) o (destinos.firstChild), falla la primera vez
    }
}

function insertarDestinos(name_origen, value_origen, id_destino){
    //var origen = document.billete.origen.value;
    //alert(value_origen);

    borrarDestinos(id_destino);
    //    $("#num_hab").children("option").remove();
    
    //Ponemos en vector_json, el json que queremos como destino.
    if(name_origen == 'tipo_inmueble'){
        vertor_json = tipo_inmueble;
    }else if(name_origen == 'tipo_transacion'){
        vertor_json = tipo_transacion;
    }
    textoOrigen = hallarPosicion(vertor_json, value_origen);
    
    crearOpciones(nDestinos, textoOrigen, id_destino);

}

function crearOpciones(nDestinos, textoOrigen, id_destino){
    //alert('número de destinos: '+ nDestinos);
    for(var i=0; i < nDestinos; i++){
        var destino = document.createElement("option"); //Creo la etiqueta
        destino.setAttribute("value",textoOrigen.value[i]); //creo los atributos
        var texto = document.createTextNode(textoOrigen.texto[i]); //Creo el texto
        destino.appendChild(texto);  //añado el texto a la etiqueta creada

        document.getElementById(id_destino).appendChild(destino);  //

    }
}

function hallarPosicion(vertor_json, value_origen){
    //alert(vertor_json);
    for(var i=0; i<vertor_json.length; i++){ //para hallar la posición de la ciudad origen en el array
        if(value_origen == vertor_json[i].origen ){
            key_origen = i;
            textoOrigen = vertor_json[key_origen];
            //alert(i);
            i=vertor_json.length;    //optimización
        }
    }
    nDestinos = vertor_json[key_origen].texto.length;
    //alert('número de destinos: '+ nDestinos);

    return textoOrigen;
}

        /*
        $(document).ready(function(){
          $("select[name='origen']").onchange(function(){
                $("#num_hab>option").remove();
          });
        });
        */


