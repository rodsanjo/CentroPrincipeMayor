<div >
    <h2>Introducir detalles del inmueble</h2>
    <?php include "form_and_inputs_detalles.php"; ?>
    <script type='text/javascript'>
//        window.document.getElementById("referencia").type='hiden';
        var formulario = <?php echo \core\Array_Datos::contenido("form_name", $datos); ?>;
//        formulario.restablecer.type = "hidden"; 
    </script>
</div>