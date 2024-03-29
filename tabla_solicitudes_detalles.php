<br/>
<br/>
<style type="text/css">
    #detalle_solicitud_paginate{padding-top: 30px;padding-bottom: 0px!important;}
</style>


<?php
require_once 'inc/checkLogin.php';
require_once 'admin/inc/const.php';
require_once 'admin/inc/funciones/all_functions.php';
$id_usuario = $_SESSION['id_usuario'];

$id_solicitud = $_GET['id_solicitud'];

$viaticos = obtener_viaticos_solicitud($_GET['id_solicitud']);

$solicitud = obtener_solicitud_datos($_GET['id_solicitud']);

$estado_solicitud = $solicitud[0]["estado_id"];

$solicitudes = usuario_solicitud_pendiente($id_usuario);


if(!empty($solicitud)){

    $solicitud_activa = $solicitud[0]['id'];

}


$estado_siguiente = conocer_siguiente_estado($estado_solicitud);
$coords_sucursal_usuario = obtener_coordenadas_sucursal_BASE_BANCO($_SESSION['id_sucursal']);

?>

<script type="text/javascript" src="js/functions_viaticos.js"></script>
<script type="text/javascript">
//Todo lo reee global:

id_usuario_js = <?= $id_usuario; ?>;

solicitud_activa_js = <?= $id_solicitud; ?>;

coords_sucursal = '<?= $coords_sucursal_usuario ?>';

coords_visita_anterior ='<?= obtener_coordenadas_ultima_visita($id_usuario, $id_solicitud); ?>';
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

    //Quizas no necesite este dato, lo traigo por las dudas:
    coords_visita_anterior = '<?= obtener_coordenadas_ultima_visita($id_usuario, $solicitud_activa); ?>';

    CARGADO_WEB = 0;

    kms_calculados_sistema = '';
    importe_calculado_sistema = '';

    datos_viaje_ida = new Array();
    datos_viaje_vuelta = new Array();

    costo_km_auto = <?= $_SESSION['costo_km_auto']; ?>;
    costo_km_moto = <?= $_SESSION['costo_km_moto']; ?>;

// Fin vars. globales

</script>
<script type="text/javascript" src="js/nuevo_viatico_ver_mapa.js"></script> 
<br />
<input type="hidden" value="<?= $solicitud[0]['id']; ?>" id="id_solicitud"/>
<input type="hidden" value="<?= $estado_siguiente; ?>" id="siguiente_estado"/>

<div class="separador">
    <h3> Vi&aacute;ticos presentados por <span style="color:gray; font-weight:bold;"><?= $_SESSION['nombre_usuario']; ?></span> correspondientes al mes de <b><?php echo(obtener_nombre_mes($solicitud[0]['mes'])); ?></b> del <?php echo($solicitud[0]['ano']); ?></h3>
    <table id="detalle_solicitud" class="display" cellspacing="0" style="width:100%; margin:0 auto;">
        <thead style="text-align: center!important;">
            <tr style="text-align: center!important;">
                <th style="text-align: left!important;">Carga por:</th>
                <th style="text-align: left!important;">Fecha de Presentaci&oacute;n</th>
                <th style="text-align: left!important;">Motivo</th>
                <th style="text-align: left!important;">Concepto</th>
                <th style="text-align: left!important;">Personas por almuerzo</th>
                <th style="text-align: left!important;">Origen</th>
                <th style="text-align: left!important;">Destino</th>
                <th style="text-align: left!important;">Km</th>
                <th style="text-align: left!important;">N&uacute;mero solicitud</th>
                <th style="text-align: left!important;">Monto</th>
                <th style="text-align: left!important;">Comentario</th>
                <th style="text-align: left!important;">Adjunto</th> 
                <th style="text-align: center!important;">Importe</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $divsComentarios2 = '';
            $importe = obtener_importe_solicitud($id_solicitud);
            if ($viaticos) {
                foreach ($viaticos as $row) {
                    echo('<tr align="center" id="fila_' . $row["id"] . '">');
                    echo("<td>" . ponerIconoPCCelular($row["cargado_en_celular"]) . "</td>");
                    echo("<td>" . presentarFechaDateTime($row["fecha"], "-", "/") . "</td>");
                    echo("<td>" . guion(obtener_motivo($row["id_motivo"], false)) . "</td>");
                    echo("<td>" . guion(obtener_concepto($row["id_concepto"])) . "</td>");
                    echo("<td>" . guion($row["personas_por_almuerzo"]) . "</td>");
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

                        $divsComentarios2 .= '<div title="Comentarios:" id="comentario2_' .
                        $row["id"] . '" style="display:none;background-color:white;">' .
                        $row["observacion"] . "</div>\n";
                    } else {
                        echo('<td> - </td>');
                    }
                    if (elViaticoTieneAttach($row["id"], '/admin')) {
                        ?>					
                        <td id="ver_viatico_<?= $row['id']; ?>">
                            <span style="cursor: pointer;" class="attach_viatico"
                            id="<?= nombreAttachViatico($row['id']); ?>">
                            Click aqu&iacute;
                        </span>
                    </td>
                    <?php
                }else{
                    echo '<td> - </td>';
                }
                echo("<td  style='text-align:center;'> $" . formatearMoneda($row["importe"]) . "</td>");
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
        <th style="text-align: left!important;">&nbsp;</th>
        <th style="text-align: left!important;">&nbsp;</th>

        <th style="text-align: left!important;">&nbsp;</th>
        <th style="text-align: left!important;">&nbsp;</th>
        <th style="text-align: left!important;">&nbsp;</th>
        <th style="text-align: left!important;">&nbsp;</th>
        <th style="text-align: center!important;"><h3><?php echo('$'.formatearMoneda($importe[0]["importe"]) );?></h3></th>
    </tr>
</tfoot>
</table>
<div style="margin-left: 42%;padding-bottom: 3%;"> 

    <div style="display:none;margin: 11px 55px;" id="load"><div class="loading"></div><span style="margin-left: -4px;">Guardando</span></div>
</div>
<?= $divsComentarios2; ?>
</div>   
<div id="mapa_ventana_modal"></div>

<div id="historial" > 

</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#detalle_solicitud').dataTable({
            //  "dom": '<"top"i>rt<"bottom"flp><"clear">',
            "paging": true,
            "ordering": true,
            "info": false,
            "search": false
        });
//TRAIGO EL HISTORIAL DESDE OTRO ARCHIVO
$("#historial").load("historial_vista.php?id_solicitud=" + <?php echo($id_solicitud); ?> + "&");

$(".ver_comentario2").click(
    function()
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

$(".attach_viatico").click(function() {
    window.open('admin/attachs/' + this.id, '_blank');
});


$("#aprobar").click(function() {
    $("#aprobar").css('display', 'none');
    $("#rechazar").css('display', 'none');
    $("#load").css('display', 'block');
    var parametros = {
        "solicitud_activa": $('#id_solicitud').val(),
        "accion": "cambiar_estado",
        "estado": $('#siguiente_estado').val()
    };

    $.ajax({
        url: 'admin/ajax_peticiones.php',
        data: parametros,
        type: 'post',
        success: function(response)
        {
            setTimeout(
                function() {
                    var id_solicitud = $('#id_solicitud').val();
                    var comentario = "Cambio de estado de la solicitud a " + $('#siguiente_estado').val() + "";
                    var usuario =<?= $id_usuario ?>;

                    insertar_comentario_historial(id_solicitud, comentario, usuario);

                    $("#load").css('display', 'none');
                                 window.location = 'home.php';
                            }, 2000);
        }
    });
});
//FUNCION VIEJA DE MAPAS
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

*/

        });
</script>
