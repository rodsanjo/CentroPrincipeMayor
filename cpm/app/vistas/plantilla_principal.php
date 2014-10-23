<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php 
        include PATH_APPLICATION_APP."vistas/zonas/head.php";
    ?>   
</head>
<body onload='onload();'>
    <div class="container">
        <div id="encabezado" class="teu">
            <div id="sendero_migas_pan">
                <?php echo \controladores\sendero::ver(); ?>
            </div>
            <?php 
                //include PATH_APPLICATION_APP."vistas/zonas/encabezado.php";
            ?>
        </div>
        <div id="menu_up">
            <?php 
                include PATH_APPLICATION_APP."vistas/zonas/menu_up.php";
            ?>		
        </div>
    </div>
    <div class="container">
        <div class="teu">
            <div id="view_content">
                <?php
                    echo $datos['view_content'];
                ?>
            </div>

            <div class="pie">
                <?php 
                    include PATH_APPLICATION_APP."vistas/zonas/pie.php";
                ?>
            </div>
       </div> 
    </div>

    <div id="piej">
            <hr/>
            <div id="conexion">
                <?php 
                    include PATH_APPLICATION_APP."vistas/zonas/form_login.php";
                ?>  
            </div>            
            <div>
                &copy; CentroPrincipeMayor<br/>
                <?php echo \core\Idioma::text("Diseñada por", "dicc"); ?> <a href="mailto:jergo23@gmail.com" style="color:yellow">Jergo</a>
            </div>
            
    </div>
    
    
    <?php echo \core\HTML_Tag::post_request_form(); ?>
		
		
    <script type="text/javascript" />
            var alerta;
            function onload() {
                    visualizar_alerta();
            }

            function visualizar_alerta() {
                    if (alerta != undefined) {
                            $("body").css("opacity","0.3").css("filter", "alpha(opacity=30)");
                            alert(alerta);
                            alerta = undefined;
                            $("body").css("opacity","1.0").css("filter", "alpha(opacity=100)");
                    }
            }

    </script>
    
<?php
if (isset($_SESSION["alerta"])) {
    echo <<<heredoc
<script type="text/javascript" />
    //alert("{$_SESSION["alerta"]}");
    var alerta = '{$_SESSION["alerta"]}';
</script>
heredoc;
    unset($_SESSION["alerta"]);
}
elseif (isset($datos["alerta"])) {
    echo <<<heredoc
<script type="text/javascript" />
    // alert("{$datos["alerta"]}");
    var alerta = '{$datos["alerta"]}';
</script>
heredoc;
}
?>	
	
<div id='globals'>
    <?php
//        var_dump($datos);
//        print "<pre>"; 
////          print_r($GLOBALS);
//          print("\$_GET "); print_r($_GET);
//          print("\$_POST ");print_r($_POST);
////          print("\$_COOKIE ");print_r($_COOKIE);
////          print("\$_REQUEST ");print_r($_REQUEST);
////          print("\$_SESSION ");print_r($_SESSION);
////          print("\$_SERVER ");print_r($_SERVER);
//        print "</pre>";
//            print("xdebug_get_code_coverage() ");
//            var_dump(xdebug_get_code_coverage());
    ?>
</div>
    
</body>
</html>