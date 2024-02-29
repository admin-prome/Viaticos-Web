<?php

function obtenerFechaActualDDMMAAAA($sep = '/')
{
	return date("d".$sep."m".$sep."Y");
}

function formatearFechaDDMMAAAA($fecha,$sepActual = '-',$sepNuevo = '-')
{
	
	$partesFecha = explode($sepActual,$fecha);
	
	$nuevaFecha = date($partesFecha[2].$sepNuevo.$partesFecha[1].$sepNuevo.$partesFecha[0]);
	
	return $nuevaFecha;

}

function presentarFechaDateTime($dateTime,$sepActual = '-',$sepNuevo = '-')
{
	

	

	$partes_fecha = explode(' ',$dateTime);
	
	$fecha = $partes_fecha[0];
	$hora  = $partes_fecha[1];
	
	$partes_fecha = explode($sepActual,$fecha);
	
	$dia  = $partes_fecha[2];
	$mes  = $partes_fecha[1];
	$anio = $partes_fecha[0];
	
	return $dia.$sepNuevo.$mes.$sepNuevo.$anio.' '.$hora;



}


function presentarFechaSinHora($dateTime,$sepActual = '-',$sepNuevo = '-')
{
	$partes_fecha = explode(' ',$dateTime);
	$fecha = $partes_fecha[0];
	
	$partes_fecha = explode($sepActual,$fecha);
	
	$dia  = $partes_fecha[2];
	$mes  = $partes_fecha[1];
	$anio = $partes_fecha[0];
	
	return $dia.$sepNuevo.$mes.$sepNuevo.$anio;
	
}

function pasarFechaDatePickerAFormatoDate($fecha)
{
	$partes_fecha = explode('/',$fecha);
	
	return $partes_fecha[2].'-'.$partes_fecha[1].'-'.$partes_fecha[0];
}

function obtener_nombre_mes($nro_mes)
{
	$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	
	return $meses[$nro_mes - 1];

}
function fecha_sin_hora($fecha)
{
	return substr($fecha,0,10);
	
	 
}