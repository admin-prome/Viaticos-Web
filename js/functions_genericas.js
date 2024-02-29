function wait(segs)
{
    var objetivo = (new Date()).getTime() + 1000 * Math.abs(segs);
    while ((new Date()).getTime() < objetivo);
}

function formatearMoneda(value, decimals, separators)
{
    
	decimals = decimals >= 0 ? parseInt(decimals, 0) : 2;
    separators = separators || ['.', "'", ','];
    var number = (parseFloat(value) || 0).toFixed(decimals);
    if (number.length <= (4 + decimals))
        return number.replace('.', separators[separators.length - 1]);
    var parts = number.split(/[-.]/);
    value = parts[parts.length > 1 ? parts.length - 2 : 0];
    var result = value.substr(value.length - 3, 3) + (parts.length > 1 ?
        separators[separators.length - 1] + parts[parts.length - 1] : '');
    var start = value.length - 6;
    var idx = 0;
    while (start > -3) {
        result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start))
            + separators[idx] + result;
        idx = (++idx) % 2;
        start -= 3;
    }
    
	return (parts.length == 3 ? '-' : '') + result;

}

function destruirElementoPorID(id_elemento)
{
	$("#" + id_elemento).remove();
}

function checkUncheckSelectBoxs(clase,check_uncheck_boolean)
{
    $('.' + clase).each(function() { this.checked = check_uncheck_boolean; });
}

function hayChecksTildados(clase)
{
	
	var retornar = false;
	
	$('.' + clase).each(function(){
		
		
		if($(this).is(':checked'))
		{ 
			retornar = true;
		}
	
	});
	
	return retornar;
}

function valuesChecksTildados(clase)
{
	var values = new Array();
	
	$('.' + clase).each(function(){
		
		if($(this).is(':checked'))
		{ 
			values[values.length] = $(this).val();
		}
	
	});
	
	return values;
	
}

// function extraerCoordenadasMapa(url_mapa)
// {

//     var partes_destino = url_mapa.split("map=");
//     var coords = partes_destino[1].split(",");

//     return (coords[0] + ',' + coords[1]);

// }
//FUNCION DE GOOGLE MAPS
function extraerCoordenadasMapa(url_mapa) {
    // Buscar el patrón de coordenadas en la URL
    var match = url_mapa.match(/@(-?\d+\.\d+),(-?\d+\.\d+),/);

    if (match) {
        // Las coordenadas se encuentran en los grupos de captura
        var latitud = match[1];
        var longitud = match[2];
        return latitud + ',' + longitud;
    } else {
        // Manejar el caso en el que la URL no tiene el formato esperado
        return null;
    }
}
function validar_url(s) {
    var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
    return regexp.test(s);
}
function estaEnArray(valor, miarray)
{
    for (i = 0; i < miarray.length; i++)
    {
        if (miarray[i] == valor)
            return true;
    }

    return false;
}

function visibilidadFormsViaticos(mostrarForm, edicion)
{

    if (edicion)
        var sufijo_formu = '_edit';
    else
        var sufijo_formu = '';

    switch (mostrarForm)
    {
        case 1:

            $("#form_1" + sufijo_formu).css("display", "block");
            $("#form_2" + sufijo_formu).css("display", "none");
            $("#form_3" + sufijo_formu).css("display", "none");

            break;

        case 2:

            $("#form_1" + sufijo_formu).css("display", "none");
            $("#form_2" + sufijo_formu).css("display", "block");
            $("#form_3" + sufijo_formu).css("display", "none");

            break;

        case 3:

            $("#form_1" + sufijo_formu).css("display", "none");
            $("#form_2" + sufijo_formu).css("display", "none");
            $("#form_3" + sufijo_formu).css("display", "block");

            break;

        case 'ninguno':

            $("#form_1" + sufijo_formu).css("display", "none");
            $("#form_2" + sufijo_formu).css("display", "none");
            $("#form_3" + sufijo_formu).css("display", "none");

            break;

    }
}

function opacidadLogo()
{
    $("#logo_banco_provincia").fadeTo(2500, 0.3);
    $("#logo_banco_provincia").fadeTo(2500, 1);
}

function validarFormLogin()
{
    var user = $('#usuario').val();
    var pass = $('#password').val();

    if (user == '')
    {



        $("#dialog_modal_user_wrong").dialog({
            show: {effect: "blind", duration: 400},
            hide: {effect: "explode", duration: 1000},
            resizable: false

        });

        return false;
    }

    if (pass == '')
    {

        $("#dialog_modal_pass_wrong").dialog({
            show: {effect: "blind", duration: 400},
            hide: {effect: "explode", duration: 1000},
            resizable: false

        });


        return false;
    }

    return true;

}


function calcular_recorrido(direccion_1, direccion_2, insert) {


    // Encode coordinates with format information

    
    const lat1 = parseFloat(direccion_1.split(',')[0]);
    const lng1 = parseFloat(direccion_1.split(',')[1]);
    const lat2 = parseFloat(direccion_2.split(',')[0]);
    const lng2 = parseFloat(direccion_2.split(',')[1]);
    
    const apiKey = 'AIzaSyBuY3ViUvLILdCPRfoZDAz8qdLBZOuOCZI';
    const mapElement = document.getElementById(insert);
    const map = new google.maps.Map(mapElement, {
      zoom: 10,
      center: { lat: lat1, lng: lng1 }, // Default center
    });
    

    function createMarker(position, label, color) {
        const marker = new google.maps.Marker({
          position,
          map,
          label,
          icon: {
            url: `https://maps.gstatic.com/mapfiles/api-3/icons/driving_man_${color}.png`,
            scaledSize: new google.maps.Size(40, 40),
          },
        });
        return marker;
      }

    // Create request with formatted coordinates
    const directionsRequest = {
        origin: new google.maps.LatLng(lat1, lng1),
        destination: new google.maps.LatLng(lat2, lng2),
        travelMode: google.maps.TravelMode.DRIVING,
      };

    
     // Get directions and display them on the map
    const directionsService = new google.maps.DirectionsService();
    directionsService.route(directionsRequest, (response, status) => {
    if (status === google.maps.DirectionsStatus.OK) {
        const directionsRenderer = new google.maps.DirectionsRenderer({
        map,
        directions: response,
        });

        // Optional: Adjust map center to fit the route
        map.fitBounds(response.routes[0].bounds);
    } else {
        console.error("Error:", status);
    }
    });
}

    // var platform = new H.service.Platform({
    //     'app_id': 'qKAtQHz1I3GtyBVt5JaB',
    //     'app_code': '6q36NzKaEnOvk8PSEJEi-Q',
    //     'useHTTPS': 'true'     
    // });

    // // Get the default map types from the Platform object:
    // var defaultLayers = platform.createDefaultLayers();

    // // Instantiate the map:
    // var map = new H.Map(
    //         document.getElementById(insert),
    //         defaultLayers.normal.map,
    //         {
    //             zoom: 10,
    //             center: {lng: 13.4, lat: 52.51}
    //         });

    // // Create the default UI:
    // var ui = H.ui.UI.createDefault(map, defaultLayers, 'es-ES');
    // var mapSettings = ui.getControl('mapsettings');
    // var zoom = ui.getControl('zoom');
    // var scalebar = ui.getControl('scalebar');



    // mapSettings.setAlignment('bottom-right');
    // zoom.setAlignment('bottom-right');
    // scalebar.setAlignment('bottom-right');
    // var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));
    // // Create the parameters for the routing request:
    // var routingParameters = {
    //     // The routing mode:
    //     'mode': 'fastest;car',
    //     // The start point of the route:
    //     'waypoint0': 'geo!' + direccion_1 + '',
    //     // The end point of the route:
    //     'waypoint1': 'geo!' + direccion_2 + '',
    //     // To retrieve the shape of the route we choose the route
    //     // representation mode 'display'
    //     'representation': 'display',
    // };
    // // Define a callback function to process the routing response:
    // var onResult = function(result) {

    //     var route,
    //             routeShape,
    //             startPoint,
    //             endPoint,
    //             strip;
    //     if (result.response.route) {
    //         // Pick the first route from the response:
    //         route = result.response.route[0];
    //         // Pick the route's shape:
    //         routeShape = route.shape;

    //         // Create a strip to use as a point source for the route line
    //         strip = new H.geo.Strip();
    //         // Push all the points in the shape into the strip:
    //         routeShape.forEach(function(point) {
    //             var parts = point.split(',');
    //             strip.pushLatLngAlt(parts[0], parts[1]);
    //         });

    //         // Retrieve the mapped positions of the requested waypoints:
    //         startPoint = route.waypoint[0].mappedPosition;
    //         endPoint = route.waypoint[1].mappedPosition;

    //         // Create a polyline to display the route:
    //          var routeLine = new H.map.Polyline(strip, {
    //         style: { lineWidth: 10 },
    //         arrows: { fillColor: 'white', frequency: 2, width: 0.8, length: 0.7 }
    //       });

    //         // Create a marker for the start point:
    //         var startMarker = new H.map.Marker({
    //             lat: startPoint.latitude,
    //             lng: startPoint.longitude,
    //             nme:'x'
    //         });

    //         // Create a marker for the end point:
    //         var endMarker = new H.map.Marker({
    //             lat: endPoint.latitude,
    //             lng: endPoint.longitude
    //         });

    //         // Add the route polyline and the two markers to the map:
    //         map.addObjects([routeLine, startMarker, endMarker]);

    //         // Set the map's viewport to make the whole route visible:
    //         map.setViewBounds(routeLine.getBounds());
    //     }
    // };

    // // Get an instance of the routing service:
    // var router = platform.getRoutingService();

    // // Call calculateRoute() with the routing parameters,
    // // the callback and an error callback function (called if a
    // // communication error occurs):
    // router.calculateRoute(routingParameters, onResult,
    //         function(error) {
    //             alert(error.message);
    //         });


//}

function estaEnArray(valor, miarray)
{
    for (i = 0; i < miarray.length; i++)
    {
        if (miarray[i] == valor)
            return true;
    }

    return false;
}

function opacidadLogo()
{
    $("#logo_banco_provincia").fadeTo(2500, 0.3);
    $("#logo_banco_provincia").fadeTo(2500, 1);
}

function validarFormLogin()
{
    var user = $('#usuario').val();
    var pass = $('#password').val();

    if (user == '')
    {



        $("#dialog_modal_user_wrong").dialog({
            show: {effect: "blind", duration: 400},
            hide: {effect: "explode", duration: 1000},
            resizable: false

        });

        return false;
    }

    if (pass == '')
    {

        $("#dialog_modal_pass_wrong").dialog({
            show: {effect: "blind", duration: 400},
            hide: {effect: "explode", duration: 1000},
            resizable: false

        });


        return false;
    }

    return true;

}

/*
 function calcular_recorrido(direccion_1, direccion_2, insert) {
 var distancia;
 var platform = new H.service.Platform({
 'app_id': 'qKAtQHz1I3GtyBVt5JaB',
 'app_code': '6q36NzKaEnOvk8PSEJEi-Q'
 });
 
 // Get the default map types from the Platform object:
 var defaultLayers = platform.createDefaultLayers();
 
 // Instantiate the map:
 var map = new H.Map(
 document.getElementById(insert),
 defaultLayers.normal.map,
 {
 zoom: 10,
 center: {lng: 13.4, lat: 52.51}
 });
 
 // Create the default UI:
 var ui = H.ui.UI.createDefault(map, defaultLayers, 'es-ES');
 var mapSettings = ui.getControl('mapsettings');
 var zoom = ui.getControl('zoom');
 var scalebar = ui.getControl('scalebar');
 
 
 
 mapSettings.setAlignment('bottom-right');
 zoom.setAlignment('bottom-right');
 scalebar.setAlignment('bottom-right');
 var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));
 
 //                    console.log(map);
 // Create the parameters for the routing request:
 var routingParameters = {
 // The routing mode:
 'mode': 'fastest;car',
 // The start point of the route:
 'waypoint0': 'geo!' + direccion_1 + '',
 // The end point of the route:
 'waypoint1': 'geo!' + direccion_2 + '',
 // To retrieve the shape of the route we choose the route
 // representation mode 'display'
 'representation': 'display'
 };
 // console.log(H.math.Rect);
 // Define a callback function to process the routing response:
 var onResult = function(result) {
 var route,
 routeShape,
 startPoint,
 endPoint,
 strip;
 if (result.response.route) {
 // Pick the first route from the response:
 route = result.response.route[0];
 // Pick the route's shape:
 routeShape = route.shape;
 
 // Create a strip to use as a point source for the route line
 strip = new H.geo.Strip();
 // Push all the points in the shape into the strip:
 routeShape.forEach(function(point) {
 var parts = point.split(',');
 strip.pushLatLngAlt(parts[0], parts[1]);
 });
 
 // Retrieve the mapped positions of the requested waypoints:
 startPoint = route.waypoint[0].mappedPosition;
 endPoint = route.waypoint[1].mappedPosition;
 
 // Create a polyline to display the route:
 var routeLine = new H.map.Polyline(strip, {
 style: {strokeColor: 'blue', lineWidth: 10}
 });
 
 // Create a marker for the start point:
 var startMarker = new H.map.Marker({
 lat: startPoint.latitude,
 lng: startPoint.longitude
 });
 
 // Create a marker for the end point:
 var endMarker = new H.map.Marker({
 lat: endPoint.latitude,
 lng: endPoint.longitude
 });
 
 // Add the route polyline and the two markers to the map:
 map.addObjects([routeLine, startMarker, endMarker]);
 
 // Set the map's viewport to make the whole route visible:
 map.setViewBounds(routeLine.getBounds());
 // console.log (map.setViewBounds(routeLine.getBounds()));
 }
 };
 
 // Get an instance of the routing service:
 var router = platform.getRoutingService();
 
 // Call calculateRoute() with the routing parameters,
 // the callback and an error callback function (called if a
 // communication error occurs):
 router.calculateRoute(routingParameters, onResult,
 function(error) {
 alert(error.message);
 });
 
 
 }*/
// FUNCION DE HERE
// function extraerCoordenadasSegunURLMapa(url)
// {
//     var partesURL = url.split(':');
//     var coords = new Array();

//     var punto1 = partesURL[2].split('/');
//     coords[0] = punto1[0];

//     var punto2 = partesURL[3].split('?');
//     coords[1] = punto2[0];

//     return coords;
// }

// NUEVA FUNCION DE GOOGLE MAPS
function extraerCoordenadasSegunURLMapa(url) {
    var coords = [];

    // Buscar el patrón de coordenadas en la URL
    var match = url.match(/@(-?\d+\.\d+),(-?\d+\.\d+),/);

    if (match) {
        // Las coordenadas se encuentran en los grupos de captura
        coords[0] = match[1]; // Latitud
        coords[1] = match[2]; // Longitud
    } else {
        // Manejar el caso en el que la URL no tiene el formato esperado
        //console.error("URL de Google Maps no válida. Por favor, ingrese una URL válida.");
    }

    return coords;
}

/*
 function calcular_distancia_viaje_desde_sucursal(coord1,coord2,costo_x_km)
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
 
 km = km * 2; // IDA + VUELTA
 
 datos_calcular_dist_viaje[0] = km;
 // datos_calcular_dist_viaje[1] = distancia;
 
 var valor_costo = Number((km * Number(costo_x_km)).toFixed(2));
 
 datos_calcular_dist_viaje[2] = valor_costo;
 
 }
 });
 
 
 }
 
 function calcular_distancia_viaje_visita_anterior(coord1,coord2,costo_x_km)
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
 
 datos_calcular_dist_viaje_visita_anterior[0] += km;
 // datos_calcular_dist_viaje_visita_anterior[1] = distancia;
 
 var valor_costo = Number((km * Number(costo_x_km)).toFixed(2));
 
 datos_calcular_dist_viaje_visita_anterior[2] += valor_costo;
 
 }
 });
 
 }
 
 */

function obtenerCoordenadasDestinoVisitaAnterior()
{

    var parametros = {"formulario": "0"}

    if (solicitud_nueva == 1)
    {
        parametros = {
            "id_usuario": usuario,
            "accion": "nueva_solicitud"
        };

        $.ajax({
            type: 'post',
            data: parametros,
            url: 'admin/ajax_peticiones.php'
        });
    }

}

function campo_esta_vacio(valor)
{
    return (valor.trim() == '');
}

function validar_vacio(valor)
{
    return (valor.trim() != '');
}

function marcar_error(id_input)
{
    $("#" + id_input + "").css('background-color', 'lightcoral');

    setTimeout(function() {
        $("#" + id_input + "").css('background-color', 'white')
    }, 2000);

}

function insertar_comentario_historial(id_solicitud, comentario, id_usuario)
{
    var id_solicitud = id_solicitud;
    var comentario = comentario;
    var usuario = id_usuario;

    var parametros = {
        "id_solicitud": id_solicitud,
        "accion": "insertar_comentario_historial",
        "comentario": comentario,
        "usuario": usuario
    };
    $.ajax({
        url: 'admin/ajax_peticiones.php',
        data: parametros,
        type: 'post',
        success: function(response)
        {
            return true;
        }

    });
}
function conocer_nombre_estado_por_id(id_estado)
{
    switch (id_estado)
    {
        case '1':

            return 'pendiente'

            break;

        case '3':
            return 'presentada'

            break;

        case '5':

            return 'autorizada'

            break;

        case '6':
            return 'revisada'

            break;

        case '7':
            return 'aprobada'
            
            break;

    }
}


function validar_numero(valor)
{
    if (valor == '')
        return false;

    return !isNaN(valor);
}
