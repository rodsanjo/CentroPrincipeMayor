<div>
    <h2>Modificar inmueble: <i><?php echo \core\Array_Datos::values('nombre', $datos); ?></i></h2>
    <?php include "form_and_inputs.php"; ?>
    <script type='text/javascript'>
        window.document.getElementById("referencia").readOnly='readonly';                
    </script>
</div>