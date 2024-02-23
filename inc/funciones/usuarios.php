<?php

function ultimo_viatico_usuario($idUsuario, $forma_carga = 'indistinto')
{

    $sql = "SELECT max(id) as ult_viatico FROM visita WHERE id_usuario = ".$idUsuario;
	
	if($forma_carga != 'indistinto')
	{
        $sql .= ' AND cargado_en_celular = '.$forma_carga;
    }

    $rs = ejecutarConsulta($sql);

    return $rs[0]['ult_viatico'];
}

function obtener_usuarios()
{
    $sql = "SELECT * FROM usuarios";
    $rs = ejecutarConsulta($sql);

    return $rs;
}

function obtener_usuarios_login($email)
{
    $sql = "SELECT id,id_sucursales FROM usuarios WHERE mail = '" . $email . "'";
    $rs = ejecutarConsulta($sql);
    
	return $rs[0];
}

function obtener_usuarios_login_BASE_BANCO($email)
{
    $tablas = array('personal_ejecutivocomercial','personal_asistentecomercial');
    $nombres_campos_personal = array('pk_personal','personalid','personalid');
      
    foreach($tablas as $indiceTabla => $table)
    {
        $query = "SELECT P.pk_depende_de , P.personalid , P.pk_puesto, P.costo_km_auto, P.costo_km_moto, P.id_estados_gestionar, ".$table.".pk_sucursal FROM personal P JOIN ".$table." ON ".$table.".".$nombres_campos_personal[$indiceTabla]." = P.personalid WHERE P.email = '".$email."' AND P.activo = true";
        
        //echo $query.'<br />';
        //die();
        
        $rs = ejecutarConsultaPostgre($query);
        
        if(!empty($rs))
            return $rs[0];
    }     
}

function insertar_usuario($datosUsuario)
{

    $sucursal = $datosUsuario['id_sucursal'];
    $nombre = $datosUsuario['nombre'];
    $mail = $datosUsuario['mail'];
    $id_perfil_viatico = $datosUsuario['id_perfil'];
    $idcargo = $datosUsuario['cargo'];
    $id_depende_de = $datosUsuario['depende_de'];

    $sql = "INSERT INTO db_viaticos_provincia.usuarios (id_sucursales, Nombre, mail, id_perfil_viatico, Cargos_idCargos, depende_de_id_usuario)"
    . " VALUES (" . $sucursal . ",'" . $nombre . "','" . $mail . "'," . $id_perfil_viatico . "," . $idcargo . "," . $id_depende_de . ")";

    return ejecutarConsulta($sql);
}

function obtener_usuario_dato($id, $campo)
{
    $sql = "SELECT * FROM usuarios WHERE id=".$id;
    $rs = ejecutarConsulta($sql);
    
	return $rs[0][$campo];
}

function obtener_usuario_dato_BASE_BANCO($id, $campo)
{
    $sql = "SELECT * FROM personal WHERE personalid=".$id;
    $rs = ejecutarConsultaPostgre($sql);
    

	return $rs[0][$campo];

}


function obtener_usuario($id)
{
    $sql = "SELECT * FROM usuarios WHERE id=".$id;
    $rs  = ejecutarConsulta($sql);
    
	return $rs;
}

function eliminar_usuario($id_usuario)
{
    $sql = "DELETE FROM db_viaticos_provincia.usuarios WHERE usuarios.id = ".$id_usuario;
    $rs = ejecutarConsulta($sql);
    
	return $rs;
}

/* * ******Se podria usar una sola funcion que reciba id usuario y id de estado , pero por ahora lo dejo asi , mas adelante es probable que se complique********* */

// La solicitud pendiente del usuario es UNICA. No puede tener mas de 1.
function usuario_solicitud_pendiente($id_usuario)
{
    $sql = "SELECT * FROM solicitud WHERE id_usuarios= ". $id_usuario .
            " AND estado_id = " . SOLICITUD_PENDIENTE. " AND rechazada =0";

    $rs = ejecutarConsulta($sql);
    return $rs;
}

function usuario_solicitudes_presentadas($id_usuario)
{
   
    $sql = "SELECT * FROM solicitud WHERE id_usuarios=" . $id_usuario .
            " AND estado_id = ".SOLICITUD_PRESENTADA." AND rechazada = 0;";

    $rs = ejecutarConsulta($sql);
    return $rs;
}

function usuario_solicitudes_autorizada($id_usuario)
{

    $sql = "SELECT * FROM solicitud where id_usuarios=".$id_usuario." AND estado_id=".SOLICITUD_AUTORIZADA." AND rechazada = 0;";
    $rs = ejecutarConsulta($sql);
    
	return $rs;
}
/*
function usuario_solicitudes_autorizada($id_usuario)
{
    $id = $id_usuario;
    $sql = "SELECT * FROM solicitud where id_usuarios=$id_usuario and estado_id=6 AND rechazada = 0;";
    $rs = ejecutarConsulta($sql);
    return $rs;
}
*/
function usuario_solicitudes_revisadas($id_usuario)
{
    $sql = "SELECT * FROM solicitud where id_usuarios=".$id_usuario." AND estado_id=".SOLICITUD_REVISADA." AND rechazada = 0";
    $rs = ejecutarConsulta($sql);
    
	return $rs;
}

function usuario_solicitudes_aprobadas($id_usuario)
{
   
    $sql = "SELECT * FROM solicitud where id_usuarios=".$id_usuario." AND estado_id=".SOLICITUD_APROBADA." AND rechazada = 0";
    $rs = ejecutarConsulta($sql);
    
	return $rs;
}
function usuario_solicitudes_exportadas($id_usuario)
{
   
    $sql = "SELECT * FROM solicitud where id_usuarios=".$id_usuario." AND estado_id=".SOLICITUD_EXPORTAR." AND rechazada = 0";
    $rs = ejecutarConsulta($sql);
    
	return $rs;
}

function usuario_solicitudes_rechazada($id_usuario)
{
    $sql = "SELECT * FROM solicitud where id_usuarios=".$id_usuario.
	" AND rechazada = 1";
    
	$rs = ejecutarConsulta($sql);
    
	return $rs;
}

function obtener_nombre_usuario_BASE_BANCO($id_usuario)
{
	$sql = "SELECT nombre FROM personal WHERE personalid = ".$id_usuario;
   
	$rs = ejecutarConsultaPostgre($sql);
	return $rs[0]['nombre'];
}

function obtener_legajo_usuario_BASE_BANCO($id_usuario)
{
	$sql = "SELECT numero_legajo FROM personal WHERE personalid = ".$id_usuario;
   
	$rs = ejecutarConsultaPostgre($sql);
	return $rs[0]['numero_legajo'];
}
function obtener_usuarios_segun_estado_gestionar($estado_gestionar)
{
	$sql = "SELECT * from personal where id_estados_gestionar = $estado_gestionar";
   
	$rs = ejecutarConsultaPostgre($sql);
	return $rs;
}

function obtener_leyenda_boton_gestion($id_estado_gestionar)
{

	switch($id_estado_gestionar)
	{
	
		case SOLICITUD_PRESENTADA:
		
			return 'Autorizar';
			break;
	
		case SOLICITUD_AUTORIZADA:
	
			return 'Revisar';
			break;
	
		case SOLICITUD_REVISADA:
	
			return 'Aprobar';
			break;
	
	}

}

function obtenerIDEjecutivoSegunIDPersonal($id_personal)
{
	$query = 'SELECT IE.id FROM personal P INNER JOIN ids_ejecutivos IE ON P.dni = IE.dni WHERE P.personalid = '.$id_personal.' AND P.activo = true';
	$rs = ejecutarConsultaPostgre($query);
	
	if(!empty($rs))
	{
		return $rs[0]['id'];
	}
	else
	{
		return "0";
	}
}

function obtenerNombreUsuarioSegunIDPersonal($id_personal)
{	
	$query = "SELECT nombre FROM personal WHERE personalid = ".$id_personal." AND activo = true";

	$rs = ejecutarConsultaPostgre($query);
	
	if(!empty($rs))
	{
		return $rs[0]['nombre'];
	}
	else
	{
		return "";
	}	
}