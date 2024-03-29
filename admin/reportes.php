<?php
require_once 'inc/checkLogin.php';
require_once 'inc/const.php';

require_once 'inc/header.php';

// require_once 'inc/menu_administrador.php';
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
  require_once 'inc/funciones/zonas.php';
 */
?>

<h1 class="ayudas" style="font-size: 16px;">Viaticos para exportar:</h1>

<form action="" method="post" class="ayudas" onsubmit="return validarFechasDesdeHasta();">
    <p class="ayudas">Seleccione una fecha de inicio y otra de fin , y el estado de las rendiciones que desea ver:</p>
    <ul class="busquedas">
        <li><p><b>Inicio:</b></p>	<input style="font-size: 11px;width: 70px;margin-left: 10px;" type="text" name="fecha_desde" id="fecha_desde" value="<?= @ $_POST['fecha_desde']; ?>"  placeholder="<?= obtenerFechaActualDDMMAAAA(); ?>" /></li>

        <li><p><b>Fin:</b></p> 	<input  style="font-size: 11px;width: 70px;margin-left: 10px;" type="text" name="fecha_hasta" id="fecha_hasta" value="<?= @ $_POST['fecha_hasta']; ?>" placeholder="<?= obtenerFechaActualDDMMAAAA(); ?>" /></li>

        <li>
            <p style="font-weight:bold;">Estado de las solicitudes</p>

            <select id="estado" style="margin-left: 10px;" name="estado">

                <option value="<?= SOLICITUD_PRESENTADA ?>"
                        <?php if (@ $_POST['estado'] == SOLICITUD_PRESENTADA) echo 'selected'; ?>>Presentadas</option>

                <option value="<?= SOLICITUD_AUTORIZADA ?>"
                        <?php if (@ $_POST['estado'] == SOLICITUD_AUTORIZADA) echo 'selected'; ?>>Autorizadas</option>

                <option value="<?= SOLICITUD_REVISADA ?>"
                        <?php if (@ $_POST['estado'] == SOLICITUD_REVISADA) echo 'selected'; ?>>Revisadas</option>

                <option value="<?= SOLICITUD_APROBADA ?>"
                        <?php if (@ $_POST['estado'] == SOLICITUD_APROBADA) echo 'selected'; ?>>Aprobadas</option>

                <option value="<?= SOLICITUD_EXPORTAR ?>"
                        <?php if (@ $_POST['estado'] == SOLICITUD_EXPORTAR) echo 'selected'; ?>>Exportadas</option>

                <option value="<?= SOLICITUD_RECHAZADA ?>"
                        <?php if (@ $_POST['estado'] == SOLICITUD_RECHAZADA) echo 'selected'; ?>>Rechazadas</option>

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
if (sizeof($_POST) > 0) {
    $solicitudes_a_exportar = '';
    $fecha_desde = $_POST['fecha_desde'];
    $fecha_hasta = $_POST['fecha_hasta'];
    $estado = $_POST['estado'];

    $partesFecha = explode('/', $fecha_desde);

    $fecha_desde = $partesFecha[2] . '-' . $partesFecha[1] . '-' . $partesFecha[0] .' 00:00:00';

    $partesFecha = explode('/', $fecha_hasta);

    $fecha_hasta = $partesFecha[2] . '-' . $partesFecha[1] . '-' . $partesFecha[0] .' 23:59:59';

    $solicitudes_a_exportar = obtener_solicitudes_con_kilometros($fecha_desde, $fecha_hasta, $estado);

    if ($solicitudes_a_exportar) {
        ?>
        <div class="separador">

            <h3>Vi&aacute;ticos presentados por: 
                <span style="color:gray; font-weight:bold;">
        <?= $_SESSION['nombre_usuario']; ?>
                </span>
            </h3>

            <div style="background-color: #eeeced;margin-top:10px;">

                <table id="table_datatable" class="display"
                       cellspacing="0" width=" width: 800px;margin: 0 auto;">

                    <thead>

                        <tr>

                            <th style="text-align: left!important;">Usuario</th>
                            <th style="text-align: left!important;">Fecha Presentacion</th>
                            <th style="text-align: left!important;">Mes correspondiente</th>
                            <th style="text-align: left!important;">km</th>
                            <th style="text-align: left!important;">Importe</th>
                            

                        </tr>

                    </thead>

                    <tbody> 

        <?php
        $id_solicitudes = '';

        foreach ($solicitudes_a_exportar as $row) {
    
            $importe = obtener_importe_solicitud($row["id"]);
            ?>

                            <tr id="<?= $row["id"]; ?>">  

                                <td><?= obtener_nombre_usuario_BASE_BANCO($row["id_usuarios"]); ?></td>
                                <td><?= presentarFechaSinHora($row["fecha_presentacion"]); ?></td> 
                                <td><?= obtener_nombre_mes($row["mes"]); ?></td>
                                <td><?php 
                                if(obtener_kilometros_de_solicitud($row["id"])){
                                echo(number_format(obtener_kilometros_de_solicitud($row["id"]), 2, ',', '.'));
                                }else{echo('-');}
                                 ?></td>
                                
                                <td>$ <?= formatearMoneda($importe[0]["importe"]); ?></td>

                            </tr>

            <?php
            $id_solicitudes .= $row["id"] . ',';
        }

        $id_solicitudes = rtrim($id_solicitudes, ",");
        ?>  

                    </tbody>
                </table>

            </div>
        </div>


        <div style="width: 100%;text-align: center;margin-top: 5%;">

            <form style="margin-left: auto;margin-right: auto;" method="post" 
                  action="exportar_solicitudes_aprobadas.php">

                <input type="hidden" name="fecha_desde"
                       value="<?= $fecha_desde; ?>" />

                <input type="hidden" name="fecha_hasta"
                       value="<?= $fecha_hasta; ?>" />

                <input type="hidden" name="ids_solicitudes" value="<?= $id_solicitudes; ?>" />

                <!--<input type="submit" name="commit" style="width: 165px;" class="botones" value="Exportar rendiciones a calipso" />-->
                <input type="button" name="commit" id="exportar_nombre" style="width: 220px;float: left;margin-left: 0%;" class="botones" value="Ver reporte en excel" />
                <input type="button" name="commit" id="exportar_generales" style="width: 220px;float: left;margin-left: 1%;" class="botones" value="Ver reporte general en excel" />

            </form>

        </div>
        <?php
    } else {
        ?>	
        <h1 class="ayudas">No existen rendiciones para exportar.</h1>

        <?php
    }
}
?>

<script type="text/javascript">

    $(document).ready(function () {

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
                    firstDay: 0,
                    isRTL: false,
                    showMonthAfterYear: false,
                    yearSuffix: ''
                };

        $.datepicker.setDefaults($.datepicker.regional['es']);

        $(function () {
            $("#fecha_desde").datepicker();
            $("#fecha_hasta").datepicker();
        });

    });

    $("#buscar").click(function () {
        $(".load").css('display', 'inline');
    });

    $("#exportar_nombre").click(function () {
        
        var desde = $("#fecha_desde").val();

        var hasta = $("#fecha_hasta").val();
        
        var estado = $("#estado option:selected").val();

        window.open("exportar_solicitudes_con_km.php?estado="+estado+"&desde=" + desde + "&hasta=" + hasta );
    });
    
        $("#exportar_generales").click(function () {
        
        var desde = $("#fecha_desde").val();

        var hasta = $("#fecha_hasta").val();
        
        var estado = $("#estado option:selected").val();

        window.open("exportar_solicitudes_generales_con_km.php?estado="+estado+"&desde=" + desde + "&hasta=" + hasta );
    });

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

        return true;
    }
/*
    function disparar_exportacion()
    {
        var desde = $("#fecha_desde").val();
        var hasta = $("#fecha_hasta").val();

        window.open("exportar_solicitudes_aprobadas.php?desde=" + desde + "&hasta=" + hasta);
    }
    function disparar_exportacion_nombre()
    {
        var desde = $("#fecha_desde").val();

        var hasta = $("#fecha_hasta").val();

        window.open("exportar_solicitudes_aprobadas_nombre_usuario.php?desde=" + desde + "&hasta=" + hasta);
    }*/

</script>


<?php
include 'inc/footer.php';
?>
</body>
</html>