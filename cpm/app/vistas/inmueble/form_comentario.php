<div id="div_form_comentario">
    <script type="text/javascript" src="<?php echo URL_ROOT ?>recursos/js/validaciones/val_form_comentario.js"></script>
    <form onsubmit="return validarForm();" name="formulario" action="<?php echo \core\URL::generar("contacto/enviar_comentario"); ?>" method="post" enctype="multipart/form-data" class="form_contact" id="form_comentario">

        <textarea name="comentario" id="comentario" class="input_form_comentario" value="Comentarios" onFocus="if(this.value=='Comentarios')this.value='';" onblur="if(this.value=='')this.value='Comentarios';"></textarea>
        <input type="text" name="nombre" id="nombre" class="input_form_comentario" value="Nombre" onFocus="if(this.value=='Nombre')this.value='';" onblur="if(this.value=='')this.value='Nombre';"/>
        <input type="tel" name="phone" id="phone" class="input_form_comentario"  value="Teléfono" onFocus="if(this.value=='Teléfono')this.value='';" onblur="if(this.value=='')this.value='Teléfono';" maxlength="15"/>
        <input type="email" name="email" id="email" class="input_form_comentario"  value="Email" style="margin-right:0px;" onFocus="if(this.value=='Email')this.value='';" onblur="if(this.value=='')this.value='Email';"/>


        <input type="hidden" name="referencia" id="referencia"  value="<?php echo $fila['referencia']; ?>" />
        <input type="hidden" name="web" id="web"  value="http://centroprincipemayor.esy.es/" />

        <?php echo \core\HTML_Tag::span_error('validacion', $datos); ?>
        <button type="submit" class="boton1">Enviar</button>

        <br/><br/>
        <div class="clear"></div>
        <a class="politica" href="<?php echo core\URL::generar('mensajes/avisoLegal'); ?>" title="Política de Privacidad" target="_self">Pol&iacute;tica de Privacidad</a>

</form>
</div>