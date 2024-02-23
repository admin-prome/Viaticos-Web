<?php

function json_conceptos_viaticos()
{
	
	$sql 	= "SELECT * FROM conceptos ORDER BY descripcion";
	$datos 	= ejecutarConsulta($sql);
	
	$json_datos = '{';
	
	foreach($datos as $motivos)
	{
		if('Taxi / Remis' == $motivos['descripcion'])
		{
			$motivos['descripcion'] = 'taxi_remis';
		}
			
		if('Trans. Publico' == $motivos['descripcion'])
		{
			$motivos['descripcion'] = 'transporte_publico';
		}
		
		$json_datos .= '"'.$motivos['descripcion'].'" : "'.$motivos['id'].'",';
	}
	
	$json_datos = rtrim($json_datos,',');
	
	$json_datos .= ' }';
	
	echo json_encode($json_datos);
	
}

function obtener_conceptos($ids_conceptos = NULL)
{
	$sql = "SELECT * FROM conceptos";
	
	if(is_array($ids_conceptos))
	{
		$sql .= " WHERE id IN (".implode(',',$ids_conceptos).")";	
	}
		
	$sql .= " ORDER BY descripcion";
	$rs = ejecutarConsulta($sql);
	
	return $rs;
}

function obtener_concepto($id)
{
	$sql = "SELECT descripcion FROM conceptos WHERE id=".$id;
	$rs = ejecutarConsulta($sql);
	
	return utf8_encode($rs[0]['descripcion']);
}