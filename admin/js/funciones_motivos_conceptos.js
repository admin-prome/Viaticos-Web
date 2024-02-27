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
	}
	
}