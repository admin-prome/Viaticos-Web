$("#motivo_viatico_select").change(function () {

    if ($("#motivo_viatico_select").select().val() == '')
    {
        visibilidadFormsViaticos('ninguno', false);
        resetearConceptos('concepto_viatico_select');
        return false;
    }
    else
    {
        var motivo_seleccionado = $("#motivo_viatico_select").select().val();

        resetearConceptos('concepto_viatico_select');
        agregarItemsConceptos(motivo_seleccionado, 'concepto_viatico_select');

        var concepto_seleccionado = $("#concepto_viatico_select").select().val();

        if (concepto_seleccionado == '')
        {
            visibilidadFormsViaticos('ninguno', false);
            return false;
        }

        var array_conceptos_transportes = new Array(CONCEPTO_AUTO, CONCEPTO_MOTO,
                CONCEPTO_TAXI_REMIS, CONCEPTO_TRANS_PUB);

        var MOTIVO_EVALUACION_TERRENO = 1;
        var MOTIVO_VISITA_COBRANZA = 9;

        if (MOTIVO_EVALUACION_TERRENO == motivo_seleccionado)
        {

            if (estaEnArray(concepto_seleccionado, array_conceptos_transportes))
            {
                visibilidadFormsViaticos(1, false);

                if (concepto_seleccionado == CONCEPTO_TAXI_REMIS || concepto_seleccionado == CONCEPTO_TRANS_PUBLICO)
                {
                    $('#form1_attach').css('display', 'block');
                    $('#div_attach_form1').css('display', 'block');
                }
                else
                {
                    $('#form1_attach').css('display', 'none');
                    $('#div_attach_form2').css('display', 'none');
                }

            }
            else
            {
                visibilidadFormsViaticos(3, false);
            }

            return true;

        }

        if (MOTIVO_VISITA_COBRANZA == motivo_seleccionado)
        {

            if (estaEnArray(concepto_seleccionado, array_conceptos_transportes))
            {
                visibilidadFormsViaticos(2, false);
                $('#nro_solicitud_form_2').show();	//por si estuviera oculto..

                if (concepto_seleccionado == CONCEPTO_TAXI_REMIS || concepto_seleccionado == CONCEPTO_TRANS_PUBLICO)
                {
                    $('#form2_attach').css('display', 'block');
                    $('#div_attach_form2').css('display', 'block');

                }
                else
                {
                    $('#form2_attach').css('display', 'none');
                    $('#div_attach_form2').css('display', 'none');
                }
            }
            else
            {
                visibilidadFormsViaticos(3, false);
            }

            return true;

        }

        // Todos los otros motivos:
        if (MOTIVO_EVALUACION_TERRENO != motivo_seleccionado && MOTIVO_VISITA_COBRANZA != motivo_seleccionado)
        {
            if (estaEnArray(concepto_seleccionado, array_conceptos_transportes))
            {
                visibilidadFormsViaticos(2, false);
                $('#nro_solicitud_form_2').hide();

                if (concepto_seleccionado == CONCEPTO_TAXI_REMIS || concepto_seleccionado == CONCEPTO_TRANS_PUBLICO)
                {
                    $('#form2_attach').css('display', 'block');
                    $('#div_attach_form2').css('display', 'block');
                }
                else
                {
                    $('#form2_attach').css('display', 'none');
                    $('#div_attach_form2').css('display', 'none');
                }

                return true;
            }
            else
            {
                visibilidadFormsViaticos(3, false);
                return true;
            }

        }

    }

});