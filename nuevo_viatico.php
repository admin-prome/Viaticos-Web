<?php
require_once 'inc/checkLogin.php';
require_once 'admin/inc/const.php';

require_once 'admin/inc/funciones/all_functions.php';
require_once 'inc/header.php';
// var_dump($_SESSION);

$id_usuario = $_SESSION['id_usuario'];

$coords_sucursal_usuario = obtener_coordenadas_sucursal_BASE_BANCO($_SESSION['id_sucursal']);

if (isset($_GET['solicitud']) && $_GET['solicitud'] == 'nueva') {
    insertar_solicitud($id_usuario);
    ?>

    <script type="text/javascript">
        window.location = 'nuevo_viatico.php';
    </script>    

    <?php
}

require_once 'inc/menu.php';

$viaticos = "";
//$ultimo_id = obtener_ultimo_id("usuarios");

$motivos = obtener_motivos();
$conceptos = obtener_conceptos();

$ids_motivos_sistema = array(MOTIVO_EVAL_TERRENO, MOTIVO_REUNION_MENSUAL, MOTIVO_CAPACITACION, MOTIVO_TRASPASO_CARTERA, MOTIVO_REUNION_MODULO, MOTIVO_OTRAS_REUNIONES, MOTIVO_BENEFICIO, MOTIVO_VISITA_SUCURSAL, MOTIVO_VISITA_COBRANZA);

$segmentos = obtener_segmentos();

$solicitudes = usuario_solicitud_pendiente($id_usuario);
$solicitudes_pendientes = count($solicitudes);

if (!empty($solicitudes))
    $solicitud_activa = $solicitudes[0]['id'];
?>

<script type="text/javascript" src="js/functions_viaticos.js"></script>
<script type="text/javascript">

//Todo lo reee global:
        url_pagina = 'nuevo_viatico.php';
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
        tope_guarderia_ley = <?= obtener_tope_almuerzo_cena_segun_sucursal(CM, 'tope_guarderia_ley'); ?>;
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
    if (!empty($solicitudes)) {
        $viaticos = obtener_viaticos_por_usuario($id_usuario, $solicitudes[0]['id']);
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
                                   echo("presentar_planilla");
                               } else {
                                   echo("");
                               }
                               ?>" type="button" value="Presentar planilla"/>
                        <p class="ayudas">Presione aqu&iacute; para presentar su planilla para autorizar.</p>
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
                <td  >

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
                            ?>value="<?= $row['id']; ?>"><?= $row['descripcion']; ?></option>
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
                    <img src="images/loading_process.gif" id="loading_ajax_form_1"
                         width="25" style="display:none;" />


                </td>

                <td>
                    <p>KM (ida + vuelta)</p>
                    <input id="form1_km_viatico" class="text" type="text" placeholder="km"
                           style="width: 50px;"/>
                </td>
                <td >
                    <p>Importe</p>
                    <input id="form1_importe_viatico" class="text" type="text" placeholder="importe"
                           style="width: 50px;"/>

                </td>

                <td>

                    <p>nro solicitud</p>
                    <input id="form1_solicitud_viatico" class="text" type="text" placeholder="solicitud" style="width: 50px;"/>

                </td>

                <td>

                    <p>Monto</p>
                    <input id="form1_monto_viatico" class="text" type="text" placeholder="monto"
                           style="width: 50px;"/>

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
                            <option value="<?= $row['id']; ?>"><?= $row['descripcion']; ?></option>
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
                        <input class="text" type="text" id="form3_importe_viatico" style="width: 80px;" />
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
        <h3> Vi&aacute;ticos presentados por <span style="color:gray; font-weight:bold;"><?= $_SESSION['nombre_usuario']; ?></span> </h3>
        <div style="background-color: #eeeced;margin-top:10px;">
            <table id="table_datatable_nuevo_viatico" class="display" cellspacing="0" style="margin: 0 auto;font-size: 11px!important;">

                <thead style="text-align: center!important;">

                    <tr style="text-align: center!important;">
                        <th style="width: 25px!important;">Carga por:</th>
                        <th style="width: 40px!important;">Nro. vi&aacute;tico</th>
                        <th>Fecha de Carga</th>
                        <th>Motivo</th>
                        <th>Concepto</th>
                        <th style="width:50px!important;">Personas por almuerzo</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th style="width:80px!important;">Km</th>
                        <th>Nro. solicitud</th>
                        <th>Monto</th>
                        <th>Comentario</th>                       
                        <th>Ver adjunto</th>
                        <th>Eliminar adjunto</th>
                        <th>Visita exitosa</th>
                        <th style="text-align: right;">Importe</th>
                        <th style="max-width: 25px;">Editar</th>
                        <th style="max-width: 25px;">Borrar</th>
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
                                <td><?= guion(obtener_motivo($row["id_motivo"], false)); ?></td>
                                <td><?= guion(obtener_concepto($row["id_concepto"])); ?></td>
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
                                    $ 

                                    <span id="importe_viatico_visible_<?= $row["id"] ?>">
                                        <?= formatearMoneda($row["importe"]); ?>
                                    </span>

                                    <span style="display:none;" id="importe_viatico_oculto_<?= $row["id"] ?>">
                                        <?= formatearMoneda($row["importe"]); ?>
                                    </span> 

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



                            </tr>
                            <?php
                        }
                    }
                    ?>  

                </tbody>
                <tfoot style="background-color: rgb(249, 249, 249);"><tr>
                        <th style="text-align: left;">
                <h2>Total</h2>
                </th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th style="width: 60px !important; padding: 0px 4px !important;"> <h4><?php echo $contador_km . " km"; ?> </h4>  </th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th style="width: 55px!important;padding: 0px 4px!important;">  <h4 id="importe_total_solicitud">
                    <?= "$ " . formatearMoneda($importe[0]["importe"]); ?>
                </h4>
                </th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>

                </tr>

                </tfoot>

            </table>

            <?php
            if (!empty($viaticos)) {
                ?>

                <br />
                <input class="botones boton-largo" type="button" value="Eliminar rendicion NO presentada" id="eliminar_rendicion" />
                <br />

                <?php
            }
            ?>

        </div>

        <div id="div_ventana_modal"></div>
        <div id="mapa_ventana_modal"></div>
        <div id="DIVMOSTRARMES" class="reveal-modal" style="display:none;top: 100px; opacity: 1; width: 500px; visibility: visible; position: fixed; margin: auto; left: 30%;">

            <?php
            $titulo_cartel_rendiciones = "Mes de la rendicion";
            $titulo_cartel_rendiciones = utf8_decode($titulo_cartel_rendiciones);
            $mensaje_cartel_rendiciones = "Seleccione el mes correspondiente a su solicitud<br/>
                 Sólo se mostrarán el mes actual y dos anteriores. En caso de tener rendiciones en alguno de estos meses, los mismos no se veran.<br/>
                 <b>Recuerde que solo puede presentar una solicitud por mes</b>";
            //$mensaje_cartel_rendiciones = utf8_decode($mensaje_cartel_rendiciones);
            ?>
            <h2 style="text-align:center;"><?php echo $titulo_cartel_rendiciones; ?></h2>
            <input type="hidden" value="<?php $ano = date("Y"); ?>" id="ano_actual">
            <p><?php echo $mensaje_cartel_rendiciones; ?></p>
            <div id="presentar_mes" align="center" style="margin-left: -8.5%; width: 427px; height: 84px;margin: auto;">


                <?php
                $ano = date("Y");
                $mes = date("m");

                $meses_asociativo = obtener_meses_rendiciones($id_usuario, $ano);
                $meses_ano_anterior = obtener_meses_rendiciones($id_usuario, $ano - 1);

                $meses = array_values($meses_asociativo);
                $meses_anteriores = array_values($meses_ano_anterior);

                $meses_nombres = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                ?>
                <select name="presentar_mes">

                    <option value="">Por favor, elija el mes</option>

                    <?php
                    if ($mes == 1) {

                        if (in_array(11, $meses_anteriores)) {
                            echo '<option value="11">Noviembre</option>';
                        }

                        if (in_array(12, $meses_anteriores)) {
                            echo '<option value="12">Diciembre</option>';
                        }
                    }

                    if ($mes == 2) {
                        if (in_array(12, $meses_anteriores)) {
                            echo '<option value="12">Diciembre</option>';
                        }
                    }

                    for ($i = 0; $i < count($meses); $i++) {

                        $numero = $meses[$i];

                        if (($numero == $mes) || ($numero == $mes - 1) || ($numero == $mes - 2)) {
                            ?>

                            <option value="<?php echo $numero; ?>"><?php echo $meses_nombres[$numero - 1]; ?></option>

                            <?php
                        }
                    }
                    ?>
                </select>
                <?php
                          
                if($mes != 1 && $mes!=2){
                    
                      $suma_meses= $mes + ($mes -1) + ($mes - 2);
                      
                }else if($mes == 1){
                    
                    $mes = 1 + 12;
                    $suma_meses = $mes + ($mes - 1) + ($mes - 2);
                    
                }else {
                    $mes = 2 + 12;
                    $suma_meses = $mes + ($mes - 1) + ($mes - 2);
                }
                
             
                if ($suma_meses = 36 || $suma_meses = 39) {
                    ?>
                <select  name='diciembre_ano' >
                    <option selected value=''>Seleccione</option>
                    <option  value='<?=$ano + 1 ?>'><?=$ano - 1 ?></option>
                    <option  value='<?=$ano ?>' selected><?=$ano?></option>                    
                </select>   
                <?php
                }else{
                    ?>
                <select  name='diciembre_ano' >
                    <option selected value=''>Seleccione</option>
                    <option  value='<?=$ano ?>' selected><?=$ano?></option>
                </select>   
                <?php
                }
                ?>
               <!-- <select  name='diciembre_ano' style="<?php
/*
                if ($mes == 1 || $mes == 2 || $mes == 11 || $mes == 12) {
                    echo('display:inline');
                } else {
                    echo('display:inline');
                }*/
                ?>">
                   
                </select>   -->

                <button>Confirmar</button>



            </div>




        </div>
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
        <script type="text/javascript" src="js/nuevo_viatico_presentar_planilla.js"></script> 

    <?= $divsComentarios; ?>

        <script type="text/javascript">

            var importe_total_solicitud = <?php
    if ($importe[0]["importe"]) {
        echo($importe[0]["importe"]);
    } else {
        echo('0');
    }
    ?>;

            $(document).ready(function () {

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



            });


        </script>


        

        <?php include_once 'inc/footer.php'; ?>

        <?php
    } // end if principal.
    ?>
