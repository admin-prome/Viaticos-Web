
<?php
require_once 'inc/checkLogin.php';
require_once 'admin/inc/const.php';

$id_usuario = $_SESSION['id_usuario'];

require_once 'admin/inc/funciones/all_functions.php';
require_once 'inc/header.php';
require_once 'inc/menu.php';


$solicitudes_rechazadas = usuario_solicitudes_rechazada($id_usuario);
?>

<style type="text/css">
    #footer{position: relative;margin-top: 20%;}
    .gestionar{display:none;}
</style>
<br />  <br />  <br />  <br />  <br />

<div class="separador">

    <h3>Mis rendiciones rechazadas</h3>

    <table id="table_datatable" class="display" cellspacing="0"
           style="margin: 0 auto;">
        <thead style="text-align: left!important;">
            <tr style="text-align: left!important;">

                <th>Nro.</th>
                <th>Fecha Presentacion</th>
                <th>Mes correspondiente</th>
                <th>Instancia del rechazo</th>
                <th>Importe</th>
                <th>Motivo del rechazo</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>

            </tr>
        </thead>

        <tbody>
            <?php
            $divsComentarios = '';

            if ($solicitudes_rechazadas) {

                foreach ($solicitudes_rechazadas as $row) {
                    $importe = obtener_importe_solicitud($row["id"]);
                    // echo( obtener_importe_solicitud($row["id"]));
                    ?>	

                    <tr id="fila_<?= $row["id"]; ?>">

                        <td><?= $row["id"]; ?></td>	
                        <td><?= presentarFechaDateTime($row["fecha_presentacion"]); ?></td>
                        <td><?= obtener_nombre_mes($row["mes"]); ?></td> 
                        <td><?= guion(obtener_estado_por_id($row["estado_id"])); ?></td>
                        <td><?= formatearMoneda($importe[0]["importe"]); ?></td> 

        <?php
        if (!empty($row["observacion"])) {
            ?>

                            <td class="ver_comentario" id="vercomentario_<?= $row["id"]; ?>">
                                <span style="cursor: pointer;">Click aqui</span>
                            </td>

            <?php
            $divsComentarios .= '<div title="Observaciones:" id="comentario_' .
                    $row["id"] . '" style="display:none; background-color:white;">' .
                    $row["observacion"] . "</div>\n";
        } else {
            ?>
                            <td> - </td>
                            <?php
                        }
                        ?>

                        <td>
                            <input id="versolicitud_<?= $row['id']; ?>" type="button"
                                   class="botones ver" value="Ver" />

        <!--estado="<?php // $row['estado_id'];  ?>"-->
                        </td>

                        <td>
                            <input class="botones eliminar" type="button" value="Eliminar"
                                   id="eliminarsolicitud_<?= $row['id']; ?>" />
                        </td>

                    </tr>

        <?php
    }
}
?>  

        </tbody>
    </table>
<?= $divsComentarios; ?>

</div>

<div id="tabla_solicitudes_detalles"></div>
<input type="hidden" value="" id="solicitud_activa" />

<script type="text/javascript">
var url_pagina='solicitudes_rechazadas.php';
    $(document).ready(function ()
    {
        $(function () {
            $("#fecha_viatico").datepicker();
        });

        $(".ver_comentario").click(function () {

            var partes_id_comentario = (this.id).split("_");

            $("#comentario_" + partes_id_comentario[1]).dialog({
                resizable: false,
                height: 150,
                width: 450,
                modal: true,
                show: {effect: 'blind', duration: 400},
                hide: {effect: 'explode', duration: 1000},
                resizable: false

            });
        });
        $(".ver").click(function () {

            var partes_id_boton_versolicitud = (this.id).split("_");
            var id_sol = partes_id_boton_versolicitud[1];

            $("#solicitud_activa").val(id_sol);
            $("#tabla_solicitudes_detalles").empty();
            $("#tabla_solicitudes_detalles").load("tabla_solicitudes_rechazadas.php?id_solicitud=" + id_sol);
        });

        $(".eliminar").click(function () {
            eliminar_solicitud_rechazada(this.id);
        });

    });

</script>
<script type="text/javascript" src="js/solicitudes_rechazadas_eliminar_solicitud.js"></script>

<?php include_once 'inc/footer.php'; ?>