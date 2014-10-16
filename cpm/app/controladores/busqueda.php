<?php
namespace controladores;

class busqueda extends \core\Controlador{
    
    private static $tabla = 'bienes';
    
    public function index(array $datos = array() ){
        $datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
        $http_body = \core\Vista_Plantilla::generar('plantilla_principal',$datos);
        \core\HTTP_Respuesta::enviar($http_body);
    }
    
    public function inmuebles(array $datos = array()){
        
        //Realizamos la busqueda
        $post = \core\HTTP_Requerimiento::post();
        //var_dump($post);
        
        if( isset($post['datos'])){
            $datos = unserialize($post['datos']);
            //var_dump($datos);
            if( isset($post['ordenar_por']) ){
                if( $post['ordenar_por'] === 'id desc' ){
                    arsort($datos['bienes']);   //No usamos krsort() pues por POST nos viene la ordenacion aleatoria en que se mostraron inicialmente
                }elseif( $post['ordenar_por'] === 'precio_venta' ){
                    self::ordenarArray($datos['bienes'], 'precio_venta', true);
                }elseif( $post['ordenar_por'] === 'precio_venta desc' ){
                    self::ordenarArray($datos['bienes'], 'precio_venta', false);
                }
            }
        }else{
            $datos['bienes'] = $this->buscarInmuebles($post);
            //$datos['bienes'] = self::buscarInmuebles($post);
        }

        $datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos);
        $http_body = \core\Vista_Plantilla::generar('DEFAULT',$datos);
        \core\HTTP_Respuesta::enviar($http_body);
        
    }
    
    private static function buscarInmuebles(array $post = array()){
        //var_dump($post);
        //Clausulas para la busqueda:
        if( isset($post['referencia']) && $post['referencia'] != '' ){
            $clausulas['where'] = " referencia like '%{$post['referencia']}%'";
        }else{
            $clausulas['where'] = " 1 ";    //Siempre es true
            if( isset( $post['tipo_inmueble'] ) && $post['tipo_inmueble'] != '' ){
                $clausulas['where'] .= " and tipo like '{$post['tipo_inmueble']}'";
            }
            if( isset( $post['buscar_nombre'] ) ){
                $clausulas['where'] .= " and ( localidad like '%{$post['buscar_nombre']}%' or provincia like '%{$post['buscar_nombre']}%' or cp = '{$post['buscar_nombre']}' )";
            }        
            
            //Si precio_venta es igual a cero quiere decir que esta para alquilar y viceversa. Si amboa son cero saldrá en ambas consultas.
            if( isset($post['tipo_transacion']) && $post['tipo_transacion'] != '' ){
                if( $post['tipo_transacion'] === 'venta'){
                    $clausulas['where'] .= " and (precio_alquiler = 0 or precio_venta > 0)";
                    if( isset($post['precio_max']) && $post['precio_max'] != '' ){
                       $clausulas['where'] .= " and precio_venta <= '{$post['precio_max']}'";
                    }
                }elseif ( $post['tipo_transacion'] === 'alquiler'){
                     $clausulas['where'] .= " and (precio_venta = 0 or precio_alquiler > 0)";
                     if( isset($post['precio_max']) && $post['precio_max'] != '' ){
                         $clausulas['where'] .= " and precio_alquiler <= '{$post['precio_max']}'";
                     }
                 }
                 
            }elseif( isset($post['precio_max']) && $post['precio_max'] != '' ){
                $clausulas['where'] .= " and (  precio_venta <= '{$post['precio_max']}' ";
                $clausulas['where'] .= " or ( precio_alquiler <= '{$post['precio_max']}' and precio_alquiler <> 0 ) ) ";
                
            }
            $clausulas['order_by']= ' rand()';
        }
        
        
        $datos["bienes"] = \modelos\Modelo_SQL::table(self::$tabla)->select($clausulas); // Recupera todas las filas ordenadas
        //$datos["bienes"] = \modelos\Modelo_SQL::execute($sql);
        //var_dump($datos);
        
        //¡¡OJO!! no hacemos la conversión y habrá que hacerla luego en la vista mediante el modelo
        
        return $datos['bienes'];
    }
    
    
    /**
    * Mediante este método, y utilizando el método de la burbuja, ordenamos el array de bienes
    *  respecto al campo enviado de forma ascendente o descendente en función del tercer parámetro.
    * @param $bienes array
    * @param $campo tipo String
    * @param $asc boolean Si es true ordenaremos de forma ascendente, descendentemente en caso de false
    */
    private static function ordenarArray(array &$bienes, $campo, $asc = true ){
       $aux = array();
       $n = count($bienes);
       //var_dump($bienes);
       
        if($asc){
            for($k=0; $k<$n-1; $k++){
                for( $i=0; $i< $n-1-$k; $i++){
                    if( $bienes[$i][$campo] > $bienes[$i+1][$campo] ){
                        $aux = $bienes[$i];
                        $bienes[$i] = $bienes[$i+1];
                        $bienes[$i+1] = $aux;
                    }
                }
            }
       }else{
            for($k=0; $k<$n-1; $k++){
                for( $i=0; $i< $n-1-$k; $i++){
                    if( $bienes[$i][$campo] < $bienes[$i+1][$campo] ){
                        $aux = $bienes[$i];
                        $bienes[$i] = $bienes[$i+1];
                        $bienes[$i+1] = $aux;
                    }
                }
            }
       }
   }

}

?>
