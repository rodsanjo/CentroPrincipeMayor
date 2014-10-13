<div>
	<!-- formulario  post_reques_form utilizado para enviar peticiones por post al servidor y evitar que el usuario modifique/juegue con los parámetros modificando la URI mostrada  -->
	
	<h1 id="titulo_seccion">Listado de usuarios</h1>
	<table class='resultados' border='3,#000000' cellspacing='0' cellpadding='10' summary="Listado de usuarios">
		<thead>
			<tr>
				<th>login</th>
				<th>email</th>
                                <th>fecha alta <br/> fecha confirmación</th>				
				<th>acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($datos['filas'] as $fila)
			{
                            echo "
                                <tr>
                                    <td>{$fila['login']}</td>
                                    <td>{$fila['email']}</td>		
                                    <td>{$fila['fecha_alta']}<br/>{$fila['fecha_confirmacion_alta']}</td>	
                                    <td><center>"
//							<a class='boton1' onclick='submit_post_request_form(\"".\core\URL::generar("usuarios/form_modificar")."\", {$fila['id']});' >modificar</a>
                                        .\core\HTML_Tag::a_boton("boton1", array("usuarios", "form_modificar", $fila['id']), "modificar")."<br/>"
//							."<a class='boton1' onclick='submit_post_request_form(\"".\core\URL::generar("usuarios/form_borrar")."\", {$fila['id']});' >borrar</a>"
                                        .\core\HTML_Tag::a_boton("boton1", array("usuarios", "form_borrar", $fila['id']), "borrar")."<br/>"
//							<a class='boton1' onclick='submit_post_request_form(\"".\core\URL::generar("usuarios/form_cambiar_password")."\", {$fila['id']});' >modificar password</a>
                                        .\core\HTML_Tag::a_boton("boton1", array("usuarios_permisos", "index", $fila['login']), "permisos&nbsp;asignados")."<br/>"
                                        .\core\HTML_Tag::a_boton("boton1", array("usuarios_roles", "index", $fila['login']), "roles&nbsp;asignados").
                                    "</center></td>
                                </tr>
                                ";
			}
			echo "
				<tr>
					<td colspan='3'></td>
						<td><center>".\core\HTML_Tag::a_boton("boton", array("usuarios", "form_insertar_interno"), "Insertar usuario")."</center></td>
				</tr>
			";
			?>
		</tbody>
	</table>
	<?php //print("<a class='boton' href='{$datos["url_volver"]}' >volver</a>"); ?>
</div>