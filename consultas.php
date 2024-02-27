<?php
require_once 'inc/checkLogin.php';
require_once 'inc/const.php';

require_once 'inc/header.php';

// require_once 'inc/menu_administrador.php';

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
?>

<style type="text/css">
.separador h3{display:none;}    
.separador h2{padding-top: 18px;
padding-left: 12px;}  
#detalle_solicitud_filter{padding-top: 20px;}
#detalle_solicitud_length{padding-top: 20px;}
#table_datatable_paginate{padding-top: 30px;padding-bottom: 30px!important;}
</style>
<h1 class="ayudas" style="font-size: 16px;">Consulta de solicitudes y viaticos realizados</h1>

<form action="" method="post" class="ayudas" onsubmit="return validarFechasDesdeHasta();">
   <p class="ayudas">Seleccione una fecha de inicio y otra de fin , y el estado de las rendiciones que desea ver:</p>
   <ul class="busquedas">
    <li><p><b>Inicio:</b></p>	<input style="font-size: 11px;width: 70px;margin-left: 10px;" type="text" name="fecha_desde" id="fecha_desde" value="<?= @ $_POST['fecha_desde']; ?>"  placeholder="<?= obtenerFechaActualDDMMAAAA(); ?>" /></li>

    <li><p><b>Fin:</b></p> 	<input style="font-size: 11px;width: 70px;margin-left: 10px;" type="text" name="fecha_hasta" id="fecha_hasta" value="<?= @ $_POST['fecha_hasta']; ?>" placeholder="<?= obtenerFechaActualDDMMAAAA(); ?>" /></li>

    <li>
    	<p style="font-weight:bold;">Estado de las solicitudes</p>
        
       <select style="margin-left: 10px;" name="estado">
        	
       <option value="<?= SOLICITUD_PRESENTADA ?>"
       <?php if(@ $_POST['estado'] == SOLICITUD_PRESENTADA) echo 'selected'; ?>>Presentadas</option>
       
       <option value="<?= SOLICITUD_AUTORIZADA ?>"
       <?php if(@ $_POST['estado'] == SOLICITUD_AUTORIZADA) echo 'selected'; ?>>Autorizadas</option>
       
       <option value="<?= SOLICITUD_REVISADA ?>"
       <?php if(@ $_POST['estado'] == SOLICITUD_REVISADA) echo 'selected'; ?>>Revisadas</option>
       
        <option value="<?= SOLICITUD_APROBADA ?>"
       <?php if(@ $_POST['estado'] == SOLICITUD_APROBADA) echo 'selected'; ?>>Aprobadas</option>
       
       <option value="<?= SOLICITUD_EXPORTAR ?>"
       <?php if(@ $_POST['estado'] == SOLICITUD_EXPORTAR) echo 'selected'; ?>>Exportadas</option>

        </select>
    
    </li> 
    <li style="clear:left;float:none!important;">
   
   	<br /><br />
   
   <input
   style="clear:left; float:none!important;line-height: 6px!important;"
   type="submit" value="Buscar" class="botones" id="buscar" />
   
   <div class="load" style="display:none;">
       <div class="loading_chico" style="margin-top:-29px!important;margin-left: 321px"></div>
      
   </div></li></ul>
   
</form>

<?php
if(sizeof($_POST) > 0)
{
    
	$solicitudes_a_exportar = '';
    
	$fecha_desde 	= $_POST['fecha_desde'];
    $fecha_hasta 	= $_POST['fecha_hasta'];
	$estado 		= $_POST['estado'];

    $partesFecha = explode('/', $fecha_desde);
    $fecha_desde = $partesFecha[2].'-'.$partesFecha[1].'-'.$partesFecha[0].
	' 00:00:00';

    $partesFecha = explode('/', $fecha_hasta);
    $fecha_hasta = $partesFecha[2].'-'.$partesFecha[1].'-'.$partesFecha[0].
	' 23:59:59';

    //$solicitudes_a_exportar = obtener_solicitudes_a_exportar($fecha_desde, $fecha_hasta);
    $solicitudes = obtener_solicitudes($fecha_desde, $fecha_hasta ,$estado);

    if($solicitudes)
	{
    ?>
<div class="separador" style="margin-top:20px!important; margin-bottom: 20px!important;">
    <h2> Viaticos  <b><?= nombre_accion_estado_pasado($estado)?> </b> desde el  <b><?= presentarFechaSinHora($fecha_desde)?></b> hasta el <b><?= presentarFechaSinHora($fecha_hasta)?></b></h2>

            <div style="background-color: #eeeced;margin-top:10px;">
            <table id="table_datatable" class="display" cellspacing="0" width=" width: 800px;
               margin: 0 auto;">
            <thead style="text-align: center!important;">
                
            <tr style="text-align: center!important;">

            	<th style="text-align: left!important;">Nro. Rendici&oacute;n</th>
                <th style="text-align: left!important;">Usuario</th>
                <th style="text-align: left!important;">Fecha Presentacion</th>
                <th style="text-align: left!important;">Mes correspondiente</th>
                <th style="text-align: left!important;">Estado</th>
                <th style="text-align: left!important;">Importe</th>
                <th style="text-align: left!important;">comentario</th>
                <th style="text-align: left!important;">&nbsp;</th>

            </tr>
            
            </thead>

            <tbody>
<?php
$divsComentarios = '';
if ($solicitudes)
{
    foreach ($solicitudes as $row)
	{
        $importe = obtener_importe_solicitud($row["id"]);
        //echo( obtener_importe_solicitud($row["id"]));
        echo("<tr id='" . $row["id"] . "'>");
        
		echo("<td>" . $row["id"] . "</td>");
		echo("<td>" .obtener_nombre_usuario_BASE_BANCO($row["id_usuarios"]). "</td>");
		echo("<td>" . presentarFechaDateTime($row["fecha_presentacion"]) . "</td>");
        echo("<td>" . obtener_nombre_mes($row["mes"]) . "</td>");
        echo("<td>" . guion(obtener_estado_por_id($row["estado_id"])) . "</td>");
        echo("<td>$ " . formatearMoneda($importe[0]["importe"] ). "</td>");
        if (!empty($row["observacion"])) {

            echo('<td class="ver_comentario" id="' . $row["id"] . '">');
            echo('<span style="cursor: pointer;">Click aqui</span></td>');
            $divsComentarios .= '<div title="Observaciones:" id="comentario_' .
                    $row["id"] . '" style="display:none;background-color:white;">' .
                    $row["observacion"] . "</div>\n";
        } else {
            echo('<td> - </td>');
        }
        echo("<td> <input id='" . $row['id'] . "' class='botones ver_solicitud' type='button' value='Ver'/></td>");
        echo("</tr> ");
        ?>

                        <?php
                    }
                }
                ?>

            </tbody>
        </table>
<?= $divsComentarios; ?>

            </div>
        </div>
 <div id="tabla_solicitudes_detalles"></div>

       
        <?php
    } else {
        echo('<h1 class="ayudas">No existen rendiciones .</h1>');
}}
    ?>

<script type="text/javascript">

    $(document).ready(function() {
        $('#table_datatable').dataTable({
            "paging": true,
            "ordering": true,
            "order": [[1, "desc"]],
            "info": false,
            "search": true
        });
		
		$.datepicker.regional['es'] =
		{
                closeText: 'Cerrar',
                prevText: '<Ant',
                nextText: 'Sig>',
                currentText: 'Hoy',
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                dayNames: ['Domingo', 'Lunes', 'Martes', 'MiÃ©rcoles', 'Jueves', 'Viernes', 's&aacute;bado '],
                dayNamesShort: ['Dom', 'Lun', 'Mar', 'MiÃ©', 'Juv', 'Vie', 's&aacute;b'],
                dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S&aacute;'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay:0,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
        };
        
		$.datepicker.setDefaults($.datepicker.regional['es']);

        $(function() {
            $("#fecha_desde").datepicker();
            $("#fecha_hasta").datepicker();
        });

    });
  $(".ver_solicitud").click(
                    function()
                    {
                        var id_solicitud = (this.id)

                        $("#tabla_solicitudes_detalles").load("tabla_solicitudes_detalles_admin.php?id_solicitud=" + id_solicitud + "");
               
                    }
            );
    
	/*
	$("#buscar").click(function() {
        $(".load").css('display', 'inline');
    });
	*/

    function validarFechasDesdeHasta()
    {
        var desde = $("#fecha_desde").val();
        var hasta = $("#fecha_hasta").val();

        if (desde == '')
        {
            alert('Ingrese una fecha de inicio');
            $("#fecha_desde").focus();
            return false;
        }

        if (hasta == '')
        {
            alert('Ingrese una fecha de fin');
            $("#fecha_hasta").focus();
            return false;
        }
		
		$(".load").css('display', 'inline');
        return true;
    }

</script>


<?php
include 'inc/footer.php';
?>
</body>
</html>