<?php
namespace controladores;

class enlaces extends \core\Controlador {
    
    private static $enlace = 'enlace';
    private static $enlaces = 'enlaces';
    private static $plantilla = 'plantilla_principal';
    public static $campo1 = 'titulo';   //Si las ponemos private no las puedo llamar desde otro sitio
    public static $campo2 = "url";
    public static $campo3 = "descripcion";

    //public static $file_name = new \modelos\Enlaces_en_fichero("enlaces.txt");


    public function index(array $datos = array()) {

        $datos[self::$enlaces] = \modelos\Enlaces_en_fichero::get_enlaces();

        $datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
        $http_body = \core\Vista_Plantilla::generar(self::$plantilla, $datos, true);
        \core\HTTP_Respuesta::enviar($http_body);

    }


    public function form_anexar(array $datos = array()) {

        $datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
        $http_body = \core\Vista_Plantilla::generar(self::$plantilla, $datos, true);
        \core\HTTP_Respuesta::enviar($http_body);

    }

    public function form_anexar_validar(array $datos = array()) {	
        //$enlace = \core\HTTP_Requerimiento::post();  //recojo en $enlace lo que viene por POST del formulario.
            // Ahora los datos recibidos del formulario los recoge el metodo \core\Validaciones::errores_validacion_request($validaciones, $datos) y los deja almacenados en un array en $datos[values], pues $datos se pasa por referencia.
            //la sentencia anterior nos guarda los valores que vienen por name en un array: $enlace['titulo'], $enlace['autor'] y $enlace['comentario'];
        $validaciones = array(
            self::$campo1 => "errores_notnull && errores_texto  && errores_punto_y_coma"
            , self::$campo2 => "errores_notnull && errores_texto && errores_punto_y_coma"
            , self::$campo3 => "errores_punto_y_coma && errores_salto_linea"
        );

        $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos);

        if (! $validacion) {
                    //print "-- Depuración: \$datos= "; print_r($datos);
                    \core\Distribuidor::cargar_controlador(self::$enlaces, "form_anexar", $datos);
        }else{
            $enlace = $datos['values']; //Valores de los input que han sido validados. Es necesario porque tras la validación ha perdido los datos introducidos en enlace.
            \modelos\Enlaces_en_fichero::anexar_enlace($enlace);   //anexa el enlace

            \core\HTTP_Respuesta::set_header_line("location", "?menu=".self::$enlaces."&submenu=index");
            \core\HTTP_Respuesta::enviar();
            //Otra forma:
            //\core\Distribuidor::cargar_controlador("$enlaces", "index");  //devuelve el listado de $enlaces
        }
    }

    public function form_modificar(array $datos =array()){
        if(!isset($datos['errores'])){
            $id = \core\HTTP_Requerimiento::get("id");  //cojo el valor enviado por el formulario para buscar el enlace a continuacion. El id va por GET porque ha sido escrito por el programador.
            $datos["values"] = \modelos\Enlaces_en_fichero::get_enlaces($id);    //values o valores son los "name" que mandaremos al formulario en value=""
            $datos['values']['id'] = $id;   //el id no existe en la BD de los $enlaces creada
        }
        $datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
        $http_body = \core\Vista_Plantilla::generar(self::$plantilla, $datos, true);
        \core\HTTP_Respuesta::enviar($http_body);
    }

    public function form_modificar_validar(array $datos =array()){
        //$enlace = \core\HTTP_Requerimiento::post();  //recojo en $enlace lo que viene por POST del formulario. ¡¡OJO!! el id ahora tb viene por POST pues es un input.
        $validaciones = array(
            "id" => 'errores_notnull && errores_numero_entero_positivo'
            , self::$campo1 => "errores_notnull && errores_texto && errores_punto_y_coma"
            , self::$campo2 => "errores_notnull && errores_texto && errores_punto_y_coma"
            , self::$campo3 => "errores_punto_y_coma && errores_salto_linea"
        );

        $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos);

        if (! $validacion) {                   
            //print "-- Depuración: \$datos= "; print_r($datos);
            \core\Distribuidor::cargar_controlador(self::$enlaces, "form_modificar", $datos);
        }else{            
            $enlace = $datos['values']; //Valores de los input que han sido validados
            \modelos\Enlaces_en_fichero::modificar_enlace($enlace);    //llamo a la función que modificará el enlace escogido.

            \core\HTTP_Respuesta::set_header_line("location", \core\URL::generar(\core\Distribuidor::get_controlador_instanciado()));
            \core\HTTP_Respuesta::enviar();
            //Otra forma:            
            //\core\Distribuidor::cargar_controlador("$enlaces", "index");  //volvemos al menu con el listado de $enlaces. El problema llega al actualizar la página.
        }
    }


    public function form_borrar(array $datos =array()){
        $id = \core\HTTP_Requerimiento::get("id");
        $datos["values"] = \modelos\Enlaces_en_fichero::get_enlaces($id);
        $datos['values']['id'] = $id;

        $datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
        $http_body = \core\Vista_Plantilla::generar(self::$plantilla, $datos, true);
        \core\HTTP_Respuesta::enviar($http_body);
    }

    public function form_borrar_validar(array $datos =array()){
        //$enlace = \core\HTTP_Requerimiento::post();  //recojo en $enlace lo que viene por POST del formulario.

        $validaciones = array(
                "id" => "errores_notnull && errores_numero_entero_positivo"
                // La siguientes reglas no son necesarias porque el formulario form_borrar es de solo lectura y los datos no se modificarán
        );

        $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos);
        if (! $validacion) {
                $datos['errores']['validacion'] = 'Error al identificar el id del objeto a borrar.' . $datos['errores']['validacion'];
                //print "-- Depuración: \$datos= "; print_r($datos);
                \core\Distribuidor::cargar_controlador(self::$enlaces, "form_borrar", $datos);
        }else{            
            $enlace = $datos["values"]; // Los datos del enlace están recogidos por la validación en $datos[values]
            \modelos\Enlaces_en_fichero::borrar_enlace($enlace['id']);

            \core\HTTP_Respuesta::set_header_line("location", \core\URL::generar(\core\Distribuidor::get_controlador_instanciado()));
            \core\HTTP_Respuesta::enviar();
            //Otra forma: pero al recargar la página intenta borrar denuevo, ya que carga: ?menu=$enlaces&submenu=form_borrar_validar
            //\core\Distribuidor::cargar_controlador("$enlaces", "index");
        }
    }
}
