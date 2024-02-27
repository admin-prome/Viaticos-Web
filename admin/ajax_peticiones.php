<?php
require 'inc/checkLogin.php';

ini_set('memory_limit', '50M');
ini_set('post_max_size', '25M');
ini_set('upload_max_filesize', '15M');

require 'inc/const.php';
require_once 'inc/funciones/all_functions_sin_admin.php';


if ($_POST['accion'] == 'actualizar_km_costo_vuelta_viatico') {
    $sql = "UPDATE visita SET km_vuelta = " . $_POST['kms'] . ", costo_vuelta = " .
            $_POST['costo'] . ", km = km_ida + " . $_POST['kms'] . ", importe = costo_ida + " .
            $_POST['costo'];

    $sql .= " WHERE id = " . $_POST['id_viatico'];

    ejecutarConsulta($sql);

    $sqlData = "SELECT km, importe FROM visita WHERE id = " . $_POST['id_viatico'];
    $rsData = ejecutarConsulta($sqlData);

    echo $rsData[0]['km'] . "@" . $rsData[0]['importe'];
}

if ($_POST['accion'] == 'obtener_destino_concepto_viatico') {

    $resp = obtener_campo_por_tabla_e_id('destino', $_POST['id_viatico'], 'visita');
    $resp .= ';' . obtener_campo_por_tabla_e_id('id_concepto', $_POST['id_viatico'], 'visita');

    echo $resp;
}

if ($_POST['accion'] == 'eliminar_attach_viatico') {
    eliminarAttachViatico($_POST['id_viatico']);
}

if ($_POST['accion'] == 'eliminar_rendicion_mensual') {
    eliminar_solicitud_completa($_POST['id_solicitud']);
}

if ($_POST['accion'] == 'ok_todas_rendiciones_tildadas') {
    cambiar_estado_array_solicitudes($_POST['ids_rendiciones'], $_POST['setear_nuevo_estado'], $_POST['usuario'], $_POST['estado_actual']);
}

if ($_POST['accion'] == 'update_tope_almuerzo_cena') {

    update_topes_almuerzos_cenas_BASE_BANCO($_POST['tope'], $_POST['sucursal'], $_POST['tipo']);
}

if ($_POST['accion'] == 'update_costo_km') {
    update_costo_km_BASE_BANCO($_POST['costo_auto'], $_POST['costo_moto'], $_POST['id_zona']);
}

if ($_POST['accion'] == 'obtener_coord_viatico_anterior') {
    /*
      echo buscar_viatico_tipovisita_anterior($_POST['id_viatico'],$_POST['solicitud'],'destino');
     */


    echo buscar_viatico_anterior_con_km($_POST['id_viatico'], $_POST['solicitud'], 'destino');
}

if ($_POST['accion'] == 'obtener_coord_visita_anterior') {
    echo obtener_coordenadas_ultima_visita($_POST['usuario'], $_POST['solicitud']);
}

if ($_POST['accion'] == 'editar_viatico') {
    editar_viatico($_POST);
}

if ($_POST['accion'] == 'agregar_usuario') {
    if (!insertar_usuario($_POST))
        echo 'error';
    else
        echo 'success';
}

if ($_POST['accion'] == 'agregar_viatico') {

    if (!insertar_viatico($_POST))
        echo 'error';
    else {
        insertar_solicitud_viatico($_POST['solicitud_activa']);
        echo 'success';
    }
}

if ($_POST['accion'] == 'eliminar_viatico') {
    echo eliminar_viatico($_POST['id_viatico'], $_POST['id_solicitud']);
}

if ($_POST['accion'] == 'modificar_usuario') {
    if (!obtener_usuario($_POST['id_usuario']))
        echo 'error';
    else
        echo 'success';
}

if ($_POST['accion'] == 'cambiar_id_solicitud') {


    $mes = $_POST['mes'];

    $ano = $_POST['ano_diciembre'];


    $sql = 'UPDATE solicitud SET estado_id = ' . SOLICITUD_PRESENTADA . ' ,fecha_presentacion = now(),mes=' . $mes . ',ano=' . $ano . ' WHERE id = ' . $_POST['solicitud_activa'];


    ejecutarConsulta($sql);
}

if ($_POST['accion'] == 'volver_presentar_planilla') {
    $sql = 'UPDATE solicitud SET rechazada = 0 WHERE id = ' .
            $_POST['solicitud_activa'];

    ejecutarConsulta($sql);
}

if ($_POST['accion'] == 'validar_usuario') {
    $datos_consulta = obtener_usuarios_login_BASE_BANCO($_POST['email']);

    if (!$datos_consulta)
        echo 'error';
    else {
        iniciarSesion($datos_consulta);
        echo 'success';
    }
}

if ($_POST['accion'] == 'cambiar_estado') {
    $estado_a_cambiar = $_POST['estado'];
    $sql = 'UPDATE solicitud SET estado_id = ' . $estado_a_cambiar . ' WHERE id = ' . $_POST['solicitud_activa'];
    ejecutarConsulta($sql);

    $id_solicitud = $_POST['solicitud_activa'];

    $usuario = $_POST['usuario'];

    $estado = obtener_solicitudes_por_id($id_solicitud);

    $estado_solicitud = $estado[0]['estado_id'];

    //$estado = obtener_solicitudes_por_id($id_solicitud);

    $actual = date("d-m-Y H:i:s");


    /* COMENTARIO USUARIO */

    if ($_POST['revisar_dejar_comentario'] == 1) {
        $comentario = $_POST['comentario'];
        $sql = "INSERT INTO historial (id_solicitud , usuario , comentario,estado_solicitud,fecha)
            VALUES ($id_solicitud, $usuario, '$comentario' ,$estado_solicitud,'$actual');";

        ejecutarConsulta($sql);
    }

    /* COMENTARIO GENERAL SISTEMA */
    $estado_solicitud_nombre = obtener_estado_por_id($estado_solicitud);
    $comentario = "Cambio de la solicitud a " . $estado_solicitud_nombre;

    $sql = "INSERT INTO historial (id_solicitud , usuario , comentario,estado_solicitud,fecha)
    VALUES ($id_solicitud, $usuario, '$comentario' ,$estado_solicitud,'$actual');";

    ejecutarConsulta($sql);
}

if ($_POST['accion'] == 'rechazar_solicitud') {

    $id_solicitud = $_POST['solicitud_activa'];
    $usuario = $_POST['usuario'];


    if (isset($_POST['rechazada_todo']) == 1) {
        $estado = obtener_solicitudes_por_id($id_solicitud);
        $estado_solicitud = $estado[0]['estado_id'];
        $actual = date("d-m-Y H:i:s");
        $sql = 'UPDATE solicitud SET rechazada = 1 ,observacion = "Su solicitud ha sido rechazada completamente , la misma va a volver a pasar por todo el proceso de revision luego de que la modifique y la vuelva a presentarla.",estado_id="' . SOLICITUD_PRESENTADA . '" WHERE id = ' . $_POST['solicitud_activa'];
        ejecutarConsulta($sql);
        $comentario = 'Su solicitud ha sido rechazada completamente , la misma va a volver a pasar por todo el proceso de revision luego de que la modifique y la vuelva a presentarla.';
        $sql = "INSERT INTO historial (id_solicitud , usuario , comentario,estado_solicitud,fecha)
    VALUES ($id_solicitud, $usuario, '$comentario' ,$estado_solicitud,'$actual');";
        ejecutarConsulta($sql);
    } else {

        $usuario = $_POST['usuario'];
        $estado = obtener_solicitudes_por_id($id_solicitud);
        $estado_solicitud = $estado[0]['estado_id'];
        //$estado = obtener_solicitudes_por_id($id_solicitud);
        $actual = date("d-m-Y H:i:s");
        $comentario = $_POST['comentario'];

        $sql = 'UPDATE solicitud SET rechazada = 1 , observacion = "' . $_POST['comentario'] . '" WHERE id = ' . $_POST['solicitud_activa'];
        ejecutarConsulta($sql);
        /* COMENTARIO USUARIO */
        $sql = "INSERT INTO historial (id_solicitud , usuario , comentario,estado_solicitud,fecha)
    VALUES ($id_solicitud, $usuario, '$comentario' ,$estado_solicitud,'$actual');";
        ejecutarConsulta($sql);
        /* COMENTARIO GENERAL SISTEMA */
        $comentario = 'Su solicitud ha sido rechazada';

        $sql = "INSERT INTO historial (id_solicitud , usuario , comentario,estado_solicitud,fecha)
    VALUES ($id_solicitud, $usuario, '$comentario' ,$estado_solicitud,'$actual');";

        ejecutarConsulta($sql);
    }

    $sql = "INSERT INTO alertas VALUES(null,$id_solicitud,$usuario,0,0)";

    ejecutarConsulta($sql);

    /* enviar mail */



    $id = obtener_usuario_datos_solicitud($id_solicitud);

    $id = $id[0]["id_usuarios"];

    $para = obtener_usuario_dato_BASE_BANCO($id, 'email');


    //$para = "rafael@marketingplus.com.ar";

    $asunto = "Su solicitud numero " . $id_solicitud . " ha sido rechazada";

    $mensaje = "Su solicitud numero <b>" . $id_solicitud . "</b> ha sido rechazada. <br/> Por favor, ingrese en <a href='https://rendicion.provinciamicrocreditos.com' target='_blank'> http://rendicion.provinciamicroempresas.com</a>, en la seccion RECHAZADAS para poder modificarla y volver a presentarla.<br/>Por favor, no responda este email. <br/> Cualquier duda contactarse con Administracion.";



    $resultado_mail = sendEmail($para, $asunto, $mensaje, $path = '');
}


if ($_POST['accion'] == 'insertar_comentario_historial') {

    $id_solicitud = $_POST['id_solicitud'];
    $comentario = $_POST['comentario'];
    $usuario = $_POST['usuario'];
    $estado = obtener_solicitudes_por_id($id_solicitud);
    $estado_solicitud = $estado[0]['estado_id'];
    $actual = date("d-m-Y H:i:s");

    $sql = "INSERT INTO historial (id_solicitud , usuario , comentario,estado_solicitud,fecha)
VALUES ($id_solicitud, $usuario, '$comentario' ,$estado_solicitud,'$actual');";

    echo $sql;

    ejecutarConsulta($sql);
}





if ($_POST['accion'] == 'edit_viatico_modal') {

    $carga_pc_celular = $_POST['carga_pc_celular'];

    $partes_viatico = explode('_', $_POST['id_viatico']);

    $viatico = obtener_viatico($partes_viatico[1]);

    $ids_motivos_sistema = array(MOTIVO_EVAL_TERRENO, MOTIVO_REUNION_MENSUAL, MOTIVO_CAPACITACION, MOTIVO_TRASPASO_CARTERA, MOTIVO_REUNION_MODULO, MOTIVO_OTRAS_REUNIONES, MOTIVO_BENEFICIO, MOTIVO_VISITA_SUCURSAL, MOTIVO_VISITA_COBRANZA);

    if ($viatico[0]['id_motivo'] == MOTIVO_BENEFICIO) {
        $ids_conceptos_sistema[] = CONCEPTO_ALOJAMIENTO;
        $ids_conceptos_sistema[] = CONCEPTO_GUARDERIA;
        $ids_conceptos_sistema[] = CONCEPTO_REFRIGERIO;
        $ids_conceptos_sistema[] = CONCEPTO_ALMUERZO;
        $ids_conceptos_sistema[] = CONCEPTO_CENA;
        $ids_conceptos_sistema[] = CONCEPTO_ESTACIONAMIENTO;
    } else {

        $ids_conceptos_sistema = array(CONCEPTO_AUTO, CONCEPTO_MOTO,
            CONCEPTO_TAXI_REMIS, CONCEPTO_TRANS_PUBLICO, CONCEPTO_PEAJE,
            CONCEPTO_ESTACIONAMIENTO);

        if ($viatico[0]['id_motivo'] == MOTIVO_CAPACITACION) {
            $ids_conceptos_sistema[] = CONCEPTO_ALOJAMIENTO;
            $ids_conceptos_sistema[] = CONCEPTO_ALMUERZO;
            $ids_conceptos_sistema[] = CONCEPTO_CENA;
        }
    }

    $motivos = obtener_motivos($ids_motivos_sistema);
    $conceptos = obtener_conceptos($ids_conceptos_sistema);
  
    ?>

    <div id="myModal" class="reveal-modal">
        <h1>Modificar Vi&aacute;tico</h1>
        <table style="border-collapse: collapse;">

            <tr>

                <td style="padding: 5px;">Fecha:</td>
                <td style="padding: 5px;">

                    <input id="edit_fecha_viatico" class="text" type="text"
                           style="width: 80px;"
                           value="<?= presentarFechaSinHora($viatico[0]['fecha'], $sepActual = '-', $sepNuevo = '/'); ?>" />

                </td>

            </tr>

            <tr>

                <td style="padding: 5px;">Motivo:</td>
                <td style="padding: 5px;">

                    <?php
                    foreach ($motivos as $row) {
                        if ($viatico[0]['id_motivo'] == $row['id']) {
                            echo ($row['descripcion']);
                             
                        }
                    }
                    ?>

                </td>

            </tr>

            <tr>

                <td style="padding: 5px;">Concepto:</td>
                <td style="padding: 5px;">

                    <?php
                    foreach ($conceptos as $row) {
                        if ($viatico[0]['id_concepto'] == $row['id']) {
                            echo utf8_encode($row['descripcion']);
                        }
                    }
                    ?>

                </td>

            </tr>

            <tr>

                <td style="padding: 5px;">Observaciones:</td>
                <td style="padding: 5px;">

                    <input id="edit_observaciones_viatico" class="text" type="text"
                           style="width: 550px;" value="<?= $viatico[0]['observacion']; ?>" />

                </td>
                <?php
                if (isset($viatico[0]['visita_exitosa'])) {
                    ?>

                    <td style="padding: 5px;">Visita exitosa

                        <select id="edit_visita" class="text" style="width:100px;height: 26px!important;">
                            <option value="1" <?php if ($viatico[0]['visita_exitosa'] == 1) echo "selected"; ?>>Si</option>
                            <option value="0" <?php if ($viatico[0]['visita_exitosa'] == 0) echo "selected"; ?>>No</option>            
                        </select>


                    </td>
                    <?php
                }
                ?>

            </tr>

        </table>


        <?php
        // Decido que formulario mostrar de ENTRADA (El 1, 2 o 3).

        $nro_form_mostrar = formu_viatico_visibilidad($viatico[0]['id_motivo'], $viatico[0]['id_concepto']);
        ?>

        <!-- FORMULARIO 1 -->

        <table>

            <tr id="form_1_edit" style="margin-top: -1.8%;
                display:<?php
                if ($nro_form_mostrar == FORM_VIATICO_1)
                    echo 'inline';
                else
                    echo 'none';
                ?>;">

                <td style="margin-left: -0.6%; padding: 8px;"><p>Origen</p> 

                    <span style="font-family: Arial;
                          font-size: 12px; text-decoration: none;">

                        <?php
                        if ($viatico[0]['origen'] == VIATICO_ORIGEN_SUCURSAL)
                            echo 'Desde Sucursal';
                        else
                            echo 'Desde Visita Anterior';
                        ?>

                    </span>		


                </td>

                <td style="margin-left: -2%; padding: 8px;"><p>Destino</p>

                    <span style="font-family: Arial; font-size: 12px;
                          text-decoration: none;"><?= $viatico[0]['destino']; ?></span>


                </td>

                <td style="margin-left: -2%; padding: 8px;"><p>KM</p>

                    <span style="font-family: Arial; font-size: 12px;
                          text-decoration: none;"><?= $viatico[0]['km']; ?></span>

                </td>

                <td style="margin-left: 0%; padding: 8px;"><p>Importe</p>

                    <span style="font-family: Arial; font-size: 12px;
                          text-decoration: none;"><?= $viatico[0]['importe']; ?></span>


                </td>

                <td style="margin-left: -3%; padding: 8px;"><p>Nro solicitud</p>

                    <input class="text" type="text"
                           id="form1_edit_solicitud_viatico"
                           value="<?= $viatico[0]['nro_solicitud']; ?>" 
                           style="width: 80px;"/>

                </td>

                <td style="margin-left: -2%; padding: 8px;"><p>Monto</p>

                    <input class="text" type="text" id="form1_edit_monto_viatico"
                           value="<?= $viatico[0]['monto']; ?>"
                           style="width: 80px;" />

                </td>


                <td style="margin-left: -2%; padding: 8px;"><p>Plazo</p>

                    <input class="text" type="text" id="form1_edit_plazo_viatico"
                           value="<?= $viatico[0]['plazo']; ?>"
                           style="width: 80px;" />

                </td>


                <td style="margin-left: -2%; padding: 8px;"><p>Segmento</p> 

                    <select class="text" style="height: 26px!important;"
                            id="form1_edit_segmento_viatico_select">

                        <?php
                        $segmentos = obtener_segmentos();

                        foreach ($segmentos as $row) {

                            echo '<option value="' . $row['id'] . '"';

                            if ($viatico[0]['id_segmento'] == $row['id'])
                                echo ' selected="selected"';

                            echo '>' . $row['descripcion'] . "</option>";
                        }
                        ?>          

                    </select>

                </td>



                <td style="margin-top:48px; padding: 8px;">

                    <br /><br />
                    <input class="botones_nueva" type="button" value="Cancelar"
                           onclick=" $('#myModal').trigger('reveal:close');"
                           style="height: 26px!important;line-height: 4px;" />

                    <input id="boton_guardado_form_1_edit"
                           class="botones_guardar" type="button" value="Guardar"
                           style="margin-left: 10px;height: 26px!important;line-height: 4px;" />

                </td>




            </tr>
        </table>

        <!-- FORMULARIO 2 --> 

        <table>

            <tr id="form_2_edit" style="margin-top: -1.8%;
                display:<?php
                if ($nro_form_mostrar == FORM_VIATICO_2)
                    echo 'inline';
                else
                    echo 'none';
                ?>;">

                <td style="margin-left: -0.6%; padding: 8px;"><p>Origen</p> 

                    <?php
                    // ORIGEN: Taxi / remis / tras. publico es EDITABLE !
                    if ($viatico[0]['id_concepto'] == CONCEPTO_TAXI_REMIS ||
                            $viatico[0]['id_concepto'] == CONCEPTO_TRANS_PUBLICO) {
                        ?>

                        <select id="form2_edit_origen_viatico" disabled>

                            <option value="<?= VIATICO_ORIGEN_SUCURSAL; ?>" <?php if ($viatico[0]['origen'] == VIATICO_ORIGEN_SUCURSAL) echo ' selected'; ?>>
                                Desde Sucursal
                            </option>

                            <option value="<?= VIATICO_ORIGEN_VISITA_ANTERIOR; ?>" <?php if ($viatico[0]['origen'] == VIATICO_ORIGEN_VISITA_ANTERIOR) echo ' selected'; ?>>
                                Desde Visita Anterior
                            </option>           

                        </select>

                        <?php
                    }
                    else {
                        ?>

                        <span style="font-family: Arial; font-size: 12px; 
                              text-decoration: none;">

                            <?php
                            if ($viatico[0]['origen'] == VIATICO_ORIGEN_SUCURSAL)
                                echo 'Desde Sucursal';
                            else
                                echo 'Desde Visita Anterior';
                            ?>

                        </span>

                        <?php
                    }
                    ?>

                </td>

                <td style="margin-left: -2%; padding: 8px;">
                    <p>Destino

                        <?php
                        if ($viatico[0]['id_concepto'] == CONCEPTO_TAXI_REMIS ||
                                $viatico[0]['id_concepto'] == CONCEPTO_TRANS_PUBLICO) {
                            echo ' (URL here.com aqui)';
                        }
                        ?>

                    </p>

                    <?php
                    // DESTINO: Taxi/remis/trans. publico es EDITABLE !

                    if ($viatico[0]['id_concepto'] == CONCEPTO_TAXI_REMIS ||
                            $viatico[0]['id_concepto'] == CONCEPTO_TRANS_PUBLICO) {
                        ?>   

                        <input disabled type="text" id="form2_edit_destino_viatico"
                               value="<?= $viatico[0]['destino']; ?>" />

                        <?php
                    } else {
                        ?>

                        <span style="font-family: Arial; font-size: 12px; 
                              text-decoration: none;"><?= $viatico[0]['destino']; ?></span>

                        <?php
                    }
                    ?>

                </td>

                <td style="margin-left: -2%; padding: 8px;"><p>KM</p>

                    <?php
                    // KM: Taxi/remis/trans. publico es EDITABLE !

                    if ($viatico[0]['id_concepto'] == CONCEPTO_TAXI_REMIS ||
                            $viatico[0]['id_concepto'] == CONCEPTO_TRANS_PUBLICO) {
                        ?>   

                        <input type="text" id="form2_edit_km_viatico"
                               value="<?= $viatico[0]['km']; ?>"
                               style="width: 60px;" />

                        <?php
                    } else {
                        ?>

                        <span style="font-family: Arial; font-size: 12px; 
                              text-decoration: none;"><?= $viatico[0]['km']; ?></span>

                        <?php
                    }
                    ?>

                </td>

                <td style="margin-left: 0%; padding: 8px;"><p>Importe</p>

                    <?php
                    // Importe: Taxi/remis/trans. publico es EDITABLE !

                    if ($viatico[0]['id_concepto'] == CONCEPTO_TAXI_REMIS ||
                            $viatico[0]['id_concepto'] == CONCEPTO_TRANS_PUBLICO) {
                        ?>   

                        <input type="number" id="form2_edit_importe_viatico"
                               value="<?= $viatico[0]['importe']; ?>"
                               style="width: 60px;" min='0' />

                        <?php
                    } else {
                        ?>

                        <span style="font-family: Arial; font-size: 12px; 
                              text-decoration: none;"><?= $viatico[0]['importe']; ?></span>

                        <?php
                    }
                    ?>

                </td>

                <?php
                if ($viatico[0]['id_motivo'] == MOTIVO_VISITA_COBRANZA) {
                    ?>

                    <td style="margin-left: -3%; padding: 8px;"><p>Nro solicitud</p>


                        <input class="text" type="text" style="width: 80px;"
                               id="form2_edit_solicitud_viatico"
                               value="<?= $viatico[0]['nro_solicitud']; ?>" />

                    </td>
                    <?php
                }
                ?>

                <td style="margin-top:48px;margin-left:29.3%; padding: 8px;">

                    <br /><br />

                    <input class="botones_nueva" type="button" value="Cancelar"
                           onclick=" $('#myModal').trigger('reveal:close');"
                           style="height: 26px!important;line-height: 4px;" />

                    <input id="boton_guardado_form_2_edit"
                           class="botones_guardar" type="button" value="Guardar"
                           style="margin-left: 10px;height: 26px!important;line-height: 4px;" />

                </td>

            </tr>

        </table>

        <!-- FORMULARIO 3 -->

        <table>

            <tr id="form_3_edit" style="margin-top: -1.8%;
                display:<?php
                if ($nro_form_mostrar == FORM_VIATICO_3)
                    echo 'inline';
                else
                    echo 'none';
                ?>;padding-bottom:30px;" >

                <td style="padding: 8px;">

                    Importe:<br />
                    <input class="text" type="number"
                           id="form3_edit_importe_viatico" min='0'
                           style="width: 80px;" value="<?= $viatico[0]['importe']; ?>"/>

                </td>

                <td style="margin-top:48px;margin-left:0%; padding: 8px;">

                    Attach:<br />
                    <input type="file" id="form3_edit_attach" />

                    <?php
                    if (elViaticoTieneAttach($partes_viatico[1])) {
                        ?>

                        <span onclick="window.open('admin/attachs/<?= nombreAttachViatico($partes_viatico[1]); ?>');" style="cursor: pointer; color: blue;">
                            &nbsp;&nbsp;VER ACTUAL ATTACH
                        </span>
                        <?php
                    }
                    ?>

                </td>

                <td style="margin-top:48px;margin-left:45.3%; padding: 8px;"><br />
                    <input class="botones_nueva cancelar" type="button" value="Cancelar"
                           onclick=" $('#myModal').trigger('reveal:close');"
                           style="height: 26px!important;line-height: 4px;" />

                    <input id="boton_guardado_form_3_edit" class="botones_guardar" type="button"
                           style="margin-left: 10px;height: 26px!important;line-height: 4px;"
                           value="Guardar" />

                </td>

            </tr>

        </table>


        <!--<a class="close-reveal-modal">&#215;</a>-->
    </div>

    <input type="hidden" id="id_viatico" value="<?= $partes_viatico[1]; ?>" />

    <script type="text/javascript" src="../js/funciones_motivos_conceptos.js"></script>

    <script type="text/javascript">

                               console.log(tope_cena);
                               console.log(tope_almuerzo);

                               $("#boton_guardado_form_1_edit,#boton_guardado_form_2_edit,#boton_guardado_form_3_edit").click(function () {

                                   var form_editado = <?= $nro_form_mostrar; ?>;

                                   switch (form_editado)
                                   {

                                       case 1:

                                           var parametros =
                                                   {
                                                       "solicitud_viatico": $('#form1_edit_solicitud_viatico').val(),
                                                       "monto_viatico": $('#form1_edit_monto_viatico').val(),
                                                       "plazo_viatico": $('#form1_edit_plazo_viatico').val(),
                                                       "segmento_viatico": $('#form1_edit_segmento_viatico_select').select().val(),
                                                   }

                                                   break;

                                           case 2:

                                           var parametros = {};

    <?php
    if ($viatico[0]['id_motivo'] == MOTIVO_VISITA_COBRANZA) {
        ?>

                                               parametros.solicitud_viatico =
                                                       $('#form2_edit_solicitud_viatico').val();

        <?php
    }

    if ($viatico[0]['id_concepto'] == CONCEPTO_TAXI_REMIS || $viatico[0]['id_concepto'] == CONCEPTO_TRANS_PUBLICO) {
        ?>

                                               parametros.origen_viatico = $('#form2_edit_origen_viatico').val();

                                               //alert($('#form2_edit_destino_viatico').val());

                                               parametros.destino_viatico = extraerCoordenadasURLUnicoPunto($('#form2_edit_destino_viatico').val());

                                               parametros.importe_viatico = $('#form2_edit_importe_viatico').val();
                                               parametros.km_viatico = $('#form2_edit_km_viatico').val();

        <?php
    }
    ?>

                                           break;


                                       case 3:
                                           var imp_viatico_form3 = $('#form3_edit_importe_viatico').val();
                                           var formData3 = new FormData();
    <?php if ($viatico[0]['id_concepto'] == CONCEPTO_CENA) { ?>
                                               if (imp_viatico_form3 > tope_cena) {
                                                   $('#form3_edit_importe_viatico').css('background-color','red');
                                                   return false;
                                               }
    <?php } else if ($viatico[0]['id_concepto'] == CONCEPTO_ALMUERZO) { ?>
                                               if (imp_viatico_form3 > tope_almuerzo) {
                                                   $('#form3_edit_importe_viatico').css('background-color','red');
                                                   return false;
                                               }
       <?php } else if ($viatico[0]['id_concepto'] == CONCEPTO_GUARDERIA) { ?>
                                               if (imp_viatico_form3 > tope_guarderia) {
                                                   $('#form3_edit_importe_viatico').css('background-color','red');
                                                   return false;
                                               }
    <?php }
    ?>
                                           formData3.append('importe', imp_viatico_form3);

                                           if ($('#form3_edit_attach')[0].files[0])
                                           {
                                               var file_attach = $('#form3_edit_attach')[0].files[0];
                                               formData3.append('form3_file_attach', file_attach);
                                           }

                                           break;

                                   }

                                   if (form_editado == '1' || form_editado == '2')
                                   {

                                       parametros.fecha_viatico = $('#edit_fecha_viatico').val();
                                       parametros.obs_viatico = $('#edit_observaciones_viatico').val();
                                       parametros.form_editado = form_editado;
                                       parametros.accion = 'editar_viatico';
                                       parametros.id_viatico = $('#id_viatico').val();

                                       if ($('#edit_visita').val() == "1" || $('#edit_visita').val() == "0") {

                                           parametros.visita_edicion = $('#edit_visita').val();

                                       }


                                       $.ajax({
                                           data: parametros,
                                           url: 'admin/ajax_peticiones.php',
                                           type: 'post',
                                           success: function (response)
                                           {

                                               $('#myModal').trigger('reveal:close');

                                               setTimeout(function () {
                                                   location.reload();
                                               }, 750);
                                               return false;

                                           }

                                       });

                                   }



                                   if (form_editado == '3')
                                   {

                                       formData3.append('fecha_viatico', $('#edit_fecha_viatico').val());
                                       formData3.append('obs_viatico', $('#edit_observaciones_viatico').val());
                                       formData3.append('form_editado', form_editado);
                                       formData3.append('accion', 'editar_viatico');
                                       formData3.append('id_viatico', $('#id_viatico').val());

                                       jQuery.ajax({
                                           url: 'admin/ajax_peticiones.php',
                                           type: 'POST',
                                           cache: false,
                                           contentType: false,
                                           processData: false,
                                           data: formData3,
                                           success: function (response)
                                           {

                                               $('#myModal').trigger('reveal:close');
                                               setTimeout(function () {
                                                   location.reload();
                                               }, 750);

                                               return false;

                                           }

                                       });

                                   }

                               });

    </script>
    <?php
}

if ($_POST['accion'] == 'chequear_numero_solicitud') {
    $rs = existe_numero_solicitud($_POST['nro_solicitud']);

    if ($rs)
        echo 'succes';
    else
        echo 'error';
}
?>
