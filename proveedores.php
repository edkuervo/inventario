<?php 
require('head.php');
require('header.php');

// Obtenemos el inventario
$inventario = get_inventario();

// Verificamos si hay que guardar un proveedor nuevo
$mensaje = new_proveedor();


?>

	<article>
		<div class="contenedor">
			
			<?php if ($mensaje !== null) echo '<div class="msj '.$mensaje['tipo'].'">Mensaje: '.$mensaje['mensaje'].'</div>'; ?>

			<h1 class="heading">Proveedores</h1>
			<?php 
				echo '<ul class="tres_cols">';
				$indice = 0;
				foreach ($inventario['proveedores'] as $prove) {
					echo '<li><a href="productos.php?indice='.$indice.'" class="btn_simple">'.$prove['nombre'].' ('.$prove['cod_prov'].')</a></li>';
					$indice++;
				}
				echo '</ul>';
			?>

			<fieldset>
				<legend><strong>&nbsp;Nuevo proveedor&nbsp;</strong></legend>
				<form action="proveedores.php" method="post" accept-charset="utf-8">
					<div>
							<label for="nombre">Nombre: </label>
							<input type="text" name="nombre" id="nombre" required="true">
					</div>
					<div>
							<label for="nombre">Código de proveedor: </label>
							<input type="text" name="cod_prov" id="cod_prov" class="upper" required="true">
					</div>
					<div>
						<button type="submit" class="btn btn_fac">Guardar</button>
					</div>
				</form>
			</fieldset>
			

		</div> <!-- /contenedor -->
	</article>

<?php require('footer.php'); ?>	