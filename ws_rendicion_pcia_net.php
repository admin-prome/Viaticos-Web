<?php
require_once "nusoap/lib/nusoap.php";

$server = new soap_server();
$server->register("getSolicitud");
$server->service($HTTP_RAW_POST_DATA);

function getSolicitud($solicitud) 
{

	if ( $solicitud == 1 || $solicitud == NULL )
	{
		$bEncontro = false;
	}
	else
	{
		$bEncontro = true;
	}
	
	return $bEncontro;
}
?>