function obtener_ids_values_checks_solicitudes_aprobar(values_checks)
{
	var partes_value = new Array();
	var ids_checks_rendiciones = new Array();
	
	for(var i = 0; i < values_checks.length; i++)
	{
		partes_value = values_checks[i].split('_');
				
		ids_checks_rendiciones[ids_checks_rendiciones.length] = partes_value[2]; 
	}
	
	return ids_checks_rendiciones;
}