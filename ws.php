<?php

function guardarVisitaEvalTerrenoWebService($nro_solicitud,$coord_destino,$fecha_hora)
{
	//Obtengo Coordenadas X e Y.
	$partes_coord = explode(',',$coord_destino);
	
	$headers[] = 'Accept: application/json';
	$headers[] = "Authorization: bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1bmlxdWVfbmFtZSI6Im1hcmt0cGx1cyIsImlzcyI6Imh0dHBzOi8vc3Zjcy5wcm92aW5jaWFtaWNyb2VtcHJlc2FzLmNvbSIsImF1ZCI6IjQxNGUxOTI3YTM4ODRmNjhhYmM3OWY3MjgzODM3ZmQxIiwiZXhwIjoxNTAxNjE3MTQ5LCJuYmYiOjE1MDE1MzA3NDl9.h4hj4qGyxptXNcj1RSbn20i5GUKx8-FXrFggvSR2SJo";

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
	
	$url ='https://svcs.provinciamicroempresas.com/CRMServices/apicrm/SiteVisitsController/SaveSiteVisitLocation/'.$nro_solicitud.'/'.$coord_X.'/'.$coord_Y.'/'.$fechahora_reformateada;
	
	/*
	echo $url;
	echo "<br>";
	echo urlencode($url);
	die();
	*/
	
	$url = str_replace(' ','%20',$url);
		
	$curl = curl_init();
	
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_HEADER,1);
	curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
	curl_setopt($curl,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
	
	if(!$response = curl_exec($curl))
	{
		$curl_errno = curl_errno($curl);
		$curl_error = curl_error($curl);
    
		if($curl_errno > 0)
		{
			echo "CURL Error ".$curl_errno.": ".$curl_error;
		}
	}
	else
	{
		echo 'exito !';
	
		echo "<pre>";
			var_dump($response);
		echo "</pre>";
	}

	curl_close($curl);
	return $response;
}

var_dump(guardarVisitaEvalTerrenoWebService('VT -102','-33.9149263,-60.5762636','2017-07-20 13:58:00'));