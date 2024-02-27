<?php
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="exportadas.xls"');

require_once 'inc/const.php';
require_once 'inc/checkLogin.php';
require_once 'inc/funciones/all_functions_sin_admin.php';

/*
require_once 'inc/funciones/cargos.php';
require_once 'inc/funciones/conceptos.php';
require_once 'inc/funciones/estados.php';
require_once 'inc/funciones/exportaciones.php';
require_once 'inc/funciones/fechas.php';
require_once 'inc/funciones/funciones_genericas.php';
require_once 'inc/funciones/kilometros.php';
require_once 'inc/funciones/login.php';
require_once 'inc/funciones/motivos.php';
require_once 'inc/funciones/perfiles_viaticos.php';
require_once 'inc/funciones/querys_mysql.php';
require_once 'inc/funciones/segmentos.php';
require_once 'inc/funciones/seguridad.php';
require_once 'inc/funciones/solicitudes.php';
require_once 'inc/funciones/sucursales.php';
require_once 'inc/funciones/usuarios.php';
require_once 'inc/funciones/viaticos.php';
require_once 'inc/funciones/zonas.php';
*/



$hasta= pasarFechaDatePickerAFormatoDate($_GET['hasta']);

$desde= pasarFechaDatePickerAFormatoDate($_GET['desde']);

$viaticos_exportar = viaticos_para_exportar($desde,$hasta);
//var_dump($viaticos_exportar);

?>

	<table border="1">
		
		<tr style="font-weight: bold;">
			<td>Legajo</td>
			<td>Id usuario</td>
			<td>Nombre usuario</td>
			<td>Fecha de Rendici&oacute;n</td>
			<td>Concepto</td>
			<td>Fecha</td>
			<td>Cantidad</td>
			<td>Importe</td>
			<td>Fecha Desde</td>
			<td>Fecha Hasta</td>
		</tr>
		
		<?php
		foreach($viaticos_exportar as $vexp)
		{
		?>
		<tr>
		
        	<td>
				<?= obtener_legajo_usuario_BASE_BANCO($vexp['id_usuario']); ?>
        	</td>
        	<td>
				<?= $vexp['id_usuario']; ?>
        	</td>
        	<td>
				<?= obtener_nombre_usuario_BASE_BANCO($vexp['id_usuario']); ?>
        	</td>
			
            <td>
				<?= presentarFechaSinHora($vexp['fecha_presentacion']); ?>
            </td>
			
            <td>
<?= generar_concepto_exportacion($vexp['id_concepto'],$vexp['id_motivo']); ?>
            </td>
			
            <td><?= presentarFechaSinHora($vexp['fecha']); ?></td>
			<td>1</td>
			<td style='mso-number-format:"0.00"'>$ <?= formatearMonedaCalipso($vexp['importe']); ?></td>
			
            <td>
	<?= presentarFechaSinHora(fecha_desde_hasta_exportacion_viatico($vexp['id_solicitud'],'desde')); ?>
       		</td>
	
    		<td>
	<?= presentarFechaSinHora(fecha_desde_hasta_exportacion_viatico($vexp['id_solicitud'],'hasta')); ?>
    		</td>
			
        </tr>
		
		<?php
		}
		
	
		?>
	
	</table>