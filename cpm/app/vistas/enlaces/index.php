<div id="enlaces">
    <h2 class="titulo_seccion">Enlaces</h2>
    <dl> 
        <?php
            foreach ( $datos['enlaces'] as $id => $enlace ) {
                $patron = '/http:\/\//';
                if(! preg_match($patron, $enlace['url'])){
                    $enlace['url'] = 'http://'.$enlace['url'];
                }
                echo "<dt>
                        <a href='{$enlace['url']}' target='on_blank' title='{$enlace['descripcion']}'>
                            {$enlace['titulo']}
                        </a>
                    </dt>
                    <dd>{$enlace['descripcion']}</dd>
                    <center>
                        ".\core\HTML_Tag::a_boton("botonAdmin", array("enlaces", "form_modificar", $id), "Modificar")
                            ." - "
                         .\core\HTML_Tag::a_boton("botonAdmin", array("enlaces", "form_borrar", $id), "Borrar")."
                    </center>
                    ";
            }
        ?>
    </dl>
    <center>
        <?php
            echo \core\HTML_Tag::a_boton("botonAdmin", array("enlaces", "form_anexar"), "Incluir un nuevo enlace");
        ?>
    </center>  
    <!--<center><button onclick='<?php //echo URL_ROOT.\core\Distribuidor::get_controlador_instanciado()."/form_anexar" ?>'>Incluir un nuevo enlace</button></center>-->
</div>
