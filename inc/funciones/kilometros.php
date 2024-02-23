<?php

function obtener_costokm_por_id($id) {
$sql = "select costo_moto ,costo_auto FROM costo_por_kilometro WHERE id=$id";
$rs = ejecutarConsulta($sql);
return $rs;
}
