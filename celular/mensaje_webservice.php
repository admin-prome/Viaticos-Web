<?php


function guardar_mensaje_webservice()
{
	// Estos 6 campos son comunes a todos los formularios:
	$id_prov   = $_POST['id_prov'];
	$mensaje  = $_POST['mensaje'];	

	
	$sql = "INSERT INTO webservice_datos (id_provincia,mensaje) VALUES (".$id_prov.",'".$mensaje."')";
	
	ejecutarConsulta($sql);
		
	return 1;
}

require '../admin/inc/const.php';
require '../admin/inc/funciones/querys_mysql.php';
require '../admin/inc/funciones/solicitudes.php';



echo guardar_mensaje_webservice();