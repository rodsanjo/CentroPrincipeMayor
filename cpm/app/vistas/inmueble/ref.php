<?php
//var_dump($datos);
$fila = $datos['bien'];

$href = \core\URL::generar(array('bienes', 'inmueble', $fila['referencia']));
$title = ((isset($fila['resenha']) and strlen($fila['resenha'])) ? $fila['resenha'] : $fila['referencia']); 

$img = isset($fila["foto"]) ? "<img class='img_cpm img-thumbnail' src='".URL_ROOT."recursos/imagenes/bienes/".$fila["foto"]."' alt='{$fila['referencia']}' title='{$fila['referencia']}' />" :"<img/>";

//Llamamos a una función que convierta el resto de datos a formato de la vista
$v = modelos\inmueble::formatoVistaInmuebleRef($fila);
$texto = \modelos\inmueble::insertarSaltosDeCarro($fila);

//var_dump($v, $fila);
//include URL_HOME_ROOT.'recursos/js/map/map.php';
?>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="<?php echo URL_HOME_ROOT ?>recursos/js/map/geoLocalizacionPosicion.js"></script>
<!--<script type="text/javascript" src="<?php echo URL_HOME_ROOT ?>recursos/js/map/conversionUTMgeo.js"></script>-->

<div id="inmueble">
    <h2></h2>
    <div id="datos_inmueble">
        <div id="top">
            <div class="titulo">
                <h2><?php echo $v['tipo'].' en '.$fila['provincia']; ?></h2>
                <p><?php echo $v['direccion']; ?></p>

            </div>
            
            <ul>
                <li>
                    <?php
                    if( $v['precio_venta'] != '' ){
                        echo "<div class='right_precio'>
                                {$v['precio_venta']}
                            </div>
                            ";
                    }
                    ?>
                </li>
                <li>
                    <?php
                    if( $v['precio_alquiler'] != '' ){
                        echo "<div class='right_precio'>
                            {$v['precio_alquiler']}
                            </div>
                        ";
                    }
                    ?>
                </li>
            </ul>
        </div>
        
        <div id="izquierda">
            <img class='foto_portada img-thumbnail' src='<?php echo URL_ROOT."recursos/imagenes/bienes/".$fila["foto"]; ?>' alt='<?php echo $fila['referencia']; ?>' title='<?php echo "foto ".$fila['referencia']; ?>' onclick="abrirImagenRef('<?php echo $fila['foto']; ?>','<?php echo "foto ".$fila['referencia']; ?>');"/>
            <div id="resenha">
                <p><?php echo $texto['resenha'] ?></p>
            </div>
<!--            <div id="masFotos" class="row">
                <div class="col-sm-6 col-md-3">
                    <a href="#" class="thumbnail">
                        <img data-src="" alt=""/>
                    </a>
                </div>                
            </div>-->
            <div id="mapa">
                <center><div id="mapholder"></div></center>
                <a class="btn btn-primary btn-lg" role="button" id="botonMostrarMapa"  onclick="getLocation();">Mostrar ubicación</a>
                <form name="frmCoord">
<!-- Evento dentro del button: onmousemove="utm_a_LatLon();"
                    <div id="coord_utm">
                        <input  type="hidden" id="utm_x" name="utm_x" value="<?php echo $v['utm_x']; ?>"/>
                        <input  type="hidden" id="utm_y" name="utm_y" value="<?php echo $v['utm_y']; ?>"/>
                        <input  type="hidden" id="huso" name="huso" value="<?php echo $v['huso']; ?>"/>
                        <input  type="hidden" id="hemis" name="hemis" value="<?php echo $v['hemis']; ?>"/>
                    </div>-->
                    <div id="coord_latlon">
                        <input  type="hidden" id="lat" name="lat" value="<?php echo $v['lat']; ?>"/>
                        <input  type="hidden" id="lon" name="lon" value="<?php echo $v['lon']; ?>"/>
                    </div>
                </form>
            </div>
            <button type='button' onclick='window.location.assign("<?php echo \core\URL::generar('bienes/busqueda'); ?>");' class="btn-default boton1">Volver al listado de inmuebles</button>
        </div>
        
        <div id="derecha">
            <p style="font-size:140%;">
                <b>Ref.:</b> <span style="color:green;"><?php echo $fila['referencia']; ?></span>
            </p>
            <p>
                <b>Datos básicos:</b>
                <br/><?php echo !is_null($fila['sup_util']) ? round($fila['sup_util'], 0) : 'Consultar'; ?> m<sup>2</sup>
                <br/>
                <?php 
                    if( $datos['precio_venta'] !=0 && $fila['sup_util'] !=0 ){
                        echo round($datos['precio_venta']/$fila['sup_util'], 1).' €/m&sup2;';
                    }
                ?>
            </p>
            <br/>
            <p>
                <b>Características:</b>
                    <br/>Planta: 
                        <?php 
                        echo
                            $fila['planta']!= '' ?
                                ($fila['planta']!= 0 ? 
                                    ( $fila['planta'] > 0 ? 
                                        $fila['planta'].'º' :
                                        $fila['planta'] ) :
                                    'baja') :
                                '-';
                    if ( isset($datos['detalles']) ){
                        //Vamos a poner un guión en todos los datos nulos:
                        foreach ($datos['detalles'] as $key => $value) {
                            $datos['detalles'][$key] == null ? $datos['detalles'][$key] = '-' : '' ;
                        }
                        echo "<br/>Año de construcción: ".$datos['detalles']['anho_const'];
                        if($fila['tipo'] == 'v'){ ?>
                            <br/><?php echo $datos['detalles']['tipo_bien'][0]['tipo']; ?>
                            <br/>Nº habitaciones: <?php echo $datos['detalles']['num_hab']; ?> 
                            <br/>Nº cuartos de baños: <?php echo $datos['detalles']['num_banhos']; ?>
                            
                        <?php
                        }else if($fila['tipo'] == 'g'){ ?>
                            <br/><?php echo $datos['detalles']['m_largo']; ?> m de largo
                            <br/><?php echo $datos['detalles']['m_ancho']; ?> m de ancho
                            <br/>Superficie total: <?php echo round( $datos['detalles']['m_ancho']*$datos['detalles']['m_largo'], 1 ); ?> m<sup>2</sup>
                        <?php } ?>
                <br/>
            </p>
                    <?php
                    }
                    ?>
            <div id="formulario">
                <?php
                    include PATH_APPLICATION_APP."vistas/inmueble/form_comentario.php";
                ?>
            </div>
            <br/><br/>
            <div id="accionesAdmin">
                <?php
                echo \core\HTML_Tag::a_boton_onclick("botonAdmin", array("bienes", "form_modificar", $fila['id']), "Modificar inmueble", array("title" => "Modificar inmueble"))."<br/><br/>";
                echo \core\HTML_Tag::a_boton_onclick("botonAdmin", array("bienes", "form_borrar", $fila['id']), "Borrar inmueble", array("title" => "Borrar inmueble"))."<br/><br/>";
                echo \core\HTML_Tag::a_boton_onclick("botonAdmin", array("inmueble", "form_anhadir_detalles", $fila['id'], $fila['tipo']), "Añadir detalles inmueble", array("title" => "Añadir detalles inmueble"));
                ?>
            </div>
        </div>
    </div>
</div>

<script type='text/javascript'>
    function abrirImagenRef(nombre_foto,titulo){
        //url = 'http://localhost/web/CentroPrincipeMayor/cpm/recursos/imagenes/bienes/' + nombre_foto;
        //url = '../../../recursos/imagenes/bienes/' + nombre_foto;
        url = '<?php echo URL_ROOT ?>recursos/imagenes/bienes/' + nombre_foto;
        //alert('hola');
        ventana2=window.open(url,titulo,'width=500, height=600');
        ventana2.window.moveTo(400,100);
        ventana2.window.focus();
    }
</script>