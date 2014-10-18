<script type="text/javascript" src="<?php echo URL_ROOT ?>recursos/js/validaciones/val_busqueda.js"></script>
<script type = "text/javascript" src="<?php echo URL_ROOT ?>recursos/js/datos/busqueda.js"></script>

<div id="buscar">
    <h3 class="titulo_seccion">Buscador de inmuebles</h3>
    <form onsubmit="return validarForm();" name="formulario" class="form_buscar" method='post' action='<?php echo \core\URL::generar("bienes/busqueda"); ?>'>
        <label>Tipo de inmueble:</label>
        <select name="tipo_inmueble" onchange="insertarDestinos(this.name, this.value, 'num_hab');">
            <option value="" selected="selected">Cualquiera</option>
            <option value="v">Vivienda</option>
            <option value="g">Garaje</option>
            <option value="l">Local</option>
            <option value="t">Trastero</option>
            <option value="p">Parcela</option>
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
        
        <input type='text' id='buscar_nombre' name='buscar_nombre' placeholder="Localidad o código postal" title='Introduzca el nombre del municipio, provincia o código postal'/>

        <input type='text' id='referencia' name='referencia' placeholder="Referencia" title='Introduzca la referencia dle inmueble'/>
        </br>
<!--
        <select id="" name="precio">
            <option value="" selected="selected" >Precio</option>
            <?php
                $precio = 50000;
                while ($precio < 400000) {
                    $precio_punto_decimal = \core\Conversiones::poner_punto_separador_miles($precio);
                    echo "
                        <option value='$precio' >Hasta $precio_punto_decimal €</option>
                    ";
                    $precio += 50000;
                }

            ?>
        </select>

        <select id="num_hab" name="num_hab">
        </select>-->

        <input class="boton1" type='submit' value='Buscar' title='Buscar'/>
    </form>
</div>
