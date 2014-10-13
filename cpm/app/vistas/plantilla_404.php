<html>
    <head>
        <?php 
            include PATH_APPLICATION_APP."vistas/zonas/head.php";
        ?>
        <link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT ?>recursos/css/notfound.css" />
    </head>
    <body style='border: 1px solid; width: 80%; margin-left: auto; margin-right: auto;  padding: 5px;'>
        <h1>Error: Documento no encontrado</h1>
        <?php 
                if (isset($datos['mensaje']))
                //	echo "<p>{$datos['mensaje']}</p>";
        ?>
        <img title="No encontrado" src="<?php echo URL_ROOT ?>recursos/imagenes/notFound.jpg" alt="No encontrado"/>
    </body>
</html>
