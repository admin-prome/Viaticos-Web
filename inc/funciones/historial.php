<?php
require_once 'querys_mysql.php';

function obtener_comentarios_solicitud($id_solicitud){
    $sql="SELECT * FROM historial WHERE id_solicitud=$id_solicitud ORDER BY id desc;";
    $rs = ejecutarConsulta($sql);
    return $rs;
}

function obtener_usuario_que_autoriza_solicitud($id_solicitud){
    $sql="SELECT usuario,fecha FROM historial WHERE id_solicitud=" . $id_solicitud . " AND comentario = 'Cambio de estado de la solicitud a revisada' ORDER BY id desc LIMIT 1";
    $rs = ejecutarConsulta($sql);
    return $rs;
}

function obtener_usuario_que_rechaza($id_solicitud){
    
    $sql="SELECT usuario FROM historial WHERE id_solicitud=" . $id_solicitud . " AND comentario LIKE '%Su solicitud ha sido rechazada%'";
    
   
    $rs = ejecutarConsulta($sql);
    return $rs;
}