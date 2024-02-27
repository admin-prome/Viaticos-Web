<?php

function obtener_sucursales()
{
	$sql = "select * from Sucursales";
	$rs = ejecutarConsulta($sql);
	
	return $rs;
}

function soyDeCasaMatriz($idSucursal)
{
	return ($idSucursal == ID_CASA_MATRIZ);
}

function nombre_sucursal($id)
{
	$sql = "select Nombre from Sucursales WHERE id=$id";
	$rs = ejecutarConsulta($sql);
	
	return $rs[0]['Nombre'];
}


function nombre_sucursal_BASE_BANCO($id)
{
	$sql = "select sucursal from sucursal WHERE sucursalid=$id";
	$rs = ejecutarConsultaPostgres($sql);
	
	return $rs[0]['sucursal'];
}

function nombre_sucursal_por_id_usuario_BASE_BANCO($id_usuario)
{
	
	$sql = "SELECT pk_sucursal FROM personal_asistentecomercial WHERE personalid=".$id_usuario;
	
        $rs = ejecutarConsultaPostgre($sql);
       if(empty($rs)){
           $sql = "SELECT pk_sucursal FROM personal_ejecutivocomercial WHERE pk_personal=$id_usuario";
           $rs = ejecutarConsultaPostgre($sql);
       }
    

$id_sucursal=$rs[0]['pk_sucursal'];
	$sql2="select sucursal from sucursal WHERE sucursalid=$id_sucursal";
       $nombre_sucursal=ejecutarConsultaPostgre($sql2);
       return $nombre_sucursal[0]['sucursal'];
}

function coordenadas_sucursal_por_id_usuario_BASE_BANCO($id_usuario)
{
	
	$sql = "SELECT pk_sucursal FROM personal_asistentecomercial WHERE personalid=".$id_usuario;
	
        $rs = ejecutarConsultaPostgre($sql);
       if(empty($rs)){
           $sql = "SELECT pk_sucursal FROM personal_ejecutivocomercial WHERE pk_personal=$id_usuario";
           $rs = ejecutarConsultaPostgre($sql);
       }
    

$id_sucursal=$rs[0]['pk_sucursal'];
	
       return obtener_coordenadas_sucursal_BASE_BANCO($id_sucursal);
}

function obtener_coordenadas_sucursal($idSuc)
{
	$sqlSuc = "SELECT coordenadas FROM sucursales WHERE id = ".$idSuc;
	$regSuc = ejecutarConsulta($sqlSuc);

	return $regSuc[0]['coordenadas'];
}
function obtener_coordenadas_sucursal_BASE_BANCO($idSuc)
{
	$sqlSuc = "SELECT latitud , longitud FROM sucursal WHERE sucursalid = ".$idSuc;
	$regSuc = ejecutarConsultaPostgre($sqlSuc);
	$rs_2=$regSuc[0]['latitud'].','.$regSuc[0]['longitud'];	
	return $rs_2;
}