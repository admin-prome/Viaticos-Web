<?php

function obtener_perfiles_viaticos() {
$sql = "select * from perfiles_viaticos ";
$rs = ejecutarConsulta($sql);
return $rs;
}
function obtener_perfil_viatico($id) {
$sql = "select * from perfiles_viaticos WHERE id=$id";
$rs = ejecutarConsulta($sql);
return $rs;
}

function obtener_campo_perfil_viaticos($id, $campo) {
    $sql = "select * from perfiles_viaticos WHERE id=$id";
    $rs = ejecutarConsulta($sql);
    return $rs[0][$campo];
}