$('#concepto_viatico_select').change(function() {

                var motivo_seleccionado = $("#motivo_viatico_select").select().val();
                var concepto_seleccionado = $("#concepto_viatico_select").select().val();

                if (motivo_seleccionado == '' || concepto_seleccionado == '')
                {
                    visibilidadFormsViaticos('ninguno', false);
                    return false;
                }


                var array_conceptos_transportes = new Array(CONCEPTO_AUTO, CONCEPTO_MOTO, CONCEPTO_TAXI_REMIS, CONCEPTO_TRANS_PUB);

                var MOTIVO_EVALUACION_TERRENO = 1;
                var MOTIVO_VISITA_COBRANZA = 9;

                if (MOTIVO_EVALUACION_TERRENO == motivo_seleccionado)
                {

                    if (estaEnArray(concepto_seleccionado, array_conceptos_transportes))
                    {
                        visibilidadFormsViaticos(1, false);
                        resetearViaticoYaCalculado(concepto_seleccionado, 1);
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
                        resetearViaticoYaCalculado(concepto_seleccionado, 2);
                    }
                    else
                    {
                        visibilidadFormsViaticos(3, false);
                    }

                    return true;

                }

                // Todos los otros motivos:
                if (MOTIVO_EVALUACION_TERRENO != motivo_seleccionado &&
                        MOTIVO_VISITA_COBRANZA != motivo_seleccionado)
                {
                    if (estaEnArray(concepto_seleccionado, array_conceptos_transportes))
                    {
                        visibilidadFormsViaticos(2, false);
						$('#nro_solicitud_form_2').hide();
                        resetearViaticoYaCalculado(concepto_seleccionado, 2);
                        return true;
                    }
                    else
                    {
                        visibilidadFormsViaticos(3, false);
                        return true;
                    }

                }


});