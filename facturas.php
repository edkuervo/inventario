<?php require('head.php'); ?>

<script type="text/javascript" src="js/facturas.js"></script>

<?php require('header.php'); ?>

<?php 
// Obtenemos el inventario
$inventario = get_inventario();

// Verificamos si hay que guardar un producto nuevo
$mensaje = new_factura();
?>

	<article class="hide_print">
		<div class="contenedor conte_fac">
			
			<?php if ($mensaje !== null) echo '<div class="msj '.$mensaje['tipo'].'">Mensaje: '.$mensaje['mensaje'].'</div>'; ?>

			<h1 class="heading">Facturas</h1>
			
			<fieldset>
				<legend><strong>&nbsp;Nueva factura&nbsp;</strong></legend>
				<form action="facturas.php?" method="post" accept-charset="utf-8" class="form_fac">
					
					<div><input type="text" readonly="true" id="fecha" name="fecha" class="fecha a_center"/></div>
					
					<div class="f_der">
						Productos: <input type="number" value="1" name="cant_p" class="cant_p a_der simple" readonly="true" />
					</div>
					<div class="f_izq">
						No: <input type="text" id="fact" name="fact" value="<?php echo $inventario['autonum'] ?>" class="fact simple" readonly="true">
					</div>
					
					<div class="tabla">
						<div class="a_center">
							<div class="w_M">Código</div><div>Descripción</div><div class="w_S">Cant.</div>
							<div class="w_M">Precio</div><div class="w_M">Total</div>
						</div>
						<div>
							<div>
								<input type="text" id="codigo1" name="codigo1" class="upper c_codigo" required="true" autofocus="true">
							</div>
							<div>
								<input type="text" id="desc1" name="desc1" readonly="true" tabindex="-1">
							</div>
							<div>
								<input type="number" id="cant1" name="cant1" required="true" class="c_cant a_der" min="0" max="0" value="0">
							</div>
							<div>
								<input type="text" id="precio1" name="precio1" class="a_der" tabindex="-1">
							</div>
							<div>
								<input type="text" id="total1" name="total1" readonly="true" class="c_total a_der" value="0" tabindex="-1">
							</div>
						</div>
						<?php for ($i = 2; $i <=20 ; $i++) { ?>
							
							<div id="prod<?php echo $i ?>" class="hidden">
								<div>
									<input type="text" id="codigo<?php echo $i ?>" name="codigo<?php echo $i ?>" class="upper c_codigo" >
								</div>
								<div>
									<input type="text" id="desc<?php echo $i ?>" name="desc<?php echo $i ?>" readonly="true" tabindex="-1">
								</div>
								<div>
									<input type="number" id="cant<?php echo $i ?>" name="cant<?php echo $i ?>" class="c_cant a_der" min="0" max="0" value="0">
								</div>
								<div>
									<input type="text" id="precio<?php echo $i ?>" name="precio<?php echo $i ?>" class="a_der" tabindex="-1">
								</div>
								<div>
									<input type="text" id="total<?php echo $i ?>" name="total<?php echo $i ?>" readonly="true" class="c_total a_der" value="0" tabindex="-1">
									<button type="button" value="<?php echo $i ?>" class="hide_print btn btn_minus">x</button>
								</div>
							</div>
						<?php } ?>

					</div> <!-- /tabla -->

					<div>
						<button type="button" class="btn btn_add hide_print">+ Otro producto</button>
					</div>
							
					<div class="a_der">
						Total: <input type="text" name="total_fac" value="" class="total_fac a_der" readonly="true" tabindex="-1">
					</div>		
					
					<div>
						<button type="submit" class="btn btn_fac">Finalizar factura</button>
					</div>
					
				</form>
			</fieldset>
			

		</div> <!-- /contenedor -->
	</article>
	
	<div class="titulo">
		<div class="contenedor">
			<h2 class="hide_print">Última factura</h2>
		</div>
	</div>

	<section class="print">
		<div class="contenedor">
			
			<div class="btn_print hide_print a_center">
				<button class="btn" onclick="javascript:window.print()" >Imprimir</button>
			</div>
			
		</div>
		<?php 
			$last_fact = $inventario['autonum'] - 1; 
			$clave = array_search($last_fact, array_column($inventario['facturas'], 'no_fac'));
			$factura = $inventario['facturas'][$clave];
		?>
		<div class="last_fac contenedor">
			<h2 class="a_center">TRES ALMAS</h2>
			<p>No: <?php echo $factura['no_fac'] ?></p>
			<p><strong>Fecha: </strong><?php echo $factura['fecha']; ?>
			</p>
			<p><strong>Nit: </strong>39 456 959</p>
			<p><strong>Dirección: </strong>Complex Llanogrande, local 65, Rionegro</p>
			<p><strong>Teléfono: </strong>304 218 2838</p>
			<p>@tresalmasco</p>
			<p>&nbsp;</p>
			
			<div class="tabla">
				<div>
					<div class="w1">DESCRIPCIÓN</div>
					<div class="w2 a_der">CANT</div>
					<div class="a_der">TOTAL</div>
				</div>
				<?php foreach ($factura['productos'] as $producto) { ?>
					<div>
						<div><?php echo $producto['producto'] ?></div>
						<div class="a_center"><?php echo $producto['cant'] ?></div>
						<div class="a_der"><?php echo number_format($producto['total'],0,',','.') ?></div>
					</div>
					
				<?php } ?>
			</div>
			<div class="total a_der">
				TOTAL: <?php echo number_format($factura['totalFac'],0,',','.') ?>
			</div>
			
			<p class="gracias a_center">
				¡Gracias por tu compra!
			</p>
			<p class="a_center gris">_</p>
		</div> <!-- /contenedor -->
	</section>

<?php require('footer.php'); ?>	