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
 * @author jesus
 */
class carpetas extends \core\Controlador {
	
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
	
}
