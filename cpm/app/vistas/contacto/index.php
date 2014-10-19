<div id="contacto">
    <script type="text/javascript" src="<?php echo URL_ROOT ?>recursos/js/validaciones/val_contacto.js"></script>

    <h1 class="titulo_seccion"><?php //echo ucfirst(iText('Contacto', 'dicc')); ?></h1>
    <p>
        Si quiere ponerse en contacto con nosotros para cualquier consulta, puede hacerlo a través de nuestro correo electrónico: <a href="mailto:email@email.com">email@email.com</a> o bien mediante el siguiente formulario y le contestaremos lo antes posible.
    </p>
    
    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12150.016687481395!2d-3.6994406!3d40.4198337!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd422886ee826823%3A0xd0132da1fe2fc4b3!2sCalle+Gran+V%C3%ADa%2C+16%2C+28013+Madrid!5e0!3m2!1ses!2ses!4v1413722057397" width="600" height="450" frameborder="0" style="border:0"></iframe>
    
    <div id="formulario">
    <form onsubmit="return validarForm();" name="formulario" action="<?php echo \core\URL::generar("contacto/enviar_mail"); ?>" method="post" enctype="multipart/form-data" id="form_contacto">
        <fieldset>
        <legend>Formulario de contacto</legend>
        
            <div class="grupo2">
                <input id="nombre" onblur="validarNombre();" type="text" name= "nombre" value="<?php echo \core\Array_Datos::values('nombre', $datos); ?>" size="20" maxlength="30" class="input_form_contacto" placeholder="Nombre"/>
                <input id="asunto" type="text" name="asunto" value="<?php echo \core\Array_Datos::values('asunto', $datos); ?>" class="input_form_contacto" placeholder="Asunto"/>
            </div>
            <?php echo \core\HTML_Tag::span_error('nombre', $datos); ?>
            <?php echo \core\HTML_Tag::span_error('asunto', $datos); ?>
        
            <div class="grupo2">
                <input id="email" type="text" name="email" value="<?php echo \core\Array_Datos::values('email', $datos); ?>" class="input_form_contacto" placeholder="Email"/>
                <input id="phone" type="text" name="phone" value="<?php echo \core\Array_Datos::values('phone', $datos); ?>" class="input_form_contacto" maxlength="15" placeholder="Teléfono"/>
            </div>
            <?php echo \core\HTML_Tag::span_error('emailPhone', $datos); ?>
            

            <textarea id="mensaje" name="mensaje"  rows="5" class="input_form_contacto" value="Comentarios" onFocus="if(this.value=='Mensaje')this.value='';" onblur="if(this.value=='')this.value='Mensaje';"><?php echo \core\Array_Datos::values('mensaje', $datos); ?></textarea>
            <?php echo \core\HTML_Tag::span_error('mensaje', $datos); ?>
            <br/>
            
            <?php echo \core\HTML_Tag::span_error('validacion', $datos); ?>
            <br/>            
            <input type="hidden" name="web" id="web"  value="http://centroprincipemayor.es/" />

            <input type="submit" class="boton1" name="enviar" value="Enviar" />
            
            <div class="clear"></div>
            
            <input type="checkbox" name="recibirCopia"/>Deseo recibir una copia del mensaje
            
            <a class="politica" href="<?php echo core\URL::generar('mensajes/avisoLegal'); ?>" title="Política de Privacidad" target="_onblank">Pol&iacute;tica de Privacidad</a>

        </fieldset>
    </form>
    </div>
    
</div>
