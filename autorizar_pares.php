<?php require_once 'inc/checkLogin.php'; ?>
<?php require_once 'admin/inc/const.php'; ?>
<?php $id_usuario = $_SESSION['id_usuario']; ?>
<?php require_once 'admin/inc/funciones/all_functions.php'; ?>
<?php require_once 'inc/header.php'; ?>
<?php require 'inc/menu.php'; ?>
<?php

if($_SESSION['pk_depende_de'] == GERENTE_MATRIZ){
    $solicitudes_a_gestionar = obtener_mis_solicitudes_a_gestionar_BASE_BANCO(GERENTE_MATRIZ,SOLICITUD_PRESENTADA,$id_usuario);
}else if($_SESSION['pk_depende_de'] == ADMINISTRACION){
    $solicitudes_a_gestionar = obtener_mis_solicitudes_a_gestionar_BASE_BANCO(ADMINISTRACION,SOLICITUD_PRESENTADA,$id_usuario);
}
?>
<style type="text/css">
    #menu{height: 100px!important; }
    .separador { margin-top:15%!important;}
</style>
<?php
?>
  <?php
        if (!$solicitudes_a_gestionar) {
    echo('<h4 class="ayudas">No tienes ninguna solicitud para autorizar</h4>');
} 
?>
<div class="gestionar" style="display:<?php
if (!empty($solicitudes_a_gestionar)) {
    echo('inline');
} else {
    echo('none');
}
?>;margin-top:80px;">
    <h4 class="ayudas">Esta viendo las rendiciones pendientes de autorizacion de sus pares , luego de autorizacion o no de las mismas seguira el curso comun del sistema de rendicion.</h4>

    <div class="titulo_rendicion_gestionar">
        <h4>RENDICIONES A AUTORIZAR</h4>
    </div>
    <div id="menu">
      
        <div style="background-color: #eeeced;margin-top:-2%;">
            <br/>
            <table id="example" class="display" cellspacing="0" width="100%">
                <thead style="text-align: center!important;">
                    <tr style="text-align: center!important;">
                        <th style="text-align: left!important;">Fecha Presentacion</th>
                        <th style="text-align: left!important;">Viaticos mes</th>
                        <th style="text-align: left!important;">Nombre</th>
                        <th style="text-align: left!important;">Cargo</th>
                        <th style="text-align: left!important;"> Sucursal</th>
                        <th style="text-align: left!important;">Importe</th>
                        <?php
                        if ($_SESSION['id_estados_gestionar'] == SOLICITUD_AUTORIZADA || $_SESSION['id_estados_gestionar'] == SOLICITUD_REVISADA || $_SESSION['id_estados_gestionar'] == SOLICITUD_APROBADA) {
                            echo('<th style="text-align: left!important;">Impreso</th>');
                        }
                        ?>
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
                            echo("<td>" . presentarFechaDateTime($row["fecha_presentacion"]) . "</td>");
                            echo("<td>" . obtener_nombre_mes($row["mes"]) . "</td>");
                            echo("<td>" . htmlentities(obtener_nombre_usuario_BASE_BANCO($row['id_usuarios'])) . "</td>");
                            echo("<td>" . $cargo[0]['puesto'] . "</td>");
                            echo("<td>" . $sucursal . "</td>");
                            echo("<td> $" . formatearMoneda($importe[0]["importe"]) . "</td>");
                              if ( $_SESSION['id_estados_gestionar'] == SOLICITUD_AUTORIZADA || $_SESSION['id_estados_gestionar'] == SOLICITUD_REVISADA || $_SESSION['id_estados_gestionar'] == SOLICITUD_APROBADA) {
                                echo('<td>');
                                if($row["impreso"]){
                                    echo('<img style="width: 30px;" src="images/ok.png">');
                                }else{echo('<img style="width: 30px;" src="images/no-ok.png">');}
                                echo('</td>');
                            }
                            echo("<td><input id='" . $row['id'] . "' class='botones ver_solicitud' type='button' value='Ver'/></td>");
                            echo('</tr>');
                        };
                    };
                    ?>
                </tbody>
            </table>
        </div>
        <br>
        <br>
        <br>
      <!--  <a href="aprobar_nivel_inferior.php"><input style="width: 180px;line-height: 1px;" id='gestionar_saltando_un_nivel' class='botones ' type='button' value='Gestionar saltando un nivel'/></a>-->
    </div>
    <div style="margin-top:15%!important;" id="tabla_ver_gestion" >
    </div>

    <script type="text/javascript">
        $(document).ready(function()
        {
            $(".separador").css('margin-top','15%');
            $(".ver_solicitud").click(function()
            {

                setTimeout(
                        function() {
                        }, 2000);

                var id_solicitud = (this.id);

                $("#tabla_ver_gestion").load("tabla_solicitudes_a_gestionar.php?id_solicitud=" + id_solicitud + "&leyenda_boton=Autorizar&pagina=autorizar_pares.php");
                var position = $('#tabla_ver_gestion').position();
                // windows.scrollTo(0,position.top);

                window.scrollTo(0, position.top);
            });
        });
    </script>
    <?php
    include_once 'inc/footer.php';
    ?>