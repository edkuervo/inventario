<?php 

// +++++++++++++++++++++++++++++++++++++++++++++++++ Get Inventario
function get_inventario() {
	// Leemos el Json
	$inv_json = file_get_contents("inventario.json");
	
	// Convertimos el json a array
	$inventario = json_decode($inv_json, true);
	
	return $inventario;
}

// +++++++++++++++++++++++++++++++++++++++++++++++++ Get Proveedores
function get_proveedores() {
	// Obtenemos el inventario
	$inventario = get_inventario();
	
	// Obtenemos los proveedores
	$proveedores = $inventario['proveedores'];
	return $proveedores;
}

// +++++++++++++++++++++++++++++++++++++++++++++++++ New Proveedor
function new_proveedor() {
	if ($_POST['nombre']) {
		$nombre = $_POST['nombre'];
		$cod_prov = strtoupper($_POST['cod_prov']);

		global $inventario;

		// Busco si ya está el nombre del proveedor
		$clave = array_search(strtolower($nombre), array_map(strtolower, array_column($inventario['proveedores'], 'nombre')));

		// Si no está el nombre del proveedor en el json busco el codigo del proveedor
		if ($clave === false) {
			// Busco el código del proveedor
			$clave = array_search($cod_prov, array_column($inventario['proveedores'], 'cod_prov'));

			// Si no encuentro el código del proveedor, procedo a guardar
			if ($clave === false) {
				$inventario['proveedores'][] = array(
					'nombre'=>$nombre, 
					'cod_prov'=>$cod_prov, 
					'auto_prov'=>1, 
					'productos'=>[]
				);
				 
				$json_string = json_encode($inventario);
				file_put_contents('inventario.json', $json_string);

				$respuesta = array('mensaje'=>'Proveedor guardado', 'tipo'=>'exito');
			}else{
				$respuesta = array('mensaje'=>'Ya existe el código '.$cod_prov, 'tipo'=>'fallo');
			}

		}else{
			$respuesta = array('mensaje'=>'Ya existe el proveedor '.$nombre, 'tipo'=>'fallo');
		}
	}else{
		$respuesta = null;
	}
	return $respuesta;
}

// +++++++++++++++++++++++++++++++++++++++++++++++++ New Producto
function new_producto($indice) {
	
	if ($_POST['codigo']) {
		$codigo = strtoupper($_POST['codigo']);
		$desc = $_POST['desc'];
		$p_dist = (int)$_POST['p_dist'];
		$p_venta = (int)$_POST['p_venta'];
		$stock = (int)$_POST['stock'];
		$modalidad = $_POST['modalidad'];

		global $inventario;
		//busco el codigo por si ya existe
		$clave = array_search($codigo, array_column($inventario['proveedores'][$indice]['productos'], 'codigo'));

		if ($clave === false) {
			$inventario['proveedores'][$indice]['productos'][] = array(
				'codigo'=>$codigo, 
				'desc'=>$desc, 
				'p_dist'=>$p_dist, 
				'p_venta'=>$p_venta, 
				'stock'=>$stock, 
				'modalidad'=>$modalidad,
				'movs'=>[]
			);
			$inventario['proveedores'][$indice]['auto_prov']++;
			 
			$json_string = json_encode($inventario);
			file_put_contents('inventario.json', $json_string);

			$respuesta = array('mensaje'=>'Producto guardado', 'tipo'=>'exito', 'inventario'=>$inventario);
		}else{
			$respuesta = array('mensaje'=>'El código '.$codigo.' ya existe', 'tipo'=>'fallo');
		}
	}elseif ($_POST['nombre']) {  // Editar proveedor
		$nombre = $_POST['nombre'];
		$cod_prov = strtoupper($_POST['cod_prov']);

		global $inventario;

		// Busco si ya está el nombre del proveedor
		$clave = array_search(strtolower($nombre), array_map(strtolower, array_column($inventario['proveedores'], 'nombre')));

		// Si no está el nombre del proveedor en el json busco el codigo del proveedor
		if ($clave === false || ( $clave !== false && $clave == $indice )) {
			// Busco el código del proveedor
			$clave = array_search($cod_prov, array_column($inventario['proveedores'], 'cod_prov'));

			// Si no encuentro el código del proveedor, procedo a guardar
			if ($clave === false || ( $clave !== false && $clave == $indice )) {
				$inventario['proveedores'][$indice]['nombre'] = $nombre;
				$inventario['proveedores'][$indice]['cod_prov'] = $cod_prov;
				 
				$json_string = json_encode($inventario);
				file_put_contents('inventario.json', $json_string);

				$respuesta = array('mensaje'=>'Proveedor guardado', 'tipo'=>'exito', 'inventario'=>$inventario);
			}else{
				$respuesta = array('mensaje'=>'Ya existe el código '.$cod_prov, 'tipo'=>'fallo');
			}

		}else{
			$respuesta = array('mensaje'=>'Ya existe el proveedor '.$nombre, 'tipo'=>'fallo');
		}
	}else{
		$respuesta = null;
	}
	return $respuesta;
}

// +++++++++++++++++++++++++++++++++++++++++++++++++ New Factura
function new_factura() {
	
	if ($_POST['fecha']) {
		$fact = $_POST['fact'];
		$fecha = $_POST['fecha'];
		$totalFac = (int)str_replace('.', '', $_POST['total_fac']);
		$cant_p = $_POST['cant_p'];
		
		global $inventario;
		//busco la factura por si ya existe
		$clave = array_search($fact, array_column($inventario['facturas'], 'no_fac'));
		
		if ($clave === false) {
			$productos = array();

			// llevar los productos a un array
			for ($i = 1; $i <= $cant_p ; $i++) {
				$codigo = strtoupper($_POST['codigo'.$i]);
				$producto = strtoupper($_POST['codigo'.$i]).' '.$_POST['desc'.$i];
				$cant = (int)$_POST['cant'.$i];
				$total = (int)str_replace('.', '', $_POST['total'.$i]);
				$productos[] = array('producto'=>$producto, 'cant'=>$cant, 'total'=>$total);

				//Descontar del inventario y guardar el movimiento
				for ($j = 0; $j < count($inventario['proveedores']) ; $j++) {
					// Busco la clave del producto
					$clave = array_search($codigo, array_column($inventario['proveedores'][$j]['productos'], 'codigo'));
					
					if ($clave !== false) {
						$stock = $inventario['proveedores'][$j]['productos'][$clave]['stock'];
						$inventario['proveedores'][$j]['productos'][$clave]['stock'] = $stock - $cant;
						$inventario['proveedores'][$j]['productos'][$clave]['movs'][] = array('fecha'=>$fecha, 'sale'=>$cant, 'entra'=>0, 'nota'=>'Venta');
						break;
					}
				}
			}

			// Llevar los datos de la factura al array del inventario
			$inventario['facturas'][] = array('no_fac'=>$fact, 'fecha'=>$fecha, 'totalFac'=>$totalFac, 'productos'=>$productos);

			
			// Aumento el numero de la factura
			$inventario['autonum']++;

			// Guardo el archivo json
			$json_string = json_encode($inventario);
			file_put_contents('inventario.json', $json_string);

			$respuesta = array('mensaje'=>'Factura registrada', 'tipo'=>'exito');
		}else{
			$respuesta = array('mensaje'=>'Ya está registrada la factura '.$fact, 'tipo'=>'fallo');
		}
	}else{
		$respuesta = null;
	}
	return $respuesta;
}


// +++++++++++++++++++++++++++++++++++++++++++++++++ Edit producto
function update_producto($i_prov, $i_prod) {
	
	if ($_POST['fecha']) { // nuevo movimiento
		$fecha = $_POST['fecha'];
		$sale = $_POST['sale'];
		$entra = $_POST['entra'];
		$nota = $_POST['nota'];

		global $inventario;

		// pongo el nuevo movimiento en el array
		$inventario['proveedores'][$i_prov]['productos'][$i_prod]['movs'][] = array('fecha'=>$fecha, 'sale'=>$sale, 'entra'=>$entra, 'nota'=>$nota);
		// actualizo el stock de los productos
		$inventario['proveedores'][$i_prov]['productos'][$i_prod]['stock'] += $entra;
		$inventario['proveedores'][$i_prov]['productos'][$i_prod]['stock'] -= $sale;

		// Guardo el json
		$json_string = json_encode($inventario);
		file_put_contents('inventario.json', $json_string);

		$respuesta = array('mensaje'=>'Movimiento guardado', 'tipo'=>'exito', 'inventario'=>$inventario);
	
	}elseif ($_POST['desc']) { // Actualizar producto
		$desc = $_POST['desc'];
		$p_dist = (int)str_replace('.', '', $_POST['p_dist']);
		$p_venta = (int)str_replace('.', '', $_POST['p_venta']);
		$modalidad = $_POST['modalidad'];

		global $inventario;

		$inventario['proveedores'][$i_prov]['productos'][$i_prod]['desc'] = $desc;
		$inventario['proveedores'][$i_prov]['productos'][$i_prod]['p_dist'] = $p_dist;
		$inventario['proveedores'][$i_prov]['productos'][$i_prod]['p_venta'] = $p_venta;
		$inventario['proveedores'][$i_prov]['productos'][$i_prod]['modalidad'] = $modalidad;

		// Guardo el json
		$json_string = json_encode($inventario);
		file_put_contents('inventario.json', $json_string);

		$respuesta = array('mensaje'=>'Producto acualizado', 'tipo'=>'exito', 'inventario'=>$inventario);
	
	}else{
		$respuesta = null;
	}
	
	return $respuesta;
}

// +++++++++++++++++++++++++++++++++++++++++++++++++ Edit producto
function busca_factura() {

	if ($_POST['factura']) { // a buscar factura
		$factura = $_POST['factura'];

		$inventario = get_inventario();
		// Busco la factura
		$clave = array_search($factura, array_column($inventario['facturas'], 'no_fac'));

		if ($clave !== false) {

			$respuesta = array('mensaje'=>'Factura encontrada', 'tipo'=>'exito', 'factura'=>$inventario['facturas'][$clave]);
		}else{
			$respuesta = array('mensaje'=>'Factura no encontrada', 'tipo'=>'fallo');
		}
	
	}else{
		$respuesta = null;
	}
	
	return $respuesta;
}

function mes($mes) {
	switch ($mes) {
		case 1: $mes_text = 'enero';	break;
		case 2: $mes_text = 'febrero';	break;
		case 3: $mes_text = 'marzo';	break;
		case 4: $mes_text = 'abril';	break;
		case 5: $mes_text = 'mayo';	break;
		case 6: $mes_text = 'junio';	break;
		case 7: $mes_text = 'julio';	break;
		case 8: $mes_text = 'agosto';	break;
		case 9: $mes_text = 'septiembre';	break;
		case 10: $mes_text = 'octubre';	break;
		case 11: $mes_text = 'noviembre';	break;
		case 12: $mes_text = 'diciembre';	break;
	}
	return $mes_text;
}



// +++++++++++++++++++++++++++++++++++++++++++++++++ Informes
function informe() {

	if ($_POST['year']) { // se va a generar un informe
		$informe = $_POST['informe'];
		$year = $_POST['year'];
		$mes = $_POST['mes'];
		$index_prov = $_POST['proveedor'];

		global $inventario;
		
		// determino cual informe se va a generar
		switch ($informe) {
			case 1:
				$informe = ['titulo' => 'Ventas de '.mes($mes).' - '.$year];

				$total_mes = 0;
				foreach ($inventario['facturas'] as $factura) {
					$separadores = array(" ","/",":");
					$ready = str_replace($separadores, ":", $factura['fecha']);
					$fecha = explode(":",$ready);
					$mes_fac = intval($fecha[1]);
					$year_fac = $fecha[2];
					if ($mes_fac == $mes && $year_fac == $year) {
						$informe['ventas'][] = $factura;
						$total_mes += $factura['totalFac'];
					}
				}
				$informe['total_mes'] = $total_mes;

				$respuesta = array('mensaje'=>'Informe generado', 'tipo'=>'exito', 'informe'=>$informe );
				break;

			case 2:
				$proveedor = $inventario['proveedores'][$index_prov];
				$informe = ['titulo' => 'Ventas de '.$proveedor['nombre'].' en '.mes($mes).' - '.$year];
				$respuesta = array('mensaje'=>'Informe generado', 'tipo'=>'exito', 'informe'=>$informe);
				break;
			
			default:
				$respuesta = array('mensaje'=>'No se seleccionó el informe a generar', 'tipo'=>'fallo');
				break;
		}
	
	}else{
		$respuesta = null;
	}
	
	return $respuesta;
}

?>




