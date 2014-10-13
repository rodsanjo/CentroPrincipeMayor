<?php
namespace modelos;

class Enlaces_en_fichero {
	
        
	private static $file_name = 'enlaces.txt';   //fichero donde están los datos
        private static $enlaces = array();
        private static $campo1 = 'titulo';
        private static $campo2 = 'url';
        private static $campo3 = 'descripcion';
        
        /**
         * Para obtener la ruta del fichero
         * @return type
         */
        private static function getRutaFichero(){
            return PATH_APP."modelos/".self::$file_name;    //ruta del fichero
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

