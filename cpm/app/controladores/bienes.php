<?php
namespace controladores;

class bienes extends \core\Controlador {
    
    private static $tabla = 'bienes';
    private static $tabla_tv = 'tipos_via';
    private static $tabla_dv = 'detalles_vivienda';
    private static $tabla_dg = 'detalles_garaje';
    
    private static $controlador = 'bienes';
    
    /**
     * Aún no existe esta vista
     * Presenta todas las acciones disponibles sobre los bienes: insertar, modificar, borrar...
     * @param array $datos
     */
    public function index(array $datos = array()) {

        \core\HTTP_Respuesta::set_header_line("location", \core\URL::generar());
        \core\HTTP_Respuesta::enviar();
        
    }
    
    public function inmueble(array $datos = array()) {
        
        if(isset($_GET['p3'])){ //viene la referencia
            $clausulas['where'] = " referencia like '%{$_GET['p3']}%' ";
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
            /*
            //Para cuando existan detalles de algunos bienes
            if( $filas[0]['tipo'] == 'v'){
                $filas = \modelos\Modelo_SQL::table(self::$tabla_dv)->select($clausulas);
                $datos["detalles"] = $filas[0];
            }elseif( $filas[0]['tipo'] == 'g'){
                $filas = \modelos\Modelo_SQL::table(self::$tabla_dg)->select($clausulas);
                $datos["detalles"] = $filas[0];
            }
            */
        }
        
        //var_dump($datos);

        //Mostramos los datos a modificar en formato europeo. Convertimos el formato de MySQL a europeo para su visualización
        self::convertir_formato_mysql_a_ususario($datos['bien'], false);
        if ( isset($datos['detalles']) ){
            self::convertir_formato_mysql_a_ususario($datos['detalles']);
        }

        $datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos);
        $http_body = \core\Vista_Plantilla::generar('DEFAULT', $datos);
        \core\HTTP_Respuesta::enviar($http_body);
        
        
        
        \core\HTTP_Respuesta::set_header_line("location", \core\URL::generar());
        \core\HTTP_Respuesta::enviar();
        
    }
    
    /**
     * Presenta un formulario para insertar nuevas filas a la tabla
     * @param array $datos
     */
    public function form_insertar(array $datos=array()) {

        $datos["form_name"] = __FUNCTION__;
        $datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos);
        $http_body = \core\Vista_Plantilla::generar('DEFAULT', $datos);
        \core\HTTP_Respuesta::enviar($http_body);

    }
    
    /**
     * Valida los datos insertados por el usuario. Si estos son correctos mostrará la lista de bienes con 
     * la nueva inserción, sino mostrará los errores por los que nos se admitió los datos introducidos.
     * @param array $datos
     */
    public function validar_form_insertar(array $datos=array()) {

        $validaciones = \modelos\bienes::$validaciones_insert;
        
        if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)){  //validaciones en PHP
            $datos["errores"]["errores_validacion"]="Corrija los errores, por favor.";
        }else{
            $validacion = self::comprobar_files($datos);
            if ($validacion) {
                //Convertimos a formato MySQL
                self::convertir_a_formato_mysql($datos['values']);
                //if ( ! $validacion = \modelos\Modelo_SQL::insert($datos["values"], self::$tabla)) // Devuelve true o false
                if ( ! $validacion = \modelos\Datos_SQL::table(self::$tabla)->insert($datos["values"])) // Devuelve true o false
                    $datos["errores"]["errores_validacion"]="No se han podido grabar los datos en la bd.";
                else {
                    //Como insertamos un nuevo articulo es necesario extraer el id antes de persistir en la base de datos las ficheros multimedia
                    $sql = 'select(last_insert_id())';
                    $last_insert_id = \core\Modelo_SQL::execute($sql);
                    //var_dump($last_insert_id[0]['(last_insert_id())']);
                    $last_insert_id = $last_insert_id[0]['(last_insert_id())'];
                    $tabla = \core\Modelo_SQL::get_prefix_tabla(self::$tabla);
                    $sql = 'select * from '.$tabla.' where id = '.$last_insert_id;
                    $consulta = \core\Modelo_SQL::execute($sql);
                    $datos["values"]['id'] = $consulta[0]['id'];
                    $datos["values"]['referencia'] = $consulta[0]['referencia'];
                    //var_dump($consulta);
                    //var_dump($datos);
                    //A continuacion con el id ya conseguido procedemos a grabar en la base de datos la imagen y el manual del articulo
                    self::mover_files($datos);
                }
            }
        }
        if ( ! $validacion){ //Devolvemos el formulario para que lo intente corregir de nuevo
            \core\Distribuidor::cargar_controlador(self::$controlador, 'form_insertar', $datos);
        }else{
            // Se ha grabado la modificación. Devolvemos el control al la situacion anterior a la petición del form_modificar
            //$datos = array("alerta" => "Se han grabado correctamente los detalles");
            // Definir el controlador que responderá después de la inserción
            //\core\Distribuidor::cargar_controlador(self::$tabla, 'index', $datos);
            $_SESSION["alerta"] = "Se han grabado correctamente los detalles";
            //header("Location: ".\core\URL::generar("self::$controlador/index"));
            \core\HTTP_Respuesta::set_header_line("location", \core\URL::generar(self::$controlador."/index"));
            \core\HTTP_Respuesta::enviar();
        }
        
        if ( ! $validacion) //Devolvemos el formulario para que lo intente corregir de nuevo
            $this->cargar_controlador(self::$controlador, 'form_insertar',$datos);
        else
        {
            // Se ha grabado la modificación. Devolvemos el control al la situacion anterior a la petición del form_modificar
            $_SESSION["alerta"] = "Se han grabado correctamente los datos";
            //header("Location: ".\core\URL::generar("categorias/index"));
            \core\HTTP_Respuesta::set_header_line("location", \core\URL::generar(\core\Distribuidor::get_controlador_instanciado()));
            \core\HTTP_Respuesta::enviar();
        }
    }
    
    /**
     * Recoge el artículo a modificar de la BD y presenta un formulario con los datos actuales del artículo a modificar
     * @param array $datos
     */
    public function form_modificar(array $datos = array()) {

        $datos["form_name"] = __FUNCTION__;

        self::request_come_by_post();   //Si viene por POST sigue adelante
        
        if ( ! isset($datos["errores"])) { // Si no es un reenvío desde una validación fallida
            $validaciones=array(
                "id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:id/".self::$tabla."/id"
            );
            if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
                $datos['mensaje'] = 'Datos erróneos para identificar el elemento a modificar';
                \core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
                return;
            }else{
                $clausulas['where'] = " id = {$datos['values']['id']} ";
                if ( ! $filas = \modelos\Datos_SQL::select( $clausulas, self::$tabla)) {
                    $datos['mensaje'] = 'Error al recuperar la fila de la base de datos';
                    \core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
                    return;
                }else{   
                    $datos['values'] = $filas[0];

                }
            }
        }
        
        //Mostramos los datos a modificar en formato europeo. Convertimos el formato de MySQL a europeo
//        var_dump($fila['masa_atomica']);
//        $datos['values']['masa_atomica']=  \core\Conversiones::decimal_punto_a_coma($datos['values']['masa_atomica']);
//        if(preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT'])){
//            $datos['values']['fecha_salida']=  \core\Conversiones::fecha_mysql_a_es($datos['values']['fecha_salida']);
//        }
        self::convertir_formato_mysql_a_ususario($datos['values']);
                
        $datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos);
        $http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos);
        \core\HTTP_Respuesta::enviar($http_body);
    }

    /**
     * Valida los datos insertados por el usuario al realizar una modificación. Si estos son correctos mostrará la lista de bienes con 
     * la nueva inserción, sino mostrará los errores por los que nos se admitió los datos introducidos.
     * @param array $datos
     */
    public function validar_form_modificar(array $datos=array()) {
        
        self::request_come_by_post();

        $validaciones = \modelos\bienes::$validaciones_update;

        if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)){  //validaciones en PHP
            $datos["errores"]["errores_validacion"]="Corrija los errores, por favor.";
        }else{
            $validacion = self::comprobar_files($datos);
            if ($validacion) {
                //Convertimos a formato MySQL
                self::convertir_a_formato_mysql($datos['values']);
                //if ( ! $validacion = \modelos\Modelo_SQL::insert($datos["values"], self::$tabla)) // Devuelve true o false
                if ( ! $validacion = \modelos\Datos_SQL::table(self::$tabla)->update($datos["values"])) // Devuelve true o false
                    $datos["errores"]["errores_validacion"]="No se han podido grabar los datos en la bd.";
                else {
                    self::mover_files($datos);
                }
            }
        }
/*
        if ( ! $validacion) //Devolvemos el formulario para que lo intente corregir de nuevo
                \core\Distribuidor::cargar_controlador(self::$controlador, 'form_modificar', $datos);
        else {
                $datos = array("alerta" => "Se han modificado correctamente.");
                // Definir el controlador que responderá después de la inserción
                \core\Distribuidor::cargar_controlador(self::$controlador, 'index', $datos);		
        }
 */       

        if ( ! $validacion) //Devolvemos el formulario para que lo intente corregir de nuevo
                $this->cargar_controlador(self::$controlador, 'form_modificar',$datos);
        else 		{
                $_SESSION["alerta"] = "Se han modificado correctamente los datos";
                \core\HTTP_Respuesta::set_header_line("location", \core\URL::generar(\core\Distribuidor::get_controlador_instanciado()));
                \core\HTTP_Respuesta::enviar();
        }  
        

    }



    public function form_borrar(array $datos=array()) {
        
        $datos["form_name"] = __FUNCTION__;

        self::request_come_by_post();

        $validaciones= \modelos\bienes::$validaciones_delete;
        
        if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
            $datos['mensaje'] = 'Datos erróneos para identificar el artículo a borrar';
            $datos['url_continuar'] = \core\URL::http('?menu='.self::$tabla.'');
            \core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
            return;
        }
        else {
            $clausulas['where'] = " id = {$datos['values']['id']} ";
            if ( ! $filas = \modelos\Datos_SQL::select( $clausulas, self::$tabla)) {
                $datos['mensaje'] = 'Error al recuperar la fila de la base de datos';
                \core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
                return;
            }else {
                $datos['values'] = $filas[0];
            }
        }

        //Mostramos los datos a modificar en formato europeo. Convertimos el formato de MySQL a europeo
        self::convertir_formato_mysql_a_ususario($datos['values']);

        $datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos);
        $http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos);
        \core\HTTP_Respuesta::enviar($http_body);
    }

    
    public function validar_form_borrar(array $datos=array()) {	
        
        self::request_come_by_post();

        $validaciones=array(
            "id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:id/".self::$tabla."/id"
        );
        if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
            $datos['mensaje'] = 'Datos erróneos para identificar el artículo a borrar';
            $datos['url_continuar'] = \core\URL::http('?menu='.self::$tabla.'');
            \core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
            return;
        }else{
            //Eliminamos la foto y el manual de nuestra carpeta, debemos de hacerlo lo primero    
            self::borrar_files($datos);
            
            if ( ! $validacion = \modelos\Datos_SQL::delete($datos["values"], self::$tabla)) {// Devuelve true o false
                $datos['mensaje'] = 'Error al borrar en la bd';
                $datos['url_continuar'] = \core\URL::http('?menu='.self::$tabla.'');
                \core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
                return;
            }else{               
                $datos = array("alerta" => "Se ha borrado correctamente.");
                \core\Distribuidor::cargar_controlador(self::$controlador, 'index', $datos);		
            }
        }

    }
    
    /**
     * Si el requerimiento viene por GET nos mostrará un mensaje indicando que en esa sección
     * no está permitida la entrada de datos de forma manual, y cargará el controlador mensajes.
     * Si viene por POST, no devuelve nada, simplemente deja continuar la ejecución.
     * @author Jorge Rodríguez <jergo23@gmail.com>
     */
    private static function request_come_by_post(){
        If ( \core\HTTP_Requerimiento::method()!= 'POST'){
            $datos['mensaje']="No se permiten añadir datos en la URL manualmanete para realizar la operación";
            \core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
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
    }

    /**
     * Guarda un archivo jpg en nuestros recursos en función del id del artículo
     * @param $id
     * @return nombre del archivo o false
     */
    private static function mover_foto($id, $ref = null) {

        // Ahora hay que añadir la foto
        $extension = substr($_FILES["foto"]["type"], stripos($_FILES["foto"]["type"], "/")+1);
        $nombre = (string)$id;
        $nombre = "bien".str_repeat("0", 6 - strlen($nombre)).$nombre;
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
        //$param['coord_lat'] = \core\Conversiones::decimal_puntoOcoma_a_punto($param['coord_lat']);
        //$param['coord_long'] = \core\Conversiones::decimal_puntoOcoma_a_punto($param['coord_long']);
        $param['coord_utm_x'] = \core\Conversiones::decimal_coma_a_punto($param['coord_utm_x']);
        $param['coord_utm_y'] = \core\Conversiones::decimal_coma_a_punto($param['coord_utm_y']);
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
        
        var_dump($param);
        if(!isset($param['id'])){   //Si existe $param['id'], es que vienen varias filas 0,1,2...,n, es decir no viene de intentar modificar o borrar ua única fila
            foreach ($param as $key => $fila) {
                if(isset($fila['precio_venta']))
                    $param[$key]['precio_venta']=  \core\Conversiones::decimal_punto_a_coma_y_miles($fila['precio_venta']);
                if(isset($param[$key]['precio_alquiler']))
                    $param[$key]['precio_alquiler']=  \core\Conversiones::decimal_punto_a_coma_y_miles($fila['precio_alquiler']);
                /*
                if(isset($param[$key]['coord_utm_x']))
                    $param[$key]['coord_utm_x']=  \core\Conversiones::decimal_punto_a_coma_y_miles($fila['coord_utm_x']);
                if(isset($param[$key]['coord_utm_y']))
                    $param[$key]['coord_utm_y']=  \core\Conversiones::decimal_punto_a_coma_y_miles($fila['coord_utm_y']);
                */
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
                if(isset($param['coord_utm_x']))
                    var_dump ($param['coord_utm_x']);
                    $param['coord_utm_x']=  \core\Conversiones::poner_punto_separador_miles($param['coord_utm_x']);
                    var_dump ($param['coord_utm_x']);
                if(isset($param['coord_utm_y']))
                    $param['coord_utm_y']=  \core\Conversiones::poner_punto_separador_miles($param['coord_utm_y']);
            }            
            if(isset($param['sup_util']))
                $param['sup_util']=  \core\Conversiones::decimal_punto_a_coma_y_miles($param['sup_util']);
            if(isset($param['sup_const']))
                $param['sup_const']=  \core\Conversiones::decimal_punto_a_coma_y_miles($param['sup_const']);
            if(isset($param['m_largo']))
                $param['m_largo']=  \core\Conversiones::decimal_punto_a_coma_y_miles($param['m_largo']);
            if(isset($param['m_ancho']))
                $param['m_ancho']=  \core\Conversiones::decimal_punto_a_coma_y_miles($param['m_ancho']);
        }
        
    }
	
	
} // Fin de la clase