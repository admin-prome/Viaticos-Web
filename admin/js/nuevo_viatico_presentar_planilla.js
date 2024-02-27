$("#presentar_planilla").click(function() {
                var parametros = {
                    "solicitud_activa": $('#solicitud_activa').val(),
                    "accion": "cambiar_id_solicitud"
                };

                $.ajax({
                    url: 'admin/ajax_peticiones.php',
                    data: parametros,
                    type: 'post',
                    success: function(response)
                    {
                        var id_solicitud = $('#solicitud_activa').val();
                        var comentario = "Solicitud presentada para revision";
                        var usuario = id_usuario_js;

                        insertar_comentario_historial(id_solicitud, comentario, usuario);
                        //window.location = "home.php";

                    }
                });
});