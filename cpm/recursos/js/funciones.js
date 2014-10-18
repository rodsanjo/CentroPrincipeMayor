function abrirImagen(nombre_foto){
    //url = 'http://localhost/web/CentroPrincipeMayor/cpm/recursos/imagenes/bienes/' + nombre_foto;
    url = '../../../recursos/imagenes/bienes/' + nombre_foto;
    //alert('hola');
    ventana2=window.open(url,'fotos inmueble','width=500, height=600');
    ventana2.window.moveTo(400,100);
    ventana2.window.focus();
}

$(document).ready(function() {
    $('#botonMostrarMapa').click(function() {
        $('#mapholder').css('border','solid')
        $('#mapholder').css('border-color','green')
    });
});