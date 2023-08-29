$(document).ready(function(){
	// Cargar el archivo json inventario.json
	let requestUrl = 'inventario.json';
	let request = new XMLHttpRequest();
	request.open('GET', requestUrl);
	request.responseType = 'json';
	request.send();

	var inventario = [];
	var regex = /(\d+)/g;

	request.onload = function() {
		inventario = request.response;

	}
	
	// Al ingresar el código busco la info del producto
	$('.c_codigo').keyup(function(){
		// Obtengo el número de la fila del producto
		var id = $(this).attr("id");
		var prod_No = id.match(regex);
		
		//busco el producto
		buscar_prod(prod_No);
	});

	function buscar_prod(prod_No) {
		var encontro = false;
		var precio;
		var total;

		// Busco el producto en el inventario
		var result = [];
		var codigo = $('#codigo' + prod_No).val().toUpperCase();
		for( var i = 0, len = inventario['proveedores'].length; i < len; i++ ) {
			for( var j = 0, lenP = inventario['proveedores'][i]['productos'].length; j < lenP; j++ ) {
				if( inventario['proveedores'][i]['productos'][j]['codigo'] === codigo ) {
					result = inventario['proveedores'][i]['productos'][j];
					$('#desc' + prod_No).val(result['desc']);
					$('#precio' + prod_No).val(Number(result['p_venta']).toLocaleString('de-DE'));
					$('#cant' + prod_No).attr("max", result['stock']);
					if ($('#cant' + prod_No).val() != '') {
						total = $('#cant' + prod_No).val() * result['p_venta'];
						$('#total' + prod_No).val(total.toLocaleString('de-DE'));
						calcula_total();
					}
					encontro = true;
					break;
				}
				if (encontro)
						break;
			} // /for j
		} // /for i
		if (!encontro) {
			$('#desc' + prod_No).val('');
			$('#precio' + prod_No).val('');
			$('#total' + prod_No).val(0);
			$('#cant' + prod_No).val(0);
			calcula_total();
		}
	}

	// Al ingresar la cantidad calculo el total a pagar por el producto y el total de la factura
	$('.c_cant').change(function(){
		var id = $(this).attr("id");
		var prod_No = id.match(regex);
		var precio;
		
		if ($('#precio'+prod_No).val() != '') {
			precio = $('#precio'+prod_No).val().replace(/\./g,'');
			var total = $('#cant' + prod_No).val() * precio;
			$('#total' + prod_No).val(total.toLocaleString('de-DE'));
			calcula_total();
		}
	});

	// Preguntar antes de guardar la factura
	$('form').submit(function(event){
		if(confirm('Ya está lista la factura?')){
			return
		}
		event.preventDefault();
	});

	// Calculo el total de la factura
	function calcula_total(){
		var prods = $('.cant_p').val();
		var suma = 0;
		var dato;
		
		for (var i = 1; i <= prods; i++) {
		 	dato = $('#total'+i).val().replace(/\./g,'');
		 	suma = suma + parseInt(dato,10);
		}
		$('.total_fac').val(suma.toLocaleString('de-DE'));

	}

	// Agregar producto
	$('.btn_add').click(function() {
		var filas = $('.cant_p').val();
		
		if( parseInt(filas,10) < 20) {
			filas = parseInt(filas,10) + 1;
			$('.cant_p').val(filas);
			$('#prod' + filas).removeClass('hidden');
			$('#codigo' + filas).focus();
		}
	});

	// Borrar una fila de producto

	$('.btn_minus').click(function () {
		var filas = parseInt($('.cant_p').val(), 10);
		var num = parseInt($(this).val(), 10);
		var sig;
		
		if (filas < 20) {
			do {
				sig = num + 1;
				$('#codigo' + num).val( $('#codigo' + sig).val() );
				$('#cant' + num).val( $('#cant' + sig).val() );
				buscar_prod(num);
				num = sig;
			}
			while (num < filas); 
		} // if
		$('#codigo' + filas).val('');
		$('#desc' + filas).val('');
		$('#cant' + filas).val('');
		$('#precio' + filas).val('');
		$('#total' + filas).val(0);
		$('#prod' + filas).addClass('hidden');
		filas = parseInt(filas,10) - 1;
		$('.cant_p').val(filas);
		calcula_total();
	});



	jQuery.mueveReloj = function(){
		d = new Date();
		var m = d.getMonth() + 1;
		var dia = d.getDate();
		str_dia = new String (dia);
		if (str_dia.length == 1)
			dia = '0' + dia;
		var mes = (m < 10) ? '0' + m : m;
		var hora = d.getHours();
		var minuto = d.getMinutes();
		var segundo = d.getSeconds();
		str_segundo = new String (segundo);
		if (str_segundo.length == 1)
			segundo = '0' + segundo;
		str_minuto = new String (minuto);
		if (str_minuto.length == 1)
			minuto = '0' + minuto;
		str_hora = new String (hora);
		if (str_hora.length == 1)
			hora = '0' + hora;
		document.getElementById('fecha').value = dia + '/' + mes + '/' + d.getFullYear() + ' ' + hora + ':' + minuto + ':' + segundo;
		
		setTimeout('jQuery.mueveReloj()', 1000);
	}

	jQuery.mueveReloj();

});

