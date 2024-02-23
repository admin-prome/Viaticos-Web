$(".ver_mapa").click(function() {

    coord_1 = '';
    coord_2 = '';

    var id_ver_mapa = this.id;
    var donde_realizo_carga = (this.id).substr(0, 1);
    var origen = (this.id).substr(2, 1);

    var partes_id_ver_mapa = id_ver_mapa.split('@');
    var destino = partes_id_ver_mapa[2];
    var id_visita = partes_id_ver_mapa[3];
    var es_admin=partes_id_ver_mapa[4];
    var_ruta='admin/';
    if(es_admin == 'admin'){
        var_ruta='';
    }else{
        var_ruta='admin/'; 
    }

    coord_2 = partes_id_ver_mapa[2];


    if (ORIGEN_SUCURSAL == origen)
    {
        coord_1 = coords_sucursal;
    }
    else
    {
        var parametros =
                {
                    "solicitud": solicitud_activa_js,
                    "id_viatico": id_visita,
                    "accion": "obtener_coord_viatico_anterior"
                };

        $.ajax({
            data: parametros,
            url: var_ruta+'ajax_peticiones.php',
            type: 'post',
            success: function(response) {
                
    
                coord_1 = response;
          
                
               
            }

        });

    }

    setTimeout(function() {

        $('#mapa_ventana_modal').html(' ');
        $("#mapa_ventana_modal").load("../get_html_mapa.php?coord1=" + coord_1 + "&coord2=" + coord_2);
       


    }, 2000);

});