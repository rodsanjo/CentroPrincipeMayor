<?php
//var_dump($datos);
$get['p3'] = \core\HTTP_Requerimiento::get('p3');
$datos['values']['tabla'] = 'tabla_d'.$get['p3'];
?>

<form id="form_subir_fotos_ajax" name='form_subir_fotos_ajax' method="post" enctype='multipart/form-data' >
    <input id='id' name='id' type='hidden' value='<?php echo \core\Array_Datos::values('id', $datos); ?>' />
    <input id='foto' name='foto' type='file' size='100'  maxlength='50' value='<?php echo \core\Array_Datos::values('foto', $datos); ?>'/>
    <?php echo \core\HTML_Tag::span_error('foto', $datos); ?>
    
    <input id="btn_enviar" type="button" value="Añadir foto" class="btn-default boton"/>
    <input type="submit" name="enviar" value="Añadir foto" class='btn-default botonBuscar'/>
</form>

<form method='post' name='<?php echo \core\Array_Datos::contenido("form_name", $datos); ?>' action="<?php echo \core\URL::generar($datos['controlador_clase'].'/validar_'.$datos['controlador_metodo'].'/'.$get['p3']); ?>" onsubmit="return validarForm();">
    <fieldset><legend>Detalles del inmueble</legend>
	<?php echo \core\HTML_Tag::form_registrar($datos["form_name"], "post"); ?>
	
	<input id='id' name='id' type='hidden' value='<?php echo \core\Array_Datos::values('id', $datos); ?>' />
        <input id='bien_id' name='bien_id' type='hidden' value='<?php echo \core\Array_Datos::values('bien_id', $datos); ?>' />
        <input id='tipo_bien' name='tipo_bien' type='hidden' value='<?php echo $datos['p3']; //Para despues hacer facil la conversión a MySQL ?>' />
        <input id='tabla' name='tabla' type='hidden' value='<?php echo \core\Array_Datos::values('tabla', $datos); ?>' />
                
       Tipo de inmueble:
        <select id='tipo_bien_id' name="tipo_bien_id">
            <?php
                $sql = 'select * from cpm_tipos_bien';
                $datos['tipos_bien'] = \core\sgbd\mysqli::execute($sql);
                if (\core\Distribuidor::get_metodo_invocado() == "form_anhadir_detalles") {
                    echo "<option disabled='true' selected='selected'>Seleccione el tipo de bien</option>";
                }
                foreach ($datos['tipos_bien'] as $key => $tipo_bien) {
                    $value = "value = '{$tipo_bien['id']}'";
                    $selected = (\core\datos::values('tipo_bien_id', $datos) == $tipo_bien['id']) ? " selected='selected' " : "";
                    echo "<option $value $selected>{$tipo_bien['tipo']}</option>\n";
                }
            ?>
        </select>
        <?php echo \core\HTML_Tag::span_error('tipo_bien_id', $datos); ?>
        
        Año de construcción: <input id='anho_const' name='anho_const' type='text' size='3'  maxlength='4' value='<?php echo \core\Array_Datos::values('anho_const', $datos); ?>'/>
	<?php echo \core\HTML_Tag::span_error('anho_const', $datos); ?>
        <br/>
        
        <?php
        if ( $datos['p3'] == 'v'){
        ?>
            Número de habitaciones:<input id='num_hab' name='num_hab' type='text' size='1'  maxlength='2' value='<?php echo \core\Array_Datos::values('num_hab', $datos); ?>' title="Número de habitaciones de la vivienda."/>
            <?php echo \core\HTML_Tag::span_error('num_hab', $datos); ?>

            Número de cuartos de baño:<input id='num_banhos' name='num_banhos' type='text' size='1'  maxlength='1' value='<?php echo \core\Array_Datos::values('num_banhos', $datos); ?>' />
            <?php echo \core\HTML_Tag::span_error('num_banhos', $datos); ?>
            <br />
            
            Descripción:<br/>
            <textarea id="descripcion" name="descripcion" maxlength='1000' cols="120" rows="10"><?php echo \core\Array_Datos::values('descripcion', $datos); ?></textarea>
            <br/>
        
        <?php
        }elseif ($datos['p3'] == 'g') {
        ?>
            Longitud:<input id='m_largo' name='m_largo' type='text' size='3'  maxlength='5' value='<?php echo \core\Array_Datos::values('m_largo', $datos); ?>' title="Número de habitaciones de la vivienda."/>
            <?php echo \core\HTML_Tag::span_error('m_largo', $datos); ?>

            Anchura:<input id='m_ancho' name='m_ancho' type='text' size='3'  maxlength='5' value='<?php echo \core\Array_Datos::values('m_ancho', $datos); ?>' />
            <?php echo \core\HTML_Tag::span_error('m_ancho', $datos); ?>
            <br />
             
        <?php
        }
        ?>
        
	<?php echo \core\HTML_Tag::span_error('errores_validacion', $datos); ?>
	
	<input type='submit' value='Enviar' class="btn-default botonAdmin" onmouseover="validarAnho('anho_const',document.f.anho_const.value);"/>
        <input type='reset' value='Restablecer' class="btn-default botonAdmin"/>
        <button type='button' onclick='window.location.assign("<?php echo $datos['url_cancelar']; ?>");' class="btn-default botonAdmin">Cancelar</button>
    </fieldset>
</form>

<script type="text/javascript">
    var ok = false;
    var f = <?php echo \core\Array_Datos::contenido("form_name", $datos); ?>;
    
    function validarNumeroEntero(id){
        var valor = document.getElementById(id).value;
        //alert(valor);
	var patron=/^\d{0,}$/;
	if(!patron.test(valor)){
            document.getElementById("error_"+id).innerHTML="Debe escribir un número entero.";                
            ok = false;
	}else{
            document.getElementById("error_"+id).innerHTML="";
	}
    }
    function validarAnho(id, anho){
        //alert(anho);
	var patron=/^\d{4}$/;
	if(!patron.test(anho)){
            document.getElementById("error_"+id).innerHTML="Debe escribir un año de 4 cifras.";                
            ok = false;
	}else{
            document.getElementById("error_"+id).innerHTML="";
	}
    }
    function validarNumeroDecimal(id){
        var valor = document.getElementById(id).value;
        //alert(valor);
	var patron=/^\d{0,}[.,]{0,1}\d{0,2}$/;
	if(!patron.test(valor)){
            document.getElementById("error_"+id).innerHTML="Debe escribir un número decimal con dos decimales máximo.";
            ok = false;
	}else{
            document.getElementById("error_"+id).innerHTML="";
	}
    }
    
    function validarForm(){
	ok=true;
	
        validarAnho('anho_const',document.f.anho_const.value);
        validarNumeroEntero("num_hab");
        validarNumeroEntero("num_banhos");
        validarNumeroDecimal("m_largo");
        validarNumeroDecimal("m_ancho");
	
	//ok=false;	//Si devolvemos false, no se envia el formulario
	return ok;
    }
</script>

<script type="text/javascript">
	
	//Fucnión para enviar el al hacer submit en el formulario.
	jQuery(document).ready(function() {
		$("#form_subir_fotos_ajax").submit(function(){
			// debugger;
			jQuery.post(
				"yvive/ajax_enviarMail.php"
				,jQuery("#form_subir_fotos_ajax").serialize()
				,function(data, textStatus, jqXHR) {
					// debugger;
					// alert("resultados: "+data);
					$("#respuestas").html(data);
				}
			);
			return false; // avoid to execute the actual submit of the form.
		});
	});	
	
	//Fucnión para enviar el mail con onclick sin enviar el formulario.
	$(function(){
		$("#btn_enviar").click(function(){
			var url = "http://yvive/ajax_enviarMail.php"; // El script a dónde se realizará la petición.
	    	$.ajax({
	           type: "POST",
	           url: url,
	           data: $("#form_subir_fotos_ajax").serialize(), // Adjuntar los campos del formulario enviado.
	           success: function(data){
	               $("#respuestas").html(data); // Mostrar la respuestas del script PHP.
	       		}
	        });
	    return false; // Evitar ejecutar el submit del formulario.
	 });
});
</script>
