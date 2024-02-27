<?php

function iniciarSesion($datosUser) {
    $_SESSION['autentificado'] = true;
    $_SESSION['id_usuario'] = $datosUser['personalid'];
    $_SESSION['id_puesto'] = $datosUser['pk_puesto'];
    $_SESSION['id_sucursal'] = $datosUser['pk_sucursal'];
    $_SESSION['costo_km_auto'] = $datosUser['costo_km_auto'];
    $_SESSION['costo_km_moto'] = $datosUser['costo_km_moto'];
    if ($datosUser['id_estados_gestionar'] != null) {
        $_SESSION['id_estados_gestionar'] = $datosUser['id_estados_gestionar'];
    } else {
        $_SESSION['id_estados_gestionar'] = 0;
    }
    $_SESSION['pk_depende_de'] = $datosUser['pk_depende_de'];
    $_SESSION['nombre_usuario'] = obtener_nombre_usuario_BASE_BANCO($_SESSION["id_usuario"]);
}
