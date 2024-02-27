function eliminar_solicitud_rechazada(id_elem)
{

	if(confirm('Procede a eliminar su rendici\u00f3n rechazada ?\nEsta operaci\u00f3n NO podr\u00e1 deshacerse y conlleva a crear una nueva rendici\u00f3n.'))
	{
					
		var partes_id_boton_eliminarsolicitud = id_elem.split("_");
		var id_sol = partes_id_boton_eliminarsolicitud[1]
		
		var parametros =
		{
        	"id_solicitud": id_sol,
           	"accion": "eliminar_rendicion_mensual"
        };
					 
		$.ajax({
                     
			url: 'admin/ajax_peticiones.php',
            data: parametros,
            type: 'post',
            success: function(response)
			{ 
			
				$("#tabla_solicitudes_detalles").empty();
				$("#fila_" + id_sol).remove();
				alert('Rendici\u00f3n eliminada.');
			
			}

              });
	
	}
				
}