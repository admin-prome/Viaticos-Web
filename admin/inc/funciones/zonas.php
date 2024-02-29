<?php
function obtener_zonas_BASE_BANCO()
{
    $sql = "SELECT * FROM zona ORDER BY zona";
    
	$rs = ejecutarConsultaPostgre($sql);
    return $rs;
}

function update_costo_km_BASE_BANCO($costo_auto,$costo_moto,$id_zona)
{
    
	if(substr_count($costo_auto,",") == 1)
		$costo_auto = str_replace(",",".",$costo_auto);
	
	if(substr_count($costo_moto,",") == 1)
		$costo_moto = str_replace(",",".",$costo_moto);	
	
	$sql = "UPDATE zona SET costo_km_auto = ".$costo_auto." , costo_km_moto = ".$costo_moto." WHERE zonaid = ".$id_zona;
    
	ejecutarConsultaPostgre($sql);
	
	$ids_update = array();
	
	$sql = "SELECT personalid FROM personal_asistentecomercial WHERE pk_zona = ".$id_zona;
	$rs  = ejecutarConsultaPostgre($sql);
	
	foreach($rs as $id)
	{
		$ids_update[] = $id['personalid'];
	}
	
	$sql = "SELECT pk_personal FROM personal_ejecutivocomercial WHERE pk_zona = ".$id_zona;
	$rs  = ejecutarConsultaPostgre($sql);

	foreach($rs as $id)
	{
		$ids_update[] = $id['pk_personal'];
	}

	$sql = "UPDATE personal SET costo_km_auto = ".$costo_auto." , costo_km_moto = ".$costo_moto. " WHERE personalid IN (".implode(",",$ids_update).")";
		
	ejecutarConsultaPostgre($sql);
}