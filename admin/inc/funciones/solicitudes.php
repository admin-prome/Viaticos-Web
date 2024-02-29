<?php

function eliminar_solicitud_completa($id_solicitud)
{
	$sql = "SELECT id_visita FROM solicitud_visita WHERE id_solicitud = ".
	$id_solicitud;
	
	$visitas = ejecutarConsulta($sql);
	
	$ids_visitas = array();
	foreach($visitas as $vis)
	{
		$ids_visitas[] = $vis['id_visita']; 	
	}
	
	$sql = "DELETE FROM solicitud_visita WHERE id_solicitud = ".$id_solicitud;
	ejecutarConsulta($sql);
	
	$sql = "DELETE FROM solicitud WHERE id = ".$id_solicitud;
	ejecutarConsulta($sql);
	
	$sql = "DELETE FROM visita WHERE id IN (".implode(',',$ids_visitas).")";		
	ejecutarConsulta($sql);
	
	eliminarMultiplesAttachsViaticos($ids_visitas);
	
}

function insertar_solicitud($id_usuario)
{
    $sql = "INSERT INTO solicitud (id_usuarios,ano,estado_id) VALUES (" .
	$id_usuario.",".date("Y").",".SOLICITUD_PENDIENTE.")";
    
	$rs = ejecutarConsulta($sql);

    return $rs;
}

function cambiar_estado_array_solicitudes($ids_solicitudes,$nuevo_estado ,$usuario,$estado_actual)
{
	$sql = "UPDATE solicitud SET estado_id = ".$nuevo_estado. " WHERE id IN(".implode(",",$ids_solicitudes).")";
	ejecutarConsulta($sql);
        $actual = date("d-m-Y H:i:s");
        $estado_solicitud_nombre = obtener_estado_por_id($nuevo_estado);
        $comentario = "Cambio de la solicitud a " . $estado_solicitud_nombre;
        foreach ($ids_solicitudes as $row){
        $sql = "INSERT INTO historial (id_solicitud , usuario , comentario,estado_solicitud,fecha)
        VALUES ($row, $usuario, '$comentario' ,$estado_actual,'$actual');";
        ejecutarConsulta($sql);
        }
}

function obtener_importe_solicitud($id_solicitud)
{
    $sql = "SELECT SUM(v.importe) as importe FROM visita v WHERE v.id IN (SELECT s_v.id_visita FROM solicitud_visita s_v WHERE s_v.id_solicitud = ".$id_solicitud.")";
    
	$rs = ejecutarConsulta($sql);
    return $rs;
}

function obtener_viaticos_solicitud($id_solicitud)
{
    
	$sql = "SELECT * FROM visita WHERE id IN(SELECT id_visita FROM 
	solicitud_visita WHERE id_solicitud = ".$id_solicitud.")";

    $rs = ejecutarConsulta($sql);
	return $rs;
}

function obtener_cantidad_viaticos_solicitud($id_solicitud)
{
    
	$sql = "SELECT count(*) FROM visita WHERE id IN(SELECT id_visita FROM 
	solicitud_visita WHERE id_solicitud = ".$id_solicitud.")";

    $rs = ejecutarConsulta($sql);
	return $rs;
}

function obtener_solicitud_datos($id_solicitud)
{
    $sql = "SELECT * FROM solicitud WHERE id = ".$id_solicitud;
    $rs = ejecutarConsulta($sql);
    
	return $rs;
}

function obtener_usuario_datos_solicitud($id_solicitud)
{
    $sql = "SELECT id_usuarios FROM solicitud WHERE id =".$id_solicitud;
    $rs = ejecutarConsulta($sql);
    
	return $rs;
}

function obtener_id_solicitud_activa($idUsuario) {
    $sql = "SELECT max(id) AS max FROM solicitud WHERE id_usuarios = " . $idUsuario .
            " AND estado_id = " . SOLICITUD_PENDIENTE . " AND rechazada = 0";

    $rs = ejecutarConsulta($sql);

    return $rs[0]['max'];
}

/*
  function obtener_id_solicitud_pendiente_del_usuario($idUsuario)
  {
  $sql = "SELECT max(id) AS max FROM solicitud WHERE id_usuarios = ".
  $idUsuario." AND estado_id = ".SOLICITUD_PENDIENTE." AND rechazada = 1";

  $rs = ejecutarConsulta($sql);

  return $rs[0]['max'];
  }
 */

function obtener_mis_usuarios_a_gestionar_BASE_BANCO($id_usuario) {
   
    $sql = "SELECT personalid FROM personal WHERE pk_depende_de = '".$id_usuario."' OR pk_depende_de LIKE '".$id_usuario."/%' OR  pk_depende_de LIKE '%/".$id_usuario."' OR pk_depende_de LIKE '%/".$id_usuario."/%'";
    
  

    $id = ejecutarConsultaPostgre($sql);
    $id_usuarios = null;
    foreach ($id as $row) {
        $id_usuarios.=$row['personalid'] . ',';
    }
    $id_usuarios = trim($id_usuarios, ',');
    return $id_usuarios;
}

function obtener_mis_solicitudes_a_gestionar_BASE_BANCO($idUsuario,$estado,$excepcion = -1)
{

	
    $ids=null;
	$sql 		 = "SELECT personalid FROM personal WHERE (pk_depende_de = '".$idUsuario."' OR pk_depende_de LIKE '".$idUsuario."/%' OR pk_depende_de LIKE '%/".$idUsuario."' OR pk_depende_de LIKE '%/".$idUsuario."/%')";
        
        if($excepcion != -1)
            $sql .= " AND personalid != ".$excepcion;
 
        $id_usuarios = ejecutarConsultaPostgre($sql);
    
	foreach($id_usuarios as $usuarios)
	{
		$ids .= $usuarios['personalid'].',';
    }
    
	$ids = substr($ids, 0, -1);
    
	if($ids)
	{
        $sql = "SELECT * FROM solicitud sol WHERE id_usuarios IN (".$ids.") AND estado_id=".$estado." AND rechazada = 0";
        $rs = ejecutarConsulta($sql);

        return $rs;
    }
	else
        return false;
}
function obtener_mis_solicitudes_a_gestionar_revisar_aprobar_BASE_BANCO($estado) {/* HACER UNA LISTA DE LOS ID DE TODOS LOS USUARIO DE LA BASE DEL BANCO QUE TENGAN USUARIOS PENDIENTE E INSERTALOS EN EL IN DEL SELECT */
  
	$sql = "SELECT * FROM solicitud sol WHERE estado_id=".$estado." AND rechazada = 0";

	$rs = ejecutarConsulta($sql);
    return $rs;

}

function obtener_solicitudes_por_id($id_solicitudes)
{
    $sql = "SELECT * FROM solicitud WHERE id IN (".$id_solicitudes.")";
    $rs = ejecutarConsulta($sql);

    return $rs;
}

function cambiar_estados_solicitudes($id_solicitudes,$estado)
{
    $sql = "UPDATE solicitud SET estado_id = ".$estado." WHERE id IN (".
	$id_solicitudes.")";
	
   ejecutarConsulta($sql);
}

function estado_imprimir_solicitud($id_solicitudes,$estado)
{
    $sql = "UPDATE solicitud SET impreso = ".$estado." WHERE id = (".$id_solicitudes.")";
    
	$rs = ejecutarConsulta($sql);
    return $rs;
}

function ultimo_viatico_solicitud($id_solicitud)
{
    
	$sql = "SELECT max(id_visita) AS max FROM solicitud_visita WHERE id_solicitud = ".
	$id_solicitud;
	
	$rs = ejecutarConsulta($sql);
   
    return $rs[0]['max'];
}

function obtener_solicitudes_a_exportar($fecha_desde,$fecha_hasta)
{
$sql  = "SELECT * FROM solicitud";
    $sql .= " WHERE fecha_presentacion BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."' AND estado_id = ".SOLICITUD_APROBADA;
	$sql .= " ORDER BY fecha_presentacion";
	
	$rs = ejecutarConsulta($sql);
	return $rs;
}

function obtener_solicitudes($fecha_desde,$fecha_hasta,$estado)
{
    
     

	$sql  = "SELECT * FROM solicitud";
        
        if($estado == 8){
            
            $sql .= " WHERE fecha_presentacion BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."' AND rechazada = 1";
        }
        else{
            $sql .= " WHERE fecha_presentacion BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."' AND estado_id = ".$estado;
        }
        
	$sql .= " ORDER BY fecha_presentacion";
      

	$rs = ejecutarConsulta($sql);
	return $rs;
}

function existe_numero_solicitud($numero)
{
	$sql  = "SELECT count(*) AS cuantos FROM visita WHERE nro_solicitud = ".$numero." AND id_motivo = ".MOTIVO_EVAL_TERRENO;
 	$rs = ejecutarConsulta($sql);
       
	return ($rs[0]['cuantos'] > 0);
}
  function obtener_meses_rendiciones($usuario,$ano){
      
      
        $meses = array(1,2,3,4,5,6,7,8,9,10,11,12);
     
	 $sql = "SELECT mes FROM `solicitud` WHERE id_usuarios = $usuario AND ano = $ano GROUP BY mes";
    
         $rs = ejecutarConsulta($sql);

       if(empty($rs)){
           
           return $meses;
       }
   
       foreach ($rs as $row) {
           
            $mes_actual = $row["mes"];
           
            
            if (in_array($mes_actual, $meses)) {
                   unset($meses[$mes_actual-1]);
            }
        }
  return $meses;
     

}
//NUEVA FUNCION REPORTES CON KM
function obtener_solicitudes_con_kilometros($fecha_desde,$fecha_hasta,$estado)
{
$sql  = "SELECT * FROM solicitud";
    $sql .= " WHERE fecha_presentacion BETWEEN '".$fecha_desde."' AND '".$fecha_hasta."' AND estado_id = ".$estado;
	$sql .= " ORDER BY fecha_presentacion";

	$rs = ejecutarConsulta($sql);
	return $rs;
}

function obtener_kilometros_de_solicitud($id_solicitud)
{
$sql  = "select sum(km) as km from visita where id IN "
        . "(select id_visita from solicitud_visita where id_solicitud = ".$id_solicitud.");";
   
	
	$rs = ejecutarConsulta($sql);
	return $rs['0']['km'];
}

function enviar_mail_imprimir_solicitud($id_solicitud,$fecha,$id_usuario)
{
    $id = obtener_usuario_datos_solicitud($id_usuario);

    $para = obtener_usuario_dato_BASE_BANCO($id_usuario,'email');


   // $para = "laureano@marketingplus.com.ar";

    $asunto    = "Su solicitud numero " . $id_solicitud .  " no ha sido impresa";

    $mensaje   = "Su solicitud numero <b>" . $id_solicitud .  "</b> presentada el dia ".$fecha."."
              . " aun no ha sido impresa ,ingrese en el siguiente link <a href='https://rendicion.provinciamicrocreditos.com/solicitudes_autorizadas.php' target='_blank'> http://rendicion.provinciamicroempresas.com/solicitudes_autorizadas.php</a>,e imprimas las solicitudes correspondientes."
              . "<br/>Por favor, no responda este email.<br/> "
              . "<br/> Cualquier duda contactarse con Administracion.<br/>"
       . "<br/> <b>Muchas Gracias</b>.<br/>";



      sendEmail($para, $asunto, $mensaje, $path = '');
      
       $sql = "UPDATE solicitud SET mail_impreso = 1 WHERE id=$id_solicitud ";	
       
      ejecutarConsulta($sql);
       return 1;
      
}




