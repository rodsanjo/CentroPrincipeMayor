<h2>Modifique los datos de un enlace</h2>
    <?php include "form_and_inputs.php"; ?>

<!-- 
    Con type="hidden" ocultamos el input
    <?php echo \core\Datos::values('titulo', $datos); ?> equivale a <?php echo $datos['values']['autor'] ?> pero con la 
    primera conseguimos que devuelva null en caso de no existir
-->