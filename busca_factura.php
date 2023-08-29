<?php require('head.php'); ?>
<?php require('header.php'); ?>

<?php 
// Verificamos si hay que buscar una factura
$mensaje = busca_factura();
?>

	<article class="hide_print">
		<div class="contenedor">
			
			<?php if ($mensaje !== null) echo '<div class="msj '.$mensaje['tipo'].'">Mensaje: '.$mensaje['mensaje'].'</div>'; ?>

			<h1 class="heading">Buscar factura</h1>
			
			<fieldset>
				<legend><strong>&nbsp;Buscar factura&nbsp;</strong></legend>
				<form action="busca_factura.php" method="post" accept-charset="utf-8">
					<div>
							<label for="factura">No factura: </label>
							<input type="text" id="factura" name="factura" required="true" 
								<?php echo $_POST['factura']? 'value="'.$_POST['factura'].'"':''; ?>
							>
					</div>
					
					<div>
						<button type="submit" class="btn btn_fac">Buscar</button>
					</div>
				</form>
			</fieldset>
			

		</div> <!-- /contenedor -->
	</article>

	<?php if ($mensaje !== null && $mensaje['tipo'] == 'exito') {?>
	
		<?php $factura = $mensaje['factura']; // Obtengo los datos de la factura ?>

		<div class="titulo">
			<div class="contenedor">
				<h2 class="hide_print">Factura <?php echo $factura['no_fac']; ?></h2>
			</div>
		</div>

		<section class="print">
			<div class="contenedor">
				
				<div class="btn_print hide_print a_center">
					<button class="btn" onclick="javascript:window.print()" >Imprimir</button>
				</div>
				
			</div>
			
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
	<?php } ?>

<?php require('footer.php'); ?>	