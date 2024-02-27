<?php

// Funciones relativas a la obtención de datos de los viaticos
function obtener_viaticos() {
    $sql = "SELECT * FROM visita";
    $rs = ejecutarConsulta($sql);

    return $rs;
}

function obtener_viatico($id_viatico) {
    $sql = "SELECT * FROM visita WHERE id = " . $id_viatico;
    $rs = ejecutarConsulta($sql);

    return $rs;
}

function obtener_viaticos_por_usuario($id_usuario, $id_solicitud) {

    $sql = "SELECT s.id ,s_v.id_visita , v.* FROM solicitud s JOIN solicitud_visita s_v
	ON s_v.id_solicitud = s.id JOIN visita v ON v.id = s_v.id_visita WHERE s.id_usuarios = " . $id_usuario . " AND s_v.id_solicitud = " . $id_solicitud;

    return ejecutarConsulta($sql);
}

// FIN funciones relativas a la obtención de datos de los viaticos
// Funciones relativas a la edicion:

function elViaticoPuedeSerEditado($donde_fue_cargado, $motivo, $concepto) {

    return true;

    /*
      if (VIATICO_CARGADO_POR_PC == $donde_fue_cargado) {
      return 'pc';
      } else { //celu
      if ($motivo == MOTIVO_EVAL_TERRENO) {

      $conceptos_lista = array(CONCEPTO_AUTO, CONCEPTO_MOTO, CONCEPTO_TAXI_REMIS,
      CONCEPTO_TRANS_PUBLICO);

      if (in_array($concepto, $conceptos_lista))
      return 'cel';
      else
      return false;
      }

      return false;
      }
     */
}

function editar_viatico($datos_viatico) {
    $fecha_viatico = pasarFechaDatePickerAFormatoDate($datos_viatico['fecha_viatico']);

    $sql = "UPDATE visita SET fecha = '" . $fecha_viatico . "', observacion = '" .
            $datos_viatico['obs_viatico'] . "'";

    switch ($datos_viatico['form_editado']) {

        case '1':

            $sql .= ", nro_solicitud = " . $datos_viatico['solicitud_viatico'];
            $sql .= ", monto = '" . $datos_viatico['monto_viatico'] . "'";
            $sql .= ", plazo = '" . $datos_viatico['plazo_viatico'] . "'";
            $sql .= ", id_segmento = " . $datos_viatico['segmento_viatico'];
            $sql .= ", visita_exitosa = " . $datos_viatico['visita_edicion'];

            echo $sql;


            break;

        case '2':

            if (!empty($datos_viatico['solicitud_viatico'])) { // Visita de cobranza...
                $sql .= ", nro_solicitud = " . $datos_viatico['solicitud_viatico'];
            }

            if (!empty($datos_viatico['origen_viatico'])) { // Taxi / Remis / T. Pub.
                $sql .= ", origen = '" . $datos_viatico['origen_viatico'] . "'";
            }

            if (!empty($datos_viatico['destino_viatico'])) { // Taxi / Remis / T. Pub.
                $sql .= ", destino = '" . $datos_viatico['destino_viatico'] . "'";
            }

            if (!empty($datos_viatico['importe_viatico'])) { // Taxi / Remis / T. Pub.
                $sql .= ", importe = '" . $datos_viatico['importe_viatico'] . "'";
            }


            if (!empty($datos_viatico['km_viatico'])) { // Taxi / Remis / T. Pub.
                $sql .= ", km = '" . $datos_viatico['km_viatico'] . "'";
            }

            if (isset($datos_viatico['visita_edicion'])) {

                if ($datos_viatico['visita_edicion'] == 0 || $datos_viatico['visita_edicion'] == 1) {

                    $sql .= ", visita_exitosa = " . $datos_viatico['visita_edicion'];
                }
            }


            break;


        case '3':

            $sql .= ", importe = '" . $datos_viatico['importe'] . "'";

            break;
    }

    $sql .= " WHERE id = " . $datos_viatico['id_viatico'];
    $rs = ejecutarConsulta($sql);

    if ($rs) {
        editAttachViatico('form3_file_attach', $datos_viatico['id_viatico']);
    }
}

//FIN funciones relativas a la edicion.
// Funciones relativas a los attach del viatico:

function extensionAttachViatico($name_file) {
    $partesNombre = explode('.', $_FILES[$name_file]['name']);
    return $partesNombre[sizeof($partesNombre) - 1];
}

function nombreAttachViatico($id_viatico) {
    $sql = "SELECT descripcion FROM attachs_viaticos WHERE id_viatico = " . $id_viatico;

    $rs = ejecutarConsulta($sql);
    return $rs[0]['descripcion'];
}

function elViaticoTieneAttach($id_viatico, $path = '') {

    $sql = "SELECT descripcion FROM attachs_viaticos WHERE id_viatico = " . $id_viatico;

    $rs = ejecutarConsulta($sql);

    if (!empty($rs) && file_exists($path . 'attachs/' . $rs[0]['descripcion']))
        return true;
    else
        return false;
}

function eliminarAttachViatico($id_viatico, $path = '') {

    $nombre_attach = nombreAttachViatico($id_viatico);

    $sql = "DELETE FROM attachs_viaticos WHERE id_viatico = " . $id_viatico;
    $rs = ejecutarConsulta($sql);

    if ($rs) {
        unlink($path . 'attachs/' . $nombre_attach);
    }
}

function editAttachViatico($input_file, $id_viatico) {
    if (is_uploaded_file($_FILES[$input_file]['tmp_name']) &&
            $_FILES[$input_file]['error'] == 0) {

        if (!elViaticoTieneAttach($id_viatico)) {
            $key_attach = generarAlfaNumericoAleatorio();
            $ext_attach = extensionAttachViatico($input_file);

            $name_new_file = $key_attach . "." . $ext_attach;

            if (move_uploaded_file($_FILES[$input_file]['tmp_name'], 'attachs/' . $name_new_file)) {
                $sql = "INSERT INTO attachs_viaticos (descripcion,id_viatico)
				VALUES ('" . $name_new_file . "'," . $id_viatico . ")";

                ejecutarConsulta($sql);
            }
        } else {

            $nombre_archivo = nombreAttachViatico($id_viatico);

            unlink('attachs/' . $nombre_archivo);

            $ext_attach = extensionAttachViatico($input_file);

            $partes_nombre_archivo = explode('.', $nombre_archivo);

            $name_new_file = $partes_nombre_archivo[0] . '.' . $ext_attach;

            move_uploaded_file($_FILES[$input_file]['tmp_name'], 'attachs/' . $name_new_file);

            $sql = "UPDATE attachs_viaticos SET descripcion = '" .
                    $name_new_file . "' WHERE id_viatico = " . $id_viatico;

            ejecutarConsulta($sql);
        }
    }
}

function subirAttachViatico($input_file) {

    if ($_FILES[$input_file]['error'] == 0) {

        $key_attach = generarAlfaNumericoAleatorio();
        $ext_attach = extensionAttachViatico($input_file);

        $name_new_file = $key_attach . "." . $ext_attach;
        $new_path = 'attachs/' . $name_new_file;


        if (move_uploaded_file($_FILES[$input_file]['tmp_name'], $new_path)) {

            $id_viatico = obtener_ultimo_id('visita');

            $sql = "INSERT INTO attachs_viaticos (descripcion,id_viatico) VALUES ('"
                    . $name_new_file . "'," . $id_viatico . ")";

            ejecutarConsulta($sql);
        }
    }
}

function name_attach_viatico($id_viatico) {
    $sql = "SELECT descripcion FROM attachs_viaticos WHERE id_viatico = " . $id_viatico;
    $res = ejecutarConsulta($sql);

    if (!empty($res))
        return $res[0]['descripcion'];
    else
        return '';
}

function eliminarMultiplesAttachsViaticos($ids_viaticos) {
    $sql = "SELECT descripcion FROM attachs_viaticos WHERE id_viatico IN (" .
            implode(',', $ids_viaticos) . ")";

    $name_attachs = ejecutarConsulta($sql);

    $sql = "DELETE FROM attachs_viaticos WHERE id_viatico IN (" .
            implode(',', $ids_viaticos) . ")";

    ejecutarConsulta($sql);

    $attachs = array();
    foreach ($name_attachs as $att) {
        $attachs[] = $att['descripcion'];
    }

    foreach ($attachs as $attach) {
        @ unlink('admin/attachs/' . $attach);
    }
}

// FIN Funciones relativas a los attach del viatico:
// Funciones relativas al borrado de los viaticos:
function elViaticoPuedeSerBorrado($id_viatico, $costo_ida, $costo_vuelta, $km_ida, $km_vuelta, $solicitud) {

    /*
      Primer condicion: viaticos tipo almuerzo, guarderia, etc.. (desde PC).
      + trans. pub o taxi / remis.
     */

    $cond1 = (empty($costo_ida) && empty($costo_vuelta) && empty($km_ida) &&
            empty($km_vuelta));

    // 2da. Condición: es el ultimo viatico (del tipo que sea).

    $cond2 = (ultimo_viatico_solicitud($solicitud) == $id_viatico);

    // 3ra. Condición: Que el proximo viatico NO sea una visita anterior.

    $cond3 = (!proximo_viatico_es_visita_anterior($id_viatico, $solicitud, true));

    return ($cond1 || $cond2 || $cond3);
}

function eliminar_viatico($id_viatico, $id_solicitud) {

    $valor_retorno = -1;

    // almuerzos, peajes, guarderias, etc ó bien: trans. publico o taxi / remis

    $costo_ida = obtener_campo_por_tabla_e_id('costo_ida', $id_viatico, 'visita');
    $costo_vuelta = obtener_campo_por_tabla_e_id('costo_vuelta', $id_viatico, 'visita');
    $km_ida = obtener_campo_por_tabla_e_id('km_ida', $id_viatico, 'visita');
    $km_vuelta = obtener_campo_por_tabla_e_id('km_vuelta', $id_viatico, 'visita');

    if (empty($costo_ida) && empty($costo_vuelta) && empty($km_ida) && empty($km_vuelta)) {
        $valor_retorno = 0;
    }


    if ($valor_retorno == -1) {
        //ultimo viatico : Sea desde sucursal o visita anterior)
        if (ultimo_viatico_solicitud($id_solicitud) == $id_viatico) {

            $origen_viat = obtener_campo_por_tabla_e_id('origen', $id_viatico, 'visita');

            if (VIATICO_ORIGEN_SUCURSAL == $origen_viat) {
                $valor_retorno = 0;
            } else {
                $valor_retorno = buscar_viatico_anterior_con_km($id_viatico, $id_solicitud);
            }
        }
    }

    // Demás casos: 

    if ($valor_retorno == -1) {
        $valor_retorno = buscar_viatico_anterior_con_km($id_viatico, $id_solicitud);
    }


    $sql_solicitud_visita = "DELETE FROM solicitud_visita WHERE id_visita = " .
            $id_viatico;

    ejecutarConsulta($sql_solicitud_visita);

    $sql_visita = "DELETE FROM visita WHERE id = " . $id_viatico;
    ejecutarConsulta($sql_visita);

    if (elViaticoTieneAttach($id_viatico)) {
        eliminarAttachViatico($id_viatico);
    }


    return $valor_retorno;
}

// FIN Funciones relativas al borrado de los viaticos:

function proximo_viatico_es_visita_anterior($id_visita, $id_solicitud, $omite_tipo_almuerzo_peaje_remis_etc = false) {

    $sql = "SELECT SV.id_visita FROM solicitud_visita SV INNER JOIN visita V
	ON SV.id_visita = V.id WHERE SV.id_visita > " . $id_visita .
            " AND SV.id_solicitud = " . $id_solicitud;

    if ($omite_tipo_almuerzo_peaje_remis_etc) {
        $sql .= " AND V.costo_ida IS NOT NULL AND V.costo_vuelta IS NOT NULL";
        $sql .= " AND V.km_ida IS NOT NULL AND V.km_vuelta IS NOT NULL";
    }

    $sql .= " ORDER BY SV.id_visita ASC LIMIT 1";
    $rs = ejecutarConsulta($sql);

    if ($rs) {
        $origen = obtener_campo_por_tabla_e_id('origen', $rs[0]['id_visita'], 'visita');
        return ($origen == VIATICO_ORIGEN_VISITA_ANTERIOR);
    } else {  // No fue encontrado en la base un próximo viático.
        return false;
    }
}

function elViaticoSeCargoPorPC($donde_fue_cargado) {
    return (VIATICO_CARGADO_POR_PC == $donde_fue_cargado);
}

function setearViaticoCeluOrigenEnSucursal($id_viatico) {
    $sql = "UPDATE visita SET origen = " . VIATICO_ORIGEN_SUCURSAL
            . " WHERE id = " . $id_viatico;

    ejecutarConsulta($sql);
}

function hallarMaxIDViatico($viaticos) {
    $max = $viaticos[0]['id'];

    for ($i = 1; $i < sizeof($viaticos); $i++) {
        if ($viaticos[$i]['id'] > $max) {
            $max = $viaticos[$i]['id'];
        }
    }

    return $max;
}

function formu_viatico_visibilidad($idMotivo, $idConcepto) {

    if ($idMotivo == MOTIVO_BENEFICIO) {
        return FORM_VIATICO_3;
    }

    if ($idMotivo == MOTIVO_VISITA_COBRANZA) {
        return FORM_VIATICO_2;
    }

    if ($idMotivo == MOTIVO_CAPACITACION) {
        if ($idConcepto == CONCEPTO_PEAJE || $idConcepto == CONCEPTO_ESTACIONAMIENTO ||
                $idConcepto == CONCEPTO_ALOJAMIENTO || $idConcepto == CONCEPTO_CENA ||
                $idConcepto == CONCEPTO_ALMUERZO) {
            return FORM_VIATICO_3;
        } else {
            return FORM_VIATICO_2;
        }
    }

    if ($idMotivo == MOTIVO_EVAL_TERRENO) {
        if ($idConcepto == CONCEPTO_PEAJE || $idConcepto == CONCEPTO_ESTACIONAMIENTO) {
            return FORM_VIATICO_3;
        } else {
            return FORM_VIATICO_1;
        }
    }

    if ($idMotivo == MOTIVO_OTRAS_REUNIONES || $idMotivo == MOTIVO_REUNION_MODULO ||
            $idMotivo == MOTIVO_REUNION_MENSUAL || $idMotivo == MOTIVO_TRASPASO_CARTERA) {
        if ($idConcepto == CONCEPTO_PEAJE || $idConcepto == CONCEPTO_ESTACIONAMIENTO) {
            return FORM_VIATICO_3;
        } else {
            return FORM_VIATICO_2;
        }
    }
}

function obtener_coordenadas_ultima_visita($id_usuario, $id_solicitud) {

    $sql = "SELECT V.destino FROM solicitud_visita SV INNER JOIN solicitud S ON
	SV.id_solicitud = S.id INNER JOIN visita V ON SV.id_visita = V.id";

    $sql .= " WHERE SV.id_solicitud = " . $id_solicitud . " AND V.id_usuario = " .
            $id_usuario . " AND V.destino IS NOT NULL ORDER BY SV.id_visita DESC LIMIT 0,1";

    $datos = ejecutarConsulta($sql);

    if (!empty($datos[0]['destino']))
        return $datos[0]['destino'];
    else
        return '';
}

function insertar_viatico($datos_viatico) {

    $fecha_viatico = pasarFechaDatePickerAFormatoDate($datos_viatico['fecha']) . ' ' . date("H:i:s");
    $id_usuario = $datos_viatico['id_usuario'];
    $motivo_viatico = @ $datos_viatico['motivo'];
    $concepto_viatico = @ $datos_viatico['concepto'];
    $observaciones_viatico = @ $datos_viatico['observaciones'];
    $solicitud_activa = @ $datos_viatico['solicitud_activa'];
    $origen_viatico = @ $datos_viatico['origen'];
    $destino_viatico = @ $datos_viatico['destino'];
    $km_viatico = @ $datos_viatico['kms'];


    if (@ $datos_viatico['personas_por_almuerzo']) {
        $cantidad_personas_almuerzo = $datos_viatico['personas_por_almuerzo'];
    } else {
        $cantidad_personas_almuerzo = 0;
    }

    if ($km_viatico == '')
        $km_viatico = 0;

    $importe_viatico = @ $datos_viatico['importe'];

    if ($importe_viatico == '')
        $importe_viatico = 0;

    $solicitud_viatico = @ $datos_viatico['nro_solicitud'];
    $plazo_viatico = @ $datos_viatico['plazo'];
    $monto_viatico = @ $datos_viatico['monto'];
    $segmento_viatico = @ $datos_viatico['segmento'];

    $visita_exitosa = @ $datos_viatico['visita'];


    if (CONCEPTO_AUTO == $concepto_viatico || CONCEPTO_MOTO == $concepto_viatico) {

        $km_ida = @ $datos_viatico['km_ida'];
        $km_vuelta = @ $datos_viatico['km_vuelta'];
        $costo_ida = @ $datos_viatico['costo_ida'];
        $costo_vuelta = @ $datos_viatico['costo_vuelta'];
    }

    switch ($datos_viatico['formulario']) {

        case FORM_VIATICO_1:

            /*
              La queries espera estos parametros no distingue entre auto/moto o
              taxi/remis si no estaban estos parametros daba error
             */

            $sql = "INSERT INTO visita (id_motivo,id_estado,fecha,origen,destino,km,importe,nro_solicitud,monto,observacion,plazo,id_segmento,id_concepto,id_usuario,cargado_en_celular,visita_exitosa";

            if (CONCEPTO_AUTO == $concepto_viatico || CONCEPTO_MOTO == $concepto_viatico) {
                $sql .= ",costo_ida,costo_vuelta,km_ida,km_vuelta";
            }

            $sql .= ") VALUES (" . $motivo_viatico . ",1,'" . $fecha_viatico . "','" .
                    $origen_viatico . "','" . $destino_viatico . "'," . $km_viatico . ",'" .
                    $importe_viatico . "'," . $solicitud_viatico . "," . $monto_viatico . ",'" .
                    $observaciones_viatico . "','" . $plazo_viatico . "'," . $segmento_viatico .
                    "," . $concepto_viatico . "," . $id_usuario . ",0," . $visita_exitosa;

            if (CONCEPTO_AUTO == $concepto_viatico || CONCEPTO_MOTO == $concepto_viatico) {
                $sql .= "," . $costo_ida . "," . $costo_vuelta . "," . $km_ida . "," . $km_vuelta;
            }


            $sql .= ")";

            break;


        case FORM_VIATICO_2:

            $sql = "INSERT INTO visita (id_motivo,id_estado,fecha,origen,destino,km,importe,observacion,id_concepto,id_usuario,cargado_en_celular,visita_exitosa";

            if (empty($visita_exitosa)) {
                $visita_exitosa = 1;
            }
            if (empty($solicitud_viatico)) {
                $solicitud_viatico = 0;
            }
            if (CONCEPTO_AUTO == $concepto_viatico || CONCEPTO_MOTO == $concepto_viatico) {
                $sql .= ",costo_ida,costo_vuelta,km_ida,km_vuelta";
            }


            if (!empty($solicitud_viatico))
                $sql .= ',nro_solicitud';


            $sql .= ") VALUES (" . $motivo_viatico . ",1,'" . $fecha_viatico . "','" . $origen_viatico . "','" . $destino_viatico . "'," . $km_viatico . ",'" . $importe_viatico . "','" . $observaciones_viatico . "'," . $concepto_viatico . "," . $id_usuario . ",0," . $visita_exitosa;


            if (CONCEPTO_AUTO == $concepto_viatico || CONCEPTO_MOTO == $concepto_viatico) {
                $sql .= "," . $costo_ida . "," . $costo_vuelta . "," . $km_ida . "," . $km_vuelta;
            }

            if (!empty($solicitud_viatico))
                $sql .= ',' . $solicitud_viatico;

            $sql .= ")";

            break;

        case FORM_VIATICO_3:

            $sql = "INSERT INTO visita (id_motivo,id_estado,fecha,importe,observacion,id_concepto,id_usuario,cargado_en_celular,personas_por_almuerzo) VALUES (" .
                    $motivo_viatico . ",1,'" . $fecha_viatico . "'," . $importe_viatico . ",'" . $observaciones_viatico . "'," . $concepto_viatico . "," . $id_usuario . ",0," . $cantidad_personas_almuerzo . ")";

            break;
    }


    $rs = ejecutarConsulta($sql);

    if (in_array($datos_viatico['formulario'], array(FORM_VIATICO_1, FORM_VIATICO_2))) {
        if ($origen_viatico == VIATICO_ORIGEN_VISITA_ANTERIOR) {
            modificarCostosKmsViaticoAnterior($id_usuario, $solicitud_activa);
        }
    }

    if ($rs) {
        subirAttachViatico('form' . $datos_viatico['formulario'] . '_file_attach');
    }

    return $sql;
}

function modificarCostosKmsViaticoAnterior($id_usuario, $solicitud) {

    /*
      $viat_ant = buscar_viatico_tipovisita_anterior(ultimo_viatico_usuario($idUsuario), $solicitud, 'id_visita');
     */

    $viat_ant = buscar_viatico_anterior_con_km(ultimo_viatico_usuario($id_usuario), $solicitud);

    $sql = "UPDATE visita SET importe = costo_ida, km = km_ida, costo_vuelta = 0, km_vuelta = 0 WHERE id = " . $viat_ant;

    ejecutarConsulta($sql);
}

function insertar_solicitud_viatico($solicitud) {
    $sql = "INSERT INTO solicitud_visita (id_solicitud, id_visita) VALUES (" . $solicitud . "," . obtener_ultimo_id("visita") . ")";
    $rs = ejecutarConsulta($sql);

    return $rs;
}

function ponerIconoPCCelular($origen_carga) {
    if (VIATICO_CARGADO_POR_CELU == $origen_carga)
        $img = 'celu';
    else
        $img = 'pc';

    return '<img src="img/' . $img . '_rojo.png" alt="origen de carga" />';
}

/*

  function buscar_viatico_tipovisita_anterior($viatico_pto_partida, $solicitud, $devolver, $forma_carga = 'indistinto')
  {
  if($forma_carga == 'pc')
  $val_forma_carga = 0;
  else
  {
  if($forma_carga == 'celu')
  $val_forma_carga = 1;
  }

  $sql  = "SELECT SV.id_visita,V.origen,V.destino,V.costo_ida,V.costo_vuelta,V.km_ida,V.km_vuelta FROM solicitud_visita SV INNER JOIN solicitud S ON
  SV.id_solicitud = S.id INNER JOIN visita V ON SV.id_visita = V.id";

  $sql .= " WHERE SV.id_solicitud = ".$solicitud;

  if($forma_carga != 'indistinto')
  {
  $sql .= " AND cargado_en_celular = ".$val_forma_carga;
  }

  $sql .= " ORDER BY V.id DESC";
  $rs = ejecutarConsulta($sql);

  for($i = 0; $i < sizeof($rs); $i++)
  {

  //excluimos: tipos almuerzo, peajes, etc y los transp. publicos o taxi/remis
  if($rs[$i]['id_visita'] < $viatico_pto_partida &&
  (!empty($rs[$i]['origen']) && !empty($rs[$i]['destino']) &&
  !empty($rs[$i]['costo_ida']) && !empty($rs[$i]['costo_vuelta']) &&
  !empty($rs[$i]['km_ida']) && !empty($rs[$i]['km_vuelta'])))
  {
  return $rs[$i][$devolver];
  }

  }

  return '';

  }

 */

function buscar_viatico_anterior($viatico_pto_partida, $solicitud, $forma_carga = 'indistinto') {

    if ($forma_carga == 'pc')
        $val_forma_carga = 0;
    else {
        if ($forma_carga == 'celu')
            $val_forma_carga = 1;
    }

    $sql = "SELECT SV.id_visita FROM solicitud_visita SV INNER JOIN solicitud S ON
	SV.id_solicitud = S.id INNER JOIN visita V ON SV.id_visita = V.id";

    $sql .= " WHERE SV.id_solicitud = " . $solicitud;

    if ($forma_carga != 'indistinto') {
        $sql .= " AND cargado_en_celular = " . $val_forma_carga;
    }

    $sql .= " ORDER BY V.id DESC";

    $rs = ejecutarConsulta($sql);

    for ($i = 0; $i < sizeof($rs); $i++) {
        if ($rs[$i]['id_visita'] < $viatico_pto_partida) {
            return $rs[$i]['id_visita'];
        }
    }

    return '';
}

function buscar_viatico_anterior_con_km($viatico_pto_partida, $solicitud, $campo_devolver = 'id_visita', $forma_carga = 'indistinto') {
    if ($forma_carga == 'pc')
        $val_forma_carga = VIATICO_CARGADO_POR_PC;
    else {
        if ($forma_carga == 'celu')
            $val_forma_carga = VIATICO_CARGADO_POR_CELU;
    }

    $sql = "SELECT SV.id_visita";

    if ($campo_devolver == 'destino') {
        $sql .= ",V." . $campo_devolver;
    }

    $sql .= " FROM solicitud_visita SV INNER JOIN solicitud S ON
	SV.id_solicitud = S.id INNER JOIN visita V ON SV.id_visita = V.id";

    $sql .= " WHERE SV.id_solicitud = " . $solicitud;

    if ($forma_carga != 'indistinto') {
        $sql .= " AND cargado_en_celular = " . $val_forma_carga;
    }

    /*
      Excluimos:
      Almuerzos, cenas, guarderias, peajes, etc.
      Trans. Publico o taxi / remis
     */

    $sql .= " AND (costo_ida IS NOT NULL AND costo_vuelta IS NOT NULL AND km_ida IS NOT NULL AND km_vuelta IS NOT NULL)";

    $sql .= " ORDER BY V.id DESC";


    $rs = ejecutarConsulta($sql);

    for ($i = 0; $i < sizeof($rs); $i++) {
        if ($rs[$i]['id_visita'] < $viatico_pto_partida) {

            return $rs[$i][$campo_devolver];
        }
    }

    return '';
}

// Funciones relativas al tope de los almuerzos:
/* $tope es el tope que quiero si almuerzo o cena */
function obtener_tope_almuerzo_cena_segun_sucursal($string_suc, $tope) {
    $sql = "SELECT $tope from topes_reestricciones where descripcion = '$string_suc' ";
    $rs = ejecutarConsultaPostgre($sql);
    return $rs[0][$tope];
}

function update_topes_almuerzos_cenas_BASE_BANCO($tope,$sucursal,$tipo) {

    $sql = "UPDATE topes_reestricciones SET $tipo=$tope where descripcion ='casa_matriz' ";
    ejecutarConsultaPostgre($sql);
/*
    $ids_update = array();
    $id_suc = ID_CASA_MATRIZ; //por ahora solo anda esto para casa matriz..

    $sql = "SELECT personalid FROM personal_asistentecomercial WHERE pk_sucursal
	= " . $id_suc;

    $rs = ejecutarConsultaPostgre($sql);

    foreach ($rs as $id) {
        $ids_update[] = $id['personalid'];
    }

    $sql = "SELECT pk_personal FROM personal_ejecutivocomercial WHERE 	
	pk_sucursal = " . $id_suc;

    $rs = ejecutarConsultaPostgre($sql);

    foreach ($rs as $id) {
        $ids_update[] = $id['pk_personal'];
    }

    $sql = "UPDATE personal SET tope_almuerzo = " . $tope . " WHERE personalid IN ("
            . implode(",", $ids_update) . ")";

    ejecutarConsultaPostgre($sql);*/
}

// FIN funciones relativas al tope de los almuerzos.
function valorVisitaCelular($id) {

    $sql = "SELECT visita_exitosa FROM visita WHERE id=" . $id;
    $rs = ejecutarConsulta($sql);


    $estado_visita = $rs[0]['visita_exitosa'];


    if (is_null($estado_visita)) {

        $estado_visita = "-";
    } else {


        switch ($estado_visita) {

            case 1:
                $estado_visita = "SI";
                break;

            case 0:
                $estado_visita = "NO";
                break;
        }
    }

    return $estado_visita;
}
