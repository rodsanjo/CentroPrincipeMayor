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
            $validacion = self::comprobar_files($datos);
            
            if($validacion){
                $id = $_POST['bien_id'];
                $nombre_carpeta = \modelos\ficheros::getNombreCarpeta($id);
                if ($_FILES["foto"]["size"]) {
                    self::mover_foto($nombre_carpeta); 
                    print_r('La foto ha sido añadida');
                }                
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
    private static function mover_foto($nombre_carpeta, $ref = null) {

        // Ahora hay que añadir la foto
        $nombre_archivo = $_FILES["foto"]["name"];
        $foto_path = PATH_APPLICATION."recursos".DS."ficheros".DS."bienes".DS.$nombre_carpeta.DS.$nombre_archivo;
//					echo __METHOD__;echo $_FILES["foto"]["tmp_name"];  echo $foto_path; exit;
        // Si existe el fichero lo borramos aunque creo que move_uploaded_file() lo sobreescribe
        if (is_file($foto_path)) {
            unlink($foto_path);
        }
        move_uploaded_file($_FILES["foto"]["tmp_name"], $foto_path);

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
    public function pruebas(){
        $fotos = $_FILES["fotos"];
                foreach ($fotos as $key => $foto) {
                }
                
    }
}