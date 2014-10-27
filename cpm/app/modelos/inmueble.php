<?php
namespace modelos;

class inmueble {
    
    private static $tabla = 'bienes';
    private static $tabla_tv = 'tipos_via';
    private static $tabla_tb = 'tipos_bien';
    private static $tabla_dv = 'detalles_vivienda';
    private static $tabla_dg = 'detalles_garaje';

    private static $file_name = 'enlaces.txt';   //fichero donde están los datos
    private static $enlaces = array();
    private static $campo1 = 'titulo';
    private static $campo2 = 'url';
    private static $campo3 = 'descripcion';
 
    /**
     * Para la vista bienes/inmueble
     * Función que devuelve los parametros que le pasemos adaptados al formato que desemaos en la vista
     * 
     * @param array $fila
     * @return array $v
     */
    public static function formatoVistaInmuebleRef( array $fila ) {
        
        //var_dump($fila);
        //Tipo de operación y precios
        $v['tipo_operacion'] = '';
        $v['precio_alquiler'] = '';
        
        //No hacemos conversión, pues ya vienen convertidos del controlador
        //$fila['precio_venta'] = \core\Conversiones::decimal_punto_a_coma_y_miles($fila['precio_venta']);
        //$fila['precio_alquiler'] = \core\Conversiones::decimal_punto_a_coma_y_miles($fila['precio_alquiler']);
        
        if($fila['precio_venta']<>0){
            $v['precio_venta'] = $fila['precio_venta'].' €';
            $v['tipo_operacion'] .= 'Venta';
            if ($fila['precio_alquiler']<>0) {
                $v['precio_alquiler'] = $fila['precio_alquiler'].' €/mes';
                $v['tipo_operacion'] .= ' / Alquiler';
            }
        }elseif($fila['precio_alquiler']<>0) {
            $v['precio_venta'] = '';
            $v['precio_alquiler'] = $fila['precio_alquiler'].' €/mes';
            $v['tipo_operacion'] .= 'Alquiler';
        }else{
            $v['precio_venta'] = '';
            $v['precio_alquiler'] = 'Consultar precio';
        }
        
        //Tipo de bien
        if($fila['tipo'] == 'g'){
            $v['tipo'] = 'Garaje';
        }elseif($fila['tipo'] == 'l'){
            $v['tipo'] = 'Local';
        }elseif($fila['tipo'] == 'n'){
            $v['tipo'] = 'Nave';
        }elseif($fila['tipo'] == 't'){
            $v['tipo'] = 'Trastero';
        }elseif($fila['tipo'] == 'p'){
            $v['tipo'] = 'Parcela';
        }else{
            $v['tipo'] = 'Vivienda';
        }
        
        //Direccion del inmueble
        $zona = ucwords( is_null($fila['localidad']) ? $fila['provincia'] : $fila['localidad'] );
        $tipo_via_id = isset($fila['tipo_via_id']) && !is_null($fila['tipo_via_id']) ? $fila['tipo_via_id'] : 0;
        $clausulas['where'] = " id = $tipo_via_id ";
        $tipo_via = \modelos\Modelo_SQL::table('tipos_via')->select($clausulas);
        $tipo_via = ($tipo_via[0]['tipo'] == 'Otro' )? '' : $tipo_via[0]['tipo'].' ';
        $num_portal = $fila['num_portal'] != 0 ? $fila['num_portal'] : 's/n' ;
        $v['direccion'] = $tipo_via.$fila['nombre_via'].', '.$num_portal.' ('.$zona.')';  
        
        //Si en la BD las coordenadas son geodésicas
        $v['lat'] = isset($fila['coord_lat']) && !is_null($fila['coord_lat']) ? $fila['coord_lat'] : 0;
        $v['lon'] = isset($fila['coord_long']) && !is_null($fila['coord_long']) ? $fila['coord_long'] : 0;
        
        //Si en la BD las coordenadas son UTM
        $v['utm_x'] = isset($fila['coord_utm_x']) && !is_null($fila['coord_utm_x']) ? $fila['coord_utm_x'] : 0;
        $v['utm_y'] = isset($fila['coord_utm_y']) && !is_null($fila['coord_utm_y']) ? $fila['coord_utm_y'] : 0;
        $v['huso'] = isset($fila['huso']) && !is_null($fila['huso']) ? $fila['huso'] : 30;
        $v['hemis'] = isset($fila['hemis']) && !is_null($fila['hemis']) ? $fila['hemis'] : 'n';
        
        return $v;
    }
    
    public static function insertarSaltosDeCarro(array $fila) {
        $fila['resenha'] = str_replace(". ", ".</p><p>", $fila['resenha']);
        
        return $fila;   
    }
    
    public static $validaciones_anhadir_detalles = array(
        "v" => array(
            "id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:id/detalles_vivienda/id"
            ,"bien_id" => "errores_requerido && errores_numero_entero_positivo && errores_unicidad_modificar:id,bien_id/detalles_vivienda/id,bien_id"
            , "tipo_bien_id" => "errores_numero_entero_positivo && errores_referencia:tipo_bien_id/tipos_bien/id"
            , "anho_const" => "errores_numero_entero_positivo"

            , "num_hab" =>"errores_numero_entero_positivo"
            , "num_banhos" => "errores_numero_entero_positivo"
            , "descripcion" => "errores_texto"

            , "file_fotos" => "errores_texto"
        ),
        "g" => array(
            "id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:id/detalles_garaje/id"
            ,"bien_id" => "errores_requerido && errores_numero_entero_positivo && errores_unicidad_modificar:id,bien_id/detalles_garaje/id,bien_id"
            , "tipo_bien_id" => "errores_numero_entero_positivo && errores_referencia:tipo_bien_id/tipos_bien/id"
            , "anho_const" => "errores_numero_entero_positivo"

            , "m_largo" => "errores_decimal"
            , "m_ancho" => "errores_decimal"

            , "file_fotos" => "errores_texto"
        )
    );
        
        /**
         * Para obtener la ruta del fichero
         * @return type
         */
        private static function getRutaFichero(){
            return PATH_APP."modelos/inmueble".self::$file_name;    //ruta del fichero
        }
 
        /**
         * Devuelve una enlace concreto o todos
         * @param type $id
         * @return type
         */
	public static function get_enlaces($id = null) {
            self::leer_fichero();
            if($id!=null){
                return self::$enlaces[$id];
            }else{
                return self::$enlaces;
            }
	}
        
        public static function anexar_enlace(array $enlace){
            $file_path = self::getRutaFichero();
            $file = fopen($file_path,"a+");
            $linea = implode(";",$enlace)."\n";
            fwrite($file,$linea);
            fflush($file);
            fclose($file);
        }
        
        /**
	 * Lee las líneas del fichero, descarta la primera línea, y cada una
	 * de ellas las guarda como un array dentro del array self::$enlaces.
	 */
        private static function leer_fichero(){
            $file_path = self::getRutaFichero();    //ruta del fichero
            self::$enlaces = array();    //// Vaciamos el array por si tuviera datos de una lectura anterior.
            $lines = file($file_path, FILE_IGNORE_NEW_LINES); // Lee las líneas y genera un array de índice entero con una cadena de caracteres en cada entrada del array. FILE_IGNORE_NEW_LINES es una constante entera de valor 2 que hace que no se incluya en la líneas los caracteres de fin de línea y nueva línea.
            foreach($lines as $numero => $line){  //$numero++ en cada vuelta y lo guarda en $line
                $enlace = explode(";", $line);
                if($numero!=0){
                    self::$enlaces[$numero][self::$campo1] = $enlace[0];
                    self::$enlaces[$numero][self::$campo2] = $enlace[1];
                    self::$enlaces[$numero][self::$campo3] = $enlace[2];   //Se lee tb el "intro" (\n) del final de linea
                }
            }
        }
        
        /**
         * Reescribe el fichero
         * @param type $numero
         */
        public static function escribir_fichero($numero=null){
            $file_path = self::getRutaFichero();
            $file = fopen ($file_path,"w+");    //Abrimos el fichero para escritura. Se borra su contenido anterior.
            fwrite($file,  self::$campo1.";".  self::$campo2.";".  self::$campo3."\n"); //Escribimos la primera línea
            foreach (self::$enlaces as $enlace) {
                $line = implode(';',$enlace)."\n";   //Debemos añadir el final de linea en cada enlace
                fwrite($file, $line);   //Cuidado el "intro" (\n) ya lo llevan al leer las lineas
            }
            fclose($file);
            
        }
               
        public static function modificar_enlace(array $enlace){
            self::leer_fichero();
            
            $numero = $enlace['id'];
            self::$enlaces[$numero][self::$campo1] = $enlace[self::$campo1];
            self::$enlaces[$numero][self::$campo2] = $enlace[self::$campo2];
            self::$enlaces[$numero][self::$campo3] = $enlace[self::$campo3];
            
            self::escribir_fichero();
        }
        
        public static function borrar_enlace($id){
            self::leer_fichero();
            
            unset(self::$enlaces[$id]);
            self::escribir_fichero();
            
            //$numero = $enlace['id'];
            //self::escribir_fichero($numero);

        }

}

