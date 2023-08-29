<?php require('head.php'); ?>

<script type="text/javascript" src="js/informes.js"></script>

<?php require('header.php'); ?>

<?php 
// Verificamos si hay que buscar una factura
$inventario = get_inventario();
$mensaje = informe();
?>

	<article>
		<div class="contenedor">
			
			<?php if ($mensaje !== null) echo '<div class="msj '.$mensaje['tipo'].'">Mensaje: '.$mensaje['mensaje'].'</div>'; ?>

			<h1 class="heading">Informes</h1>
			
			<fieldset>
				<legend><strong>&nbsp;Generar informe&nbsp;</strong></legend>
				<form action="informes.php" method="post" accept-charset="utf-8">
					<div>
						<label for="year">Año: </label>
						<select name="year" id="year">
							<?php for ($i = date('Y') ; $i >= 2019; $i--) {
								echo '<option value="'.$i.'" '.($i == $_POST['year']? 'selected="true"':'').'>'.$i.'</option>';
							} ?>
						</select>
					</div>
					<div>
						<label for="mes">Mes: </label>
						<select name="mes" id="mes">
							<?php for ($i = 1 ; $i <= 12; $i++) {
								echo '<option value="'.$i.'" '.($i == $_POST['mes']? 'selected="true"':'').'>'.$i.'</option>';
							} ?>
						</select>
					</div>
					<div>
							<label for="informe">Informe a generar: </label>
							<select name="informe" id="informe">
								<option value="0">Seleccionar...</option>
								<option value="1" <?php echo ($_POST['informe'] == '1'? 'selected="true"':'');?>>Ventas del mes</option>
								<option value="2" <?php echo ($_POST['informe'] == '2'? 'selected="true"':'');?>>Proveedor por mes</option>
							</select>
					</div>
					<div class="prov_div <?php echo ($_POST['informe'] == '2'? '':'hide');?>">
						<label for="proveedor">proveedor: </label>
						<select name="proveedor" >
							<?php 
								$i= 0;
								foreach ($inventario['proveedores'] as $proveedor) {
									echo '<option value="'.$i.'" '.($i == $_POST['proveedor']? 'selected="true"':'').'>'.$proveedor['nombre'].'</option>';
									$i++;
								} 
							?>
						</select>
					</div>
					
					<div>
						<button type="submit" class="btn btn_fac">Generar</button>
					</div>
				</form>
			</fieldset>
			

		</div> <!-- /contenedor -->
	</article>

	<?php if ($mensaje !== null && $mensaje['tipo'] == 'exito') {?>
	
		<?php 
			$factura = $mensaje['factura']; // Obtengo los datos de la factura 
			$informe = $mensaje['informe']; // Obtengo los datos del informe
		?>

		<div class="titulo">
			<div class="contenedor">
				<h2 class="hide_print">Informe generado</h2>
			</div>
		</div>

		<section class="print">
						
			<div class="contenedor">
				<h1><?php echo $informe['titulo'] ?></h1>
				<?php var_dump($informe['ventas']); ?>
				<?php echo 'Total mes: '.$informe['total_mes']; ?>
			</div> <!-- /contenedor -->
		</section>
	<?php } ?>

<?php require('footer.php'); ?>	