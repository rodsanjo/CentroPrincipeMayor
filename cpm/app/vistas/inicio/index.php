
<div id="inicio">
    <div id="colum_izq">
        <div id="busqueda">
            <?php include "form_buscar.php"; ?> 
        </div>
        <div id="acciones">
            <?php
            //echo \core\HTML_Tag::a_boton_onclick("botonAdmin", array("bienes", "form_insertar"), "Insertar un inmueble");
            echo \core\HTML_Tag::a_boton("botonAdmin", array("bienes", "form_insertar"), "insertar un nuevo inmueble", array("title" => "insertar un nuevo inmueble"));
            ?>
        </div>
    </div>
    <div id="colum_der">
        <p style="text-align:center;float:none;">"El portal inmobiliario"</p>
        <div id="carousel">
            <?php include "carousel.php"; ?> 
        </div>
    </div>
</div>


