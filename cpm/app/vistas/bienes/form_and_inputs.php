<form method='post' name='<?php echo \core\Array_Datos::contenido("form_name", $datos); ?>' action="<?php echo \core\URL::generar($datos['controlador_clase'].'/validar_'.$datos['controlador_metodo']); ?>" enctype='multipart/form-data' onsubmit="return validarForm();">
    <fieldset><legend>Datos del inmueble</legend>
	<?php echo \core\HTML_Tag::form_registrar($datos["form_name"], "post"); ?>
	
	<input id='id' name='id' type='hidden' value='<?php echo \core\Array_Datos::values('id', $datos); ?>' />
	
        Tipo de inmueble:
        <select id='tipo' name="tipo">
            <?php
                $sql = 'select * from cpm_tipos_bien';
                $datos['tipos_bien'] = \core\sgbd\mysqli::execute($sql);
                //Por defecto estará seleccionada la vivienda que es el primer elemento de la lista
                foreach ($datos['tipos_bien'] as $key => $tipo_bien) {
                    if( ! is_null($tipo_bien['id_letra']) ){
                        $value = "value = '{$tipo_bien['id_letra']}'";
                        $selected = (\core\datos::values('tipo', $datos) == $tipo_bien['id_letra']) ? " selected='selected' " : "";
                        echo "<option $value $selected>{$tipo_bien['tipo']}</option>\n";
                    }
                }
            ?>
        </select>
        
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
        <?php echo \core\HTML_Tag::span_error('tipo_via_id', $datos); ?>
        <br/>
        
        <label for="nombre_via">Nombre de la vía*:</label><input id='nombre_via' name='nombre_via' type='text' size='100'  maxlength='100' value='<?php echo \core\Array_Datos::values('nombre_via', $datos); ?>'/>
	<?php echo \core\HTML_Tag::span_error('nombre_via', $datos); ?>
	<br />
     
        <label for="num_portal">Número de portal*:</label><input id='num_portal' name='num_portal' type='text' size='1'  maxlength='5' value='<?php echo \core\Array_Datos::values('num_portal', $datos); ?>' title="0 en caso de s/n, almacena el número de portal o el numero de plaza de garaje o el numero de local."/>
	<?php echo \core\HTML_Tag::span_error('num_portal', $datos); ?>
        
        Portal o bloque: <input id='portal_bloque' name='portal_bloque' type='text' size='1'  maxlength='10' value='<?php echo \core\Array_Datos::values('portal_bloque', $datos); ?>'/>
	<?php echo \core\HTML_Tag::span_error('portal_bloque', $datos); ?>
        
        Planta:<input id='planta' name='planta' type='text' size='1'  maxlength='3' value='<?php echo \core\Array_Datos::values('planta', $datos); ?>' title="planta de vivienda o planta de garaje. en locales tendrá un nulo."/>
	<?php echo \core\HTML_Tag::span_error('planta', $datos); ?>
        
        Puerta:<input id='puerta' name='puerta' type='text' size='1'  maxlength='3' value='<?php echo \core\Array_Datos::values('puerta', $datos); ?>' />
	<?php echo \core\HTML_Tag::span_error('puerta', $datos); ?>
	<br />
        
        Código postal:<input id='cp' name='cp' type='text' size='5'  maxlength='5' value='<?php echo \core\Array_Datos::values('cp', $datos); ?>' />
	<?php echo \core\HTML_Tag::span_error('cp', $datos); ?>
 
        Localidad:<input id='localidad' name='localidad' type='text' size='5'  maxlength='50' value='<?php echo \core\Array_Datos::values('localidad', $datos); ?>' />
	<?php echo \core\HTML_Tag::span_error('localidad', $datos); ?>
        
        Provincia:<input id='provincia' name='provincia' type='text' size='5'  maxlength='50' value='<?php echo \core\Array_Datos::values('provincia', $datos); ?>' />
	<?php echo \core\HTML_Tag::span_error('provincia', $datos); ?>
        
        País:<input id='pais' name='pais' type='text' size='5'  maxlength='50' value='<?php echo \core\Array_Datos::values('pais', $datos); ?>' />
	<?php echo \core\HTML_Tag::span_error('pais', $datos); ?>
	<br />
        
        <table class="colum2">
            <tr>
                <td>
                    Superficie construida:<input id='sup_const' name='sup_const' type='text' size='5'  maxlength='50' value='<?php echo \core\Array_Datos::values('sup_const', $datos); ?>' />
                    m<sup>2</sup>
                    <?php echo \core\HTML_Tag::span_error('sup_const', $datos); ?>
                </td>
                <td>
                    Superficie útil:<input id='sup_util' name='sup_util' type='text' size='5'  maxlength='50' value='<?php echo \core\Array_Datos::values('sup_util', $datos); ?>' />
                    m<sup>2</sup>
                    <?php echo \core\HTML_Tag::span_error('sup_util', $datos); ?>
                </td>
            </tr>

            <tr>
                <td>
                    Precio de venta: <input id='precio_venta' name='precio_venta' type='text' size='8'  maxlength='12' value='<?php echo \core\Array_Datos::values('precio_venta', $datos); ?>'/>
                    €
                    <?php echo \core\HTML_Tag::span_error('precio_venta', $datos); ?>
                </td>
                <td>
                    Precio de alquler: <input id='precio_alquiler' name='precio_alquiler' type='text' size='3'  maxlength='12' value='<?php echo \core\Array_Datos::values('precio_alquiler', $datos); ?>'/>
                    €/mes
                    <?php echo \core\HTML_Tag::span_error('precio_alquiler', $datos); ?>
                </td>
            </tr>
        </table>
	<br />
        
        Coordenadas geodésicas del inmueble:
        <ul>
            <li>
                Latitud: <input id='coord_lat' name='coord_lat' type='text' size='10'  maxlength='20' value='<?php echo \core\Array_Datos::values('coord_lat', $datos); ?>'/>
                <?php echo \core\HTML_Tag::span_error('coord_lat', $datos); ?>
            </li>
            <li>
                Longitud: <input id='coord_long' name='coord_long' type='text' size='10'  maxlength='20' value='<?php echo \core\Array_Datos::values('coord_long', $datos); ?>'/>
                <?php echo \core\HTML_Tag::span_error('coord_long', $datos); ?>
            </li>
        </ul>

<!--        Coordenadas UTM del inmueble:
        <ul>
            <li>
                X: <input id='coord_utm_x' name='coord_utm_x' type='text' size='12'  maxlength='15' value='<?php echo \core\Array_Datos::values('coord_utm_x', $datos); ?>'/>
                <?php echo \core\HTML_Tag::span_error('coord_utm_x', $datos); ?>
            </li>
            <li>
                 Y: <input id='coord_utm_x' name='coord_utm_y' type='text' size='12'  maxlength='15' value='<?php echo \core\Array_Datos::values('coord_utm_y', $datos); ?>'/>
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
                        if (isset($datos['values']['huso'])){
                            echo "<option value='{$datos['values']['huso']}' selected='selected'>".\core\Array_Datos::values('huso', $datos)."</option>";
                        }
                    ?>
                </select>
                <?php echo \core\HTML_Tag::span_error('huso', $datos); ?>
            </li>
            <li>
                Hemisferio:
                <?php 
                    if ( isset($datos['values']['hemis']) && $datos['values']['hemis'] == 's'){
                        ?>
                        <input id='hemis_norte' name='hemis' type='radio' value='n' />Norte 
                        <input id='hemis_sur' name='hemis' type='radio' value='s' checked="checked"/>Sur
                        <?php
                    }else{
                        ?>
                        <input id='hemis_norte' name='hemis' type='radio' value='n' checked="checked"/>Norte 
                        <input id='hemis_sur' name='hemis' type='radio' value='s'/>Sur
                        <?php
                    }
                echo \core\HTML_Tag::span_error('hemmis', $datos); ?>
            </li>
        </ul>-->
        
        <?php
            $check = isset($datos['values']['foto']) ? "<img src='".URL_ROOT."recursos/imagenes/check.jpg' width='40px'/>" : "<img src='".URL_ROOT."recursos/imagenes/no_check.jpg' width='40px'/>";
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
        <textarea id="resenha" name="resenha" maxlength='300' cols="120" rows="3"><?php echo \core\Array_Datos::values('resenha', $datos); ?></textarea>
        <br/>

        *Campos obligatorios<br />
        
	<?php echo \core\HTML_Tag::span_error('errores_validacion', $datos); ?>
	
	<input type='submit' value='Enviar' class="btn-default botonAdmin"/>
        <input type='reset' value='Restablecer' class="btn-default botonAdmin"/>
        <button type='button' onclick='window.location.assign("<?php echo $datos['url_cancelar']; ?>");' class="btn-default botonAdmin">Cancelar</button>
    </fieldset>
</form>

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
	var patron=/[\wñ]{1,}/i;
	if(!patron.test(valor)){
            document.getElementById("error_nombre_via").innerHTML="Este campo debe contener al menos 1 carácter.";
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
