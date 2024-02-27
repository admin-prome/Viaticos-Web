<?php
require_once 'inc/checkLogin.php';
require_once 'admin/inc/const.php';
require_once 'admin/inc/funciones/all_functions.php';

$id_usuario = $_SESSION['id_usuario'];
$coords_sucursal_usuario = obtener_coordenadas_sucursal_BASE_BANCO($_SESSION['id_sucursal']);

if (isset($_GET['solicitud']) && $_GET['solicitud'] == 'nueva') {insertar_solicitud($id_usuario);
    ?>

    <script type="text/javascript">
        window.location = 'solicitudes_rechazadas.php';
    </script>    

    <?php
}


$id_solicitud = $_GET['id_solicitud'];
$viaticos = obtener_viaticos_solicitud($_GET['id_solicitud']);
$solicitud = obtener_solicitud_datos($_GET['id_solicitud']);
$motivos = obtener_motivos();
$conceptos = obtener_conceptos();
$ids_motivos_sistema = array(MOTIVO_EVAL_TERRENO, MOTIVO_REUNION_MENSUAL, MOTIVO_CAPACITACION, MOTIVO_TRASPASO_CARTERA, MOTIVO_REUNION_MODULO, MOTIVO_OTRAS_REUNIONES, MOTIVO_BENEFICIO, MOTIVO_VISITA_SUCURSAL, MOTIVO_VISITA_COBRANZA);
$segmentos = obtener_segmentos();
$solicitud_activa = $id_solicitud;
?>
<script type="text/javascript" src="js/functions_viaticos.js"></script>
<script type="text/javascript">
//Todo lo reee global:
        id_usuario_js = <?= $id_usuario; ?>;
        solicitud_activa_js = <?= $solicitud_activa; ?>;
        coords_sucursal = '<?= $coords_sucursal_usuario ?>';

<?php
if (soyDeCasaMatriz($_SESSION['id_sucursal'])) {
    ?>
            tope_cena = <?= obtener_tope_almuerzo_cena_segun_sucursal(CM, 'tope_cena'); ?>;
            tope_almuerzo = <?= obtener_tope_almuerzo_cena_segun_sucursal(CM, 'tope_almuerzo'); ?>;
            SOY_CASA_MATRIZ = 1;
    <?php
} else {
    ?>
            tope_cena = <?= obtener_tope_almuerzo_cena_segun_sucursal(DEMAS_SUCURSALES, 'tope_cena'); ?>;
            tope_almuerzo = <?= obtener_tope_almuerzo_cena_segun_sucursal(DEMAS_SUCURSALES, 'tope_almuerzo'); ?>;
            SOY_CASA_MATRIZ = 0;
    <?php
}
?>
        tope_guarderia = <?= obtener_tope_almuerzo_cena_segun_sucursal(CM, 'tope_guarderia'); ?>;
    //Quizas no necesite este dato, lo traigo por las dudas:
        coords_visita_anterior = '<?= obtener_coordenadas_ultima_visita($id_usuario, $solicitud_activa);
?>';

        CARGADO_WEB = 0;

        kms_calculados_sistema = '';
        importe_calculado_sistema = '';

        datos_viaje_ida = new Array();
        datos_viaje_vuelta = new Array();

        costo_km_auto = <?= $_SESSION['costo_km_auto']; ?>;
        costo_km_moto = <?= $_SESSION['costo_km_moto']; ?>;

// Fin vars. globales
</script>

<?php require 'celular/seteos_costo_kms.php'; ?>
<?php
if (!$actualizando_costos_kms_celu) { //este IF atrapa TOOOODO el codigo de la pagina:
    if (!empty($solicitud)) {
        $viaticos = obtener_viaticos_por_usuario($id_usuario, $solicitud[0]['id']);
    }
    ?>
    <style type="text/css">
        .gestionar{display:none;}
    </style>
    <br />
    <div class="cargar-presentar">
        <table style="margin-left:auto;margin-right:auto;">
            <tr>
                <td colspan="3" style="text-align: center;">
                    <input class="botones boton-largo" id="nuevo_viatico" type="button" value="Nuevo viatico"/>
                    <p class="ayudas">Presione aqui para cargar un nuevo viatico.</p>
                </td>
                <td style="width: 20%;">&nbsp;</td>

                <?php
                if (!empty($viaticos)) {
                    ?>
                    <td colspan="3" style="margin-left:10px;text-align:center;">
                        <input class="botones boton-largo"   <?php
                        if ($viaticos) {
                            echo("botones");
                        } else {
                            echo("style='cursor: not-allowed;' disabled='disabled' ");
                        }
                        ?>  id="<?php
                               if ($viaticos) {
                                   echo("volver_presentar_planilla");
                               } else {
                                   echo("");
                               }
                               ?>" type="button" value="Volver a Presentar planilla"/>
                        <p class="ayudas">Presione aqu&iacute; para volver a presentar su planilla .</p>
                    </td>
                    <?php
                }
                ?>
            </tr>
        </table>
    </div>
    <input type="hidden" id="solicitud_activa" value="<?= $solicitud_activa; ?>">
    <div class="primer_carga" style="display:none;">

        <!--CONCEPTO GENERALES A TODAS LAS CARGAS -->
        <table id="nueva">
            <tr>
                <td>

                    <p>Fecha</p>
                    <input id="fecha_viatico" class="text" type="text"
                           placeholder="<?= obtenerFechaActualDDMMAAAA(); ?>" style="width: 80px;"/>

                </td>
                <td>
                    <p>Motivos</p>
                    <select id="motivo_viatico_select" class="text">

                        <option value="">Seleccione</option>

                        <?php
                        $permiso = permisos_motivo_cobranza($id_usuario);

                        foreach ($motivos as $row) {

                            if (!in_array($row['id'], $ids_motivos_sistema)) //var global
                                continue;
                            ?>
                            <option <?php
                            if ($permiso == false && $row ['id'] == 9) {
                                echo "style='display:none;'";
                            }
                            ?>value="<?= $row['id']; ?>"><?= utf8_encode($row['descripcion']); ?></option>
                                <?php
                            }
                            ?>     

                    </select>
                </td>
                <td>
                    <p>Concepto</p>
                    <select id="concepto_viatico_select" class="text">
                        <option value="">Seleccione</option>
                    </select>                   
                </td>
                <td><p>Observaciones</p><input id="observaciones_viatico"  class="text" type="text" placeholder="Escriba sus observaciones aqui" style="width: 550px;"/></td>
            </tr>
        </table>

        <!-- FORMULARIOS PARA CADA CONCEPTO *1 -->

        <table id="form_1" style="margin-top: -1.8%;display:none;padding-bottom:30px;">
            <tr>
                <td >
                    <p>Origen</p> 
                    <select id="form1_origen_viatico_select" class="text" style="width:85px!important;height: 26px!important;">
                        <option value="1">Sucursal</option>
                        <option value="2">Visita anterior</option>            
                    </select>
                </td>
                <td>

                    <p>URL Mapa</p>
                    <input id="form1_destino_viatico" class="text" type="text"
                           placeholder="destino" style="width: 80px;" />
                    &nbsp;
                    <img src="images/loading_process.gif" id="loading_ajax_form_1" width="25" style="display:none;" />
                </td>

                <td>
                    <p>KM (ida + vuelta)</p>
                    <input id="form1_km_viatico" class="text" type="text" placeholder="km" style="width: 50px;"/>
                </td>
                <td >
                    <p>Importe</p>
                    <input id="form1_importe_viatico" class="text" type="text" placeholder="importe" style="width: 50px;"/>

                </td>

                <td>

                    <p>nro solicitud</p>
                <input id="form1_solicitud_viatico" class="text" type="text" placeholder="solicitud" style="width: 50px;"/>

                </td>

                <td>

                    <p>Monto</p>
                    <input id="form1_monto_viatico" class="text" type="text" placeholder="monto" style="width: 50px;"/>
                </td>
                <td>
                    <p>Plazo</p>
                    <input id="form1_plazo_viatico" class="text" type="text" placeholder="plazo" style="width: 50px;"/>

                </td>

                <td>

                    <p>Segmento</p> 


                    <select id="form1_segmento_viatico_select" class="tamano_select text">

                        <option value="">Seleccione</option>

                        <?php
                        foreach ($segmentos as $row) {
                            ?>	
                            <option value="<?= $row['id']; ?>"><?= utf8_decode($row['descripcion']); ?></option>
                            <?php
                        }
                        ?>        

                    </select>

                </td>

                <td>

                    <p>Visita</p> 

                    <select id="form1_visita_select" class="tamano_select">

                        <option value="">Seleccione</option>

                        <option value="1">Exitosa</option>
                        <option value="0">Rechazada</option>

                    </select>

                </td>

                <td>

                    <div style="display: none;" id="div_attach_form1">

                        <p>Attach</p> 
                        <input type="file" id="form1_attach" style="display:none;" />

                    </div>  

                </td>

            </tr>

            <tr>
                <td style="margin-top:48px;"><input class="botones_nueva cancelar"  style="height: 26px!important;line-height: 4px;" type="button" value="Cancelar"/>
                </td>
                <td>  <input id="boton_guardado_form_1" class="botones_guardar" style="margin-left: 10px;height: 26px!important;line-height: 4px;" type="button" value="Guardar"/>
                </td>
            </tr>
        </table>

        <!-- FORMULARIOS PARA CADA CONCEPTO *2-->    

        <table id="form_2" style="margin-top: -1.8%;display:none;padding-bottom:30px;" >
            <tr id="primer_fila">
                <td style="margin-left: -2%" ><p >Origen</p> 

                    <select id="form2_origen_viatico_select" class="text" style="width:100px;height: 26px!important;">
                        <option value="1">Sucursal</option>
                        <option value="2">Visita anterior</option>            
                    </select>

                </td>

                <td style="margin-left: -2%">

                    <p>URL Mapa</p>
                    <input id="form2_destino_viatico"  class="text" type="text"
                           placeholder="destino" style="float: left;width: 80px;" />
                    &nbsp;
                    <img src="images/loading_process.gif" id="loading_ajax_form_2"
                         width="25" style="display:none;" />

                </td>

                <td style="margin-left: -2%"><p>KM (ida + vuelta)</p><input id="form2_km_viatico" class="text" type="text" placeholder="km" style="width: 80px;"/></td>
                <td style="margin-left: 0%"><p>Importe</p><input id="form2_importe_viatico" class="text" type="text" placeholder="importe" style="width: 80px;"/></td>
                <td style="margin-left: -3%" id="nro_solicitud_form_2"><p>nro solicitud</p><input id="form2_solicitud_viatico" class="text" type="text" placeholder="solicitud" style="width: 80px;"/></td>                
                <td>               
                    <p>Visita</p>               
                    <select id="form2_visita_select" class="tamano_select">                       
                        <option value="">Seleccione</option>                       
                        <option value="1">Exitosa</option>
                        <option value="0">Rechazada</option>                        
                    </select>

                </td>

                <td>

                    <div style="display: none;" id="div_attach_form2">

                        <p>Attach</p> 
                        <input type="file" id="form2_attach" style="display:none;" />

                    </div> 

                </td>


            </tr>

            <tr>
                <td style="margin-top:48px;"><input class="botones_nueva cancelar"  style="height: 26px!important;line-height: 4px;" type="button" value="Cancelar"/>
                </td>
                <td>  <input id="boton_guardado_form_2" class="botones_guardar" style="margin-left: 10px;height: 26px!important;line-height: 4px;" type="button" value="Guardar"/>
                </td>
            </tr>
        </table>



        <!-- FORMULARIOS PARA CADA CONCEPTO *3-->
        <form method="post" action="">
            <table id="form_3" style="margin-top: -1.8%;display:none;padding-bottom:30px;">
                <tr>
                    <td style="margin-left: -2%"><p>Importe</p>
                        <input class="text" type="text" id="form3_importe_viatico"
                               style="width: 80px;" />
                    </td>
                     <td class="cantidad_personas"  style="margin-left: -2%;display:none;"><p>Cantidad de personas</p>
                       
                        <select id ="select_cantidad_personas" class="text cantidad_personas" style="display:none;width: 100px!important;" >

                            <option value="">Seleccione</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>

                        </select>
                    </td>
                    <td >
                        <input type="file" style="margin-top:36px;margin-left:0%" id="form3_attach" />
                    </td>
                </tr>
                <tr>
                    <td style="margin-top:48px;"><input class="botones_nueva cancelar"  style="height: 26px!important;line-height: 4px;" type="button" value="Cancelar"/>
                    </td>
                    <td>  <input id="boton_guardado_form_3" class="botones_guardar" style="margin-left: 95px!important;height: 26px!important;line-height: 4px;" type="button" value="Guardar"/>
                    </td>
                </tr>
            </table>
        </form> 
        <div id="p_error" class="comentario_detalles"></div>
    </div>

    <br /><br /><br />
    <div id="myModal2" class="reveal-modal">
        <div style="width: 200px;height: 200px;display:block;margin: 10px 48%;border-radius: 25px;" id="load">
            <div class="loading" style="margin-bottom:25%;"></div><span id="finalizado_ok" style="font-weight: bold;font-size: 16px;margin-left:-38%;">Guardando ,aguarde un instante....</span></div>

    </div>

    <div class="separador" style="margin-top:5%!important;">
        <h3> Vi&aacute;ticos presentados por <span style="color:gray; font-weight:bold;">
                <?= $_SESSION['nombre_usuario']; ?>
                </span> correspondientes al mes de <b>
                    <?php echo(obtener_nombre_mes($solicitud[0]['mes'])); ?>
                </b> del <?php echo($solicitud[0]['ano']); ?></h3>
        <div style="background-color: #eeeced;margin-top:10px;">
            <table id="detalle_solicitud_rechazada" class="display" style="font-size:0.84em!important;" cellspacing="0"
                   width="margin: 0 auto;">

                <thead style="text-align: center!important;">

                    <tr style="text-align: center!important;">

                        <th style="max-width: 25px;">Carga por:</th>
                        <th style="max-width: 40px;">Nro. vi&aacute;tico</th>
                        <th>Fecha de Carga</th>
                        <th>Motivo</th>
                        <th>Concepto</th>
                        <th>Personas por almuerzo</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th style="width:120px;">Km</th>
                        <th>Nro. solicitud</th>
                        <th>Monto</th>
                        <th>Comentario</th>
                        <th>Ver adjunto</th>
                        <th>Eliminar adjunto</th>
                        <th>Visita exitosa</th>
                        <th style="max-width: 25px;">Editar</th>
                        <th style="max-width: 25px;">Borrar</th>
                        <th style="text-align: right;">Importe</th>
                    </tr>

                </thead>

                <tbody>
                    <?php
                    $contador_km = "0";

                    $divsComentarios = '';
                    $importe = obtener_importe_solicitud($solicitud_activa);
                    if ($viaticos) {

                        echo('<prev></prev>');

                        foreach ($viaticos as $row) {

                            $editar = elViaticoPuedeSerEditado($row["cargado_en_celular"], $row["id_motivo"], $row["id_concepto"]);
                            ?>


                            <tr align="center" id="fila_<?= $row["id"]; ?>">

                                <td><?= ponerIconoPCCelular($row["cargado_en_celular"]); ?></td>
                                <td><?= $row["id"]; ?></td>
                                <td><?= presentarFechaDateTime($row["fecha"], "-", "/"); ?></td>
                                <td><?= utf8_encode(guion(obtener_motivo($row["id_motivo"], false))); ?></td>
                                <td><?= utf8_encode(guion(obtener_concepto($row["id_concepto"]))); ?></td>
                                 <td>
                                       <?= guion($row["personas_por_almuerzo"]); ?> 
                                    </td>    
                                <td>

                                    <?php
                                    if ($row["origen"]) {
                                        if ($row["origen"] == VIATICO_ORIGEN_SUCURSAL)
                                            echo "Sucursal";
                                        else {
                                            if ($row["origen"] == VIATICO_ORIGEN_VISITA_ANTERIOR)
                                                echo "Visita anterior";
                                        }
                                    } else
                                        echo '-';
                                    ?>	

                                </td>

                                <td>

                                    <?php
                                    if ($row["destino"]) {
                                        ?>

                                        <span style="cursor: pointer;" class="ver_mapa"
                                              id="<?=
                                              $row["cargado_en_celular"] . '@' . $row["origen"] . '@' .
                                              $row["destino"] . '@' . $row["id"];
                                              ?>">Ver mapa</span>

                                        <?php
                                    } else
                                        echo "-";
                                    ?>

                                </td>

                                <td id="km_viatico_<?= $row["id"]; ?>">
                                    <?= guion($row["km"]); ?>

                                    <?php $contador_km += guion($row["km"]); ?>
                                </td>

                                <td><?= guion($row["nro_solicitud"]); ?></td>
                                <td><?= guion($row["monto"]); ?></td>

                                <?php
                                if ($row["observacion"]) {
                                    ?>

                                    <td class="ver_comentario" id="div_comentario_<?= $row["id"]; ?>">
                                        <span style="cursor: pointer;">
                                            Click aqu&iacute;
                                        </span>
                                    </td>

                                    <?php
                                    $divsComentarios .= '<div title="Observaciones:" id="comentario_' .
                                            $row["id"] . '" style="display:none;background-color:white;">' .
                                            $row["observacion"] . "</div>\n";
                                } else
                                    echo '<td> - </td>';


                                if (elViaticoTieneAttach($row["id"], 'admin/')) {
                                    ?>					

                                    <td id="ver_viatico_<?= $row['id']; ?>">

                                        <span style="cursor: pointer;" class="attach_viatico"
                                              id="<?= nombreAttachViatico($row['id']); ?>">
                                            Click aqu&iacute;
                                        </span>

                                    </td>

                                    <?php
                                } else
                                    echo '<td> - </td>';
                                ?>

                                <td>

                                    <?php
                                    if (elViaticoTieneAttach($row["id"], 'admin/')) {
                                        ?>

                                        <span style="cursor:pointer;"
                                              id="attach_viatico_<?= $row["id"]; ?>"
                                              class="eliminar_attach">Eliminar</span>

                                        <?php
                                    } else
                                        echo '-';
                                    ?>

                                </td> 

                                <td>

                                    <?php
                                    if (($row["id"])) {


                                        $res = valorVisitaCelular($row["id"]);

                                        echo "<span>" . $res . "</span>";
                                    } else {

                                        echo "<span>-</span>";
                                    }
                                    ?>

                                </td>

                                <td>	

                                    <?php
                                    if ($editar) {
                                        ?>

                                        <img src="img/editar.png" alt="editar" title="<?= $editar; ?>"
                                             class="icon_edit_viatico" style="cursor: pointer;"
                                             id="viatico_<?= $row["id"]; ?>" />

                                        <?php
                                    }
                                    ?>

                                </td>

                                <td>

                                    <?php
                                    if (elViaticoPuedeSerBorrado($row["id"], $row["costo_ida"], $row["costo_vuelta"], $row["km_ida"], $row["km_vuelta"], $solicitud_activa)) {
                                        $display_borrado = 'block';
                                    } else
                                        $display_borrado = 'none';
                                    ?> 

                                    <img src="img/borrar.png" class="eliminar_viatico"
                                         style="display:<?= $display_borrado; ?>;" width="25"
                                         id="eliminar_viatico_<?= $row["id"] ?>" />

                                </td>

                                <td>
                                    $ 

                                    <span id="importe_viatico_visible_<?= $row["id"] ?>">
                                        <?= formatearMoneda($row["importe"]); ?>
                                    </span>

                                    <span style="display:none;" id="importe_viatico_oculto_<?= $row["id"] ?>">
                                        <?= $row["importe"]; ?>
                                    </span> 

                                </td>

                            </tr>
                            <?php
                        }
                    }
                    ?>  

                </tbody>
                <tfoot style="background-color: rgb(249, 249, 249);">
                    <tr>

                        <th style="text-align: left;" colspan="7">
                <h2>Total</h2>
                </th>


                <th style="text-align: right!important;" rowspan="1" colspan="1">

                <h4><?php echo $contador_km . " km"; ?> </h4>   


                <th style="text-align: right!important;" rowspan="1" colspan="9"">

                <h4 id="importe_total_solicitud">
                    <?= "$ " . formatearMoneda($importe[0]["importe"]); ?>
                </h4>

                </th>

                </tr>

                </tfoot>

            </table>



        </div>
     
        <div id="div_ventana_modal"></div>
        <div id="mapa_ventana_modal"></div>

        <?= $divsComentarios; ?>
        <div id="historial" style="background-color: white!important;padding-top:75px!important;"></div>
        <script type="text/javascript">

            var importe_total_solicitud = <?php
    if ($importe[0]["importe"]) {
        echo($importe[0]["importe"]);
    } else {
        echo('0');
    }
    ?>;

            $(document).ready(function () {
                
                $("#historial").load("historial_vista.php?id_solicitud=" + <?= $id_solicitud ; ?>);

                $('#detalle_solicitud_rechazada').dataTable({
                    "lengthMenu": [10, 25, 50, 100],
                    "iDisplayLength": 100,
                    "paging": true,
                    "ordering": true,
                    "order": [[0, "desc"]],
                    "info": false,
                    "search": false,
                      "scrollX": true,

                });
                var error = false;

                $(function () {
                    $("#fecha_viatico").datepicker({
                        minDate: "-60D",
                        maxDate: "+0D"
                    });
                });

                $(".editar").click(function () {
                    $('#myModal').reveal($(this).data());
                });

                $(".cancelar").click(function () {
                    $("#form_1").css("display", "none");
                    $("#form_2").css("display", "none");
                    $("#form_3").css("display", "none");
                });

                $("#nuevo_viatico").click(function () {
                    $(".primer_carga").css("display", "block");
                });

                $(".icon_edit_viatico").mouseover(function () {
                    this.src = 'img/editar_hover.png';
                });

                $(".icon_edit_viatico").mouseout(function () {
                    this.src = 'img/editar.png';
                });

                $(".eliminar_viatico").mouseover(function () {
                    this.src = 'img/borrar_hover.png';
                });

                $(".eliminar_viatico").mouseout(function () {
                    this.src = 'img/borrar.png';
                });

                $(".eliminar_attach").click(function () {
                    eliminar_attach_viatico(this.id);
                });

                $(".eliminar_viatico").click(function () {
                    eliminar_viatico(this.id,
                            '<?= $coords_sucursal_usuario; ?>', costo_km_auto, costo_km_moto);
                });

                $(".attach_viatico").click(function () {
                    window.open('admin/attachs/' + this.id, '_blank');
                });
                $(".icon_edit_viatico").click(function () {
                    var parametros = {
                        "id_viatico": this.id,
                        "accion": "edit_viatico_modal",
                        "carga_pc_celular": $(this).attr('title')
                    };
                    $.ajax({
                        url: 'admin/ajax_peticiones.php',
                        data: parametros,
                        type: 'post',
                        success: function (response)
                        {
                            $('#div_ventana_modal').html(response);
                            $('#myModal').reveal('open');
                            $("#edit_fecha_viatico").datepicker();
                        }
                    });
                });
                // VOLVER A PRESENTAR LA PLANILLA
                $("#volver_presentar_planilla").click(function () {
                    $('#myModal3').reveal('open');
                    var parametros = {
                        "solicitud_activa": $('#solicitud_activa').val(),
                        "accion": "volver_presentar_planilla"
                    };

                    $.ajax({
                        url: 'admin/ajax_peticiones.php',
                        data: parametros,
                        type: 'post',
                        success: function (response)
                        {
                            setTimeout(function () {
                                $("#load").html('<img src="img/ok.png"><br/><span id="finalizado_ok" style="font-weight: bold;font-size: 16px;margin-left:-25%;">Presentada correctamente .</span>');
                                setTimeout(function () {
                                    window.location = "solicitudes_rechazadas.php";
                                }, 750);
                            }, 750);
                        }
                    });
                });

            });


        </script>


        <script type="text/javascript"
        src="js/funciones_motivos_conceptos.js"></script>

        <script type="text/javascript" src="js/nuevo_viatico_alta.js"></script>

        <script type="text/javascript"
        src="js/nuevo_viatico_eliminar_rendicion.js"></script>

        <script type="text/javascript"
        src="js/nuevo_viatico_change_motivo.js"></script>

        <script type="text/javascript"
        src="js/nuevo_viatico_focusout_destino.js"></script>

        <script type="text/javascript" src="js/nuevo_viatico_change_concepto.js"></script>
        <script type="text/javascript" src="js/nuevo_viatico_change_origen.js"></script> 
        <script type="text/javascript" src="js/nuevo_viatico_change_destino.js"></script> 
        <script type="text/javascript" src="js/nuevo_viatico_ver_comentario.js"></script> 
        <script type="text/javascript" src="js/nuevo_viatico_ver_mapa.js"></script> 

        <?php
    } // end if principal.
    ?>