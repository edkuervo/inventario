<?php 
require('head.php');
require('header.php');

// Obtenemos el inventario
$inventario = get_inventario();
// Obtenemos el indice del proveedor
$indice = $_GET['indice'];


// Verificamos si hay que guardar un producto nuevo
$mensaje = new_producto($indice);

if ($mensaje !== null) {
	if ($mensaje['tipo'] == 'exito') $inventario = $mensaje['inventario'];
}

$proveedor = $inventario['proveedores'][$indice];
$codigo = $proveedor['cod_prov'];
$auto_prov = $proveedor['auto_prov'];
$codigo .=  $auto_prov < 10? '0'.$auto_prov:$autoprov;

?>

	<article>
		<div class="contenedor">
			
			<?php if ($mensaje !== null) echo '<div class="msj '.$mensaje['tipo'].'">Mensaje: '.$mensaje['mensaje'].'</div>'; ?>

			<div class="a_der"><a href="proveedores.php" class="btn_simple">Volver atrás</a></div>

			<h1 class="heading">Productos - <?php echo $proveedor['nombre']; ?></h1>

			<fieldset>  <!-- Editar proveedor -->
				<legend><strong>&nbsp;Editar proveedor&nbsp;</strong></legend>
				<form action="productos.php?indice=<?php echo $indice ?>" method="post" accept-charset="utf-8">
					<div>
							<label for="nombre">Nombre: </label>
							<input type="text" name="nombre" id="nombre" required="true" value="<?php echo $proveedor['nombre']; ?>">
					</div>
					<?php if ($proveedor['cod_prov'] == '') { ?>
						<div>
								<label for="nombre">Código de proveedor: </label>
								<input type="text" name="cod_prov" id="cod_prov" class="upper" required="true">
						</div>
					<?php }else { ?>
						<input type="hidden" name="cod_prov" id="cod_prov" value="<?php echo $proveedor['cod_prov']; ?>">
					<?php } ?>
					<div>
						<button type="submit" class="btn btn_fac">Guardar</button>
					</div>
				</form>
			</fieldset>

			<fieldset> <!-- Crear producto nuevo -->
				<legend><strong>&nbsp;Nuevo producto&nbsp;</strong></legend>
				<form action="productos.php?indice=<?php echo $indice ?>" method="post" accept-charset="utf-8">
					<div>
							<label for="codigo">Código: </label>
							<input type="text" id="codigo" name="codigo" readonly="true" value="<?php echo $codigo; ?>">
					</div>
					<div>
							<label for="desc">Descripción: </label>
							<input type="text" id="desc" name="desc" >
					</div>
					<div>
							<label for="p_dist">Precio de distribuidor: </label>
							<input type="text" id="p_dist" name="p_dist" >
					</div>
					<div>
							<label for="p_venta">Precio de venta: </label>
							<input type="text" id="p_venta" name="p_venta" >
					</div>
					<div>
							<label for="stock">Stock inicial: </label>
							<input type="text" id="stock" name="stock" >
					</div>
					<div>
							<label for="modalidad">Modalidad: </label>
							<select id="modalidad" name="modalidad" >
								<option value="0">Seleccionar..</option>}
								<option value="1">Consignación</option>
								<option value="2">Compra</option>
							</select>
					</div>
					<div>
						<button type="submit" class="btn btn_fac">Guardar</button>
					</div>
				</form>
			</fieldset>

			<div class="tabla">
				<div class="cell_strong">
					<div>Producto</div><div class="a_center">Precio distribuidor</div>
					<div class="a_center">Precio venta</div><div class="a_center">Stock</div>
					<div class="a_center">Modalidad</div>
				</div>
				<?php 
					
					$indice_prod = 0;
					foreach ($proveedor['productos'] as $producto) {
						switch ($producto['modalidad']) {
							case '1':
								$modalidad = 'Consignación';
								break;
							case '2':
								$modalidad = 'Compra';
								break;
							default:
								$modalidad = '-';
								break;
						}
						echo '<div>';
						echo '<div><a href="producto.php?prov='.$indice.'&prod='.$indice_prod.'">'.$producto['codigo'].' '.$producto['desc'].'</a></div>';
						echo '<div class="a_der">'.number_format($producto['p_dist'],0,',','.').'</div>';
						echo '<div class="a_der">'.number_format($producto['p_venta'],0,',','.').'</div>';
						echo '<div class="a_der">'.number_format($producto['stock'],0,',','.').'</div>';
						echo '<div class="a_der">'.$modalidad.'</div>';
						echo '</div>';
						$indice_prod++;
					}
					
				?>
			</div>
			
		</div> <!-- /contenedor -->
	</article>

<?php require('footer.php'); ?>	