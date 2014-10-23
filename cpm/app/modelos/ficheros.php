<?php
namespace modelos;

/**
 * Description of ficheros
 *
 * @author jesus
 */
class ficheros {
	
	

	public static function get_carpetas() {
//		echo __METHOD__;
		$carpetas = array();
		
		$directorio = PATH_APP."ficheros";
		
		if (is_dir($directorio)) {
			
			if ($dh = opendir($directorio)){
				while (($file = readdir($dh)) !== false){
//					echo "filename:" . $file . "<br>";
					if (is_dir($directorio."/$file")) {
						array_push($carpetas, $file);
					}
				}
				
				closedir($dh);
			
			}
		}
		return $carpetas;
	}
	
	/**
	 * 
	 * @return array $ficheros["carpeta"]=array("file1", "file2", ...)
	 */
	public static function get_ficheros($carpeta) {
		
		$directorio = PATH_APPLICATION."recursos/ficheros/bienes";                 

		$ficheros = array();
		
		$subdirectorio = $directorio."/$carpeta";
		if ($dh = opendir($subdirectorio)){
			while (($file = readdir($dh)) !== false){
//				echo "filename:" . $file . "<br>";
				if (is_file($subdirectorio."/$file")) {
//					array_push($ficheros, $file);
					$ficheros[$file] = \modelos\descargas::get_contador_descargas("$carpeta/$file");
				}
			}

			closedir($dh);

		}

		
		return $ficheros;
	}

	
	public static function get_mime_type($extension) {
		
		$mime_types = array ( 
		
			".mp3" => "audio/mpeg3",
			".js" => "application/x-javascript",
		);
		
		return (array_key_exists($extension,$mime_types) ? $mime_types[$extension] : null);
		
	}
        
        /**
         * Consigue el titulo del articulo cuyo archivo es guardado en nuestra DB con un nombre generico
         * @author Jorge
         * @param type $fichero
         * @return type string
         */
        public static function get_titulo_articulo($fichero){
            $nombre_tabla = 'articulos';
            $tabla = \core\Modelo_SQL::get_prefix_tabla($nombre_tabla);
            $sql = 'select * from '.$tabla.' where manual = "'.$fichero.'"';
            $titulos = \core\Modelo_SQL::execute($sql);
            return $titulos[0]['nombre'];
        }
    
    /**
     * Función para crear una carpeta
     * @param type $path
     * @param type $nombre_carpeta
     */
    public static function crearCarpeta($path, $nombre_carpeta){
        $pathname = $path.DS.$nombre_carpeta;
        $chmod = 0777;  //Permisos. En windows no sirve
        if(!is_dir($pathname)){ 
            mkdir( $pathname, $chmod ); 
        }
    }
    
    /**
     * Borra una carpeta y todos los archivos en ella
     * @param type $path
     * @param type $nombre_carpeta
     */
    public static function borrarCarpeta($path, $nombre_carpeta){
        $dir = $path.DS.$nombre_carpeta;
        
        if (is_dir($dir)) {
            $objects = scandir($dir); 
            foreach ($objects as $object) { //Borramos los archivos del directorio
                if ($object != "." && $object != "..") { 
                    if (filetype($dir."/".$object) == "dir")
                        rrmdir($dir."/".$object);
                    else
                        unlink($dir."/".$object); 
                } 
            } 
            reset($objects); 
            rmdir($dir); //Si el directorio no está vacio no puedo borrarlo
        }  
    }
    
    public static function getNombreCarpeta($id) {
        $nombre = (string)$id;
        $nombre = "bien".str_repeat("0", 6 - strlen($nombre)).$nombre;
        
        return $nombre;
    }
	
}
