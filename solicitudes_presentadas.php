<?php
require_once 'inc/checkLogin.php';
require_once 'admin/inc/const.php';

$id_usuario = $_SESSION['id_usuario'];

require_once 'admin/inc/funciones/all_functions.php';
require_once 'inc/header.php';
require_once 'inc/menu.php';

$solicitudes_presentadas = usuario_solicitudes_presentadas($id_usuario);

?>
<br /><br /><br />
<style type="text/css">
    #footer{position: relative;margin-top:10%;}
	.gestionar{display:none;}
</style>
<br /><br /><br /><br /><br />

<div class="separador">
       
         <h3> Vi&aacute;ticos presentados por <span style="color:gray; font-weight:bold;"><?= $_SESSION['nombre_usuario']; ?></span> correspondientes al mes de <b><?php echo(obtener_nombre_mes($solicitudes_presentadas[0]['mes']));?></b> del <?php echo($solicitudes_presentadas[0]['ano']);?></h3>
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

            </tr>
        </thead>

        <tbody>
            <?php
            $divsComentarios = '';
            if ($solicitudes_presentadas) {
                foreach ($solicitudes_presentadas as $row) {
                    $importe = obtener_importe_solicitud($row["id"]);
                    //echo( obtener_importe_solicitud($row["id"]));
                    
                    echo("<tr id='" . $row["id"] . "'>");
                    
					echo("<td>".$row["id"]."</td>");
					echo("<td>" . presentarFechaDateTime($row["fecha_presentacion"]) . "</td>");
                    echo("<td>" . obtener_nombre_mes($row["mes"]) . "</td>");
                    echo("<td>" . guion(obtener_estado_por_id($row["estado_id"])) . "</td>");
                    echo("<td> $" . formatearMoneda($importe[0]["importe"]) . "</td>");
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
<div id="tabla_solicitudes_detalles">

</div>

<script type="text/javascript">


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
                        width:450,
                        modal: true,
                        show: {effect: 'blind', duration: 400},
                        hide: {effect: 'explode', duration: 1000},
                        resizable: false
                    });
                }
        );

        $(".ver_solicitud").click(function()
        {
           

            var id_solicitud = (this.id);

            $("#tabla_solicitudes_detalles").load("tabla_solicitudes_detalles.php?id_solicitud=" + id_solicitud + "");
            
        }
        );
    });

</script>
        <?php
         
        include_once 'inc/footer.php';
        ?>