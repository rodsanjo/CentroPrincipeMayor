<p><?php echo \core\Idioma::text("Idioma", "dicc"); ?></p>
<?php
    //$fichero = 'idiomas.txt';
    $ruta_fichero = PATH_APPLICATION."app/modelos/idiomas/idiomas.txt";
    $idiomas_disponibles = file($ruta_fichero,FILE_IGNORE_NEW_LINES);    // Lee las líneas y genera un array de índice entero con una cadena de caracteres en cada entrada del array. FILE_IGNORE_NEW_LINES es una constante entera de valor 2 que hace que no se incluya en la líneas los caracteres de fin de línea y nueva línea.

    foreach ($idiomas_disponibles as $key => $idioma) {
        $linea = explode("||", $idioma);
        $src = URL_ROOT.'/recursos/imagenes/banderas/'.'flag_'.$linea[1].'.png';
        //$src = \core\URL::generar_sin_idioma('recursos/imagenes/banderas').'flag_'.$linea[1].'.png';
        $title = \core\Idioma::text($linea[0],'dicc');
        $lang = $linea[1];
        $url = \core\URL::generar_sin_idioma("inicio");                    
        $funcion = 'set_lang("'.$lang.'", "'.$url.'")'; //al cambiar las comillas ' por " y viceversa, no va: "set_lang('".$linea[1]."', '".$url."')";
        echo"
            <a  onclick='$funcion'>
                <img src='$src' title='$title'/>            
            </a>
            ";                
    }
?>