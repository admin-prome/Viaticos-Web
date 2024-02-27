<?php

require '../admin/inc/const.php';
require '../admin/inc/funciones/querys_mysql.php';
require '../admin/inc/funciones/usuarios.php';

// Recibo el id de personal, y devuelvo el ID de Ejecutivo.
if(empty($_POST['id_personal']))
{
	echo (-1);
	die();
}

echo obtenerIDEjecutivoSegunIDPersonal($_POST['id_personal']);