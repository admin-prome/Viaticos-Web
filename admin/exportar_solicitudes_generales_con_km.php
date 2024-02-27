<?php
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="reporte_desde_'.$_GET['desde'].'_hasta_'.$_GET['hasta'].'.xls"');

require_once 'inc/const.php';
require_once 'inc/checkLogin.php';
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

$hasta = pasarFechaDatePickerAFormatoDate($_GET['hasta']);

$desde = pasarFechaDatePickerAFormatoDate($_GET['desde']);

$hasta.=' 23:59:59';

$desde.=' 00:00:00';

$estado = $_GET['estado'];

    $solicitudes_a_exportar = obtener_solicitudes_con_kilometros($desde, $hasta, $estado);

    if ($solicitudes_a_exportar) {
        ?>
        <div class="separador">

     
            <h3 style="border:none!important;">
                    Informacion general de Vi&aacute;ticos  presentados desde <?php echo $desde . " " . "hasta " . $hasta; ?>
                
            </h3>
       

            <div style="background-color: #eeeced;margin-top:10px;">

                <table id="table_datatable" border="1">

                    <thead>

                        <tr>

                            <th style="text-align: left!important;">Nro de Legajo</th>
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
        $total_km = 0;

        foreach ($solicitudes_a_exportar as $row) {
            
      
            $importe = obtener_importe_solicitud($row["id"]);
            ?>

                            <tr id="<?= $row["id"]; ?>">  
                               <td>
                                     <?= obtener_legajo_usuario_BASE_BANCO($row['id_usuarios']); ?>
                                 </td>                                <td style="text-align:center;"><?= obtener_nombre_usuario_BASE_BANCO($row["id_usuarios"]); ?></td>
                                 <td style="text-align:center;"><?= presentarFechaSinHora($row["fecha_presentacion"]); ?></td> 
                                <td style="text-align:center;"><?= obtener_nombre_mes($row["mes"]); ?></td>
                                <td style="text-align:center;"><?php 
                                if(obtener_kilometros_de_solicitud($row["id"])){
                                        $km_mostrar = number_format(obtener_kilometros_de_solicitud($row["id"]), 2, ',', '.');
                                        $km_suma = obtener_kilometros_de_solicitud($row["id"]);
                                        echo $km_mostrar;
                                         $total_km=$total_km+$km_suma;
                                        
                                        
                                }else{echo('-');}
                                 ?></td>
                                
                                <td style="text-align:center;">$ <?= formatearMoneda($importe[0]["importe"]); ?></td>

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
                <input type="button" name="commit" id="exportar_nombre" style="width: 220px;float: left;margin-left: 0%;" class="botones" value="Exportar reporte en excel" />
                <input type="button" name="commit" id="exportar_generales" style="width: 220px;float: left;margin-left: 1%;" class="botones" value="Exportar generales en excel" />

            </form>

        </div>
        <?php
    } else {
        ?>	
        <h1 class="ayudas">No existen rendiciones para exportar.</h1>

        <?php
    }
    ?>


<h3>Total de kilometros recorridos <?php echo(number_format($total_km, 2, ',', '.')); ?> </h3>