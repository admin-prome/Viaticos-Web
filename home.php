<?php require_once 'inc/checkLogin.php'; ?>
<?php require_once 'admin/inc/const.php'; ?>
<?php $id_usuario = $_SESSION['id_usuario']; ?>
<?php require_once 'admin/inc/funciones/all_functions.php'; ?>
<?php require_once 'inc/header.php'; ?>
<?php require 'inc/menu.php';


if ($_SESSION['id_estados_gestionar'] == SOLICITUD_REVISADA || $_SESSION['id_estados_gestionar'] == SOLICITUD_AUTORIZADA) {
    $solicitudes_a_gestionar = obtener_mis_solicitudes_a_gestionar_revisar_aprobar_BASE_BANCO($_SESSION['id_estados_gestionar']);
} else if ($_SESSION['id_estados_gestionar']) {
    $solicitudes_a_gestionar = obtener_mis_solicitudes_a_gestionar_BASE_BANCO($id_usuario, $_SESSION['id_estados_gestionar']);
}
?>

<style type="text/css">
    #menu{	height: 100px!important;	}
</style>
<?php
 if (($_SESSION['pk_depende_de'] == GERENTE_MATRIZ || $_SESSION['pk_depende_de'] == ADMINISTRACION) && $id_usuario != ID_VERONICA_LANUS) {
  ?>
  <!-- <a href="aprobar_nivel_inferior.php"><input style="width: 180px;line-height: 1px;" id='gestionar_saltando_un_nivel' class='botones ' type='button' value='Gestionar saltando un nivel'/></a>-->
  <input style="width: 180px;line-height: 1px; margin-left: 0px;height: 32px" onclick="window.location = 'autorizar_pares.php';" class='botones ' type='button' value='Autorizar pares' />

  <?php
  } 
?>
<div class="gestionar" style="display:<?php
if (!empty($solicitudes_a_gestionar)) {
    echo('inline');
} else {
    echo('none');
}
?>;margin-top:50px;">

    <div class="separador" style="background-color: white!important;">

        <div class="titulo_rendicion_gestionar">
            <h4>RENDICIONES A GESTIONAR</h4>
        </div>
        <div id="menu">
            <div style="background-color: #eeeced;margin-top:-2%;margin-bottom:5%!important;">
                <br />
                <table id="table_datatable" class="display" cellspacing="0" width="100%">
                    <thead style="text-align: center">

                        <tr>

                            <th width="9%" style="text-align: left!important;">ID Rendici&oacute;n</th>

                            <th width="6%" id="check_all">Tildar Todo</th> 
                            <th width="15%" style="text-align: left!important;">Fecha Presentacion</th>
                            <th width="10%" style="text-align: left!important;">Viaticos mes</th>
                            <th width="15%" style="text-align: left!important;">Nombre</th>
                            <th width="10%" style="text-align: left!important;">Cargo</th>
                            <th width="10%" style="text-align: left!important;">Sucursal</th>
                            <th width="8%" style="text-align: left!important;">Importe</th>

                            <?php
                            if ($_SESSION['id_estados_gestionar'] == SOLICITUD_AUTORIZADA || $_SESSION['id_estados_gestionar'] == SOLICITUD_REVISADA || $_SESSION['id_estados_gestionar'] == SOLICITUD_APROBADA) {
                                ?>

                                <th width="6%" style="text-align: left!important;">Impreso</th>

                                <?php
                            }
                            ?>
                            <th width="11%" style="text-align: left!important;">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($solicitudes_a_gestionar) {
                            foreach ($solicitudes_a_gestionar as $row) {
                                $importe = obtener_importe_solicitud($row["id"]);
                                $cargo = obtener_cargo_de_usuario_BASE_BANCO($row["id_usuarios"]);
                                $sucursal = nombre_sucursal_por_id_usuario_BASE_BANCO($row["id_usuarios"]);
                                ?>							
                                <tr align="left" id="fila_<?= $row["id"]; ?>">

                                    <td><?= $row["id"]; ?></td>                  		

                                    <td>
                                        <input type="checkbox" value="rendicion_check_<?= $row["id"]; ?>"
                                               class="checks_aprobar" />
                                    </td>

                                    <td><?= presentarFechaDateTime($row["fecha_presentacion"]); ?></td>
                                    <td><?= obtener_nombre_mes($row["mes"]); ?></td>
                                    <td>
                                        <?= htmlentities(obtener_nombre_usuario_BASE_BANCO($row['id_usuarios'])); ?>
                                    </td> 

                                    <td><?= $cargo[0]['puesto']; ?></td>
                                    <td><?= $sucursal; ?></td>
                                    <td>$ <?= formatearMoneda($importe[0]["importe"]); ?></td>

                                    <?php
                                    if ($_SESSION['id_estados_gestionar'] == SOLICITUD_AUTORIZADA || $_SESSION['id_estados_gestionar'] == SOLICITUD_REVISADA || $_SESSION['id_estados_gestionar'] == SOLICITUD_APROBADA) {
                                        ?>	 

                                        <td>

                                            <?php
                                            if ($row["impreso"])
                                                $src_image = 'ok';
                                            else
                                                $src_image = 'no-ok';
                                            ?>
                                            <img style="width: 30px;" src="images/<?= $src_image; ?>.png" />

                                        </td>

                                        <?php
                                    }
                                    ?>

                                    <td>
                                        <input id="<?= $row['id']; ?>" class="botones ver_solicitud"
                                               type='button' value='Ver' />
                                    </td>

                                </tr>

                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <input type="button" style="width: 180px;line-height: 1px; margin-left: 30px;height: 32px" class="botones" value="<?= obtener_leyenda_boton_gestion($_SESSION['id_estados_gestionar']); ?> rendiciones tildadas" id="ok_all_rendiciones" />
                <br/><br/><br/>

                <div id="tabla_ver_gestion">   
                </div>

            </div>


           
 <div id="historial" > 

    </div>
            <br /><br /><br />

        </div>
    </div>       

    <script type="text/javascript">

        $(document).ready(function ()
        {
             
            $('select[name="table_datatable_length"] > option[value="10"]').attr('selected', 'selected');

            $("#ok_all_rendiciones").click(function ()
            {

                if (!hayChecksTildados('checks_aprobar'))
                {
                    alert('Seleccione al menos 1 rendicion');
                    return false;
                }

                if (confirm('Proceder\u00e1 a <?= conocer_nombre_accion_siguiente_estado($_SESSION['id_estados_gestionar']); ?> todas las rendiciones seleccionadas SIN dejar comentarios.\nDesea proceder?'))
                {

                    var params = {};

                    var checks_tildados = valuesChecksTildados('checks_aprobar');
                    var ids_values_checks = obtener_ids_values_checks_solicitudes_aprobar(checks_tildados);
                    
                    params.usuario =<?= $id_usuario ?>;
                    params.ids_rendiciones = ids_values_checks;
                    params.accion = 'ok_todas_rendiciones_tildadas';
                    params.estado_actual=  <?= $_SESSION['id_estados_gestionar'] ?>;
                    params.setear_nuevo_estado = <?= conocer_siguiente_estado($_SESSION['id_estados_gestionar']); ?>;

                    $.ajax({
                        type: 'post',
                        data: params,
                        url: 'admin/ajax_peticiones.php',
                        success: function (resp) {

                            for (var i = 0; i < ids_values_checks.length; i++)
                            {
                                destruirElementoPorID('fila_' + ids_values_checks[i]);
                            }
                        }

                    });

                }

            });

            $("#check_all").click(function ()
            {
                if ($("#check_all").html() == 'Tildar Todo')
                {
                    checkUncheckSelectBoxs('checks_aprobar', true);
                    $("#check_all").html('Destildar Todo');
                }
                else
                {
                    checkUncheckSelectBoxs('checks_aprobar', false);
                    $("#check_all").html('Tildar Todo');
                }

            });


            $(".ver_solicitud").click(function ()
            {

                setTimeout(function () {
                }, 2000);

                var id_solicitud = (this.id);



                $("#tabla_ver_gestion").load("tabla_solicitudes_a_gestionar.php?id_solicitud=" + id_solicitud + "&leyenda_boton=Autorizar&pagina=home.php");
                var position = $('#tabla_ver_gestion').position();


                window.scrollTo(0, position.top);
            });


        });

    </script>

   

    <?php
    // include_once 'inc/footer.php'; ?>