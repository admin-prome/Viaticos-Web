<script type="text/javascript" src="js/nuevo_viatico_ver_mapa.js"></script> 
<?php
require_once 'inc/checkLogin.php';
require_once 'admin/inc/const.php';

$id_usuario = $_SESSION['id_usuario'];

require_once 'admin/inc/funciones/all_functions.php';

$id_solicitud = $_GET['id_solicitud'];

$leyenda_boton = $_GET['leyenda_boton'];

$viaticos = obtener_viaticos_solicitud($_GET['id_solicitud']);

$solicitud = obtener_solicitud_datos($_GET['id_solicitud']);

$estado_solicitud = $solicitud[0]["estado_id"];

$estado_siguiente = conocer_siguiente_estado($estado_solicitud);
$importe = obtener_importe_solicitud($id_solicitud);
$coords_sucursal_usuario = coordenadas_sucursal_por_id_usuario_BASE_BANCO($solicitud[0]['id_usuarios']);
$km=0;
?>

<input type="hidden" value="<?= $solicitud[0]['id']; ?>" id="id_solicitud"/>
<input type="hidden" value="<?= $estado_siguiente; ?>" id="siguiente_estado"/>

<script type="text/javascript">
	solicitud_activa_js = $('#id_solicitud').val();
         solicitud_activa_js = <?= $id_solicitud; ?>;
	coords_sucursal = '<?= $coords_sucursal_usuario; ?>';
</script>

<div class="separador" style="margin-top:0!important;">
    <div style="height: 25px!important;background-color: white!important;color: white;">p</div>
    <h3>  Viaticos a <?= $leyenda_boton ?> del usuario <?php echo(obtener_nombre_usuario_BASE_BANCO($solicitud[0]['id_usuarios'])); ?></h3>
    <table id="detalle_solicitud" class="display" cellspacing="0" width=" width: 800px;      margin: 0 auto;">
        <thead style="text-align: center!important;">
            <tr style="text-align: center!important;">
                <th>Carga por :</th>
                <th>Fecha de Presentaci&oacute;n</th>
                <th>Motivo</th>
                <th>Concepto</th>
                <th>Origen</th>
                <th>Destino</th>
                <th >Km</th>
                <th>N&uacute;mero solicitud</th>
                <th >Monto</th>
                <th >Comentario</th>
                <th >Adjunto</th>
                <th >Importe</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $divsComentarios2 = '';

            if ($viaticos) {
                foreach ($viaticos as $row) {
                    $km=$km+$row["km"];
                    echo('<tr align="center" id="fila_' . $row["id"] . '">');
                    echo("<td>" . ponerIconoPCCelular($row["cargado_en_celular"]) . "</td>");
                    echo("<td>" . presentarFechaDateTime($row["fecha"]) . "</td>");
                    echo("<td>" . guion(obtener_motivo($row["id_motivo"], false)) . "</td>");
                    echo("<td>" . guion(obtener_concepto($row["id_concepto"])) . "</td>");

                    if ($row["origen"] == "1")
                        echo("<td>Sucursal</td>");
                    else
                        echo("<td>Visita anterior</td>");


                    if ($row["destino"]) {
                        echo("<td><span style='cursor: pointer;' class='ver_mapa' id=" . $row["cargado_en_celular"] . '@' . $row["origen"] . '@' . guion($row["destino"]) . '@' . $row["id"] . ">Ver mapa</span></td>");
                    } else {
                        echo("<td> - </td>");
                    }
                    echo("<td>" . guion($row["km"]) . "</td>");

                    echo("<td>" . guion($row["nro_solicitud"]) . "</td>");
                    echo("<td>" . guion($row["monto"]) . "</td>");

                    if (!empty($row["observacion"])) {

                        echo('<td class="ver_comentario2" id="' . $row["id"] . '">');
                        echo('<span style="cursor: pointer;">Click aqui</span></td>');

                        $divsComentarios2 .= '<div title="Comentarios :" id="comentario2_' .
                                $row["id"] . '" style="display:none;background-color:white;">' .
                                $row["observacion"] . "</div>\n";
                    } else {
                        echo('<td> - </td>');
                    }


                    if (elViaticoTieneAttach($row["id"], 'admin/')) {
                        ?>					
                    <td id="ver_viatico_<?= $row['id']; ?>">
                        <span style="cursor: pointer;" class="attach_viatico"
                              id="<?= nombreAttachViatico($row['id']); ?>">
                            Click aqu&iacute;
                        </span>
                    </td>
                    <?php
                } else {
                    echo '<td> - </td>';
                }
                echo("<td style='text-align:right;'> $" . formatearMoneda($row["importe"]) . "</td>");
                ?>

                </tr>
                <?php
            }
        }
        ?>  
        </tbody>
        <tfoot style="background-color: rgb(249, 249, 249);">
            <tr>
                <th style="text-align: center!important;"><h2>Total</h2></th>
        <th style="text-align: left!important;">&nbsp;</th>
        <th style="text-align: left!important;">&nbsp;</th>
        <th style="text-align: left!important;">&nbsp;</th>
        <th style="text-align: left!important;">&nbsp;</th>
        <th style="text-align: left!important;">&nbsp;</th>

        <th style="text-align: right!important;"><?php echo($km); ?> Km</th>
        <th style="text-align: left!important;">&nbsp;</th>
        <th style="text-align: left!important;">&nbsp;</th>
        <th style="text-align: left!important;">&nbsp;</th>
        <th style="text-align: left!important;">&nbsp;</th>
        <th style="text-align: right!important;padding: 10px 8px 6px;"><h4><?php echo('$' . formatearMoneda($importe[0]["importe"]) ); ?></h4></th>
        </tr>
        </tfoot>
    </table>
    <div id="botones_rechazo_apruebo" style="padding-bottom: 3%;<?php
    if ($_SESSION['id_estados_gestionar'] == 6 || $_SESSION['id_estados_gestionar'] == 7) {
        echo("margin-left:31.5%;");
    } else {
        echo("margin-left:40%;");
    }
    ?>"> 

        <input class="botones cancelar boton-largo" id="aprobar" style="height: 26px!important;line-height: 4px;" type="button" value="<?= obtener_leyenda_boton_gestion($_SESSION['id_estados_gestionar']); ?> sin dejar comentarios" />
        <input class="botones cancelar boton-largo" id="rechazar_todo" style="height: 26px!important;line-height: 4px;<?php
        if ($_SESSION['id_estados_gestionar'] == SOLICITUD_REVISADA || $_SESSION['id_estados_gestionar'] == SOLICITUD_APROBADA || $_SESSION['id_estados_gestionar'] == SOLICITUD_AUTORIZADA) {
            echo("display:inline;");
        } else {
            echo("display:none;");
        }
        ?>" type="button" value="Rechazar todo"/>


        <div style="display:none;margin: 11px 18%;" id="load"><div class="loading"></div><span style="margin-left: -4px;">Guardando</span></div>
    </div>
    <?= $divsComentarios2; ?>
</div>   

<div id="mapa_ventana_modal"></div>


<input type="hidden" id="estados_gestionar_rechazar_todo" value="<?php echo($_SESSION['id_estados_gestionar']); ?>">
<script type="text/javascript">
    id_usuario_js = <?= $id_usuario; ?>;
    coords_sucursal = '<?= $coords_sucursal_usuario ?>';
</script>
<script type="text/javascript">
    $(document).ready(function () {

        $('#detalle_solicitud').dataTable({
            //  "dom": '<"top"i>rt<"bottom"flp><"clear">',
            "paging": true,
             "iDisplayLength": 100,
            "ordering": true,
            "info": false,
            "search": false
        });
//TRAIGO EL HISTORIAL DESDE OTRO ARCHIVO
        $("#historial").load("historial.php?id_solicitud=" + <?php echo($id_solicitud); ?> + "&leyenda_boton=Autorizar");

        $(".ver_comentario2").click(
                function ()
                {
                    var id_td_comentario = (this.id).split("_");
                    console.log(id_td_comentario);
                    console.log("comentario2_" + id_td_comentario);
                    $("#comentario2_" + id_td_comentario).dialog({
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

        $(".attach_viatico").click(function () {
            window.open('admin/attachs/' + this.id, '_blank');
        });



        $("#aprobar").click(function () {
            // $("#aprobar").css('display', 'none');
            // $("#rechazar").css('display', 'none');
            $("#load").css('display', 'block');
            var parametros = {
                "solicitud_activa": $('#id_solicitud').val(),
                "accion": "cambiar_estado",
                "usuario":id_usuario_js,
                "estado": $('#siguiente_estado').val(),
                "revisar_dejar_comentario":0
            };

            $.ajax({
                url: 'admin/ajax_peticiones.php',
                data: parametros,
                type: 'post',
                success: function (response)
                {
                    setTimeout(
                            function () {
                                var id_solicitud = $('#id_solicitud').val();
                                var comentario = "Cambio de estado de la solicitud a " + conocer_nombre_estado_por_id($('#siguiente_estado').val()) + "";
                              /*  console.log($('#siguiente_estado').val());
                                console.log(conocer_nombre_estado_por_id($('#siguiente_estado').val()));
                                console.log(comentario);*/
                                var usuario =id_usuario_js;

                               // insertar_comentario_historial(id_solicitud, comentario, usuario);

                                $("#load").css('display', 'none');

                               window.location = '<?php echo $_GET["pagina"]; ?>';
                            }, 2000);
                }
            });
        });

        /* $(".ver_mapa").click(function() {
         
         var CARGADO_WEB = 0;
         var ORIGEN_SUCURSAL = 1;
         
         coord_1 = '';
         coord_2 = '';
         
         var id_ver_mapa = this.id;
         var donde_realizo_carga = (this.id).substr(0, 1);
         var origen = (this.id).substr(2, 1);
         
         var partes_id_ver_mapa = id_ver_mapa.split('@');
         
         if (CARGADO_WEB == donde_realizo_carga)
         {
         var myArray = partes_id_ver_mapa[2].split(':');
         
         var punto_1 = myArray[2].split('/');
         coord_1 = punto_1[0];
         
         var punto_2 = myArray[3].split('?');
         coord_2 = punto_2[0];
         
         }
         else // cargado por celular
         {
         
         if (ORIGEN_SUCURSAL == origen)
         {
         
         coord_1 = '<?= obtener_coordenadas_sucursal_BASE_BANCO($_SESSION['id_sucursal']); ?>';
         coord_2 = partes_id_ver_mapa[2];
         }
         else // visita anterior
         {
         
         coord_2 = partes_id_ver_mapa[2];
         
         var id_visita = partes_id_ver_mapa[3];
         
         var parametros =
         {
         "id_visita_actual": id_visita,
         "accion": "obtener_coord_visita_anterior"
         };
         
         
         $.ajax({
         data: parametros,
         url: 'admin/ajax_peticiones.php',
         type: 'post',
         success: function(response) {
         
         coord_1 = response;
         
         
         }
         
         });
         
         
         }
         
         
         }
         
         
         setTimeout(function() {
         $('#mapa_ventana_modal').html(' ');
         $("#mapa_ventana_modal").load("get_html_mapa.php?coord1=" + coord_1 + "&coord2=" + coord_2);
         }, 1000);
         
         
         });
         */
        var estados_gestionar = $("#estados_gestionar_rechazar_todo").val();

        $("#rechazar_todo").click(
                function ()
                {
                    var parametros = {
                        "solicitud_activa": $('#id_solicitud').val(),
                        "usuario":id_usuario_js,
                        "accion": "rechazar_solicitud",
                        "rechazada_todo": "1",
                    };

                    $.ajax({
                        url: 'admin/ajax_peticiones.php',
                        data: parametros,
                        type: 'post',
                        success: function (response)
                        {
                            setTimeout(
                                    function () {
                                        var id_solicitud = $('#id_solicitud').val();
                                        var comentario = "Su solicitud ha sido rechazada completamente , la misma va a volver a pasar por todo el proceso de revision luego de que la modifique y la vuelva a presentarla.";
                                        var usuario =id_usuario_js;

                                        insertar_comentario_historial(id_solicitud, comentario, usuario);

                                        $("#load").css('display', 'none');
                                       window.location = 'home.php';
                                    }, 2000);
                        }
                    });
                });
    });
	
</script>