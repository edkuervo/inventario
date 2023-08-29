<?php 
require('head.php');
require('header.php');

// Obtenemos el inventario
$inventario = get_inventario();

// Obtenemos el indice del proveedor y del producto
$i_prov = $_GET['prov'];
$i_prod = $_GET['prod'];

// Verificamos si hay que guardar cambios del producto
$mensaje = update_producto($i_prov,$i_prod);

if ($mensaje !== null) {
	if ($mensaje['tipo'] == 'exito') $inventario = $mensaje['inventario'];
}

// Obtengo el producto
$producto = $inventario['proveedores'][$i_prov]['productos'][$i_prod];

?>

	<article>
		<div class="contenedor">
			
			<?php if ($mensaje !== null) echo '<div class="msj '.$mensaje['tipo'].'">Mensaje: '.$mensaje['mensaje'].'</div>'; ?>

			<div class="a_der"><a href="productos.php?indice=<?php echo $i_prov; ?>" class="btn_simple">Volver atrás</a></div>

			<h1 class="heading">Producto: <?php echo $producto['codigo'].' '.$producto['desc']?></h1>

			<fieldset>
				<legend><strong>&nbsp;Editar producto&nbsp;</strong></legend>
				<form action="producto.php?prov=<?php echo $i_prov ?>&prod=<?php echo $i_prod ?>" method="post" accept-charset="utf-8">
					<div>
							<label for="desc">Descripción: </label>
							<input type="text" id="desc" name="desc" value="<?php echo $producto['desc']?>">
					</div>
					<div>
							<label for="p_dist">Precio de distribuidor: </label>
							<?php $p_dist = number_format($producto['p_dist'],0,',','.'); ?>
							<input type="text" id="p_dist" name="p_dist" value="<?php echo $p_dist; ?>">
					</div>
					<div>
							<label for="p_venta">Precio de venta: </label>
							<?php $p_venta = number_format($producto['p_venta'],0,',','.'); ?>
							<input type="text" id="p_venta" name="p_venta" value="<?php echo $p_venta; ?>">
					</div>
					<div>
							<label for="modalidad">Modalidad: </label>
							<select id="modalidad" name="modalidad" >
								<option value="0">Seleccionar..</option>
								<option value="1" <?php echo $producto['modalidad']=='1'? 'selected="selected"':'' ?>>Consignación</option>
								<option value="2" <?php echo $producto['modalidad']=='2'? 'selected="selected"':'' ?>>Compra</option>
							</select>
					</div>
					<div>
						<button type="submit" class="btn btn_fac">Guardar</button>
					</div>
				</form>
			</fieldset>

		</div> <!-- /contenedor -->
	</article>

	<div class="titulo">
		<div class="contenedor">
			<h2 class="hide_print">Stock: <?php echo $producto['stock']?></h2>
		</div>
	</div>

	<section class="b_blanco">
		<div class="contenedor">
			<!-- Crear un movimiento nuevo -->
			<fieldset>
				<legend><strong>&nbsp;Actualizar stock&nbsp;</strong></legend>
				<form action="producto.php?prov=<?php echo $i_prov ?>&prod=<?php echo $i_prod ?>" method="post" accept-charset="utf-8">
					<div>
							<label for="fecha">Fecha: </label>
							<input type="text" id="fecha" name="fecha" value="<?php echo date('d/m/Y'); ?>">
					</div>
					<div>
							<label for="nota">Observación: </label>
							<input type="text" id="nota" name="nota" >
					</div>
					<div>
							<label for="sale">Sale: </label>
							<input type="number" id="sale" name="sale" min="0" value="0">
					</div>
					<div>
							<label for="entra">Entra: </label>
							<input type="number" id="entra" name="entra" min="0" value="0">
					</div>
					<div>
						<button type="submit" class="btn btn_fac">Guardar</button>
					</div>
				</form>
			</fieldset>

			<!-- Histórico de los movientos de los productos -->
			<h2>Histórico</h2>
			<div class="tabla">
				<div class="cell_strong">
					<div>Fecha</div><div>Sale/n</div><div>Entra/n</div><div>Observación</div>
				</div>
				<?php 
					
					foreach (array_reverse($producto['movs']) as $movto) {
						echo '<div>';
						echo '<div>'.$movto['fecha'].'</div>';
						echo '<div>'.$movto['sale'].'</div>';
						echo '<div>'.$movto['entra'].'</div>';
						echo '<div>'.$movto['nota'].'</div>';
						echo '</div>';
						$indice_prod++;
					}
					
				?>
			</div>
		</div>
	</section>

<?php require('footer.php'); ?>	