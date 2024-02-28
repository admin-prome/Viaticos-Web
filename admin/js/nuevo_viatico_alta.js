$("#form1_solicitud_viatico").blur(function() {
    params2 = {};

    params2.nro_solicitud = $("#form1_solicitud_viatico").val();
    params2.accion = "chequear_numero_solicitud";
    console.log(params2);
    $.ajax({
        type: 'post',
        data: params2,
        url: 'admin/ajax_peticiones.php',
        success: function(response)
        {
            if (response == 'succes') {
                
                marcar_error('form1_solicitud_viatico');
                alert('El numero de solicitud debe ser unico en la evaluacion de terreno.');
                error_validacion_alta_viatico = true;
            } else {
                error_validacion_alta_viatico = false;
            }
        }

    });

});
$("#boton_guardado_form_1,#boton_guardado_form_2,#boton_guardado_form_3").click(function() {

    if ($("#form_1").css('display') == 'block')
    {
        var formu_activo = 1;
    }

    if ($("#form_2").css('display') == 'block')
    {
        var formu_activo = 2;
    }

    if ($("#form_3").css('display') == 'block')
    {
        var formu_activo = 3;
    }

    var error_validacion_alta_viatico = false;
	
    if (formu_activo == 1 || formu_activo == 2)
    {

        params = {};
        params.accion = "agregar_viatico";
        params.formulario = formu_activo;
        params.id_usuario = id_usuario_js;
        params.solicitud_activa = $("#solicitud_activa").val();
        params.fecha = $("#fecha_viatico").val();
        params.motivo = $("#motivo_viatico_select option:selected").val();
        params.concepto = $("#concepto_viatico_select option:selected").val();
        params.observaciones = $("#observaciones_viatico").val();
        if (formu_activo == 1 || (formu_activo == 2 && $('#nro_solicitud_form_2').is(':visible')))
        {
            params.nro_solicitud = $("#form" + formu_activo + "_solicitud_viatico").val();
        }

        params.importe = $("#form" + formu_activo + "_importe_viatico").val();
        params.kms = $("#form" + formu_activo + "_km_viatico").val();
		
		if(params.concepto == CONCEPTO_AUTO || params.concepto == CONCEPTO_MOTO)
		{
        
			params.km_ida = datos_viaje_ida[0];
        	params.km_vuelta = datos_viaje_vuelta[0];
        	params.costo_ida = datos_viaje_ida[1];
			params.costo_vuelta = datos_viaje_vuelta[1];
		
		}
       
        
		params.destino = $("#form" + formu_activo + "_destino_viatico").val();
        params.monto = $("#form1_monto_viatico").val();
        params.origen = $("#form" + formu_activo + "_origen_viatico_select option:selected").val();
        var url_here = $('#form' + formu_activo + '_destino_viatico').val().slice(0, 20);
        if (formu_activo == 1)
        {
            params.plazo = $("#form1_plazo_viatico").val();
            params.segmento = $("#form1_segmento_viatico_select option:selected").val();
        }


        /****************************** VALIDAR FORMULARIO 1 ****************************/

        if (formu_activo == 1)
        {

            //la fecha:
            if (!validar_vacio(params.fecha))
            {
                error_validacion_alta_viatico = true;
                marcar_error("fecha_viatico");
            }

            //el plazo:
            if (!validar_vacio(params.plazo))
            {
                error_validacion_alta_viatico = true;
                marcar_error('form1_plazo_viatico');
            }

            // el monto:
            if (!validar_numero(params.monto))
            {
                error_validacion_alta_viatico = true;
                marcar_error('form1_monto_viatico');
            }
            
            valor_solicitud = $('#form1_solicitud_viatico').val();
            
            if (valor_solicitud == ''){
                
                error_validacion_alta_viatico = true;
                marcar_error('form1_solicitud_viatico');
            }

//Debemos cambiar esta funcion 
// Validar destino:
            if (validar_vacio(url_here) != false && validar_url(url_here) != false && url_here.slice(0, 20) == "https://www.google.com/maps") {
                params.destino = extraerCoordenadasMapa($("#form1_destino_viatico").val());
            }
            else
            {
                error_validacion_alta_viatico = true;
                marcar_error("form1_destino_viatico");
            }
            /*if (validar_numero(params.nro_solicitud) != false) {
             
             
             }
             */    }

        /************************* VALIDAR FORMULARIO 2 **********************/
        if (formu_activo == 2)
        {

            // La fecha:
            if (!validar_vacio(params.fecha))
            {
                error_validacion_alta_viatico = true;
                marcar_error("fecha_viatico");
            }

            if ($('#nro_solicitud_form_2').is(':visible')) // A veces esta oculto..
            {
                // nro solicitud:
                if (!validar_numero(params.nro_solicitud))
                {
                    error_validacion_alta_viatico = true;
                    marcar_error('form2_solicitud_viatico');
                }
            }

// Validar destino:
            if (validar_vacio(url_here) != false && validar_url(url_here) != false && url_here.slice(0, 20) == "https://www.google.com/maps")
                params.destino = extraerCoordenadasMapa($("#form2_destino_viatico").val());
            else
            {
                error_validacion_alta_viatico = true;
                marcar_error("form2_destino_viatico");
            }
        }

// Validacion OK:
        if (!error_validacion_alta_viatico)
        {
            $('#myModal2').reveal('open');
            $.ajax({
                type: 'post',
                data: params,
                url: 'admin/ajax_peticiones.php',
                success: function(response)
                {

                    setTimeout(function() {
                        $("#load").html('<img src="img/ok.png"><br/><span id="finalizado_ok" style="font-weight: bold;font-size: 16px;margin-left:-25%;">Guardado correctamente .</span>');
                        
					setTimeout(function() {
                            window.location = "nuevo_viatico.php";
                        }, 750);
                    }, 750);
                }

            });
        }
        else
        {
            $("#p_error").css("display", "inline");
            $("#p_error").html("Debe completar todos los campos correctamente");
            $("#p_error").fadeOut(5000);
        }
    }
    else // Formulario 3
    {
        var importe_viatico = validar_numero($("#form3_importe_viatico").val());
        var fecha_viatico = validar_vacio($("#fecha_viatico").val());
        var formData3 = new FormData();
        formData3.append('accion', 'agregar_viatico');
        formData3.append('id_usuario', id_usuario_js);
        formData3.append('formulario', '3');
        formData3.append('solicitud_activa', $("#solicitud_activa").val());
        formData3.append('fecha', $("#fecha_viatico").val());
        formData3.append('observaciones', $("#observaciones_viatico").val());
        formData3.append('motivo', $("#motivo_viatico_select option:selected").val());
        formData3.append('concepto', $("#concepto_viatico_select option:selected").val());
        formData3.append('importe', $("#form3_importe_viatico").val());

        if($('#form3_attach')[0].files[0])
        {
            var file_attach = $('#form3_attach')[0].files[0];
            formData3.append('form3_file_attach', file_attach);
        }

        //el importe:
        if (!validar_numero($("#form3_importe_viatico").val()))
        {
            error_validacion_alta_viatico = true;
            marcar_error('form3_importe_viatico');
        }
        else
        {
            var concepto = $("#concepto_viatico_select option:selected").val();

            if ((concepto == ID_ALMUERZO || concepto == ID_CENA) && SOY_DE_CASA_MATRIZ)
            {
                if ($("#form3_importe_viatico").val() > tope_almuerzo_cena)
                {
                    error_validacion_alta_viatico = true;
                     alert('El tope de almuerzo es de $'+tope_almuerzo_cena+'.');
                    marcar_error('form3_importe_viatico');
                }
            }

        }

        if (!validar_vacio($("#fecha_viatico").val()))
        {
            error_validacion_alta_viatico = true;
            marcar_error('fecha_viatico');
        }


        if (!error_validacion_alta_viatico) // Validacion OK
        {

            $('#myModal2').reveal('open');
            jQuery.ajax({
                url: 'admin/ajax_peticiones.php',
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                data: formData3,
                success: function(response)
                {

                    setTimeout(function() {
                        $("#load").html('<img src="img/ok.png"><br/><span id="finalizado_ok" style="font-weight: bold;font-size: 16px;margin-left:-25%;">Guardado correctamente .</span>');
                        setTimeout(function() {
                            window.location = "nuevo_viatico.php";
                        }, 750);
                    }, 750);
                }

            });
        }
        else
        {
            $("#p_error").html("Debe completar todos los campos correctamente");
            $("#p_error").fadeOut(5000);
        }


    } // FIN Formulario 3

});