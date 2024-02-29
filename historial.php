
<style>
    .ayudas{color: #757475!important;
            font-size: 12px!important;
            font-family: neosansregular!important;}

    .historial{text-align: left;font-size: 12px!important;}
    #historial_comentarios ul {list-style: none!important;}
    .comentario_detalles{color: #757475!important;font-family: neosansregular!important;font-size: 12px!important;}


</style>

<?php
require_once 'inc/checkLogin.php';
require_once 'admin/inc/const.php';
require_once 'admin/inc/funciones/historial.php';
require_once 'admin/inc/funciones/all_functions.php';

if ($_GET['id_solicitud']) {
    $solicitud = $_GET['id_solicitud'];
}
$id_usuario = $_SESSION['id_usuario'];
$comentarios = obtener_comentarios_solicitud($_GET['id_solicitud']);
?>
<div class="historial" style="float:none!important;clear:both;">
    <p class="ayudas" style="font-size: 16px!important;"><b>Comentarios</b></p>
    <div style="width: 1220px;">
        <p class="ayudas"></p>
        <input id="comentario_rechazo" class="textbox" style="width: 50%; height: 75px; border: 2px solid lightgray;margin-left:2%;font-size: 11px;" type="text" Placeholder="En caso de rechazar la solicitud debe dejar aqui un comentario , indicando el por que ."/>
        <div style="display:none;margin: 11px 55px;" id="load"><div class="loading"></div><span style="margin-left: -4px;">Guardando</span></div>
        <br/>

        <input class="botones cancelar boton-largo" id="aprobar_comentario" style="margin-left:140px;" type="button" value="<?= obtener_leyenda_boton_gestion($_SESSION['id_estados_gestionar']); ?> y dejar comentario" />
        <input class="botones aceptar boton-largo" id="rechazar" style="margin:11px;" type="button" value="Rechazar y dejar comentario"/>
    </div>

    <?php
    if ($comentarios) {
        ?><p class="ayudas" style="font-size: 16px!important;">Historial .</p>
        <div id="historial_comentarios">
            <ul style="float:none!important;clear:both;background-color:white!important;">
                <?php
                foreach ($comentarios as $row) {
                    if ($row["usuario"] != null) {
                        $usuario = obtener_nombre_usuario_BASE_BANCO($row["usuario"]);
                    } else
                        $usuario = '-';
                    ?>
                    <li style="border-left :5px solid #A72626;margin-top: 25px;float:none!important;clear:both;">
                        <div style="margin-left: 15px;">
                            <span class="comentario_detalles"><b> <?= $usuario ?> </b> ( <?= $row['fecha'] ?> )<br/>
                                Estado de la solicitud:<?= obtener_estado_por_id($row['estado_solicitud']) ?>  </span> 
                            <p style="color: black!important;"><?= $row['comentario'] ?></p>
                        </div>
                    </li>
                    <?php
                };
            };
            ?>



        </ul>

    </div>

</div>
<script type="text/javascript">

    $(document).ready(function ()
    {
        $("#insertar_comentario_historial").click(
                function () {

                    var id_solicitud = <?= $solicitud ?>;
                    var comentario = $("#comentario_rechazo").val();
                    var usuario =<?= $id_usuario ?>;

                    var parametros = {
                        "id_solicitud": id_solicitud,
                        "accion": "insertar_comentario_historial",
                        "comentario": $("#comentario_rechazo").val(),
                        "usuario": usuario
                    };

                    $.ajax({
                        url: 'admin/ajax_peticiones.php',
                        data: parametros,
                        type: 'post',
                        success: function (response)
                        {
                            var id_solicitud = <?= $solicitud ?>;
                            $("#historial").load("historial.php?id_solicitud=" + id_solicitud + "");
                        }
                    });
                }
        );

        $("#rechazar").click(function () {
            var comentario_rechazo = $('#comentario_rechazo').val();
            var id_solicitud = <?= $solicitud ?>;

            if (comentario_rechazo) {
                var parametros = {
                    "solicitud_activa": id_solicitud,
                    "accion": "rechazar_solicitud",
                    "comentario": $('#comentario_rechazo').val(),
                    "usuario":<?= $id_usuario ?>
                };

                $.ajax({
                    url: 'admin/ajax_peticiones.php',
                    data: parametros,
                    type: 'post',
                    success: function (response)
                    {
                        $("#historial").load("historial.php?id_solicitud=" + (id_solicitud) + "");
                      window.location = "home.php";
                    }
                });

            } else {

                $('#comentario_rechazo').css("border", "2px red solid");
                $('#comentario_rechazo').attr("placeholder", "Debes dejar un comentario para rechazar la solicitud").placeholder();
                setTimeout(function () {
                    $('#comentario_rechazo').css("border", "1px #C9C9C9 solid");

                }, 2000);
            }
        });

        $("#aprobar_comentario").click(function () {
            var comentario_apruebo = $('#comentario_rechazo').val();

            if (comentario_apruebo) {
                $("#aprobar").css('display', 'none');
                $("#rechazar").css('display', 'none');
                $("#load").css('display', 'block');

                var id_solicitud = <?= $solicitud ?>;
                var comentario = $("#comentario_rechazo").val();
                var usuario =<?= $id_usuario ?>;

                var parametros = {
                    "solicitud_activa": $('#id_solicitud').val(),
                    "accion": "cambiar_estado",
                    "estado": $('#siguiente_estado').val(),
                    "id_solicitud": id_solicitud,
                    "comentario": $("#comentario_rechazo").val(),
                    "usuario": usuario,
                    "revisar_dejar_comentario":1
                };
                $.ajax({
                    url: 'admin/ajax_peticiones.php',
                    data: parametros,
                    type: 'post',
                    success: function (response)
                    {

                        $("#load").css('display', 'none');
                        var id_solicitud = <?= $solicitud ?>;
                        $("#historial").load("historial.php?id_solicitud=" + id_solicitud + "");
                       window.location = 'home.php';
                    }
                });
            } else {

                $('#comentario_rechazo').css("border", "2px red solid");
                $('#comentario_rechazo').attr("placeholder", "Debes dejar un comentario para Aprobar la solicitud").placeholder();
                setTimeout(function () {
                    $('#comentario_rechazo').css("border", "1px #C9C9C9 solid");

                }, 2000);
            }
            ;
        });
    });

</script>