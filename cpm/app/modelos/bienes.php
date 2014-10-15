<?php

namespace modelos;

class bienes extends \core\sgbd\bd {
    
    private static $tabla = 'bienes';
    private static $tabla2 = 'tipos_via';
    private static $tabla3 = 'detalles_vivienda';
    private static $tabla4 = 'detalles_garaje';
    
    /* Rescritura de propiedades de validación */
    public static $validaciones_insert = array(
        "tipo" => "errores_texto"
        , "tipo_via_id" => "errores_numero_entero_positivo && errores_referencia:tipo_via_id/tipos_via/id"
        , "nombre_via" => "errores_requerido && errores_texto && errores_unicidad_insertar:id,nombre_via/bienes/id,nombre_via"
        //, "referencia" =>""
        , "num_portal" =>"errores_requerido && errores_numero_entero_positivo"
        , "portal_bloque" => "errores_texto"
        , "planta" => "errores_numero_entero"
        , "puerta" => "errores_texto"
        , "cp" => "errores_numero_entero_positivo"                 
        , "localidad" => "errores_texto"
        , "provincia" => "errores_texto"
        , "pais" => "errores_texto"
        , "sup_const" => "errores_decimal"
        , "sup_util" => "errores_decimal"
        , "precio_venta" => "errores_precio_entero"
        , "precio_alquiler" => "errores_precio_entero"
        , "resenha" => "errores_texto"
        /*
        , "coord_lat" => "errores_decimal"
        , "coord_long" => "errores_decimal"
         */
        , "coord_utm_x" => "errores_numero_con_coma_decimal"
        , "coord_utm_y" => "errores_numero_con_coma_decimal"
        , "huso" => "errores_numero_entero_positivo"
        , "hemis" => "errores_texto"
    );


    public static $validaciones_update = array(
        "id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:id/bienes/id"
        , "tipo" => "errores_texto"
        , "tipo_via_id" => "errores_numero_entero_positivo && errores_referencia:tipo_via_id/tipos_via/id"
        , "nombre_via" => "errores_requerido && errores_texto && errores_unicidad_modificar:id,nombre_via/bienes/id,nombre_via"
        //, "referencia" =>""
        , "num_portal" =>"errores_numero_entero_positivo"
        , "portal_bloque" => "errores_texto"
        , "planta" => "errores_numero_entero"
        , "puerta" => "errores_texto"
        , "cp" => "errores_numero_entero_positivo"                 
        , "localidad" => "errores_texto"
        , "provincia" => "errores_texto"
        , "pais" => "errores_texto"
        , "sup_const" => "errores_decimal"
        , "sup_util" => "errores_decimal"
        , "precio_venta" => "errores_precio_entero"
        , "precio_alquiler" => "errores_precio_entero"
        , "resenha" => "errores_texto"
        /*
        , "coord_lat" => "errores_decimal"
        , "coord_long" => "errores_decimal"
         */
        , "coord_utm_x" => "errores_numero_con_coma_decimal"
        , "coord_utm_y" => "errores_numero_con_coma_decimal"
        , "huso" => "errores_numero_entero_positivo"
        , "hemis" => "errores_texto"
    );


    public static $validaciones_delete = array(
        "id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:id/bienes/id"
    );
    
    /**
     * Para la vista busqueda/inmuebles
     * Función que devuelve los parametros que le pasemos adaptados al formato que desemaos en la vista
     * 
     * @param array $fila
     * @return array $v
     */
    public static function formatoVistaBusquedaInmuebles( array &$fila ) {
        
        //Tipo de operación y precios
        $v['tipo_operacion'] = '';
        $v['precio_alquiler'] = '';
        $fila['precio_venta'] = \core\Conversiones::decimal_punto_a_coma_y_miles($fila['precio_venta']);
        $fila['precio_alquiler'] = \core\Conversiones::decimal_punto_a_coma_y_miles($fila['precio_alquiler']);
        
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
        $v['direccion'] = $tipo_via.$fila['nombre_via'].' ('.$zona.')';  
        
        return $v;
    }
    
    /**
     * Para la vista bienes/inmueble
     * Función que devuelve los parametros que le pasemos adaptados al formato que desemaos en la vista
     * 
     * @param array $fila
     * @return array $v
     */
    public static function formatoVistaBienesInmueble( array &$fila ) {
        
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
        $v['lat'] = isset($fila['coord_lat']) && !is_null($fila['coord_lat']) ? $fila['coord_lat'] : 40.64734769451827;
        $v['lon'] = isset($fila['coord_long']) && !is_null($fila['coord_long']) ? $fila['coord_long'] : -4.67889608147584;
        
        //Si en la BD las coordenadas son UTM
        $v['utm_x'] = isset($fila['coord_utm_x']) && !is_null($fila['coord_utm_x']) ? $fila['coord_utm_x'] : 443732.4071036273;
        $v['utm_y'] = isset($fila['coord_utm_y']) && !is_null($fila['coord_utm_y']) ? $fila['coord_utm_y'] : 4471166.909313631;
        $v['huso'] = isset($fila['huso']) && !is_null($fila['huso']) ? $fila['huso'] : 30;
        $v['hemis'] = isset($fila['hemis']) && !is_null($fila['hemis']) ? $fila['hemis'] : 'n';
        
        return $v;
    }
    
    public static function insertarSaltosDeCarro(array $fila) {
        $fila['resenha'] = str_replace(". ", ".</p><p>", $fila['resenha']);
        
        return $fila;   
    }


}
