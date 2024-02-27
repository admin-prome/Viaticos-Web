<?php

/*
$fp = fopen('testing_params_app_mobile.txt','a');
foreach($_POST as $key => $value)
{
	fwrite($fp,$key.': '.$value."\n");	
}

fwrite($fp,"\n\n");

fclose($fp);
echo 1;
die();
*/

function guardar_visita_celular()
{
	// Estos 6 campos son comunes a todos los formularios:
	$motivo   = $_POST['id_motivo_visita'];
	$usuario  = $_POST['id_usuario'];
	$concepto = $_POST['transporte'];
	
	if($concepto == CONCEPTO_TAXI_REMIS || $concepto == CONCEPTO_TRANS_PUBLICO)
            $costo_trans = $_POST['costo_transporte'];
        else
            $costo_trans = '';
		
	$origen  = $_POST['id_origen'];
	$destino = $_POST['coordenadas'];
	
	// Fecha y hora del celu: A partir de la Version 1.5
	if(isset($_POST['fechahora']))
		$fechahora = $_POST['fechahora'];
	else
		$fechahora = date("Y-m-d H:i:s");
	
	$sql = "INSERT INTO visita (cargado_en_celular,fecha,id_estado,id_motivo,id_usuario,id_concepto,origen,destino,importe";
        
  	switch($motivo)
	{
		case '1':
	
			$nro_solicitud 	= $_POST['nro_solicitud'];
			$monto 		= $_POST['monto'];
			$plazo 		= $_POST['plazo'];
			$segmento 	= $_POST['id_segmento'];
			
			// Visita Exitosa: A partir de la Version 1.4
			if(isset($_POST['visita_exitosa']))
			{
				$visita_exitosa = $_POST['visita_exitosa'];
			}
			
			/*
			if(existe_numero_solicitud($nro_solicitud))
			{
				echo 'repite_solicitud';
				die();
			}
			*/	
			
			$sql .= ",nro_solicitud, monto, plazo, id_segmento";
			
			if(isset($visita_exitosa))
			{
				$sql .= ",visita_exitosa";
			}
			
			$sql .= ") VALUES (1,'".$fechahora."',1,".$motivo.",".$usuario.",".$concepto.",'".$origen."','".$destino."','".$costo_trans."',".$nro_solicitud.",'".$monto."','".$plazo."',".$segmento;
			
			if(isset($visita_exitosa))
			{
				$sql .= ",".$visita_exitosa;
			}
			
			$sql .= ')';
                        
		break;
	
		case '9':

			$nro_solicitud = $_POST['nro_solicitud'];
			
			// Visita Exitosa: A partir de la Version 1.4
			if(isset($_POST['visita_exitosa']))
			{
				$visita_exitosa = $_POST['visita_exitosa'];
			}
			
			$sql .= ",nro_solicitud";
			
			if(isset($visita_exitosa))
			{
				$sql .= ",visita_exitosa";
			}
						
			$sql .= ") VALUES (1,'".$fechahora."',1,".
			$motivo.",".$usuario.",".$concepto.",'".$origen."','".$destino."','".$costo_trans."',".$nro_solicitud;
			
			if(isset($visita_exitosa))
			{
				$sql .= ",".$visita_exitosa;
			}
			
			$sql .= ')';                        
             
		break;
			
		default:
			
			$sql .= ") VALUES (1,'".$fechahora."',1,".
			$motivo.",".$usuario.",".$concepto.",'".$origen."','".$destino."','".$costo_trans."'";
							
			$sql .= ')';
                        
      	break;
	
	}
		
	$rs = ejecutarConsulta($sql);
	$solicitud_activa = obtener_id_solicitud_activa($usuario);
	
	if(empty($solicitud_activa))
	{
		insertar_solicitud($usuario);
		$solicitud_activa = obtener_ultimo_id('solicitud');
	}
	
	$sql = "INSERT INTO solicitud_visita (id_solicitud,id_visita) VALUES (".$solicitud_activa.
	",".obtener_ultimo_id('visita').")";
	
	ejecutarConsulta($sql);
		
	return 1;
}

require '../admin/inc/const.php';
require '../admin/inc/funciones/querys_mysql.php';
require '../admin/inc/funciones/solicitudes.php';

if(!isset($_POST['id_motivo_visita']) || !isset($_POST['id_usuario']) || !isset($_POST['id_origen']) || !isset($_POST['coordenadas']) || !isset($_POST['transporte']))
{
	die("-1");
}

echo guardar_visita_celular();