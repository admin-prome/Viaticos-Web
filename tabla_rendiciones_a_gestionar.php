<?php
session_start();
require_once 'inc/checkLogin.php'; 
require_once 'admin/inc/const.php'; 
require_once 'admin/inc/funciones/all_functions.php';
 

if ($_SESSION['id_estados_gestionar'] == SOLICITUD_AUTORIZADA || $_SESSION['id_estados_gestionar'] == SOLICITUD_REVISADA) {
    $solicitudes_a_gestionar_revisar_aprobar = obtener_mis_solicitudes_a_gestionar_revisar_aprobar_BASE_BANCO($_SESSION['id_estados_gestionar']);
} else {
    $solicitudes_a_gestionar = obtener_mis_solicitudes_a_gestionar_BASE_BANCO($id_usuario, $_SESSION['id_estados_gestionar']);
}

?>
<style type="text/css">
    #menu{height: 100px!important; }

</style>
<?php
?>
<div class="gestionar" style="display:<?php
if (!empty($solicitudes_a_gestionar)) {
    echo('inline');
} else {
    echo('none');
}
?>;margin-top:80px;">

    <div class="titulo_rendicion_gestionar">
        <h4>RENDICIONES A GESTIONAR</h4>
    </div>
    <div id="menu">
        <div style="background-color: #eeeced;margin-top:-2%;">
            <br/>
            <table id="example" class="display" cellspacing="0" width="100%">
                <thead style="text-align: center!important;">
                    <tr style="text-align: center!important;">
                        <th style="text-align: left!important;">Carga por :</th>
                        <th style="text-align: left!important;">Fecha Presentacion</th>
                        <th style="text-align: left!important;">Viaticos mes</th>
                        <th style="text-align: left!important;">Nombre</th>
                        <th style="text-align: left!important;">Cargo</th>
                        <th style="text-align: left!important;"> Sucursal</th>
                        <th style="text-align: left!important;">Importe</th>
                        <th style="text-align: left!important;">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($solicitudes_a_gestionar) {
                        foreach ($solicitudes_a_gestionar as $row) {
                            $importe = obtener_importe_solicitud($row["id"]);
                            $cargo = obtener_cargo_de_usuario_BASE_BANCO($row["id_usuarios"]);

                            $sucursal = nombre_sucursal_por_id_usuario_BASE_BANCO($row["id_usuarios"]);
                            echo('<tr align="center" id="fila_' . $row["id"] . '">');
                             echo("<td>" . ponerIconoPCCelular($row["cargado_en_celular"]) . "</td>");
                            echo("<td>" . formatearFechaDDMMAAAA($row["fecha_presentacion"]) . "</td>");
                            echo("<td>" . obtener_nombre_mes($row["mes"]) . "</td>");
                            echo("<td>" . htmlentities(obtener_nombre_usuario_BASE_BANCO($row['id_usuarios'])) . "</td>");
                            echo("<td>" . $cargo[0]['puesto'] . "</td>");
                            echo("<td>" . $sucursal . "</td>");
                            echo("<td> $" . formatearMoneda($importe[0]["importe"]) . "</td>");
                            echo("<td><input id='" . $row['id'] . "' class='botones ver_solicitud' type='button' value='Ver'/></td>");
                            echo('</tr>');
                        };
                    };
                    ?>
                </tbody>
            </table>
        </div>

    </div>
    <div id="tabla_ver_gestion" >
    </div>
    
    <style type="text/css">
        #footer{	position: relative; margin-top: 50%;	}
    </style>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $(".ver_solicitud").click(function()
            {
                setTimeout(
                        function() {
                        }, 2000);

                var id_solicitud = (this.id);

                $("#tabla_ver_gestion").load("tabla_solicitudes_a_gestionar.php?id_solicitud=" + id_solicitud + "");
                // $('#tabla_ver_gestion').reveal('open');
            });
        });
    </script>