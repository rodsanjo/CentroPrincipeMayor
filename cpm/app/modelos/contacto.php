<?php
namespace modelos;

class contacto extends \modelos\Modelo_SQL {
    /* Rescritura de propiedades de validación */
    public static $validaciones_insert = array(
        'nombre' => 'errores_requerido',
        'email' => 'errores_email',
        'phone' => 'errores_phone',
        'asunto' => 'errores_requerido && errores_texto',
        'mensaje' => 'errores_requerido',
    );
    
    public static $validaciones_insert_comentario = array(
        'nombre' => 'errores_requerido',
        'email' => 'errores_texto',
        'phone' => 'errores_texto',
        'referencia' => 'errores_requerido && errores_texto', //Este campo está oculto y será el asunto
        'comentario' => 'errores_requerido',
    );
}
