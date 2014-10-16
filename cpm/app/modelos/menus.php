<?php

namespace modelos;

class Menus{    //la clase se tiene que llamar igual que el archivo
    private static $menuUp = array(
        /*
            item => "controlador,metodo/clausula,title"
            item => array( subitem, subitem, ...)
                subitem => "controlador,metodo,title"

        */
        /*Colocamos el menú en sentido inverso dado que hemos usado en el CSS float: right*/
        "Inicio" => "inicio,index,Inicio"
        /*,"Buscar" => array(
            "Pisos" => "busqueda,pisos,Pisos"
            ,"Garajes" => "busqueda,garajes,Garajes"
            ,"Locales" => "busqueda,locales,Locales"
            )*/
        ,"Otros estudios" => array(
            "Certificación Energética" => "estudios,certificacionEnergetica,Certificación Energética"
            ,"IEE" => "estudios,iee,IEE"
            ,"Legalizaciones" => "estudios,legalizaciones,Legalizaciones"
            )
        ,"Enlaces" => "enlaces,index,Enlaces de interés"
        ,"Contacto" => "contacto,index,Contacto"
    );
    
    public static function get_menuUp(){
        return self::$menuUp;
    }
}
?>
