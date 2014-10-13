<form name='<?php echo \core\Array_Datos::contenido("form_name", $datos); ?>' method='post' action="<?php echo \core\URL::generar($datos['controlador_clase'].'/'.$datos['controlador_metodo'].'_validar'); ?>">
    <?php $pos = isset($_POST['id']) ? $_POST['id'] : null; //Debo de hacer esto ya que los datos no vienen de la base de datos, si no de un fichero ?>
    <fieldset><legend>Datos del artículo</legend>
        <input type="hidden" id="id" name="id" value="<?php echo isset($pos) ? $pos : \core\Datos::values('id', $datos) ?>"/> 
        
        Título*:
        <input type='text' id='<?php echo \controladores\Enlaces::$campo1 ?>' name='<?php echo \controladores\Enlaces::$campo1 ?>' maxsize='50' value="<?php echo isset($datos['values'][$pos]['titulo']) ? $datos['values'][$pos]['titulo'] : str_replace("\\", "", \core\Array_Datos::values('titulo', $datos)); ?>"/>
        <?php echo \core\HTML_Tag::span_error('titulo', $datos) ?>
        <br/>
        
        URL*:
        <input type='text' id='<?php echo \controladores\Enlaces::$campo2 ?>' name='<?php echo \controladores\Enlaces::$campo2 ?>' maxsize='50' size='89' value="<?php echo isset($datos['values'][$pos]['url']) ? $datos['values'][$pos]['url'] : str_replace("\\", "", \core\Array_Datos::values('url', $datos)); ?>"/>
        <?php echo \core\HTML_Tag::span_error('url', $datos) ?>
        <br/>
        
        Descripción:<br/>
        <textarea id="<?php echo \controladores\Enlaces::$campo3 ?>" name="<?php echo \controladores\Enlaces::$campo3 ?>" maxlength='255' cols="100" rows="4" ><?php echo isset($datos['values'][$pos]['descripcion']) ? $datos['values'][$pos]['descripcion'] : str_replace("\\", "",  str_replace("\\r\\n", "", \core\Array_Datos::values('descripcion', $datos))); ?></textarea>
        <?php echo \core\HTML_Tag::span_error('descripcion', $datos) ?>
        
        
        <?php echo \core\HTML_Tag::span_error('validacion', $datos) ?>  
        <br/>
        
        <small>* Campos obligatorios</small><br/>
        <input type='submit' value='Enviar' />
        <input name="restablecer" type='reset' value='Restabler'/>
        <button type='button' onclick='window.location.assign("<?php echo \core\URL::generar($datos['controlador_clase']); ?>");'>Cancelar</button>
    </fieldset>
</form>
