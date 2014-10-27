<?php
namespace controladores;

class inmueble extends \core\Controlador {
    
    private static $tabla = 'bienes';
    private static $tabla_tv = 'tipos_via';
    private static $tabla_tb = 'tipos_bien';
    private static $tabla_dv = 'detalles_vivienda';
    private static $tabla_dg = 'detalles_garaje';
    
    private static $controlador = 'inmueble';
    
    public function index(array $datos = array() ){
            //header("Location: ".\core\URL::generar("self::$controlador/index"));
            \core\HTTP_Respuesta::set_header_line("location", \core\URL::generar("inicio/index"));
            \core\HTTP_Respuesta::enviar();
    }    
    
    /**
     * Función que muestra un único inmueble con todos sus detalles pasando una referencia.
     * @param array $datos
     * @return type
     */
    public function ref(array $datos = array()) {
        
        if(isset($_REQUEST['p3'])){ //viene la referencia
            $clausulas['where'] = " referencia like '%{$_GET['p3']}%' ";
        }elseif(isset($_REQUEST['referencia'])){ //viene la referencia tras hacer un comentario
            $clausulas['where'] = " referencia like '%{$_POST['referencia']}%' ";
        }else{
            $clausulas['where'] = "";   //Por si alguien maneja la URL sin introducir referencia, mostrará el primero
        }
        
        if ( ! $filas = \modelos\Datos_SQL::select( $clausulas, self::$tabla)) {
            $datos['mensaje'] = 'El inmueble no existe';
            \core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
            return;
        }else{   
            $datos['bien'] = $filas[0];
            
            //Usando articulo_id como FK buscamos los detalles del inmueble
            $bien_id = $filas[0]['id'];
            $clausulas['where'] = " bien_id like '%$bien_id%' ";
            
            //Para cuando existan detalles de algunos bienes
            $tieneTipo = false;
            if( $filas[0]['tipo'] == 'v'){
                if( $filas = \modelos\Modelo_SQL::table(self::$tabla_dv)->select($clausulas))
                    $datos["detalles"] = $filas[0];
                $tieneTipo = true;
            }elseif( $filas[0]['tipo'] == 'g'){
                if( $filas = \modelos\Modelo_SQL::table(self::$tabla_dg)->select($clausulas) )
                    $datos["detalles"] = $filas[0];
                $tieneTipo = true;
            }
            if($tieneTipo && isset($datos['detalles']['tipo_bien_id'])){
                $tabla = \core\Modelo_SQL::get_prefix_tabla(self::$tabla_tb);
                $sql = 'select tipo from '.$tabla.' where id = '.$datos['detalles']['tipo_bien_id'];
                $datos['detalles']['tipo_bien'] = \modelos\Modelo_SQL::execute($sql);
            }
            
        }
        
        //var_dump($datos);
        
        $datos['precio_venta'] = $datos['bien']['precio_venta'];    //Me lo guardo antes de la conversión para poder hacer calculos
        //Mostramos los datos a modificar en formato europeo. Convertimos el formato de MySQL a europeo para su visualización
        self::convertir_formato_mysql_a_ususario($datos['bien'], false);
        if ( isset($datos['detalles']) ){
            self::convertir_formato_mysql_a_ususario($datos['detalles']);
        }

        $datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos);
        $http_body = \core\Vista_Plantilla::generar('DEFAULT', $datos);
        \core\HTTP_Respuesta::enviar($http_body);
        
    }
    
    /**
     * Función que mostrara un formulario para añadir detalles del inmueble seleccionado.
     * En función del tipo de inmueble que reciba mostrará un formulario diferente.
     * Si es la primera vez que se añaden detalles hará un insert primero para que exista la fila
     * @param array $datos
     */
    public static function form_anhadir_detalles(array $datos=array()){
        
        $get = \core\HTTP_Requerimiento::get();
        $datos["form_name"] = __FUNCTION__.'_'.$get['p3'];
        
        \core\HTTP_Requerimiento::request_come_by_post();   //Si vienen por POST sigue adelante
        
        $datos['p3'] = $get['p3']; //Para poder usar en la vista.
        $datos['values']['bien_id'] = \core\HTTP_Requerimiento::post('id'); //Siempre viaja string
        //var_dump($get);
        //var_dump($_SESSION);
        
        //$datos['values']['tipo_bien'] = $get['p3']; //Para poder usar en la vista.
        //$datos['values']['bien_id'] = $_POST['id']; //Para poder usar en la vista.
        
        if( $get['p3'] == 'v' || $get['p3'] == 'g' ){       
            $tabla = 'tabla_d'.$get['p3'];    //Voy a usar una variable de variable
            
            //Si es la primera vez que añadimos detalles, se inserta una nueva fila en la tabla
            $clausulas['where'] = ' bien_id = '.$datos['values']['bien_id'] ;
            if ( ! $filas = \modelos\Datos_SQL::select( $clausulas , self::$$tabla)){
                if ( ! $validacion = \modelos\Datos_SQL::table( self::$$tabla )->insert($datos["values"])){ // Devuelve true o false
                    $datos['mensaje'] = 'Ha surgido un problema inesperado. No se ha pueden añadir detalles en la bd.';
                    \core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);       
                    return;
                }
            }
            
            //Ahora extraemos la fila insertada para mostrarla en el formulario
            if ( ! isset($datos["errores"]) ) { // Si no es un reenvío desde una validación fallida
                $validaciones = array(
                    "id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:bien_id/".self::$$tabla."/bien_id"
                );
                
                //¡¡OJO!! Añado "false" o "is_null($datos['values']['bien_id']) && ! is_string($datos['values']['bien_id']) &&" porque no funciona, pierde el valor de $datos['values']['bien_id'] y no sé por que
                //La razón es porque el formulario oculto que se envia con on_boton_onclik() tiene un input llamado id en vez de bien_id y la fucnión errores_validacion_request() crea dos variables quedandse vacio el bien_id
                //Por ello me guardo la variable antes en otra porque al comprobar la validacion se pierde.
                $bien_id = $datos['values']['bien_id'];
                if (   ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
                    $datos['mensaje'] = 'Datos erróneos para identificar el elemento a modificar';
                    \core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
                    return;
                }else{
                    $clausulas['where'] = " bien_id = $bien_id ";
                    if ( ! $filas = \modelos\Datos_SQL::select( $clausulas, self::$$tabla)) {
                        $datos['mensaje'] = 'Error al recuperar la fila de la base de datos';
                        \core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
                        return;
                    }else{
                        //En el select volvemos a recuperar $datos['values']['bien_id']
                        $datos['values'] = $filas[0];
                    }
                }
                
            }
            
            $datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos);
            $http_body = \core\Vista_Plantilla::generar('DEFAULT', $datos);
            \core\HTTP_Respuesta::enviar($http_body);
            
        }else{
            //Para que nos lleve a la misma ubicación donde estabamos:
            $url_anterior = $_SESSION['url']['anterior'];
            
            $_SESSION["alerta"] = 'Lo siento, a este tipo de inmuebles no se le pueden añadir detalles, solo a viviendas y garajes.';
            \core\HTTP_Respuesta::set_header_line("location", $url_anterior);
            \core\HTTP_Respuesta::enviar();
            
            //Mediante mensaje
            //$datos['mensaje'] = 'Lo siento, a este tipo de inmuebles no se le pueden añadir detalles';
            //\core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
        }
        

    }
    
    /**
     * Valida los datos insertados por el usuario. Si estos son correctos mostrará la lista de bienes con 
     * la nueva inserción, sino mostrará los errores por los que nos se admitió los datos introducidos.
     * @param array $datos
     */
    public function validar_form_anhadir_detalles(array $datos=array()) {

        $get = \core\HTTP_Requerimiento::get();
        $tabla = 'tabla_d'.$get['p3'];    //Voy a usar una variable de variable
        
        $validaciones = \modelos\bienes::$validaciones_anhadir_detalles;
        
        if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones[$get['p3']], $datos)){  //validaciones en PHP
            $datos["errores"]["errores_validacion"]="Corrija los errores, por favor.";
        }else{
            //$validacion = self::comprobar_files($datos);
            if ($validacion) {
                //Convertimos a formato MySQL
                self::convertir_detalles_a_formato_mysql($datos['values'], $get['p3']);
                
                //Ponemos el nombre donde guardaremos las fotos de los detalles
                //Creamos la carpeta por si aún no estuviera
                $datos['values']['file_fotos'] = \modelos\ficheros::getNombreCarpeta($datos['values']['bien_id']);
                $ficherosBienes_path = PATH_APPLICATION."recursos".DS."ficheros".DS."bienes";
                \modelos\ficheros::crearCarpeta($ficherosBienes_path, $datos['values']['file_fotos']);
                
                //if ( ! $validacion = \modelos\Modelo_SQL::insert($datos["values"], self::$tabla)) // Devuelve true o false
                if ( ! $validacion = \modelos\Datos_SQL::table(self::$$tabla)->update($datos["values"])) // Devuelve true o false
                    $datos["errores"]["errores_validacion"]="No se han podido grabar los datos en la bd.";
            }
        }
        if ( ! $validacion){ //Devolvemos el formulario para que lo intente corregir de nuevo
            \core\Distribuidor::cargar_controlador(self::$controlador, 'form_anahdir_detalles', $datos);
        }else{
            // Se ha grabado la modificación. Devolvemos el control al la situacion anterior a la petición del form_modificar
            //$datos = array("alerta" => "Se han grabado correctamente los detalles");
            // Definir el controlador que responderá después de la inserción
            //\core\Distribuidor::cargar_controlador(self::$tabla, 'index', $datos);
            $_SESSION["alerta"] = "Se han grabado correctamente los detalles";

            \core\Distribuidor::cargar_controlador('bienes', 'busqueda');


        }
    }
    

    /**
     * Comprueba que los ficheros que el usuario intenta subir a la aplicación cumple con los requerimietnos exigidos.
     * @param array $datos
     * @return boolean
     */
    private static function comprobar_files(array &$datos){
        $validacion = true;
        if ($_FILES["foto"]["size"]) {
                if ($_FILES["foto"]["error"] > 0 ) {
                    $datos["errores"]["foto"] = $_FILES["foto"]["error"];
                }
                elseif ( ! preg_match("/image/", $_FILES["foto"]["type"])) {
                    $datos["errores"]["foto"] = "El fichero no es una imagen.";
                }
                elseif ($_FILES["foto"]["size"] > 1024*1024*1) {
                    $datos["errores"]["foto"] = "El tamaño de la foto debe ser menor que 1MB.";
                }
                if (isset($datos["errores"]["foto"])) {
                    $validacion = false;
                }
        }
        return $validacion;
    }
    
        
    /**
     * Función que a través del id de un artículo guarda en la BD la referencia de los archivos multimedia del mismo que serán guardados en los recusros de la aplicación.  
     * @param array $datos
     */
    private static function mover_files(array $datos){
        //var_dump($datos);
        $id = $datos["values"]['id'];
        if ($_FILES["foto"]["size"]) {
            if ($datos["values"]["foto"] = self::mover_foto($id)) {
                $validacion = \modelos\Modelo_SQL::tabla(self::$tabla)->update($datos["values"]);
            }
        }
        //Creamos una carpeta para guardar más fotos en ella al añadir detalles
        $nombre = \modelos\ficheros::getNombreCarpeta($id);
        $ficherosBienes_path = PATH_APPLICATION."recursos".DS."ficheros".DS."bienes";
        \modelos\ficheros::crearCarpeta($ficherosBienes_path, $nombre);
    }

    /**
     * Guarda un archivo jpg en nuestros recursos en función del id del artículo
     * Además crea una carpeta para añadir ficheros
     * @param $id
     * @return nombre del archivo o false
     */
    private static function mover_foto($id, $ref = null) {

        // Ahora hay que añadir la foto
        $extension = substr($_FILES["foto"]["type"], stripos($_FILES["foto"]["type"], "/")+1);
        $nombre = \modelos\ficheros::getNombreCarpeta($id);
        $foto_path = PATH_APPLICATION."recursos".DS."imagenes".DS."bienes".DS.$nombre.".".$extension;
//					echo __METHOD__;echo $_FILES["foto"]["tmp_name"];  echo $foto_path; exit;
        // Si existe el fichero lo borramos
        if (is_file($foto_path)) {
            unlink($foto_path);
        }
                    
        $validacion = move_uploaded_file($_FILES["foto"]["tmp_name"], $foto_path);
        return ($validacion ? $nombre.".".$extension : false);

    }
    
     /**
     * Guarda un archivo pdf en nuestros recursos en función del id del artículo
     * @param  $id
     * @param  $articulo_nombre = null
     * @return nombre del archivo o false
     */
    private static function mover_manual($id, $articulo_nombre = null) {

        // Ahora hay que añadir la manual
        $extension = substr($_FILES["manual"]["type"], stripos($_FILES["manual"]["type"], "/")+1);
        if($articulo_nombre){
            $nombre = str_replace(" ", "-", $articulo_nombre);
        }else{
            $nombre = (string)$id;
            $nombre = "art".str_repeat("0", 5 - strlen($nombre)).$nombre;
        }
        $manual_path = PATH_APPLICATION."recursos".DS."ficheros".DS."manuales".DS.$nombre.".".$extension;
//					echo __METHOD__;echo $_FILES["manual"]["tmp_name"];  echo $manual_path; exit;
        // Si existe el fichero lo borramos
        if (is_file($manual_path)) {
            unlink($manual_path);
        }
        $validacion = move_uploaded_file($_FILES["manual"]["tmp_name"], $manual_path);

        return ($validacion ? $nombre.".".$extension : false);

    }

    /**
     * Elimina los ficheros guardados en nuestra aplicación.
     * @author Jorge Rodríguez <jergo23@gmail.com>
     * @param array $datos
     */
    private static function borrar_files(array $datos){
        $id = $datos["values"]['id'];
        
        $sql = 'select * from '.\core\Modelo_SQL::get_prefix_tabla(self::$tabla).' where id = '.$id;
        $fila = \core\Modelo_SQL::execute($sql);
        
        $foto = $fila[0]['foto'];
        //$plano = $fila[0]['plano'];
        
        self::borrar_foto($foto);
        //self::borrar_manual($plano);
        
        //Borramos la carpeta creada al crear el inmueble para meter las fotos de los detalles
        $ficherosBienes_path = PATH_APPLICATION."recursos".DS."ficheros".DS."bienes";
        $nombre_carpeta = \modelos\ficheros::getNombreCarpeta($id); //$nombre_carpeta = substr($foto, 0, stripos($foto, '.' ) ); No funciona cuanod no existe la foto 
        //var_dump($nombre_carpeta);
        \modelos\ficheros::borrarCarpeta($ficherosBienes_path, $nombre_carpeta);
        
    }

    /**
     * Elimina una foto de la ruta recursos/imagenes/bienes
     * @param string $foto
     * @return null
     */
    private static function borrar_foto($foto) {
                
        $foto_path = PATH_APPLICATION."recursos".DS."imagenes".DS."bienes".DS.$foto;

        // Si existe el fichero lo borramos
        if (is_file($foto_path)) {
            return unlink($foto_path);
        }
        else {
            return null;
        }

    }

    /**
     * Fución que realiza las conversiones de los campos usados en está aplicación al formato utilizado por MySQL.
     * Convertimos a formato MySQL
     * @author Jorge Rodriguez Sanz
     * @param array $param Se corresponderá por regla general con datos['values'] y lo pasamos por referencia, para que modifique el valor
     */
    private static function convertir_a_formato_mysql(array &$param) {  //$param = datos['values'] y lo pasamos por referencia, para que modifique el valor        
        $param['precio_venta'] = \core\Conversiones::decimal_coma_a_punto($param['precio_venta']);
        $param['precio_alquiler'] = \core\Conversiones::decimal_coma_a_punto($param['precio_alquiler']);
        $param['coord_lat'] = \core\Conversiones::decimal_puntoOcoma_a_punto($param['coord_lat']);
        $param['coord_long'] = \core\Conversiones::decimal_puntoOcoma_a_punto($param['coord_long']);
        //$param['coord_utm_x'] = \core\Conversiones::decimal_coma_a_punto($param['coord_utm_x']);
        //$param['coord_utm_y'] = \core\Conversiones::decimal_coma_a_punto($param['coord_utm_y']);
        $param['sup_const'] = \core\Conversiones::decimal_puntoOcoma_a_punto($param['sup_const']);
        $param['sup_util'] = \core\Conversiones::decimal_puntoOcoma_a_punto($param['sup_util']);
    }    
    
     /**
     * Fución que realiza las conversiones de los campos que muestran las tablas del formato utilizado por MySQL al formato europeo.
     * Convertimos a formato MySQL
     * @author Jorge Rodriguez Sanz <jergo23@gmail.com>
     * @param array $param Se corresponderá por regla general con datos['values'] y lo pasamos por referencia, para que modificque el valor
     */
    private static function convertir_formato_mysql_a_ususario(array &$param, $coords = true) {  //$param = datos['values'] o $param = datos['filas'] si enviamos toda la tabla, y lo pasamos por referencia, para que modifique el valor
        
        //var_dump($param);
        if(!isset($param['id'])){   //Si existe $param['id'], es que vienen varias filas 0,1,2...,n, es decir no viene de intentar modificar o borrar ua única fila
            foreach ($param as $key => $fila) {
                if(isset($fila['precio_venta']))
                    $param[$key]['precio_venta']=  \core\Conversiones::decimal_punto_a_coma_y_miles($fila['precio_venta']);
                if(isset($param[$key]['precio_alquiler']))
                    $param[$key]['precio_alquiler']=  \core\Conversiones::decimal_punto_a_coma_y_miles($fila['precio_alquiler']);
                if($coords){
                    if(isset($param[$key]['coord_lat']))
                        $param[$key]['coord_lat']=  \core\Conversiones::decimal_punto_a_coma($fila['coord_lat']);
                    if(isset($param[$key]['coord_long']))
                        $param[$key]['coord_long']=  \core\Conversiones::decimal_punto_a_coma($fila['coord_long']);
//                    if(isset($param[$key]['coord_utm_x']))
//                        $param[$key]['coord_utm_x']=  \core\Conversiones::poner_punto_separador_miles($fila['coord_utm_x']);
//                    if(isset($param[$key]['coord_utm_y']))
//                        $param[$key]['coord_utm_y']=  \core\Conversiones::decimal_punto_a_coma_y_miles($fila['coord_utm_y']);
                }
                if(isset($param[$key]['sup_util']))
                    $param[$key]['sup_util']=  \core\Conversiones::decimal_punto_a_coma_y_miles($fila['sup_util']);
                if(isset($param[$key]['sup_const']))
                    $param[$key]['sup_const']=  \core\Conversiones::decimal_punto_a_coma_y_miles($fila['sup_const']);
                if(isset($param[$key]['m_largo']))
                    $param[$key]['m_largo']=  \core\Conversiones::decimal_punto_a_coma_y_miles($fila['m_largo']);
                if(isset($param[$key]['m_ancho']))
                    $param[$key]['m_ancho']=  \core\Conversiones::decimal_punto_a_coma_y_miles($fila['m_ancho']);
            }
        }else{
            if(isset($param['precio_venta']))
                $param['precio_venta']=  \core\Conversiones::poner_punto_separador_miles($param['precio_venta']);
            if(isset($param['precio_alquiler']))
                $param['precio_alquiler']=  \core\Conversiones::poner_punto_separador_miles($param['precio_alquiler']);
            if($coords){
                if(isset($param['coord_lat']))
                    $param['coord_lat']=  \core\Conversiones::decimal_punto_a_coma($param['coord_lat']);
                if(isset($param['coord_long']))
                    $param['coord_long']=  \core\Conversiones::decimal_punto_a_coma($param['coord_long']);
//            if($coords){
//                if(isset($param['coord_utm_x']))
//                    var_dump ($param['coord_utm_x']);
//                    $param['coord_utm_x']=  \core\Conversiones::decimal_punto_a_coma_y_miles($param['coord_utm_x']);
//                    var_dump ($param['coord_utm_x']);
//                if(isset($param['coord_utm_y']))
//                    $param['coord_utm_y']=  \core\Conversiones::decimal_punto_a_coma_y_miles($param['coord_utm_y']);
            }
            if(isset($param['sup_util']))
                $param['sup_util']=  \core\Conversiones::decimal_punto_a_coma_y_miles($param['sup_util']);
            if(isset($param['sup_const']))
                $param['sup_const']=  \core\Conversiones::decimal_punto_a_coma_y_miles($param['sup_const']);
//            if(isset($param['m_largo']))
//                $param['m_largo']=  \core\Conversiones::decimal_punto_a_coma_y_miles($param['m_largo']);
//            if(isset($param['m_ancho']))
//                $param['m_ancho']=  \core\Conversiones::decimal_punto_a_coma_y_miles($param['m_ancho']);
        }
        
    }
    
    /**
     * Fución que realiza las conversiones de los campos usados en está aplicación al formato utilizado por MySQL.
     * Convertimos a formato MySQL
     * @author Jorge Rodriguez Sanz
     * @param array $param Se corresponderá por regla general con datos['values'] y lo pasamos por referencia, para que modifique el valor
     */
    private static function convertir_detalles_a_formato_mysql(array &$param , $tipo_bien) {  //$param = datos['values'] y lo pasamos por referencia, para que modifique el valor        
        //var_dump($param);
        if($tipo_bien == 'g'){
            $param['m_largo'] = \core\Conversiones::decimal_puntoOcoma_a_punto($param['m_largo']);
            $param['m_ancho'] = \core\Conversiones::decimal_puntoOcoma_a_punto($param['m_ancho']);
        }
    }
    
    private function carpeta($id, array &$datos = array()) {

        $validaciones = array(
            "p3" => "errores_requerido && errores_identificador"
        );
        if ( ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
            $datos["carpeta"] = $datos["values"]["p3"];
            $datos["ficheros"] = \modelos\ficheros::get_ficheros($datos["values"]["p3"]);
        }


    }
	
} // Fin de la clase