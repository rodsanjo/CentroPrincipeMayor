<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controladores;

/**
 * Description of carpetas
 *
 * @author jorge
 */
class ajax extends \core\Controlador {
	
	public function index(array $datos = array()) {
		
		$validaciones = array(
			"p3" => "errores_requerido && errores_identificador"
		);
		if ( ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
			$datos["carpeta"] = $datos["values"]["p3"];
			$datos["ficheros"] = \modelos\ficheros::get_ficheros($datos["values"]["p3"]);
			
                        $datos["view_content"] = \core\Vista::generar(__FUNCTION__, $datos);

			$http_body_content = \core\Vista_Plantilla::generar("DEFAULT", $datos);
			\core\HTTP_Respuesta::enviar($http_body_content);

		}
		else {
			header("Location: ".\core\URL::generar());
		}	
	}
        
        public static function anhadir_foto(array $datos = array()){
            
            var_dump($_POST);
            var_dump($_FILES);
            $num_fotos = count($_FILES["foto"]['name']);
            $validacion = self::comprobar_files($datos, $num_fotos);
            
            if($validacion){
                $id = $_POST['bien_id'];
                $nombre_carpeta = \modelos\ficheros::getNombreCarpeta($id);
                
                //Varias fotos
                for( $i=0; $i < $num_fotos; $i++){
                    if ($_FILES["foto"]["size"][$i]) {
                        self::mover_foto($nombre_carpeta, $i);
                    }
                }
                /*
                //Solo viene una foto
                if ($_FILES["foto"]["size"]) {
                    self::mover_foto($nombre_carpeta);
                }
                */
                print_r('Las fotos han sido añadidas');
            }else{
                print_r($datos["errores"]["foto"]);
            }
            
        }
        
     /**
     * Guarda un archivo jpg en nuestros recursos en función del id del artículo
     * Además crea una carpeta para añadir ficheros
     * @param $id
     * @return nombre del archivo o false
     */
    private static function mover_foto($nombre_carpeta, $i = 0) {

        // Ahora hay que añadir la foto
        $nombre_archivo = $_FILES["foto"]["name"][$i];
        $foto_path = PATH_APPLICATION."recursos".DS."ficheros".DS."bienes".DS.$nombre_carpeta.DS.$nombre_archivo;
//					echo __METHOD__;echo $_FILES["foto"]["tmp_name"];  echo $foto_path; exit;
        // Si existe el fichero lo borramos aunque creo que move_uploaded_file() lo sobreescribe
        if (is_file($foto_path)) {
            unlink($foto_path);
        }
        move_uploaded_file($_FILES["foto"]["tmp_name"][$i], $foto_path);

    }
    
    /**
     * Comprueba que los ficheros que el usuario intenta subir a la aplicación cumple con los requerimietnos exigidos.
     * @param array $datos
     * @return boolean
     */
    private static function comprobar_files(array &$datos, $num_fotos){
        $validacion = true;
        //Varias fotos
        for( $i=0; $i < $num_fotos; $i++){
            if ($_FILES["foto"]["size"][$i]) {
                    if ($_FILES["foto"]["error"][$i] > 0 ) {
                        $datos["errores"]["foto"] = $_FILES["foto"]["error"][$i];
                    }
                    elseif ( ! preg_match("/image/", $_FILES["foto"]["type"][$i])) {
                        $datos["errores"]["foto"] = "El fichero no es una imagen. Falló ".$_FILES["foto"]["name"][$i];
                    }
                    elseif ($_FILES["foto"]["size"][$i] > 1024*1024*1) {
                        $datos["errores"]["foto"] = "El tamaño de la foto debe ser menor que 1MB. Falló ".$_FILES["foto"]["name"][$i];
                    }
                    if (isset($datos["errores"]["foto"])) {
                        $validacion = false;
                    }
            }
        }
        return $validacion;
    }
    public function pruebas(){
        $fotos = $_FILES["fotos"];
                foreach ($fotos as $key => $foto) {
                }
                
    }
}