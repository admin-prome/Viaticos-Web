$('#form1_destino_viatico, #form2_destino_viatico').change(function(){
                        	
	if($("#form_1").css('display') == 'block')
	{
    	var form_activo = 1;
 	}
    else
    {
    	var form_activo = 2;
    }
	
	var concepto = $("#concepto_viatico_select option:selected").val();
	
	if(concepto == 5 || concepto == 6) //auto ó moto.
	{
		$("#loading_ajax_form_" + form_activo).css('display','inline');
	}

	buscar_distancia_costo();

});