<?php
require_once 'inc/checkLogin.php';
require_once 'inc/const.php';

$id_usuario = $_SESSION['id_usuario'];

require_once 'inc/header.php';
require_once 'inc/menu_administrador.php';

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
*/

?>

<h1 class="ayudas" style="font-size: 16px;" >Viaticos para exportar:</h1>

<form action="" method="post" class="ayudas" onsubmit="return validarFechasDesdeHasta();">
	
    <p class="ayudas">Seleccione una fecha de inicio y otra de fin:</p>
	
    Inicio:	<input style="font-size: 11px;width: 70px;" type="text" name="fecha_desde" id="fecha_desde" value="<?= @ $_POST['fecha_desde']; ?>"  placeholder="<?= obtenerFechaActualDDMMAAAA(); ?>" />
	&nbsp;&nbsp;
	Fin: 	<input  style="font-size: 11px;width: 70px;" type="text" name="fecha_hasta" id="fecha_hasta" value="<?= @ $_POST['fecha_hasta']; ?>" placeholder="<?= obtenerFechaActualDDMMAAAA(); ?>" />
	
        &nbsp;<input type="submit" value="Buscar" class="botones" id="buscar" style="line-height: 6px!important;"/><div class="load" style="display:none;"><div class="loading_chico" style="margin-top:-29px!important;margin-left: 321px"></div><span ></span></div>
	
</form>

<?php
if(sizeof($_POST) > 0)
{
	$viaticos_exportar = viaticos_para_exportar($_POST['fecha_desde'],$_POST['fecha_hasta']);
	
	if($viaticos_exportar)
	{
?>
	<br />
	<input type="button" value="Exportar" class="botones" onclick="disparar_exportacion();" />
	<br /><br />
	 <div style="background-color: #eeeced;margin-top:10px;">

        <table id="table_datatable" class="display" cellspacing="0" width=" width: 800px;
               margin: 0 auto;">
            <thead style="text-align: center!important;">
		
		<tr>
			<th>Legajo</th>
                        <th>Fecha de Rendici&oacute;n</th>
			<th>Concepto</th>
			<th>Fecha</th>
			<th>Cantidad</th>
			<th>Importe</th>
			<th>Fecha Desde</th>
			<th>Fecha Hasta</th>
		</tr>
            </thead>   <tbody>
<?php	
		foreach($viaticos_exportar as $vexp)
		{
?>
			<tr>
				<td><?= obtener_legajo_empleado_BASE_BANCO($id_usuario); ?></td>
				<td><?= $vexp['fecha_presentacion']; ?></td>
				<td><?= generar_concepto_exportacion($vexp['id_concepto'],$vexp['id_motivo']); ?></td>
				<td><?= formatearFechaDDMMAAAA($vexp['fecha'],"-","/"); ?></td>
				<td>1</td>
				<td>$ <?= $vexp['importe']; ?></td>
				<td><?= $_POST['fecha_desde']; ?></td>
				<td><?= $_POST['fecha_hasta']; ?></td>
			</tr>
<?php
		}
?>
	  </tbody></table>
<?php
	}
}
?>

<script type="text/javascript">

$(document).ready(function(){
         $('#table_datatable').dataTable({
                    "paging": true,
                    "ordering": true,
                    "order": [[1, "desc"]],
                    "info": false,
                    "search": true
                });
	 
		$(function() {
            $("#fecha_desde").datepicker();
			$("#fecha_hasta").datepicker();
        });
		
});
  $("#buscar").click(
                    function() {
                        $(".load").css('display','inline');
                    }
        );
function validarFechasDesdeHasta()
{
	var desde = $("#fecha_desde").val();
	var hasta = $("#fecha_hasta").val();
	
	if(desde == '')
	{
		alert('Ingrese una fecha de inicio');
		$("#fecha_desde").focus();
		return false;
	}
	
	if(hasta == '')
	{
		alert('Ingrese una fecha de fin');
		$("#fecha_hasta").focus();
		return false;
	}
	
	return true;
}	

function disparar_exportacion()
{
	var desde = $("#fecha_desde").val();
	var hasta = $("#fecha_hasta").val();
	
	window.open("exportar_solicitudes_aprobadas_nuevo.php?desde=" + desde + "&hasta=" + hasta);
}

</script>

</div> <!--contenedor-->

</body>
</html>