$('#form1_origen_viatico_select, #form2_origen_viatico_select').change(function(){
                        
	if($("#form_1").css('display') == 'block')
		var form_activo = 1;
	else
		var form_activo = 2;
	
	if(coords_visita_anterior == '') //var. global
	{
		alert('En este momento NO se dispone de una visita anterior.');
		$('#form' + form_activo + '_origen_viatico_select').val('1');
		
		return false;
	}

	$("#form" + form_activo + "_destino_viatico").select().val('');
	$("#form" + form_activo + "_km_viatico").select().val('');
	$("#form" + form_activo + "_importe_viatico").select().val('');

});