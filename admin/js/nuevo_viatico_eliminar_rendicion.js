$("#eliminar_rendicion").click(function() {
				
	if(confirm('Procede a eliminar su rendici\u00f3n mensual ?\nEsta operaci\u00f3n NO podr\u00e1 desahacerse.'))
	{
					
		var parametros =
		{
        	"id_solicitud": solicitud_activa_js,
           	"accion": "eliminar_rendicion_mensual"
        };
					 
		$.ajax({
                     
			url: 'admin/ajax_peticiones.php',
            data: parametros,
            type: 'post',
            success: function(response)
            {
                            
				alert('Rendici\u00f3n eliminada. Se crear\u00e1 una nueva.');
				window.location = 'nuevo_viatico.php?solicitud=nueva';

            }

               });
	}
				
});