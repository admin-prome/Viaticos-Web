<?php

require '../admin/inc/const.php';
require '../admin/inc/funciones/querys_mysql.php';

// Recibo solo el mail, y verifico si existe en la base de datos del banco:

if(empty($_POST['mail']))
{
	die (-1);
}

$query_pg 	= "SELECT personalid FROM personal WHERE email = '".$_POST['mail']."' AND activo = true";
$rs 		= ejecutarConsultaPostgre($query_pg);

if(!empty($rs)) 
{
	echo $rs[0]['personalid'];
}
else
{
	echo "-1";
}