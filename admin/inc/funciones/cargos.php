<?php

function obtener_cargos() {
$sql = "select * from Cargos ";
$rs = ejecutarConsulta($sql);
return $rs;
}
function obtener_cargo($id) {
$sql = "select descripcion from Cargos WHERE id=$id";
$rs = ejecutarConsulta($sql);
return $rs[0]['descripcion'];
}
function obtener_cargo_de_usuario($id_usuario) {
$sql = "select descripcion from cargos where id = (select Cargos_idCargos from usuarios where id =$id_usuario)";
$rs = ejecutarConsulta($sql);
return $rs;
}
function obtener_cargo_de_usuario_BASE_BANCO($id_usuario){
    $sql = "select puesto from puesto where puestoid = (select pk_puesto from personal where personalid =$id_usuario)";
$rs = ejecutarConsultaPostgre($sql);
return $rs;
}