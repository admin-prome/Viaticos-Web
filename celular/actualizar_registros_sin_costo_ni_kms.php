<?php

require_once '../admin/inc/const.php';

require_once '../admin/inc/funciones/cargos.php';
require_once '../admin/inc/funciones/conceptos.php';
require_once '../admin/inc/funciones/estados.php';
require_once '../admin/inc/funciones/exportaciones.php';
require_once '../admin/inc/funciones/fechas.php';
require_once '../admin/inc/funciones/funciones_genericas.php';
require_once '../admin/inc/funciones/kilometros.php';
require_once '../admin/inc/funciones/login.php';
require_once '../admin/inc/funciones/motivos.php';
require_once '../admin/inc/funciones/perfiles_viaticos.php';
require_once '../admin/inc/funciones/querys_mysql.php';
require_once '../admin/inc/funciones/segmentos.php';
require_once '../admin/inc/funciones/seguridad.php';
require_once '../admin/inc/funciones/solicitudes.php';
require_once '../admin/inc/funciones/sucursales.php';
require_once '../admin/inc/funciones/usuarios.php';
require_once '../admin/inc/funciones/viaticos.php';
require_once '../admin/inc/funciones/zonas.php';

$ids_visitas = $_POST['ids_visitas'];
$origenes = $_POST['origenes'];

$solicitud_activa = $_POST['solicitud_activa'];

$costos_y_kms_idas = $_POST['costos_y_kms_idas'];
$costos_y_kms_vueltas = $_POST['costos_y_kms_vueltas'];

foreach ($costos_y_kms_idas as $pos => $cost_kms) {
    if ($pos % 2 == 0) {
        $kms_idas[] = $cost_kms;
    } else {
        $costos_idas[] = $cost_kms;
    }
}

foreach ($costos_y_kms_vueltas as $pos => $cost_kms) {
    if ($pos % 2 == 0) {
        $kms_vueltas[] = $cost_kms;
    } else {
        $costos_vueltas[] = $cost_kms;
    }
}

for ($i = 0; $i < sizeof($ids_visitas); $i++) {
   
    $costo_total = $costos_idas[$i] + $costos_vueltas[$i];
    $km_total = $kms_idas[$i] + $kms_vueltas[$i];

    $sql = "UPDATE visita SET importe = " . $costo_total . ", km = " . $km_total .
            ", costo_ida = " . $costos_idas[$i] . ", costo_vuelta = " . $costos_vueltas[$i] .
            ", km_ida = " . $kms_idas[$i] . ", km_vuelta = " . $kms_vueltas[$i];

    $sql .= " WHERE id = " . $ids_visitas[$i];

    ejecutarConsulta($sql);

    // Actualizamos el reg. anterior... 
    // Salvo que: el origen es la sucursal..

    if ($origenes[$i] != VIATICO_ORIGEN_SUCURSAL) {
        $sql = "UPDATE visita SET importe = costo_ida, km = km_ida, costo_vuelta = 0,
		km_vuelta = 0";


        if ($i == 0) {
            $id_setear = buscar_viatico_anterior($ids_visitas[$i], $solicitud_activa);
        } else {
            $id_setear = $ids_visitas[$i - 1];
        }


        $sql .= " WHERE id = " . $id_setear;

        ejecutarConsulta($sql);
    }
}