<div >
    <h2>Borrar inmueble</h2>
    <?php include "form_and_inputs.php"; ?>
    <script type='text/javascript'>
        $(" [type=reset] ").css("display", "none");
        $(" [type=submit] ").value("Borrar");
        
        var formulario = <?php echo \core\Array_Datos::contenido("form_name", $datos); ?>;
        window.document.getElementById("tipo").readOnly='readonly';
        window.document.getElementById("tipo_via_id").readOnly='readonly';
        window.document.getElementById("nombre_via").readOnly='readonly';
        window.document.getElementById("num_portal").readOnly='readonly';
        window.document.getElementById("portal_bloque").readOnly='readonly';
        window.document.getElementById("anho").readOnly='readonly';
        window.document.getElementById("planta").readOnly='readonly';
        window.document.getElementById("puerta").readOnly='readonly';
        window.document.getElementById("cp").readOnly='readonly';
        window.document.getElementById("localidad").readOnly='readonly';
        window.document.getElementById("provincia").readOnly='readonly';
        window.document.getElementById("pais").readOnly='readonly';
        window.document.getElementById("superficie").readOnly='readonly';
        window.document.getElementById("precio_venta").readOnly='readonly';
        window.document.getElementById("precio_alquiler").readOnly='readonly';
        window.document.getElementById("coord_utm_x").readOnly='readonly';
        window.document.getElementById("coord_utm_y").readOnly='readonly';
        
        window.document.getElementById("resenha").readOnly='readonly';
        document.getElementById("resenha").style.display = "none";

        function modificar_permisos() {
                $(" [type=checkbox] ").removeAttr("disabled");
                $(" [type=submit], [type=reset], [type=button], button#btn_checked_all ").css("display", "inline");
                $(" button#btn_modificar, button#btn_cancelar ").css("display", "none");
        }

        function chequear_todo() {
                $(" [type=checkbox] ").attr("checked", "checked");

        }
        
    </script>
</div>