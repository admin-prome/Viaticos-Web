<?php
require_once 'inc/checkLogin.php';
require_once 'admin/inc/const.php';

$id_usuario = $_SESSION['id_usuario'];

require_once 'admin/inc/funciones/all_functions.php';
require_once 'inc/header.php';
require_once 'inc/menu.php';

$solicitudes_autorizadas = usuario_solicitudes_autorizada($id_usuario);
?>

<style type="text/css">
    .gestionar{	display:none;	}
</style>
<br />  <br />  <br />  <br />  <br />

<div class="separador">
<?php
if($solicitudes_autorizadas){
?>
    <h3>Vi&aacute;ticos presentados por <span style="color:gray; font-weight:bold;"><?= $_SESSION['nombre_usuario']; ?></span> correspondientes al mes de <b><?php echo(obtener_nombre_mes($solicitudes_autorizadas[0]['mes'])); ?></b> del <?php echo($solicitudes_autorizadas[0]['ano']); ?></h3>
<?php
}else{echo('<h3>Solicitudes Autorizadas</h3>');}
?>
    <div style="background-color: #eeeced;margin-top:10px;">

        <table id="table_datatable" class="display" cellspacing="0" width=" width: 800px;
               margin: 0 auto;">
            <thead style="text-align: center!important;">
                <tr style="text-align: center!important;">

                <th style="text-align: left!important;">ID Rendici&oacute;n</th>
                <th style="text-align: left!important;">Fecha Presentacion</th>
                <th style="text-align: left!important;">Mes correspondiente</th>
                <th style="text-align: left!important;">Estado</th>
                <th style="text-align: left!important;">Importe</th>
                <th style="text-align: left!important;">comentario</th>
                <th style="text-align: left!important;">&nbsp;</th>
                <th style="text-align: left!important;">&nbsp;</th>

                </tr>
            </thead>

            <tbody>
                <?php
                $ya_impreso='';
                $divsComentarios = '';
                if ($solicitudes_autorizadas) {
                    foreach ($solicitudes_autorizadas as $row) {
                        $importe = obtener_importe_solicitud($row["id"]);
                        $impreso = 'botones';
                        if ($row["impreso"] == 1) {
                           // $impreso = 'botones_disabled';
                            //Me parece mejor dejar que los usuarios impriman cuantas veses quieran las rendiciones , si no descomentar la linea de arriba y borra la de abajp
                            $impreso = 'botones';
                            //$ya_impreso = 'disabled="disabled"';
                        }
                        echo("<tr id='" . $row["id"] . "'>");
                        
						echo("<td>".$row["id"]."</td>");
						echo("<td>" .presentarFechaDateTime($row["fecha_presentacion"]) . "</td>");
                        echo("<td>" . obtener_nombre_mes($row["mes"]) . "</td>");
                        echo("<td>" . guion(obtener_estado_por_id($row["estado_id"])) . "</td>");
                        echo("<td>" . formatearMoneda($importe[0]["importe"]) . "</td>");
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
                        echo("<td> <input $ya_impreso id='impreso_".$row['id']."' class='" . $impreso . " imprimir_solicitud' type='button' value='Imprimir' onclick='disparar_impresion(".$row['id'].");'/></td>");
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
<div id="tabla_solicitudes_detalles" >
</div>
<div id="historial" > 

</div>
<script type="text/javascript">
 function disparar_impresion(id_sol)
        { 
            alert('Recuerde que debe imprimir  , firmar y enviar esta rendicion a las oficinas de administracion.');
            window.open("imprimir_solicitudes_autorizadas.php?id_solicitud="+id_sol+ "");
        };
    $(document).ready(function()
    {
        $(function() {

            $("#fecha_viatico").datepicker();
        });

        $(".ver_comentario").click(
                function()
                {
                    var id_td_comentario = (this.id).split("_");

                    $("#comentario_" + id_td_comentario).dialog({
                        resizable: false,
                        height: 150,
                        width: 450,
                        modal: true,
                        show: {effect: 'blind', duration: 400},
                        hide: {effect: 'explode', duration: 1000},
                        resizable: false
                    });
                }
        );

        $(".ver_solicitud").click(
                function()
                {
                    var id_solicitud = (this.id)

                    $("#tabla_solicitudes_detalles").load("tabla_solicitudes_detalles.php?id_solicitud=" + id_solicitud + "");

                }
        );

       
    });

</script>
<?php
include_once 'inc/footer.php';
?>