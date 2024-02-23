<?php

function obtener_segmentos()
{
	$sql = "SELECT * FROM segmentos ORDER BY descripcion";
	$rs = ejecutarConsulta($sql);
	
	return $rs;
}