<?php

function detenerProcesoPorFaltanteDatos($post)
{
	if(empty($post) || empty($post['id_motivo_visita']) || 
	empty($post['id_usuario']) || empty($post['id_origen']) ||
	empty($post['coordenadas']) || empty($post['transporte'])){
		
		echo "-1"; 
		die();
	
	}
}

function guardarDatosEnArchivo($id_usuario,$nro_solicitud,$coord_X,$coord_Y,$fecha_hora)
{
	$handler = fopen('visitas_terreno_fallidas_ws.txt','a');
	
	$registro = $id_usuario.'#'.$nro_solicitud.'#'.$coord_X.'#'.$coord_Y.'#'.$fecha_hora."\r\n";
	
	fwrite($handler,$registro);
	fclose($handler);
}

function registrarVisitaCelularEnDB($insert_sql,$id_usuario)
{
	$rs = ejecutarConsulta($insert_sql);
	$solicitud_activa = obtener_id_solicitud_activa($id_usuario);
	
	if(empty($solicitud_activa))
	{
		insertar_solicitud($id_usuario);
		$solicitud_activa = obtener_ultimo_id('solicitud');
	}
	
	$querySV = "INSERT INTO solicitud_visita (id_solicitud,id_visita) VALUES (".$solicitud_activa.",".obtener_ultimo_id('visita').")";
	
	ejecutarConsulta($querySV);
}

function formatearNroSolicitudWS($nro_solicitud)
{
	$prefijo = 'VT -';
	
	if($nro_solicitud > 999)
	{
		$nro_con_coma = number_format($nro_solicitud,0,'.',',');
		return ($prefijo.$nro_con_coma);
	}
	else
	{
		return ($prefijo.$nro_solicitud);
	}
	
}

function guardarVisitaTerrenoWebService($id_usuario,$token,$nro_solicitud,$coord_destino,$fecha_hora)
{
	//Obtengo Coordenadas X e Y.
	$coord_destino= str_replace(' ', '', $coord_destino);
	$partes_coord = explode(',',$coord_destino);
	
	$headers[] = 'Accept: application/json';
	$headers[] = "Authorization: bearer $token";
	
	$coord_X = $partes_coord[0];
	$coord_Y = $partes_coord[1];
	
	// Re-formateo la fecha:
	// Pasamos de:  Y-m-d H:i:s --> ddmmyyyy-hh-mm
	$partes_fechahora = explode(' ',$fecha_hora);
	$fecha = $partes_fechahora[0];
	$hora  = $partes_fechahora[1];
	
	$partes_fecha = explode('-',$fecha);
	$partes_hora  = explode(':',$hora);
	
	$fechahora_reformateada = $partes_fecha[2].$partes_fecha[1].$partes_fecha[0].'-'.$partes_hora[0].'-'.$partes_hora[1];
	
	//$url ='https://svcs.provinciamicroempresas.com/crmservices/apicrm/SiteVisitsController/SaveSiteVisitLocation/'.formatearNroSolicitudWS($nro_solicitud).'/'.$coord_X.'/'.$coord_Y.'/'.$fechahora_reformateada;

	$url ='https://365-svcs.provinciamicroempresas.com/crmservices/apicrm/SiteVisitsController/SaveSiteVisitLocation/VT -'.$nro_solicitud.'/'.$coord_X.'/'.$coord_Y.'/'.$fechahora_reformateada;
	
	$url  = str_replace(' ','%20',$url);
	$curl = curl_init();
	
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_HEADER,1);
	curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
	curl_setopt($curl,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
	
	$response = curl_exec($curl);
	
	if($response == false)
	{
		guardarDatosEnArchivo($id_usuario,$nro_solicitud,$coord_X,$coord_Y,$fechahora_reformateada);
	}

	curl_close($curl);
	return $response;
}

function guardar_visita_celular($post)
{
	$evalTerreno_visitaCobranza = false;
	
	if(MOTIVO_EVAL_TERRENO == $post['id_motivo_visita'] ||
	MOTIVO_VISITA_COBRANZA == $post['id_motivo_visita'])
	{
		$evalTerreno_visitaCobranza = true;
	}
	
	if($evalTerreno_visitaCobranza)
	{
		$nro_solicitud 	= $post['nro_solicitud'];
		$visita_exitosa = $post['visita_exitosa'];
	}
	
	$id_usuario  	= $post['id_usuario'];
	$id_motivo   	= $post['id_motivo_visita'];
	$id_concepto 	= $post['transporte'];
	$id_origen   	= $post['id_origen'];
	//$coord_destino  = $post['coordenadas'];
	$coord_destino= str_replace(' ', '', $post['coordenadas']);
	$fecha_hora 	= $post['fechahora']; 		// Y-m-d H:i:s
		
	if($id_concepto == CONCEPTO_TAXI_REMIS || $id_concepto == CONCEPTO_TRANS_PUBLICO)
	{
		$costo_trans = $post['costo_transporte'];
    }
	else
	{
		$costo_trans = 0;
	}
	
	$sql = "INSERT INTO visita (cargado_en_celular,fecha,id_estado,id_motivo,id_usuario,id_concepto,origen,destino,importe";
	
	if($evalTerreno_visitaCobranza)
	{
		$sql .= ',nro_solicitud,visita_exitosa';
	}	
	
	$sql .= ") VALUES (1,'".$fecha_hora."',1,".$id_motivo.",".$id_usuario.",".$id_concepto.",'".$id_origen."','".$coord_destino."','".$costo_trans."'";	
	
	if($evalTerreno_visitaCobranza)
	{
		$sql .= ",".$nro_solicitud.",".$visita_exitosa;
	}
	
	$sql .= ')';
	
	registrarVisitaCelularEnDB($sql,$id_usuario);
	
	if(MOTIVO_EVAL_TERRENO == $post['id_motivo_visita'])
	{
		guardarVisitaTerrenoWebService($id_usuario,$_POST['token'],$nro_solicitud,$coord_destino,$fecha_hora);
	}
	
	return 1;

}