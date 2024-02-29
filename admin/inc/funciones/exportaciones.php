<?php

function fecha_desde_hasta_exportacion_viatico($id_solicitud,$desde_hasta)
{
	$sql = "SELECT";
	
	if($desde_hasta == 'desde')
		$min_max = ' min';
	else
		$min_max = ' max';
	
	$sql .= $min_max."(V.fecha) AS fecha FROM solicitud_visita SV INNER JOIN
	visita V ON SV.id_visita = V.id INNER JOIN solicitud S ON SV.id_solicitud =
	S.id WHERE S.id = ".$id_solicitud." ORDER BY V.fecha DESC";
	
	$rs = ejecutarConsulta($sql);
	
	return $rs[0]['fecha'];
}


function viaticos_para_exportar($fecha_desde,$fecha_hasta)
{
	
	$sql  = "SELECT V.id_usuario, V.fecha,V.importe,V.id_motivo,V.id_concepto,S.fecha_presentacion, S.id AS id_solicitud FROM solicitud_visita SV INNER JOIN solicitud S ON SV.id_solicitud = S.id INNER JOIN visita V ON SV.id_visita = V.id";
	$sql .= " WHERE S.fecha_presentacion BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."' AND S.estado_id = ".SOLICITUD_APROBADA;

	$sql .= " ORDER BY S.fecha_presentacion DESC";
        
	$rs = ejecutarConsulta($sql);
	return $rs;
}

function solicitudes_a_exportar($fecha_desde,$fecha_hasta)
{
	
	$sql  = "SELECT V.fecha,V.importe,V.id_motivo,V.id_concepto,S.fecha_presentacion FROM solicitud_visita SV INNER JOIN solicitud S ON SV.id_solicitud = S.id INNER JOIN visita V ON SV.id_visita = V.id";
	
    $sql .= " WHERE S.fecha_presentacion BETWEEN '".$fecha_desde."' AND '".
	$fecha_hasta."' AND S.estado_id = ".SOLICITUD_APROBADA ;
	        
	$sql .= " ORDER BY S.fecha_presentacion";
	
        echo $sql;
        
	$rs = ejecutarConsulta($sql);
	
	return $rs;
}

function generar_concepto_exportacion($id_concepto,$id_motivo)
{
	//Evaluacion de Terreno:
	if($id_motivo == MOTIVO_EVAL_TERRENO)
	{
		if($id_concepto == CONCEPTO_AUTO || $id_concepto == CONCEPTO_MOTO)
		{
			return htmlentities('Evaluación en Terreno Transporte Propio');
		}
		
		if($id_concepto == CONCEPTO_TAXI_REMIS)
		{
			return htmlentities('Evaluación en Terreno Remis/Taxis');
		}
		
		if($id_concepto == CONCEPTO_TRANS_PUBLICO)
		{
			return htmlentities('Evaluación en Terreno Transporte Público');
		}
		
		if($id_concepto == CONCEPTO_PEAJE)
		{
			return 'Peaje';
		}
		
		if($id_concepto == CONCEPTO_ESTACIONAMIENTO)
		{
			return 'Estacionamiento';
		}
	}
	
	//Reunion de Modulo:
	if($id_motivo == MOTIVO_REUNION_MODULO)
	{
		if($id_concepto == CONCEPTO_AUTO || $id_concepto == CONCEPTO_MOTO
		|| $id_concepto == CONCEPTO_TAXI_REMIS
		|| $id_concepto == CONCEPTO_TRANS_PUBLICO)
		{
			return htmlentities('Viáticos por Reunión de Módulo');
		}
		
		if($id_concepto == CONCEPTO_PEAJE)
		{
			return 'Peaje';
		}
		
		if($id_concepto == CONCEPTO_ESTACIONAMIENTO)
		{
			return 'Estacionamiento';
		}
	}
	
	//Reunion Mensual:
	if($id_motivo == MOTIVO_REUNION_MENSUAL)
	{
		if($id_concepto == CONCEPTO_AUTO || $id_concepto == CONCEPTO_MOTO
		|| $id_concepto == CONCEPTO_TAXI_REMIS
		|| $id_concepto == CONCEPTO_TRANS_PUBLICO)
		{
			return htmlentities('Viáticos por Reunión Mensual');
		}
		
		if($id_concepto == CONCEPTO_PEAJE)
		{
			return 'Peaje';
		}
		
		if($id_concepto == CONCEPTO_ESTACIONAMIENTO)
		{
			return 'Estacionamiento';
		}
	}
	
	//Otras Reuniones:
	if($id_motivo == MOTIVO_OTRAS_REUNIONES)
	{
		if($id_concepto == CONCEPTO_AUTO || $id_concepto == CONCEPTO_MOTO
		|| $id_concepto == CONCEPTO_TAXI_REMIS
		|| $id_concepto == CONCEPTO_TRANS_PUBLICO)
		{
			return htmlentities('Viáticos por Otras Reuniones');
		}
		
		if($id_concepto == CONCEPTO_PEAJE)
		{
			return 'Peaje';
		}
		
		if($id_concepto == CONCEPTO_ESTACIONAMIENTO)
		{
			return 'Estacionamiento';
		}
	}
	
	//Traspaso de Cartera:
	if($id_motivo == MOTIVO_TRASPASO_CARTERA)
	{
		if($id_concepto == CONCEPTO_AUTO || $id_concepto == CONCEPTO_MOTO
		|| $id_concepto == CONCEPTO_TAXI_REMIS
		|| $id_concepto == CONCEPTO_TRANS_PUBLICO)
		{
			return htmlentities('Gestión de Traspaso de cartera');
		}
		
		if($id_concepto == CONCEPTO_PEAJE)
		{
			return 'Peaje';
		}
		
		if($id_concepto == CONCEPTO_ESTACIONAMIENTO)
		{
			return 'Estacionamiento';
		}
	}
	
	// Capacitacion:
	if($id_motivo == MOTIVO_CAPACITACION)
	{
		if($id_concepto == CONCEPTO_AUTO || $id_concepto == CONCEPTO_MOTO)
		{
			return htmlentities('Viáticos en capacitación Transporte Propio');
		}
		
		if($id_concepto == CONCEPTO_TAXI_REMIS
		|| $id_concepto == CONCEPTO_TRANS_PUBLICO)
		{
			return htmlentities('Viáticos en capacitación Bus');
		}
		
		if($id_concepto == CONCEPTO_PEAJE)
		{
			return 'Peaje';
		}
		
		if($id_concepto == CONCEPTO_ESTACIONAMIENTO)
		{
			return 'Estacionamiento';
		}
		
		if($id_concepto == CONCEPTO_ALOJAMIENTO)
		{
			return htmlentities('Alojamiento en Capacitación');
		}
		
		if($id_concepto == CONCEPTO_ALMUERZO || $id_concepto == CONCEPTO_CENA)
		{
			return 'Gastos de Almuerzo';
		}

	}
	
	// Beneficios:
	
	if($id_motivo == MOTIVO_BENEFICIO)
	{
		if($id_concepto == CONCEPTO_ALOJAMIENTO)
		{
			return 'Alojamiento';
		}
		
		if($id_concepto == CONCEPTO_GUARDERIA)
		{
			return htmlentities('Guardería');
		}
		
		if($id_concepto == CONCEPTO_REFRIGERIO)
		{
			return 'Refrigerio';
		}
		
		if($id_concepto == CONCEPTO_ESTACIONAMIENTO)
		{
			return 'Estacionamiento';
		}
		
		if($id_concepto == CONCEPTO_ALMUERZO || $id_concepto == CONCEPTO_CENA)
		{
			return 'Gastos de Almuerzo';
		}
		
		if($id_concepto == CONCEPTO_PEAJE)
		{
			return 'Peaje';
		}

                if($id_concepto == CONCEPTO_GUARDERIA_LEY)
                {
                        return htmlentities('Reintegro de Guarderia D.144/22');
                }

	}
	
	// Visitas a sucursal:
	
	if($id_motivo == MOTIVO_VISITA_SUCURSAL)
	{
		if($id_concepto == CONCEPTO_AUTO || $id_concepto == CONCEPTO_MOTO)
		{
			return 'Visita a Sucursal Transporte Propio';
		}
		
		if($id_concepto == CONCEPTO_TAXI_REMIS)
		{
			return 'Visita a Sucursal Remis/Taxis';
		}
		
		if($id_concepto == CONCEPTO_TRANS_PUBLICO)
		{
			return htmlentities('Visita a Sucursal Transporte Público');
		}
		
		if($id_concepto == CONCEPTO_PEAJE)
		{
			return 'Peaje';
		}
		
		if($id_concepto == CONCEPTO_ESTACIONAMIENTO)
		{
			return 'Estacionamiento';
		}
	}
	
	// Visitas de cobranza:
	
	if($id_motivo == MOTIVO_VISITA_COBRANZA)
	{
		if($id_concepto == CONCEPTO_AUTO || $id_concepto == CONCEPTO_MOTO)
		{
			return htmlentities('Gestión de Cobranza Transporte Propio');
		}
		
		if($id_concepto == CONCEPTO_TAXI_REMIS)
		{
			return htmlentities('Gestión de Cobranza Remis/Taxi');
		}
		
		if($id_concepto == CONCEPTO_TRANS_PUBLICO)
		{
			return htmlentities('Gestión de Cobranza Transporte Publico');
		}
		
		if($id_concepto == CONCEPTO_PEAJE)
		{
			return 'Peaje';
		}
		
		if($id_concepto == CONCEPTO_ESTACIONAMIENTO)
		{
			return 'Estacionamiento';
		}
	}
}

function obtener_legajo_empleado_BASE_BANCO($id_usuario)
{
	$sql = "SELECT numero_legajo FROM personal WHERE personalid = ".$id_usuario;
	$rs  = ejecutarConsultaPostgre($sql);
	
	return $rs[0]['numero_legajo'];
}

function viaticos_para_exportar_estado($fecha_desde,$fecha_hasta ,$estado)
{
	
	$sql  = "SELECT V.id_usuario, V.fecha,V.importe,V.id_motivo,V.km,V.id_concepto,S.fecha_presentacion, S.id AS id_solicitud FROM solicitud_visita SV INNER JOIN solicitud S ON SV.id_solicitud = S.id INNER JOIN visita V ON SV.id_visita = V.id";
	$sql .= " WHERE S.fecha_presentacion BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."' AND S.estado_id = ".$estado;

	$sql .= " ORDER BY S.fecha_presentacion DESC";
        
	
        $rs = ejecutarConsulta($sql);
	return $rs;
}
