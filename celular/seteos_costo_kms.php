<?php

$sql_where = '';
$sql_orderby = '';

$sql_select_from  = "SELECT V.id,V.origen,V.destino,V.id_concepto FROM solicitud_visita SV INNER JOIN solicitud S ON SV.id_solicitud = S.id INNER JOIN visita V ON SV.id_visita = V.id";

$sql_where .= " WHERE V.id_usuario = ".$id_usuario." AND SV.id_solicitud = ".$solicitud_activa." AND V.cargado_en_celular = ".VIATICO_CARGADO_POR_CELU." AND (V.importe IS NULL OR V.importe = '') AND id_concepto IN (".CONCEPTO_AUTO.",".CONCEPTO_MOTO.")";

$sql_orderby .= " ORDER BY id ASC";

$sql = $sql_select_from.$sql_where.$sql_orderby;
$rs  = ejecutarConsulta($sql);

// echo '<div style="display:none;">'.$sql.'</div>';

$actualizando_costos_kms_celu = false;

if(sizeof($rs) > 0)
{
	$actualizando_costos_kms_celu = true;	
}

if($actualizando_costos_kms_celu) // si hay algo que actualizar...
{

echo '<br /><br /><br /><br />';
echo '<div align="center">';
	echo '<img src="images/loading_process.gif" alt="procesando" />';
	echo '<h2>Procesando Vi&aacute;ticos, espere por favor.</h2>';
echo '</div>';

for($i = 0; $i < sizeof($rs); $i++)
{
	$id_visitas[] = $rs[$i]['id'];
	
	if($i == 0)
	{
		$id_primer_viatico = $rs[$i]['id'];
	}
	
	$origenes_viaticos[]  = $rs[$i]['origen'];
	$destinos_viaticos[]  = $rs[$i]['destino'];
	$conceptos_viaticos[] = $rs[$i]['id_concepto'];
	
	if($rs[$i]['id_concepto'] == CONCEPTO_AUTO)
    {
		$costos_kms_viaticos[] = $_SESSION['costo_km_auto'];
    }
    elseif($rs[$i]['id_concepto'] == CONCEPTO_MOTO)
    {
		$costos_kms_viaticos[] = $_SESSION['costo_km_moto'];
    }
	
	if($rs[$i]['origen'] == VIATICO_ORIGEN_VISITA_ANTERIOR)
	{
		if($i == 0)
		{	
			$viat_ant = buscar_viatico_anterior($rs[$i]['id'], $solicitud_activa);
			
			if($viat_ant)
			{	
				$destinos_regs_anteriores[] = obtener_campo_por_tabla_e_id('destino',$viat_ant,'visita');
			}
			else
			{	
				//Parche... Esto NO deberia suceder NUNCA.
				setearViaticoCeluOrigenEnSucursal($rs[$i]['id']);
				$rs[$i]['origen'] = VIATICO_ORIGEN_SUCURSAL;
				$destinos_regs_anteriores[] = -1;
				// Fin parche
			}	
		}	
		else
			$destinos_regs_anteriores[] = $rs[$i - 1]['destino'];
	}
	else
	{
		$destinos_regs_anteriores[] = -1;
	}
}
?>

<script type="text/javascript">
	
	var id_visitas_array = new Array();
    <?php
        
		for($i = 0; $i < count($id_visitas); $i++)
		{
			echo 'id_visitas_array['.$i.'] = '. $id_visitas[$i].";\n";
        }
    ?>
	 
	var origenes_array = new Array();
    <?php
        
		for($i = 0; $i < count($origenes_viaticos); $i++)
		{
			echo 'origenes_array['.$i.'] = '. $origenes_viaticos[$i].";\n";
        }
    ?>
	 
	var destinos_array = new Array();
    <?php
        
		for($i = 0; $i < count($destinos_viaticos); $i++)
		{
			echo 'destinos_array['.$i.'] = "'.$destinos_viaticos[$i].'"'.";\n";
        }
    ?>
	 
	var conceptos_array = new Array();
    <?php
        
		for($i = 0; $i < count($conceptos_viaticos); $i++)
		{
			echo 'conceptos_array['.$i.'] = '. $conceptos_viaticos[$i].";\n";
        }
    ?>
	 
	var costos_kms_array = new Array();
    <?php
        
		for($i = 0; $i < count($costos_kms_viaticos); $i++)
		{
			echo 'costos_kms_array['.$i.'] = '. $costos_kms_viaticos[$i].";\n";
        }
    ?>
	 
	var destinos_regs_anteriores = new Array();
    <?php
        
		for($i = 0; $i < count($destinos_regs_anteriores); $i++)
		{
            echo 'destinos_regs_anteriores['.$i.'] = "'. $destinos_regs_anteriores[$i].
			'"'.";\n";
        }
    ?>
	
	proceso_actualizar_costos_kms_datos_celu('<?= $coords_sucursal_usuario; ?>',<?= $id_primer_viatico; ?>,id_visitas_array,origenes_array,destinos_array,destinos_regs_anteriores,costos_kms_array,<?= $solicitud_activa; ?>);
	
</script>

<?php
} // end if.
?>