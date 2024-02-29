<?php

require '../../admin/inc/const.php';
require '../../admin/inc/funciones/querys_mysql.php';

$sql = "SELECT * FROM wphone_app_version";
$rs = ejecutarConsulta($sql);

echo '{["currentVersion":"'.$rs[0]['number'].'","description":"'.$rs[0]['description'].'"]}';