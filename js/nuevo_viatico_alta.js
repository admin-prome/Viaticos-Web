var error_validacion_alta_viatico = false;
function validar_numero(valor)
{
    if (valor == '')
        return false;

    return !isNaN(valor);
}
$("#form1_solicitud_viatico").blur(function () {
    params2 = {};
    params2.nro_solicitud = $("#form1_solicitud_viatico").val();
    params2.accion = "chequear_numero_solicitud";
    // console.log(params2);
    $.ajax({
        type: 'post',
        data: params2,
        url: 'admin/ajax_peticiones.php',
        success: function (response)
        {
            if (response == 'succes')
            {

                marcar_error('form1_solicitud_viatico');
                alert('El numero de solicitud debe ser unico en la evaluacion de terreno.');
                error_validacion_alta_viatico = true;
            }
            else
            {
                error_validacion_alta_viatico = false;
            }
        }

    });

});

$("#boton_guardado_form_1,#boton_guardado_form_2,#boton_guardado_form_3").click(function () {

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
        formu_activo = 3;
    }



    var formData12 = new FormData();

    if (formu_activo == 1 || formu_activo == 2)
    {

        formData12.append('accion', 'agregar_viatico');
        formData12.append('formulario', formu_activo);
        formData12.append('id_usuario', id_usuario_js);
        formData12.append('solicitud_activa', $("#solicitud_activa").val());
        formData12.append('fecha', $("#fecha_viatico").val());
        formData12.append('motivo', $("#motivo_viatico_select option:selected").val());
        formData12.append('concepto', $("#concepto_viatico_select option:selected").val());
        formData12.append('observaciones', $("#observaciones_viatico").val());

        if (formu_activo == 1 || (formu_activo == 2 && $('#nro_solicitud_form_2').is(':visible')))
        {
            formData12.append('nro_solicitud', $("#form" + formu_activo + "_solicitud_viatico").val());
        }

        formData12.append('importe', $("#form" + formu_activo + "_importe_viatico").val());
        formData12.append('kms', $("#form" + formu_activo + "_km_viatico").val());
        formData12.append('visita', $("#form" + formu_activo + "_visita_select option:selected").val());

        if ($("#concepto_viatico_select option:selected").val() == CONCEPTO_AUTO || $("#concepto_viatico_select option:selected").val() == CONCEPTO_MOTO)
        {
            formData12.append('km_ida', datos_viaje_ida[0]);
            formData12.append('km_vuelta', datos_viaje_vuelta[0]);
            formData12.append('costo_ida', datos_viaje_ida[1]);
            formData12.append('costo_vuelta', datos_viaje_vuelta[1]);
        }

        formData12.append('destino', $("#form" + formu_activo + "_destino_viatico").val());

        formData12.append('monto', $("#form1_monto_viatico").val());
        formData12.append('origen', $("#form" + formu_activo + "_origen_viatico_select option:selected").val());

        var url_here = $('#form' + formu_activo + '_destino_viatico').val().slice(0, 20);

        if (formu_activo == 1)
        {

            error_validacion_alta_viatico = false;
            formData12.append('plazo', $("#form1_plazo_viatico").val());

            formData12.append('segmento', $("#form1_segmento_viatico_select option:selected").val());


            if ($('#form1_attach').is(':visible'))
            {
                if ($('#form1_attach')[0].files[0])
                {
                    formData12.append('form1_file_attach', $('#form1_attach')[0].files[0]);
                }
            }
        }

        // VALIDAR FORMULARIO 1:

        if (formu_activo == 1)
        {

            error_validacion_alta_viatico = false;

            //la fecha:
            if (!validar_vacio($("#fecha_viatico").val()))
            {
                error_validacion_alta_viatico = true;
                marcar_error("fecha_viatico");
            }

            //el plazo:
            if (!validar_vacio($("#form1_plazo_viatico").val()))
            {
                error_validacion_alta_viatico = true;
                marcar_error('form1_plazo_viatico');
            }

            // el monto:
            if (!validar_numero($("#form1_monto_viatico").val()))
            {
                error_validacion_alta_viatico = true;
                marcar_error('form1_monto_viatico');
            }

            // valido que no pongas comas en importe

            importe_chequeo1 = $("#form1_importe_viatico").val();
            importe_chequeo_res1 = importe_chequeo1.indexOf(",");
            //console.log(importe_chequeo1);
            //console.log(importe_chequeo_res1);


            if (importe_chequeo_res1 != -1)
            {
                //console.log("error importe coma");

                error_validacion_alta_viatico = true;
                marcar_error('form1_importe_viatico');
            }


            valor_solicitud = $('#form1_solicitud_viatico').val();

            if (valor_solicitud == '')
            {
                error_validacion_alta_viatico = true;
                marcar_error('form1_solicitud_viatico');
            }


            if ($("#form1_visita_select option:selected").val() == '')
            {
                error_validacion_alta_viatico = true;
                marcar_error('form1_visita_select');
            }


            // Validar destino:

            if (validar_vacio(url_here) != false && validar_url(url_here) != false && url_here.slice(0, 20) == "https://wego.here.co")
            {
                formData12.append('destino', extraerCoordenadasMapa($("#form1_destino_viatico").val()));
            }
            else
            {
                error_validacion_alta_viatico = true;
                marcar_error("form1_destino_viatico");
            }
            /*if (validar_numero(params.nro_solicitud) != false) {
             
             
             }
             */


            /*Valido numero solicitud*/
            params2.nro_solicitud = $("#form1_solicitud_viatico").val();
            params2.accion = "chequear_numero_solicitud";
            $.ajax({
                type: 'post',
                data: params2,
                url: 'admin/ajax_peticiones.php',
                success: function (response)
                {
                    if (response == 'succes')
                    {

                        marcar_error('form1_solicitud_viatico');
                        alert('El numero de solicitud debe ser unico en la evaluacion de terreno.');
                        error_validacion_alta_viatico = true;



                    }



                    if (!error_validacion_alta_viatico)
                    {
                        $('#myModal2').reveal('open');

                        jQuery.ajax({
                            url: 'admin/ajax_peticiones.php',
                            type: 'POST',
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData12,
                            success: function (response)
                            {



                                setTimeout(function () {
                                    $("#load").html('<img src="img/ok.png"><br/><span id="finalizado_ok" style="font-weight: bold;font-size: 16px;margin-left:-25%;">Guardado correctamente .</span>');
                                    setTimeout(function () {
                                        window.location = url_pagina;

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

            });

        }

        /************************* VALIDAR FORMULARIO 2 **********************/
        if (formu_activo == 2)
        {

            error_validacion_alta_viatico = false;

            // La fecha:
            if (!validar_vacio($("#fecha_viatico").val()))
            {
                error_validacion_alta_viatico = true;
                marcar_error("fecha_viatico");
            }



            importe_chequeo2 = $("#form2_importe_viatico").val();
            importe_chequeo_res2 = importe_chequeo2.indexOf(",");
            // console.log(importe_chequeo2);
            //console.log(importe_chequeo_res2);


            if (importe_chequeo_res2 != -1)
            {
                // console.log("error importe coma");

                error_validacion_alta_viatico = true;
                marcar_error('form2_importe_viatico');
            }


            // A veces esta oculto..
            if ($("#motivo_viatico_select option:selected").val() != 9) {
                if ($('#nro_solicitud_form_2').is(':visible'))
                {
                    // nro solicitud:
                    if (!validar_numero($("#form2_solicitud_viatico").val()))
                    {
                        error_validacion_alta_viatico = true;
                        marcar_error('form2_solicitud_viatico');
                    }
                }
            }
            // Validar destino:
            if (validar_vacio(url_here) != false && validar_url(url_here) != false && url_here.slice(0, 20) == "https://wego.here.co")
            {
                formData12.append('destino', extraerCoordenadasMapa($("#form2_destino_viatico").val()));
            }
            else
            {
                error_validacion_alta_viatico = true;
                marcar_error("form2_destino_viatico");
            }
            if ($("#motivo_viatico_select option:selected").val() != 9) {
                if ($("#form2_visita_select option:selected").val() == '')
                {
                    error_validacion_alta_viatico = true;
                    marcar_error('form2_visita_select');
                }
            }
            if ($('#form2_attach').is(':visible'))
            {
                if ($('#form2_attach')[0].files[0])
                {
                    formData12.append('form2_file_attach', $('#form2_attach')[0].files[0]);
                }
            }

        }

        // Validacion OK: Formulario 1 ó 2:

    }

    if (formu_activo == 2) {


        if (!error_validacion_alta_viatico)
        {
            $('#myModal2').reveal('open');

            jQuery.ajax({
                url: 'admin/ajax_peticiones.php',
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                data: formData12,
                success: function (response)
                {



                    setTimeout(function () {
                        $("#load").html('<img src="img/ok.png"><br/><span id="finalizado_ok" style="font-weight: bold;font-size: 16px;margin-left:-25%;">Guardado correctamente .</span>');
                        setTimeout(function () {
                            window.location = url_pagina;

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


    if (formu_activo == 3)
    {
        error_validacion_alta_viatico = false;

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

        if ($("#select_cantidad_personas option:selected").val() != 0) {
            formData3.append('personas_por_almuerzo', $("#select_cantidad_personas option:selected").val());
        }

        if ($('#form3_attach')[0].files[0])
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

            if ((concepto == ID_ALMUERZO))
            {
                if ($("#select_cantidad_personas option:selected").val() != 0) {

                    cantidad_personas = $("#select_cantidad_personas option:selected").val();

                    tope_almuerzo_cena = tope_almuerzo * cantidad_personas;

                } else {
                    tope_almuerzo_cena = tope_almuerzo;
                }
                if ($("#form3_importe_viatico").val() > tope_almuerzo_cena)
                {
                    error_validacion_alta_viatico = true;
                    alert('El tope de almuerzo es de $' + tope_almuerzo_cena + '.');
                    marcar_error('form3_importe_viatico');
                }
            } else if (concepto == ID_CENA)
            {
                if ($("#select_cantidad_personas option:selected").val() != 0) {

                    cantidad_personas = $("#select_cantidad_personas option:selected").val();

                    tope_almuerzo_cena = tope_cena * cantidad_personas;

                } else {
                    tope_almuerzo_cena = tope_cena;
                }

                if ($("#form3_importe_viatico").val() > tope_almuerzo_cena)
                {
                    error_validacion_alta_viatico = true;
//                    alert('El tope de la cena es de $' + tope_almuerzo_cena + '.');
                    marcar_error('form3_importe_viatico');
                }
            } else if (concepto == ID_GUARDERIA)
            {
               
                    tope_guarderia = tope_guarderia;
              

                if ($("#form3_importe_viatico").val() > tope_guarderia)
                {
                    error_validacion_alta_viatico = true;
                    alert('El tope de la guarderia es de $' + tope_guarderia + '.');
                    marcar_error('form3_importe_viatico');
                }
            } else if (concepto == ID_GUARDERIA_LEY)
            {
               
                    tope_guarderia_ley = tope_guarderia_ley;
              

                if ($("#form3_importe_viatico").val() > tope_guarderia_ley)
                {
                    error_validacion_alta_viatico = true;
                    alert('El tope de la guarderia es de $' + tope_guarderia_ley + '.');
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
                success: function (response)
                {

                    setTimeout(function () {
                        $("#load").html('<img src="img/ok.png"><br/><span id="finalizado_ok" style="font-weight: bold;font-size: 16px;margin-left:-25%;">Guardado correctamente .</span>');
                        setTimeout(function () {
                            window.location = url_pagina;

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
