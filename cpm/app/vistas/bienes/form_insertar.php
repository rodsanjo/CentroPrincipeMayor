
<div >
    <h2>Introducir un nuevo inmueble</h2>
    <?php include "form_and_inputs.php"; ?>
    <script type='text/javascript'>
//        window.document.getElementById("referencia").type='hiden';
        var formulario = <?php echo \core\Array_Datos::contenido("form_name", $datos); ?>
//        formulario.restablecer.type = "hidden"; 
    </script>
</div>