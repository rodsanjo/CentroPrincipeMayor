<div >
    <h2>Borrar un artículo</h2>
    <?php include "form_and_inputs.php"; ?>
    <script type='text/javascript'>
        var formulario = <?php echo \core\Array_Datos::contenido("form_name", $datos); ?>;
        window.document.getElementById("nombre").readOnly='readonly';
        window.document.getElementById("precio").readOnly='readonly';
        window.document.getElementById("referencia").readOnly='readonly';
        window.document.getElementById("autor").readOnly='readonly';
        window.document.getElementById("editorial").readOnly='readonly';
        window.document.getElementById("anho").readOnly='readonly';
        window.document.getElementById("num_min_jug").readOnly='readonly';
        window.document.getElementById("num_max_jug").readOnly='readonly';
        window.document.getElementById("duracion").readOnly='readonly';
        window.document.getElementById("edad_min").readOnly='readonly';
        window.document.getElementById("foto").readOnly='readonly';
        window.document.getElementById("video").readOnly='readonly';
        window.document.getElementById("manual").readOnly='readonly';
        window.document.getElementById("categoria").readOnly='readonly';
        window.document.getElementById("tematica").readOnly='readonly';
        document.getElementById("resenha").style.display = "block";
        window.document.getElementById("descripcion").readOnly='readonly';
        window.document.getElementById("unds_stock").readOnly='readonly';
        formulario.restablecer.style.display = "none";                
    </script>
</div>