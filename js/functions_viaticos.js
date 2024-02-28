


/*
function extraerCoordenadasURLUnicoPunto(url)
{	
	var partes_url = url.split('map=');
	partes_url = partes_url[1];
	
	var coords_url = partes_url.split(',');
	
	return coords_url[0] + ',' + coords_url[1];
	
}
*/

function extraerCoordenadasURLUnicoPunto(url)
{	
    
   // console.log("en funcion extraer coordenadas");

	if ('map'.indexOf(url) > -1)  {
    	//console.log("existe map");
	
		var partes_url = url.split('map=');
		partes_url = partes_url[1];

	

		var coords_url = partes_url.split(',');

		return coords_url[0] + ',' + coords_url[1];
	}
	else{

	return url;

	}

}



function actualizar_importe_total_reporte_nuevo_viatico(restar)
{

	var nuevo_importe_total = (importe_total_solicitud - restar).toFixed(2);
		
	var nro_formateado_moneda = formatearMoneda(nuevo_importe_total,2,['.', ".",',']);
	
	$('#importe_total_solicitud').html(nro_formateado_moneda);
	importe_total_solicitud = nuevo_importe_total;
}

function eliminar_attach_viatico(id)
{
	var id_elem = id;
	var partes_id_elem = id_elem.split("_");
    var id_viatico = partes_id_elem[2];

    if(confirm('Procede a eliminar el archivo adjunto del vi\u00e1tico ' + id_viatico + ' ?'))
    {

    	var parametros =
        {
        	"id_viatico": id_viatico,
            "accion": "eliminar_attach_viatico"
        };

        $.ajax({
        			url: 'admin/ajax_peticiones.php',
                    data: parametros,
                    type: 'post',
                    success: function(response)
                    {

                        $('#attach_viatico_' + id_viatico).html('-');
                        $('#ver_viatico_' + id_viatico).html('-');

                    }

               });
    }

}

function eliminar_viatico(idElem,coordsSucUsuario,costoKMAuto,costoKMMoto)
{

  if(confirm('Procede a eliminar este vi\u00e1tico ?\nEsta operaci\u00f3n NO puede deshacerse.'))
  {

	var partes_id_viatico = idElem.split('_');
	var id_viatico = partes_id_viatico[2];

	var parametros =
    {
        "id_viatico": id_viatico,
        "id_solicitud": solicitud_activa_js,
        "accion": "eliminar_viatico"
    };

    $.ajax({
                        
			data: parametros,
            url: 'admin/ajax_peticiones.php',
            type: 'post',
            beforeSend: function() {},
            success: function(response)
			{

    var resp_server = response.trim();
	var importe_viatico_a_borrar = Number($('#importe_viatico_oculto_' + id_viatico).html());

	var tr = $("#fila_" + id_viatico);
    tr.css("background-color", "#FF3700");
    tr.fadeOut(800, function() { tr.remove(); });

    if(resp_server != '0')
	{
		$('#eliminar_viatico_' + resp_server).css('display','block');
		
		recalcular_costos_kms_viatico_anterior(resp_server,coordsSucUsuario,costoKMAuto,costoKMMoto);
	}
				  
	actualizar_importe_total_reporte_nuevo_viatico(importe_viatico_a_borrar);
	
             }
            
			
			});

  }
}

function recalcular_costos_kms_viatico_anterior(id_viatico_anterior,coords_suc_usuario,costoKMAuto,costoKMMoto)
{
	
	var params = {};
	
	params.id_viatico = id_viatico_anterior;
	params.accion = 'obtener_destino_concepto_viatico';
	
	$.ajax({
			
		type: 'post',
		data: params,
		url: 'admin/ajax_peticiones.php',
		success: function(resp){ 
			
			var array_destino_concepto = (resp.trim()).split(";");
			
			var destino  = array_destino_concepto[0];
			var concepto = array_destino_concepto[1];
			
			if(concepto == 5) //auto
			{
				var costo_km = costoKMAuto;
			}
			else //moto
			{
				var costo_km = costoKMMoto;
			}
			
			calcular_distancia_viaje(destino,coords_suc_usuario,costo_km,'vuelta');
			
			var interv = setInterval(function(){ 
			
				if(!isNaN(datos_viaje_vuelta[0]) && !isNaN(datos_viaje_vuelta[1]))
				{
				
					clearInterval(interv);
					var kms_actualizar = datos_viaje_vuelta[0];
					var costo_actualizar = datos_viaje_vuelta[1];
					
					var params_update = {};
					
					params_update.accion = 'actualizar_km_costo_vuelta_viatico';
					params_update.id_viatico = id_viatico_anterior;
					params_update.kms 	 = datos_viaje_vuelta[0].toFixed(2);
					params_update.costo  = datos_viaje_vuelta[1];
					
					
					$.ajax({
						
						type: 'post',
						data: params_update,
						url: 'admin/ajax_peticiones.php',
						success: function(resp){
								
						var array_km_importe = (resp.trim()).split("@");
						
						var km = array_km_importe[0];
						var importe = formatearMoneda(array_km_importe[1],2,['.', ".",',']);
							
					$('#importe_viatico_visible_' + id_viatico_anterior).html(importe);
					$('#km_viatico_' + id_viatico_anterior).html(km);
		
								
												}
						
						
							});
				
			//alert('kms: '+datos_viaje_vuelta[0]+' costo: '+datos_viaje_vuelta[1]);
				
				
				}
			
				},1500);

								
								}
		  						
			});
	
}

function aux_calcular_distancias_viajes_celu(coords_suc_usuario,id_primer_viatico,id_visita,origen,destino,destino_reg_anterior,costo_km,demora,cant_visitas)
{
	/*
	
	// ¿ Llegan los datos ?
	
	alert(coords_suc_usuario + 'x' + id_primer_viatico + 'xx' +id_visita+ 'xxx' +origen +
	'xxxx' + destino + 'xxxxx' + destino_reg_anterior + 'xxxxxx' + costo_km + 'xxxxxxx' + demora);
	
	*/
	
	setTimeout(function() {
	
		if(origen == 1) //sucursal
		{
			calcular_distancia_viaje_celu(coords_suc_usuario,destino,costo_km,'ida');
			calcular_distancia_viaje_celu(destino,coords_suc_usuario,costo_km,'vuelta');
		}
		else // visita anterior
		{	
			calcular_distancia_viaje_celu(destino_reg_anterior,destino,costo_km,'ida');
			calcular_distancia_viaje_celu(destino,coords_suc_usuario,costo_km,'vuelta');
		}
		
		if((demora + 1) == cant_visitas)
		{
			todos_calculos_distancias_terminados = true;
		}
	
	},1000*(demora + 1));

}


function proceso_actualizar_costos_kms_datos_celu(coords_suc_usuario,id_primer_viatico,ids_visitas,origenes,destinos,destinos_regs_anteriores,costos_kms,solicitud_activa)
{
	
	/*
	
	//	¿¿ Llegan los datos ?? 
	
		alert(coords_suc_usuario);
		alert(id_primer_viatico);
		alert(ids_visitas);
		alert(origenes);
		alert(destinos);
		alert(destinos_regs_anteriores);
		alert(costos_kms);
	
	*/

	todos_calculos_distancias_terminados = false;
	
	for(var i=0; i < ids_visitas.length; i++)
	{
	
	aux_calcular_distancias_viajes_celu(coords_suc_usuario,id_primer_viatico,
	ids_visitas[i],origenes[i],destinos[i],destinos_regs_anteriores[i],costos_kms[i],i,
	ids_visitas.length);
	
	}

	var id_stop_interval = setInterval(function(){ 
	
		if(todos_calculos_distancias_terminados)
		{
	
			params =
			{	
	
				"costos_y_kms_idas": 	datos_viaje_ida,
				"costos_y_kms_vueltas": datos_viaje_vuelta,
				"ids_visitas": 			ids_visitas,
				"origenes": 			origenes,
				"solicitud_activa": 	solicitud_activa
				
				// "id_primer_viat": 			id_primer_viatico,
				// "destinos_regs_anteriores": 	destinos_regs_anteriores,
				// "destinos": 					destinos

			};
	
			$.ajax({
			
				type: 'post',
				data: params,
				url: 'celular/actualizar_registros_sin_costo_ni_kms.php',
				success: function(resp){ 
                                    //alert('test');
                                    window.location = 'nuevo_viatico.php'; }
		  			});
				
			clearInterval(id_stop_interval);	
		
		}
	
	}, 6500);

}

indiceIdas 	  = 0;
indiceVueltas = 0;

function calcular_distancia_viaje_celu(coord1,coord2,costo_x_km,ida_vuelta)
{
    parameters =
            {
                "waypoint0": coord1,
                "waypoint1": coord2,
                "mode": 'fastest;car',
                "app_id": 'qKAtQHz1I3GtyBVt5JaB',
                "app_code": '6q36NzKaEnOvk8PSEJEi-Q',
                "departure": "now"
            };

    
	$.ajax({
       
	    type: 'get',
        data: parameters,
        url: 'https://route.cit.api.here.com/routing/7.2/calculateroute.json',
        success: function(response) {

            var distancia = Number(response.response.route[0].summary.distance);
            var km = Number((Number(distancia) / 1000).toFixed(2));
            var valor_costo = Number((km * Number(costo_x_km)).toFixed(2));

            if(ida_vuelta == 'ida')
            {
                datos_viaje_ida[indiceIdas] = km;
                indiceIdas++;
				
				datos_viaje_ida[indiceIdas] = valor_costo;
				indiceIdas++;

			}
            else // vuelta
            {
                datos_viaje_vuelta[indiceVueltas] = km;
				indiceVueltas++;
                
				datos_viaje_vuelta[indiceVueltas] = valor_costo;
				indiceVueltas++;
            }
	
        }

    	});

}


// Esta funcion (calcular_distancia_viaje) NO se tocaaaa:
// function calcular_distancia_viaje(coord1, coord2, costo_x_km, ida_vuelta)
// {
//     parameters =
//             {
//                 "waypoint0": coord1,
//                 "waypoint1": coord2,
//                 "mode": 'fastest;car',
//                 "app_id": 'qKAtQHz1I3GtyBVt5JaB',
//                 "app_code": '6q36NzKaEnOvk8PSEJEi-Q',
//                 "departure": "now"
//             };

//     $.ajax({
       
// 	    type: 'get',
//         data: parameters,
//         url: 'https://route.cit.api.here.com/routing/7.2/calculateroute.json',
//         success: function(response) {

//             var distancia = Number(response.response.route[0].summary.distance);
//            // alert(distancia);
//             var km = Number((Number(distancia) / 1000).toFixed(2));
//             var valor_costo = Number((km * Number(costo_x_km)).toFixed(2));

//             if(ida_vuelta == 'ida')
//             {
//                 datos_viaje_ida[0] = km;
//                 datos_viaje_ida[1] = valor_costo;
//             }
//             else // vuelta
//             {
//                 datos_viaje_vuelta[0] = km;
//                 datos_viaje_vuelta[1] = valor_costo;
//             }

//         }

//     });

// }

// Fin NO se toca.

// FUNCION DE GOOGLE
function calcular_distancia_viaje(coord1, coord2, costo_x_km, ida_vuelta) {
    // Utilizando la API de Google Maps
    var apiKey = 'AIzaSyBuY3ViUvLILdCPRfoZDAz8qdLBZOuOCZIY'; // Reemplaza 'TU_API_KEY' con tu clave de API de Google Maps

    var url = 'https://maps.googleapis.com/maps/api/directions/json';
    var origin = coord1;
    var destination = coord2;
    var mode = 'driving';

    var parameters = {
        'origin': origin,
        'destination': destination,
        'mode': mode,
        'key': apiKey
    };

    $.ajax({
        type: 'GET',
        data: parameters,
        url: url,
        success: function(response) {
            var distancia = response.routes[0].legs[0].distance.value;
            var km = Number((Number(distancia) / 1000).toFixed(2));
            var valor_costo = Number((km * Number(costo_x_km)).toFixed(2));

            if (ida_vuelta === 'ida') {
                datos_viaje_ida[0] = km;
                datos_viaje_ida[1] = valor_costo;
            } else {
                datos_viaje_vuelta[0] = km;
                datos_viaje_vuelta[1] = valor_costo;
            }
        },
        error: function(error) {
            console.error('Error al calcular la ruta:', error);
            // Manejar el error según tus necesidades
        }
    });
}

function buscar_distancia_costo()
{
	if($("#form_1").css('display') == 'block')
	{
    	var form_activo = 1;
 	}
    else
    {
    	var form_activo = 2;
    }
	
	if($("#form" + form_activo + "_destino_viatico").val() == '')
	{
		$('#form' + form_activo + '_km_viatico').val('');
		$('#form' + form_activo + '_importe_viatico').val('');
		$(".load").css('display', 'none');

		return false;
	}
	
	var origen = $("#form" + form_activo + "_origen_viatico_select option:selected").val();
	var destino = extraerCoordenadasMapa($("#form" + form_activo + "_destino_viatico").val());

    var value_concepto_viatico = $("#concepto_viatico_select option:selected").val();

    if(value_concepto_viatico == CONCEPTO_TAXI_REMIS || 
	value_concepto_viatico == CONCEPTO_TRANS_PUB)
    {
    	$('#form' + form_activo + '_km_viatico').val('');
		$('#form' + form_activo + '_importe_viatico').val('');
       	
		return false;
    }

    if(CONCEPTO_AUTO == value_concepto_viatico || CONCEPTO_MOTO == value_concepto_viatico)
    {

    	if(value_concepto_viatico == CONCEPTO_AUTO)
        {
        	var costo_km = costo_km_auto;
        }
        else
        {
        	var costo_km = costo_km_moto;
        }
 
		if(origen == ORIGEN_SUCURSAL)
		{
			calcular_distancia_viaje(coords_sucursal,destino,costo_km,'ida');
		}
		else // Visita anterior
		{
			calcular_distancia_viaje(coords_visita_anterior,destino,costo_km,'ida');
		}
		
		var segundoViaje = setInterval(function() 	{ 
		
			// Esto implica que ya calculo la ida...
			if(!isNaN(datos_viaje_ida[0]) && !isNaN(datos_viaje_ida[1]))
			{
				calcular_distancia_viaje(destino,coords_sucursal,costo_km,'vuelta');
				clearInterval(segundoViaje);
				
				var calculoFinal = setInterval(function(){ 
			
			//Esto implica que ya calculo la vuelta...
			if(!isNaN(datos_viaje_vuelta[0]) && !isNaN(datos_viaje_vuelta[1]))
			{

var dist_km_total = Number((datos_viaje_ida[0] + datos_viaje_vuelta[0]).toFixed(2));
var imp_total = Number((datos_viaje_ida[1] + datos_viaje_vuelta[1]).toFixed(2));

$("#form" + form_activo + "_km_viatico").val(dist_km_total);
$("#form" + form_activo + "_importe_viatico").val(imp_total);

kms_calculados_sistema 	= dist_km_total;
importe_calculado_sistema = imp_total;
								
$("#load_here").css('display', 'none');
clearInterval(calculoFinal);
$("#loading_ajax_form_" + form_activo).css('display','none');

			}
			
															},500);

			
			}
	
													},500);
	}
}

function resetearViaticoYaCalculado(concepto, nroForm)
{


                if (concepto == CONCEPTO_TAXI_REMIS || concepto == CONCEPTO_TRANS_PUB)
                {

                    $('#form' + nroForm + '_km_viatico').val('');
                    $('#form' + nroForm + '_importe_viatico').val('');

                    $('#form' + nroForm + '_km_viatico').removeAttr("readonly");
                    $('#form' + nroForm + '_importe_viatico').removeAttr("readonly");

                }
                else
                {

                    if (concepto == CONCEPTO_AUTO || concepto == CONCEPTO_MOTO)
                    {

                        // kms_calculados_sistema e importe_calculado_sistema:
                        // Variables suuuper globales.

                        $('#form' + nroForm + '_importe_viatico').val(importe_calculado_sistema);
                        $('#form' + nroForm + '_km_viatico').val(kms_calculados_sistema);

                        $('#form' + nroForm + '_km_viatico').attr('readonly', 'readonly');
                        $('#form' + nroForm + '_importe_viatico').attr('readonly', 'readonly');

                    }
                }

}
