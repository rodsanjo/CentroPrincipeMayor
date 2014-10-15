<div id="inmuebles">
    <div id="colum_izq">
        <div id="busqueda_mini">
            <?php include "form_buscar_mini.php"; ?>
            <div class="acciones">
            <?php
                echo \core\HTML_Tag::a_boton("botonAdmin", array("bienes", "form_insertar"), "insertar un nuevo inmueble", array("title" => "insertar un nuevo inmueble"));
            ?>
            </div>
        </div>
    </div>
    <div id="resultado_busqueda">
        <?php
        $num_inmuebles = count($datos['bienes']);
        if( $num_inmuebles == 0 ){
            echo "
                <p>Lo sentimos, no disponemos de inmuebles que se correspondan con la busqueda seleccionada.</p>
                ";
        }else{
            echo "<p>$num_inmuebles inmuebles encontrados.</p>
                ";
            ?>
            <form method='post' action='<?php echo \core\URL::generar("busqueda/inmueblesssssssss"); ?>' class="form_derecha">
                <select id='ordenar_por' name="ordenar_por" onchange="ordenar_por(this.value);" >
                    <option value='precio_venta' >Precio ascendente</option>
                    <option value='precio_venta desc' >Precio descendente</option>
                    <option value='id desc' selected='selected'>Recientes</option>
                </select>  
                <input type="submit" value="Ordenar" title="Ordenar por" />
            </form>
            <?php
            echo"
                <div>
                ";
            foreach ($datos['bienes'] as $key => $fila) {
                $href = \core\URL::generar(array('bienes', 'inmueble', $fila['referencia']));
                $title = ((isset($fila['resenha']) and strlen($fila['resenha'])) ? $fila['resenha'] : $fila['referencia']); 
                
                $img = isset($fila["foto"]) ? "<img class='img_cpm' src='".URL_ROOT."recursos/imagenes/bienes/".$fila["foto"]."' alt='{$fila['referencia']}' title='{$fila['referencia']}' />" :"<img/>";

                //Llamamos a una función que convierta el resto de datos que usaremos a formato de la vista
                $v = modelos\bienes::formatoVistaBusquedaInmuebles($fila);

                echo "
                    <a class='inmueble' href='$href' title='$title'>
                        <div class='direccion'>
                            $img
                            <span>{$v['direccion']}</span>
                        </div>
                        <div class='datos'>
                            {$v['tipo']} en {$fila['provincia']}
                            <br/>{$v['tipo_operacion']}
                        </div>
                        <div class='right-precio'>
                            {$v['precio_venta']}<br/>
                            {$v['precio_alquiler']}
                        </div>
                    </a>
                    "
                            ;
                echo \core\HTML_Tag::a_boton_onclick("botonAdmin", array("bienes", "form_modificar", $fila['id']), "Modificar inmueble", array("title" => "Modificar inmueble"));
                echo \core\HTML_Tag::a_boton_onclick("botonAdmin", array("bienes", "form_borrar", $fila['id']), "Borrar inmueble", array("title" => "Borrar inmueble"));
            }
            echo "</div>";
        }
        ?>
    </div>
</div>
