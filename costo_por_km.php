<?php
require_once 'inc/checkLogin.php';
require_once 'inc/const.php';

require_once 'inc/header.php';

// require_once 'inc/menu_administrador.php';

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

?>

<h1 class="ayudas" style="font-size: 16px;">Costos kilometros por zona:</h1>
<div style="background-color: #eeeced;">
  <table id="tabla_km" class="display" cellspacing="0" style="width: 0px auto;">
        
        <thead>
            
            <tr style="text-align:left;">

                <th>Descripcion</th>
                <th>Costo km auto</th>
                <th>Costo km moto</th>
                <th>&nbsp;</th>

            </tr>
        
        </thead>

        <tbody>
            
			<?php

            $zonas = obtener_zonas_BASE_BANCO();
			
			foreach($zonas as $row)
			{
                echo("<tr id='fila_" . $row["zonaid"] . "'>");
					echo("<td>". $row["zona"]."</td>");
					echo("<td>$ <input id='costo_auto_".$row['zonaid']."' size='5' type='text' value='".$row['costo_km_auto']."' /></td>");
					echo("<td>$ <input id='costo_moto_".$row['zonaid']."' size='5' type='text' value='".$row['costo_km_moto']."' /></td>");
					echo("<td><input id='". $row['zonaid']."' class='botones ver_solicitud guardar_costo' type='button' value='Guardar' /></td>");
                echo("</tr>");
            }
            ?>

        </tbody>
    
    </table>
</div>
<script type="text/javascript">

    $(document).ready(function() {
        
		$('#tabla_km').dataTable({
            "paging": false,
            "ordering": true,
            "order": [[1, "desc"]],
            "info": false,
            "search": false,
			"bFilter": false
        });
		
		$(".guardar_costo").click(function(){
           
			var id_zona = this.id;
            var costo_auto = $("#costo_auto_" + id_zona).val();
            var costo_moto = $("#costo_moto_" + id_zona).val();

            if(validar_numero(costo_auto) && validar_numero(costo_moto))
			{
                var parametros = { "id_zona": id_zona, "accion": "update_costo_km", "costo_auto": costo_auto, "costo_moto": costo_moto };
				
                 $.ajax({
                        
						url: 'ajax_peticiones.php',
                        data: parametros,
                        type: 'post',
                        success: function(response)
                        {
                            alert('Costos de zona actualizados.');
                        }
					
					});
					
			}
			else
			{
				alert('Los valores ingresados para esta zona NO son num\u00e9ricos o quiz\u00e1s est\u00e9n en blanco. Verifique');
				$("#costo_auto_" + id_zona).focus();
			}
             
			});
    });

</script>
<?php include 'inc/footer.php'; ?>