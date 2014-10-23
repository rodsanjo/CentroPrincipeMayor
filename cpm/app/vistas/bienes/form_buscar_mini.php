<script type="text/javascript" src="<?php echo URL_ROOT ?>recursos/js/validaciones/val_busqueda.js"></script>
<script type = "text/javascript" src="<?php echo URL_ROOT ?>recursos/js/datos/busqueda.js"></script>

<div id="buscar">
    <form onsubmit="return validarForm();" name="formulario" class="form_buscar" method='post' action='<?php echo \core\URL::generar("bienes/busqueda"); ?>'>
        <label>Tipo de inmueble:</label>
        <select name="tipo_inmueble" onchange="insertarDestinos(this.name, this.value, 'num_hab');">
            <option value="" selected="selected">Cualquiera</option>
            <option value="v">Vivienda</option>
            <option value="g">Garaje</option>
            <option value="l">Local</option>
            <option value="t">Trastero</option>
            <option value="p">Parcela</option>
            <option value="n">Nave</option>
        </select>

        <label>Tipo operación:</label>
        <select name="tipo_transacion" onchange="insertarDestinos(this.name, this.value, 'precio');">
            <!--<option disabled="true" selected="selected">Tipo de operación</option>-->
            <option value="" selected="selected">Todos</option>
            <option value="venta">Venta</option>
            <option value="alquiler">Alquiler</option>
        </select>

        <input type='text' id='precio_max' name='precio_max' placeholder="Precio máximo" title='Introduzca el precio máximo deseado'/>        
        <?php echo \core\HTML_Tag::span_error('precio', $datos); ?>

        <input class="boton1" type='submit' value='Buscar' title='Buscar'/>
    </form>
</div>
