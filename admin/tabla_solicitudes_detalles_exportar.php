<?php
require_once 'inc/checkLogin.php';
require_once 'inc/const.php';
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

$solicitud_nueva = $_GET['id_solicitud'];

$viaticos = obtener_viaticos_solicitud($solicitud_nueva);
function guion($parametro) {
    if (empty($parametro))
        return " - ";
    else
        return $parametro;
}

if ($viaticos) {
?>

<br/>

<div style="background-color: #eeeced;margin-top:-2%;">
    <br />
    <br />
    <br />
     
    <!--<h3 style="margin-left: 20%;color:graytext;font-family: neosansregular;">
        <br />
        Vi&aacute;ticos presentados por <b style="color:gray;">Juan perez</b> correspondientes al mes de <b>Noviembre</b> del 2014</h3>-->
    <table id="detalle_solicitud" class="display" cellspacing="0" width=" width: 800px;      margin: 0 auto;">

        <thead style="text-align: center!important;">

            <tr style="text-align: center!important;">

                <th style="text-align: left!important;">Fecha de Presentaci&oacute;n</th>
                <th style="text-align: left!important;">Motivo</th>
                <th style="text-align: left!important;">Concepto</th>
                <th style="text-align: left!important;">Origen</th>
                <th style="text-align: left!important;">Destino</th>
                <th style="text-align: left!important;">Km</th>
                <th style="text-align: left!important;">Importe</th>
                <th style="text-align: left!important;">N&uacute;mero solicitud</th>
                <th style="text-align: left!important;">Monto</th>
                <th style="text-align: left!important;">Comentario</th>
                <th style="text-align: left!important;">Adjunto</th>
            </tr>

        </thead>

        <tbody>
            <?php
            $divsComentarios2 = '';
           
            
                foreach ($viaticos as $row) {
                    echo('<tr align="center" id="fila_' . $row["id"] . '">');
                    echo("<td>" . formatearFechaDDMMAAAA($row["fecha"],"-","/") . "</td>");
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
                    echo("<td> $" .guion(formatearMoneda($row["importe"])) . "</td>");
                    echo("<td>" . guion($row["nro_solicitud"]) . "</td>");
                    echo("<td>" . guion($row["monto"]) . "</td>");

                    if (!empty($row["observacion"])) {

                        echo('<td class="ver_comentario2" id="'.$row["id"].'">');
                        echo('<span style="cursor: pointer;">Click aqui</span></td>');

                        $divsComentarios2 .= '<div title="Comentarios:" id="comentario2_' .
                                $row["id"] . '" style="display:none;background-color:white;">' .
                                $row["observacion"] . "</div>\n";
                    } else {
                        echo('<td> - </td>');
                    }
                 $attach_file_name = name_attach_viatico($row["id"]);

        if ($attach_file_name) {
            echo '<td style="cursor: pointer;" class="attach_viatico"
				  id="' . $attach_file_name . '">Click aqu&iacute;</td>';
        } else
            echo('<td> - </td>');
                    ?>
                    </tr>
                    <?php
                }
            }
            ?>  
        </tbody>
    </table>
  <!-- <input id='<?= $solicitud_nueva?>' class='botones ver_solicitud' type='button' style="width: 150px;" value='Exportar esta solicitud'/> -->
    <?= $divsComentarios2; ?>
</div>   
<div id="mapa_ventana_modal"></div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#detalle_solicitud').dataTable({
            //  "dom": '<"top"i>rt<"bottom"flp><"clear">',
            "paging": true,
            "ordering": true,
            "info": false,
            "search": false
        });

        $(".ver_comentario2").click(
                function()
                {
                    var id_td_comentario = (this.id).split("_");
                    //console.log(id_td_comentario);
                    //console.log("comentario2_" + id_td_comentario);
                    $("#comentario2_" + id_td_comentario).dialog({
                       resizable: false,
                        height: 150,
                        width:450,
                        modal: true,
                        show: {effect: 'blind', duration: 400},
                        hide: {effect: 'explode', duration: 1000},
                        resizable: false
                    });
                }
        );

        $(".attach_viatico").click(
                function()
                {
                    var partes_viatico = (this.id).split('_');
                    var id_viatico = partes_viatico[2];
                    window.open('attachs/' + id_viatico + '.pdf', '_blank');
                }
        );
     $(".ver_mapa").click(function() {

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
                        url: '../admin/ajax_peticiones.php',
                        type: 'post',
                        success: function(response) {

                            coord_1 = response;


                        }

                    });


                }


            }


            setTimeout(function() {
                $('#mapa_ventana_modal').html(' ');
                $("#mapa_ventana_modal").load("../get_html_mapa.php?coord1=" + coord_1 + "&coord2=" + coord_2);
            }, 1000);


        });
    });
</script>
