<?php
function obtener_estado_por_id($id)
{
    $sql = "select * from estado WHERE id=".$id;
    $rs = ejecutarConsulta($sql);
    return $rs[0]['descripcion'];
}


function conocer_siguiente_estado($estado)
{
    switch($estado)
	{
        case SOLICITUD_PRESENTADA:
        	return SOLICITUD_AUTORIZADA;
     
	 	case SOLICITUD_AUTORIZADA:
       		return SOLICITUD_REVISADA;
     
	 	case SOLICITUD_REVISADA:
         	return SOLICITUD_APROBADA;
     
	 	case SOLICITUD_APROBADA:
        	return SOLICITUD_EXPORTAR;
    }

}

function nombre_accion_estado($estado)
{
    switch($estado)
	{
        case SOLICITUD_PRESENTADA:
        	return 'Presentar';
     
	 	case SOLICITUD_AUTORIZADA:
       		return 'Autorizar';
     
	 	case SOLICITUD_REVISADA:
         	return 'Revisar';
     
	 	case SOLICITUD_APROBADA:
        	return 'Aprobar';
	 	case SOLICITUD_EXPORTAR:
        	return 'Exportar';
   	}
}
function nombre_accion_estado_pasado($estado)
{
    switch($estado)
	{
        case SOLICITUD_PRESENTADA:
        	return 'Presentados';
     
	 	case SOLICITUD_AUTORIZADA:
       		return 'Autorizados';
     
	 	case SOLICITUD_REVISADA:
         	return 'Revisados';
     
	 	case SOLICITUD_APROBADA:
        	return 'Aprobados';
	 	case SOLICITUD_EXPORTAR:
        	return 'Exportados';
   	}
}

function conocer_nombre_accion_siguiente_estado($estado)
{
    switch($estado)
	{
        case SOLICITUD_PRESENTADA:
        	return 'Autorizar';
     
	 	case SOLICITUD_AUTORIZADA:
       		return 'Revisar';
     
	 	case SOLICITUD_REVISADA:
         	return 'Aprobar';
     
	 	case SOLICITUD_APROBADA:
        	return 'Exportar';
   	}
}