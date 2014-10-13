<form id="form_comentario" name="form" method="post" class="form_contact">

    <textarea name="comentario" id="comentario" class="input_form_comentario" value="Comentarios" onFocus="if(this.value=='Comentarios')this.value='';" onblur="if(this.value=='')this.value='Comentarios';"></textarea>
    <input type="text" name="nombre" id="nombre" class="input_form_comentario" value="Nombre" onFocus="if(this.value=='Nombre')this.value='';" onblur="if(this.value=='')this.value='Nombre';"/>
    <input type="text" name="telefono" id="telefono" class="input_form_comentario"  value="Teléfono" onFocus="if(this.value=='Teléfono')this.value='';" onblur="if(this.value=='')this.value='Teléfono';"/>
    <input type="text" name="email" id="email" class="input_form_comentario"  value="Email" style="margin-right:0px;" onFocus="if(this.value=='Email')this.value='';" onblur="if(this.value=='')this.value='Email';"/>


    <input type="hidden" name="anuncio" id="anuncio"  value="" />
    <input type="hidden" name="web" id="web"  value="http://centroprincipemayor.es/" />

    <button type="submit" class="boton1">Enviar</button>

    <br/><br/>
    <div class="clear"></div>
    <a class="politica" href="<?php echo core\URL::generar('mensajes/avisoLegal'); ?>" title="Política de Privacidad" target="_self">Pol&iacute;tica de Privacidad</a>

</form>