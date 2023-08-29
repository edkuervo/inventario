<?php 
require('head.php');
require('header.php');
?>		

	<article>
		<div class="contenedor">
			
			<h1 class="heading">Inventario</h1>
			<?php 
				// Obtenemos los proveedores
				$proveedores = get_proveedores();
				
				foreach ($proveedores as $prove) { ?>
					<div class="head_table">
						<h3>Proveedor: <?php echo $prove[nombre]; ?></h3>
					</div>
					
					<?php // obtengo los productos del proveedor
					$productos = $prove['productos'];
					if (count($productos) > 0) { ?>
						<div class="tabla">
							<div class="cell_strong">
								<div>Código de producto</div><div>Descripción</div><div>Precio distribuidor</div><div>Precio venta</div><div>Stock</div>
							</div>
						
							<?php // Mostramos cada uno de los productos de este proveedor
							foreach ($productos as $prod) {
								echo '<div>';
								echo '<div>'.$prod['codigo'].'</div>';
								echo '<div>'.$prod['desc'].'</div>';
								echo '<div>'.$prod['p_dist'].'</div>';
								echo '<div>'.$prod['p_venta'].'</div>';
								echo '<div>'.$prod['stock'].'</div>';
								echo '</div>';
							} ?>
						</div>
					<?php 
					}else{
						
						echo '<div class="tabla"><div><div>No tiene productos registrados</div></div></div>';
					}
				}
				// var_dump($proveedores);
			?>
		</div>
		
	</article><!-- article -->
		
<?php require('footer.php'); ?>	