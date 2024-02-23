<?php

function obtener_motivos($ids_motivos = NULL)
{
	$sql = "SELECT * FROM motivos";
	
	if(is_array($ids_motivos))
	{
		$sql .= " WHERE id IN (".implode(',',$ids_motivos).")";	
	}
	
	$sql .= " ORDER BY descripcion";
	
	$rs = ejecutarConsulta($sql);
	return $rs;
}

function obtener_motivo($id,$encodear=true)
{
	$sql = "SELECT descripcion FROM motivos WHERE id = ".$id;
	$rs  = ejecutarConsulta($sql);
	
	if($encodear)
		return utf8_encode($rs[0]['descripcion']);
	else
		return $rs[0]['descripcion'];	
}


function permisos_motivo_cobranza($id){
    
    $permiso = false;
    
    switch($id){
        
        case 619:
        $permiso = true;
        break;
    
        case 620:
        $permiso = true;
        break;
    
        case 621:
        $permiso = true;
        break;
    
     case 616:
        $permiso = true;
        break;
    
     case 623:
        $permiso = true;
        break;

	     case 563:
        $permiso = true;
        break;

     case 316:
        $permiso = true;
        break;

     case 314:
        $permiso = true;
        break;

     case 163:
        $permiso = true;
        break;

     case 343:
        $permiso = true;
        break;

     case 315:
        $permiso = true;
        break;

     case 54:
        $permiso = true;
        break;
    
     case 387:
        $permiso = true;
        break;
   
    case 622:
        $permiso = true;
        break;
 
         
    }
    
    return $permiso;

    
    
}

