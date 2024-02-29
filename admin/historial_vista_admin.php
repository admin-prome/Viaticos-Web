<?php
require_once 'inc/checkLogin.php';
require_once 'inc/const.php';
require_once 'inc/funciones/historial.php';
require_once 'inc/funciones/all_functions_sin_admin.php';

$solicitud = $_GET['id_solicitud'];
$id_usuario = $_SESSION['id_usuario'];
$comentarios = obtener_comentarios_solicitud($_GET['id_solicitud']);
?>
<div class="historial">

    <div style="width: 1220px;">
        <p class="ayudas"></p>
        <input  id="comentario_rechazo" class="textbox" style="width: 50%; height: 75px; border: 2px solid lightgray;margin-left:2%;font-size: 11px;" type="text" Placeholder=""/>
        <div style="display:none;margin: 11px 55px;" id="load"><div class="loading"></div><span style="margin-left: -4px;">Guardando</span></div>
        <br/>
        <input style="margin-top: 11px;margin-left: 25px;"  class="botones boton-largo" id="insertar_comentario_historial" type="button" value="Dejar comentario"/>
    </div>
    <?php
    if ($comentarios) {
        ?><p class="ayudas" style="font-size: 16px;">Historial .</p>
        <div id="historial_comentarios">
            <ul>
                <?php
            if ($comentarios) {
                foreach ($comentarios as $row) {
                    
                    
                    if($row["usuario"]){
                        
                        $usuario = obtener_nombre_usuario_BASE_BANCO($row["usuario"]);
                    }
                    else{
                        
                        $usuario = '-';
                    }
                    ?>
                    <li style="border-left :5px solid #A72626;margin-top: 25px;">
                        <div style="margin-left: 15px;">
                            <span class="comentario_detalles"><b> <?= $usuario ?> </b> ( <?= $row['fecha'] ?> )<br/>
                                Estado de la solicitud:<?= obtener_estado_por_id($row['estado_solicitud']) ?>  </span> 
                            <p><?= $row['comentario'] ?></p>
                        </div>
                    </li>
                    <?php
                };
    };};
            ?>



        </ul>

    </div>
</div>
<script type="text/javascript">

    $(document).ready(function()
    {
        $("#insertar_comentario_historial").click(
                function() {
                    var comentario_input = $("#comentario_rechazo").val()
                    if (comentario_input) {
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
                            success: function(response)
                            {
                                var id_solicitud = <?= $solicitud ?>;
                                $("#historial").load("historial_vista.php?id_solicitud=" + (id_solicitud) + "");
                            }
                        });
                    } else {
                        $('#comentario_rechazo').css("border", "2px red solid");
                        $('#comentario_rechazo').attr("placeholder", "Debe escribir un comentario").placeholder();
                        setTimeout(function() {
                            $('#comentario_rechazo').css("border", "1px #C9C9C9 solid");

                        }, 2000);
                    }
                    ;
                });


    });

</script>