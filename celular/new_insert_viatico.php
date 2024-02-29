<?php

require '../admin/inc/const.php';
require '../admin/inc/funciones/querys_mysql.php';
require '../admin/inc/funciones/solicitudes.php';
require '../admin/inc/funciones/celular.php';

$datapost = $_POST;

detenerProcesoPorFaltanteDatos($datapost);
echo guardar_visita_celular($datapost);