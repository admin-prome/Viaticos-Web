<?php require 'check_login.php'; ?>
<?php

require '../../admin/inc/const.php';
require '../../admin/inc/funciones/querys_mysql.php';

$nro  = $_POST['version_numero'];
$desc = $_POST['version_descripcion'];

$sql = "UPDATE wphone_app_version SET number = ".$nro.", description = '".$desc."' WHERE id = 1";

ejecutarConsulta($sql);
header('Location: version_mobile.php?updateapp=1');