
<form method='post' name='<?php echo \core\Array_Datos::contenido("form_name", $datos); ?>' action="<?php echo \core\URL::generar($datos['controlador_clase'].'/validar_'.$datos['controlador_metodo']); ?>" enctype='multipart/form-data' onsubmit="return validarForm();">
    <fieldset><legend>Datos del inmueble</legend>
	<?php echo \core\HTML_Tag::form_registrar($datos["form_name"], "post"); ?>
	
	<input id='id' name='id' type='hidden' value='<?php echo \core\Array_Datos::values('id', $datos); ?>' />
	
        Tipo de inmueble:
        <select id='tipo' name="tipo">
            <option value = 'v' selected='selected'>Vivienda</option>
            <option value = 'g' >Garaje</option>
            <option value = 'l' >Local</option>
            <option value = 't' >Trastero</option>
            <option value = 'p' >Parcela</option>
            <option value = 'n' >Nave</option>
        </select>
        <br/>
        
        Tipo de vía:
        <select id='tipo_via_id' name="tipo_via_id">
            <?php
                $sql = 'select * from cpm_tipos_via';
                $datos['tipos_via'] = \core\sgbd\mysqli::execute($sql);
                if (\core\Distribuidor::get_metodo_invocado() == "form_insertar") {
                    echo "<option disabled='true' selected='selected'>Seleccione el tipo de vía</option>";
                }
                foreach ($datos['tipos_via'] as $key => $tipo_via) {
                    $value = "value = '{$tipo_via['id']}'";
                    $selected = (\core\datos::values('tipo_via_id', $datos) == $tipo_via['id']) ? " selected='selected' " : "";
                    echo "<option $value $selected>{$tipo_via['tipo']}</option>\n";
                }
            ?>
        </select>
        <?php echo \core\HTML_Tag::span_error('categoria_id', $datos); ?>
        <br/>
        
        <label for="nombre_via">Nombre de la vía*:</label><input id='nombre_via' name='nombre_via' type='text' size='100'  maxlength='100' value='<?php echo \core\Array_Datos::values('nombre_via', $datos); ?>'/>
	<?php echo \core\HTML_Tag::span_error('nombre_via', $datos); ?>
	<br />
     
        <label for="num_portal">Número de portal*:</label><input id='num_portal' name='num_portal' type='text' size='2'  maxlength='5' value='<?php echo \core\Array_Datos::values('num_portal', $datos); ?>' title="0 en caso de s/n, almacena el número de portal o el numero de plaza de garaje o el numero de local."/>
	<?php echo \core\HTML_Tag::span_error('num_portal', $datos); ?>
        
        Portal o bloque: <input id='portal_bloque' name='portal_bloque' type='text' size='10'  maxlength='10' value='<?php echo \core\Array_Datos::values('portal_bloque', $datos); ?>'/>
	<?php echo \core\HTML_Tag::span_error('portal_bloque', $datos); ?>
        
        <label for="planta">Planta:</label><input id='planta' name='planta' type='text' size='5'  maxlength='3' value='<?php echo \core\Array_Datos::values('planta', $datos); ?>' title="planta de vivienda o planta de garaje. en locales tendrá un nulo."/>
	<?php echo \core\HTML_Tag::span_error('planta', $datos); ?>
        
        <label for="puerta">Puerta:</label><input id='puerta' name='puerta' type='text' size='5'  maxlength='3' value='<?php echo \core\Array_Datos::values('puerta', $datos); ?>' />
	<?php echo \core\HTML_Tag::span_error('puerta', $datos); ?>
	<br />
        
        <label for="cp">Código postal:</label><input id='cp' name='cp' type='text' size='5'  maxlength='5' value='<?php echo \core\Array_Datos::values('cp', $datos); ?>' />
	<?php echo \core\HTML_Tag::span_error('cp', $datos); ?>
	<br />
 
        <label for="localidad">Localidad:</label><input id='localidad' name='localidad' type='text' size='5'  maxlength='50' value='<?php echo \core\Array_Datos::values('localidad', $datos); ?>' />
	<?php echo \core\HTML_Tag::span_error('localidad', $datos); ?>
        
        <label for="provincia">Provincia:</label><input id='provincia' name='provincia' type='text' size='5'  maxlength='50' value='<?php echo \core\Array_Datos::values('provincia', $datos); ?>' />
	<?php echo \core\HTML_Tag::span_error('provincia', $datos); ?>
        
        <label for="pais">País:</label><input id='pais' name='pais' type='text' size='5'  maxlength='50' value='<?php echo \core\Array_Datos::values('pais', $datos); ?>' />
	<?php echo \core\HTML_Tag::span_error('pais', $datos); ?>
	<br />
        
        <label for="superficie">Superficie:</label><input id='superficie' name='superficie' type='text' size='5'  maxlength='50' value='<?php echo \core\Array_Datos::values('superficie', $datos); ?>' />
        m2
	<?php echo \core\HTML_Tag::span_error('superficie', $datos); ?>
	<br />
        
        Precio de venta: <input id='precio_venta' name='precio_venta' type='text' size='15'  maxlength='12' value='<?php echo \core\Array_Datos::values('precio_venta', $datos); ?>'/>
        €
	<?php echo \core\HTML_Tag::span_error('precio_venta', $datos); ?>
	<br />
        
        Precio de alquler: <input id='precio' name='precio_alquiler' type='text' size='15'  maxlength='12' value='<?php echo \core\Array_Datos::values('precio_alquiler', $datos); ?>'/>
        €/mes
	<?php echo \core\HTML_Tag::span_error('precio_alquiler', $datos); ?>
	<br />
        
<!--        Coordenadas geodésicas del inmueble:
        <ul>
            <li>
                Latitud: <input id='coord_lat' name='coord_lat' type='text' size='10'  maxlength='20' value='<?php echo \core\Array_Datos::values('coor_lat', $datos); ?>'/>
                <?php echo \core\HTML_Tag::span_error('coor_lat', $datos); ?>
            </li>
            <li>
                Longitud: <input id='coord_long' name='coord_long' type='text' size='10'  maxlength='20' value='<?php echo \core\Array_Datos::values('coor_long', $datos); ?>'/>
                <?php echo \core\HTML_Tag::span_error('coor_long', $datos); ?>
            </li>
        </ul>-->

        Coordenadas UTM del inmueble:
        <ul>
            <li>
                X: <input id='coord_utm_x' name='coord_utm_x' type='text' size='10'  maxlength='10' value='<?php echo \core\Array_Datos::values('coor_lat', $datos); ?>'/>
                <?php echo \core\HTML_Tag::span_error('coord_utm_x', $datos); ?>
            </li>
            <li>
                Y: <input id='coord_utm_x' name='coord_utm_y' type='text' size='10'  maxlength='10' value='<?php echo \core\Array_Datos::values('coor_long', $datos); ?>'/>
                <?php echo \core\HTML_Tag::span_error('coord_utm_y', $datos); ?>
            </li>
            <li>
                Huso: 
                <select id="huso" name="huso">
                    <?php
                        for( $huso = 1; $huso<=60 ; $huso++){
                            if( $huso == 30){
                                echo "<option value='$huso' selected='selected'>$huso</option>";
                            }else{
                                echo "<option value='$huso' >$huso</option>";
                            }
                        }
                        if (isset($datos['values'][$huso])){
                            echo "<option value='$huso' selected='selected'>".\core\Array_Datos::values('huso', $datos)."</option>";
                        }
                    ?>
                </select>
                <?php echo \core\HTML_Tag::span_error('huso', $datos); ?>
            </li>
            <li>
                Hemisferio:
                <input id='hemis_norte' name='hemis' type='radio' value='n' checked="checked"/>Norte 
                <input id='hemis_sur' name='hemis' type='radio' value='s'/>Sur
                <?php echo \core\HTML_Tag::span_error('hemmis', $datos); ?>
            </li>
        </ul>
        
        <?php
            $check = isset($datos['values']['foto']) ? "<img src='".URL_ROOT."recursos/imagenes/check.jpg' width='15px'/>" : "<img src='".URL_ROOT."recursos/imagenes/no_check.jpg' width='15px'/>";
            echo $check;
        ?>
        
        Foto:<input id='foto' name='foto' type='file' size='100'  maxlength='50' value='<?php echo \core\Array_Datos::values('foto', $datos); ?>'/>
	<?php echo \core\HTML_Tag::span_error('foto', $datos); ?>
	<br />
<!--        
        Video: <input id='video' name='video' type='file' size='100'  maxlength='50' value='<?php //echo \core\Array_Datos::values('video', $datos); ?>'/>
	<?php //echo \core\HTML_Tag::span_error('video', $datos); ?>
	<br />
        -->

        Reseña:<br/>
        <textarea id="resenha" name="resenha" maxlength='300' cols="50" rows="3"><?php echo \core\Array_Datos::values('resenha', $datos); ?></textarea>
        <br/>
<!--        
        Descripción:<br/>
        <textarea id="descripcion" name="descripcion" maxlength='1000' cols="80" rows="8"><?php echo \core\Array_Datos::values('descripcion', $datos); ?></textarea>
        <br/>
        -->
        *Campos obligatorios<br />
        
	<?php echo \core\HTML_Tag::span_error('errores_validacion', $datos); ?>
	
	<input type='submit' value='Enviar'/>
        <input name="restablecer" type='reset' value='Restablecer'/>
        <button type='button' onclick='window.location.assign("<?php echo \core\URL::generar($datos['controlador_clase']); ?>");'>Cancelar</button>
    </fieldset>
</form>

<script type="text/javascript" src="<?php echo URL_ROOT ?>recursos/js/validaciones.js"></script>

<script type="text/javascript">
    var ok = false;
    var f = <?php echo \core\Array_Datos::contenido("form_name", $datos); ?>
    
    function validarNum_portal(){
        //var nombre_via = f.nombre_via.value;
        var num_portal = f.num_portal.value;
        //alert(num_min_jug+" - "+num_max_jug);
	var patron=/^\d{0,}$/;
	if(!patron.test(num_portal)){
            document.getElementById("error_num_portal").innerHTML="Debe escribir solo números, 0 en caso de s/n, almacena el número de portal o el numero de plaza de garaje o el numero de local.";                
            ok = false;
	}else{
            document.getElementById("error_num_portal").innerHTML="";
	}
    }
    function validarNombre_via(){
	var valor = document.getElementById("nombre_via").value;
	var patron=/[a-zñ]{5,}/i;
	if(!patron.test(valor)){
            document.getElementById("error_nombre_via").innerHTML="Este campo debe contener al menos 5 caracteres.";
            ok = false;
	}else{
            document.getElementById("error_nombre_via").innerHTML="";
	}                      
}
    
    function validarForm(){
	ok=true;
	
        validarNombre_via();
	validarNum_portal();
	
	//ok=false;	//Si devolvemos false, no se envia el formulario
	return ok;
    }
</script>
