<div id="busqueda">
    <?php include "form_buscar_mini.php"; ?> 
    <form onsubmit="return validarForm();" name="formulario" action="<?php echo \core\URL::generar("pisos/busqueda"); ?>" method="post" enctype="multipart/form-data">
        <fieldset>

            <input type="submit" name="enviar" value="Enviar" />
        </fieldset>
    </form>
</div>
