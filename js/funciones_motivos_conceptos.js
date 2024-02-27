function resetearConceptos(id_selector)
{
	$('#' + id_selector).find('option').remove().end().
	append('<option value="">Seleccione</option>').val('');
}

function agregarItemsConceptos(id_motivo,id_selector_concepto)
{
	if(id_motivo == 10) // beneficio
	{
		$('#' + id_selector_concepto).append($('<option>',
		{	value: 11,	text: 'Alojamiento'	}));
		
		$('#' + id_selector_concepto).append($('<option>',
		{	value: 3,	text: 'Almuerzo'	}));
		
		$('#' + id_selector_concepto).append($('<option>',
		{	value: 1,	text: 'Cena'	}));
	
		$('#' + id_selector_concepto).append($('<option>',
		{	value: 4,	text: 'Guarder\u00eda'	}));
	
		$('#' + id_selector_concepto).append($('<option>',
		{	value: 9,	text: 'Refrigerio'	}));
                
		$('#' + id_selector_concepto).append($('<option>',
		{	value: 2,	text: 'Estacionamiento'	}));
		
		$('#' + id_selector_concepto).append($('<option>',
		{	value: 12,	text: 'Beneficio Fit'	}));

                $('#' + id_selector_concepto).append($('<option>',
                {       value: 13,      text: 'Beneficio Prepaga'   }));
	        
                 $('#' + id_selector_concepto).append($('<option>',
                {       value: 14,      text: 'Reposicion Caja Chica'   }));	

                 $('#' + id_selector_concepto).append($('<option>',
                {       value: 20,      text: 'Reintegro de guardería D144/2022'   }));
               return true;	
	
	}
	
	
	$('#' + id_selector_concepto).append($('<option>',
	{	value: 5,	text: 'Auto'	}));
	
	$('#' + id_selector_concepto).append($('<option>',
	{	value: 6,	text: 'Moto'	}));
	
	$('#' + id_selector_concepto).append($('<option>',
	{	value: 7,	text: 'Taxi / Remis'	}));
	
	$('#' + id_selector_concepto).append($('<option>',
	{	value: 8,	text: 'Trans. P\u00fablico'	}));
	
	$('#' + id_selector_concepto).append($('<option>',
	{	value: 10,	text: 'Peaje'	}));
	
	$('#' + id_selector_concepto).append($('<option>',
	{	value: 2,	text: 'Estacionamiento'	}));
	
	
	if(id_motivo == 4) // capacitacion
	{
		$('#' + id_selector_concepto).append($('<option>',
		{	value: 11,	text: 'Alojamiento'	}));
	
		$('#' + id_selector_concepto).append($('<option>',
		{	value: 3,	text: 'Almuerzo'	}));
	
		$('#' + id_selector_concepto).append($('<option>',
		{	value: 1,	text: 'Cena'	}));

		$('#' + id_selector_concepto).append($('<option>',
                {       value: 15,      text: 'Formacion' }));
	}
		
		if(id_motivo == 5) // Otros Gastos
        {
                $('#' + id_selector_concepto).append($('<option>',
                {       value: 16,      text: 'Gastos de libreria Red'     }));

                $('#' + id_selector_concepto).append($('<option>',
                {       value: 17,      text: 'Gastos de infusiones Red'        }));

                $('#' + id_selector_concepto).append($('<option>',
                {       value: 18,      text: 'Otros Gastos No listados'    }));

                $('#' + id_selector_concepto).append($('<option>',
                {       value: 19,      text: 'Reuniones de area' }));
        }
	
}
