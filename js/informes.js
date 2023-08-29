$(document).ready(function(){
	$('#informe').change(function(){
			if ( $(this).val() == 2) {
				$('.prov_div').removeClass('hide');
			}else{
				$('.prov_div').addClass('hide');
			}
	});

});

