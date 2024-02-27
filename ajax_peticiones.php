<?php
require 'inc/checkLogin.php';
require 'inc/const.php';

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

if($_POST['accion'] == 'eliminar_attach_viatico')
{
	eliminarAttachViatico($_POST['id_viatico']);
}

if($_POST['accion'] == 'eliminar_rendicion_mensual')
{
	eliminar_solicitud_completa($_POST['id_solicitud']);
}

if($_POST['accion'] == 'ok_todas_rendiciones_tildadas')
{
	cambiar_estado_array_solicitudes($_POST['ids_rendiciones'],
	$_POST['setear_nuevo_estado']);
}

if($_POST['accion'] == 'update_tope_almuerzo_cena')
{
	update_topes_almuerzos_cenas_BASE_BANCO($_POST['suc'],$_POST['tope']);
}

if($_POST['accion'] == 'update_costo_km')
{
	update_costo_km_BASE_BANCO($_POST['costo_auto'],$_POST['costo_moto'],$_POST['id_zona']);
}

if($_POST['accion'] == 'obtener_coord_viatico_anterior')
{
	echo buscar_viatico_tipovisita_anterior($_POST['id_viatico'],$_POST['solicitud'],'destino');
}

if($_POST['accion'] == 'obtener_coord_visita_anterior')
{
	echo obtener_coordenadas_ultima_visita($_POST['usuario'],$_POST['solicitud']);
}

if($_POST['accion'] == 'editar_viatico')
{
   editar_viatico($_POST);
}

if ($_POST['accion'] == 'agregar_usuario')
{
    if (!insertar_usuario($_POST))
		echo 'error';
    else
		echo 'success';
}

if ($_POST['accion'] == 'agregar_viatico')
{
    if (!insertar_viatico($_POST))
		echo 'error';
	else
	{
        insertar_solicitud_viatico($_POST['solicitud_activa']);
        echo 'success';
    }
}

if($_POST['accion'] == 'eliminar_viatico')
{
	echo eliminar_viatico($_POST['id_viatico'], $_POST['id_solicitud']);
}

if ($_POST['accion'] == 'modificar_usuario') {
    if (!obtener_usuario($_POST['id_usuario']))
        echo 'error';
    else
        echo 'success';
}

if ($_POST['accion'] == 'cambiar_id_solicitud') {
    $sql = 'UPDATE solicitud SET estado_id = '.SOLICITUD_PRESENTADA.' ,fecha_presentacion = now() WHERE id = ' . $_POST['solicitud_activa'];
    ejecutarConsulta($sql);
}

if ($_POST['accion'] == 'volver_presentar_planilla') {
    $sql = 'UPDATE solicitud SET rechazada = 0 WHERE id = ' .
            $_POST['solicitud_activa'];

    ejecutarConsulta($sql);
}

if ($_POST['accion'] == 'validar_usuario') {
    $datos_consulta = obtener_usuarios_login_BASE_BANCO($_POST['email']);

    if (!$datos_consulta)
        echo 'error';
    else {
        iniciarSesion($datos_consulta);
        echo 'success';
    }
}

if ($_POST['accion'] == 'cambiar_estado') {
    $estado_a_cambiar = $_POST['estado'];
    $sql = 'UPDATE solicitud SET estado_id = ' . $estado_a_cambiar . ' WHERE id = ' . $_POST['solicitud_activa'];

    ejecutarConsulta($sql);
}

if ($_POST['accion'] == 'rechazar_solicitud') {
    if (isset($_POST['rechazada_todo'])) {
        $sql = 'UPDATE solicitud SET rechazada = 1 ,observacion = "Su solicitud ha sido rechazada completamente , la misma va a volver a pasar por todo el proceso de revision luego de que la modifique y la vuelva a presentarla.",estado_id="' . SOLICITUD_PRESENTADA . '" WHERE id = ' . $_POST['solicitud_activa'];
    } else {
        $sql = 'UPDATE solicitud SET rechazada = 1 , observacion = "' . $_POST['comentario'] . '" WHERE id = ' . $_POST['solicitud_activa'];
    }

    ejecutarConsulta($sql);
}
if ($_POST['accion'] == 'insertar_comentario_historial') {
    $id_solicitud = $_POST['id_solicitud'];
    $comentario = $_POST['comentario'];
    $usuario = $_POST['usuario'];
    $estado = obtener_solicitudes_por_id($id_solicitud);
    $estado_solicitud = $estado[0]['estado_id'];
    $actual = date("d-m-Y H:i:s");

    $sql = "INSERT INTO historial (id_solicitud , usuario , comentario,estado_solicitud,fecha)
VALUES ($id_solicitud, $usuario, '$comentario' ,$estado_solicitud,'$actual');";

    echo $sql;

    ejecutarConsulta($sql);
}

if($_POST['accion'] == 'edit_viatico_modal')
{
    
	$partes_viatico = explode('_', $_POST['id_viatico']);

    $viatico = obtener_viatico($partes_viatico[1]);

	
	$ids_motivos_sistema = array(MOTIVO_EVAL_TERRENO,MOTIVO_REUNION_MENSUAL,MOTIVO_CAPACITACION,MOTIVO_TRASPASO_CARTERA,MOTIVO_REUNION_MODULO,MOTIVO_OTRAS_REUNIONES,MOTIVO_BENEFICIO);
		
if($viatico[0]['id_motivo'] == MOTIVO_BENEFICIO)
	{
		$ids_conceptos_sistema[] = CONCEPTO_ALOJAMIENTO;
		$ids_conceptos_sistema[] = CONCEPTO_GUARDERIA;
		$ids_conceptos_sistema[] = CONCEPTO_REFRIGERIO;
		$ids_conceptos_sistema[] = CONCEPTO_ALMUERZO;
		$ids_conceptos_sistema[] = CONCEPTO_CENA;
		$ids_conceptos_sistema[] = CONCEPTO_ESTACIONAMIENTO;
	}
	else
	{
	
		$ids_conceptos_sistema = array(CONCEPTO_AUTO,CONCEPTO_MOTO,
		CONCEPTO_TAXI_REMIS,CONCEPTO_TRANS_PUBLICO,CONCEPTO_PEAJE,
		CONCEPTO_ESTACIONAMIENTO);
	
		if($viatico[0]['id_motivo'] == MOTIVO_CAPACITACION)
		{
			$ids_conceptos_sistema[] = CONCEPTO_ALOJAMIENTO;
			$ids_conceptos_sistema[] = CONCEPTO_ALMUERZO;
			$ids_conceptos_sistema[] = CONCEPTO_CENA;
		}
	}
	
    $motivos   = obtener_motivos($ids_motivos_sistema);
	
    $conceptos = obtener_conceptos($ids_conceptos_sistema);
?>
    <div id="myModal" class="reveal-modal">
        <h1>Modificar Vi&aacute;tico</h1>

        <div class="primer_carga" style="display:inline;">
            <table id="nueva">

                <tr>

                    <td style="margin-left: -4%"><p>Fecha</p>

      <input id="edit_fecha_viatico" class="text" type="text"
      style="width: 80px;"
      value="<?= presentarFechaSinHora($viatico[0]['fecha'],$sepActual = '-',$sepNuevo = '/'); ?>" />

                    </td>

                    <td>
                    
                    	<p>Motivo</p>

                        <select id="edit_motivo_viatico_select" class="text"
                        style="width:180px; height: 26px!important;">

                            <option value="">Seleccione</option>     
						
					<?php	
						
    				foreach ($motivos as $row)
					{
					?>
                    
         <option value="<?= $row['id']; ?>"<?php if ($viatico[0]['id_motivo'] ==$row['id']) echo ' selected'; ?>><?= utf8_encode($row['descripcion']); ?></option>
                    
					<?php
					}
					?>     

                        </select>

                    </td>

                    <td>
                    
                    	<p>Concepto</p>

                       <select id="edit_concepto_viatico_select"
                       class="text" style="width:180px;height: 26px!important;">

                        <option value="">Seleccione</option>

                       	<?php
                       	foreach ($conceptos as $row)
						{	
						?>
                        	 <option value="<?= $row['id']; ?>"<?php if($viatico[0]['id_concepto'] == $row['id']) echo ' selected'; ?>><?= utf8_encode($row['descripcion']); ?></option>
                        <?php
						}
						?>

                        </select>

                    </td>


                    <td style="margin-left: -2%"><p>Observaciones</p>

                        <input id="edit_observaciones_viatico" class="text" type="text"
                               style="width: 550px;" value="<?= $viatico[0]['observacion']; ?>" />

                    </td>

                </tr>

            </table>
            
            <?php
			
			// Decido que formulario mostrar de ENTRADA (El 1, 2 o 3).
			
			$nro_form_mostrar = 
			formu_viatico_visibilidad($viatico[0]['id_motivo'],
			$viatico[0]['id_concepto']);
			
			?>
           
            <!-- FORMULARIO 1 -->
            
            <table>
                <tr id="form_1_edit" style="margin-top: -1.8%;
         display:<?php if($nro_form_mostrar == FORM_VIATICO_1) echo 'inline';
		 else echo 'none'; ?>;">

                    <td style="margin-left: -0.6%"><p>Origen</p> 

      <span style="font-family: Arial; font-size: 12px; text-decoration: none;">

          <?php
                   if($viatico[0]['origen'] == VIATICO_ORIGEN_SUCURSAL)
                   		echo 'DESDE SUCURSAL';
                   else
                   		echo 'DESDE VISITA ANTERIOR';
           ?>

       </span>				

                        <!--
                        <select id="form1_edit_origen_viatico_select" class="text"
    style="width:100px;height: 26px!important;">

    <option value="1" <?php if ($viatico[0]['origen'] == 1) echo "selected"; ?>>Sucursal</option>

    <option value="2" <?php if ($viatico[0]['origen'] == 2) echo "selected"; ?>>Visita anterior</option>            

    </select>
                        -->

                    </td>

                    <td style="margin-left: -2%"><p>Destino</p>

                        <input  class="text" type="text" id="form1_edit_destino_viatico"
                                value="<?php echo $viatico[0]['destino']; ?>"
                                style="width: 80px;" />

                    </td>

                    <td style="margin-left: -2%"><p>KM</p>

                        <input class="text" type="text" id="form1_edit_km_viatico" 
                               value="<?php echo $viatico[0]['km']; ?>"
                               style="width: 80px;" />

                    </td>

                    <td style="margin-left: 0%"><p>Importe</p>

                        <input class="text" type="text" id="form1_edit_importe_viatico"
                               value="<?php echo $viatico[0]['importe']; ?>"
                               style="width: 80px;" />

                    </td>

                    <td style="margin-left: -3%"><p>Nro solicitud</p>

                        <input class="text" type="text" id="form1_edit_solicitud_viatico"
                               value="<?php echo $viatico[0]['nro_solicitud']; ?>"
                               style="width: 80px;"/>

                    </td>

                    <td style="margin-left: -2%"><p>Monto</p>

                        <input class="text" type="text" id="form1_edit_monto_viatico"
                               value="<?php echo $viatico[0]['monto']; ?>"
                               style="width: 80px;"/>

                    </td>


                    <td style="margin-left: -2%"><p>Plazo</p>
                        <input class="text" type="text" id="form1_edit_plazo_viatico"
                               value="<?php echo $viatico[0]['plazo']; ?>"
                               style="width: 80px;"  />

                    </td>


                    <td style="margin-left: -2%" ><p>Segmento</p> 

                        <select class="text" style="height: 26px!important;"
                                id="form1_edit_segmento_viatico_select">

                            <?php
                            $segmentos = obtener_segmentos();

                            foreach ($segmentos as $row) {

                                echo '<option value="' . $row['id'] . '"';

                                if ($viatico[0]['id_segmento'] == $row['id'])
                                    echo ' selected';

                                echo '>' . $row['descripcion'] . "</option>";
                            }
                            ?>          

                        </select>

                    </td>


                    <td style="margin-top:48px;">

                        <br />
                      <br />
                    <input class="botones_nueva" type="button" value="Cancelar"
                               onclick=" $('#myModal').trigger('reveal:close');"
                               style="height: 26px!important;line-height: 4px;" />

                        <input id="boton_guardado_form_1_edit" class="botones_guardar" type="button" value="Guardar"
                               style="margin-left: 10px;height: 26px!important;line-height: 4px;" />

                    </td>


                </tr>
            </table>
            
            
            <!-- FORMULARIO 2 --> 

            <table>
               
                <tr id="form_2_edit" style="margin-top: -1.8%;
                display:<?php if($nro_form_mostrar == FORM_VIATICO_2)
				echo 'inline'; else echo 'none'; ?>;">

                    <td style="margin-left: -0.6%"><p>Origen</p> 

                        <span style="font-family: Arial; font-size: 12px; text-decoration: none;">
                            <?php
                            if ($viatico[0]['origen'] == VIATICO_ORIGEN_SUCURSAL)
                                echo 'Desde Sucursal';
                            else
                                echo 'Desde Visita Anterior';
                            ?>

                        </span>

                        <!--
                        <select id="form2_edit_origen_viatico_select" class="text"
        style="width:100px;height: 26px!important;">

    <option value="1" <?php if ($viatico[0]['origen'] == 1) echo "selected"; ?>>Sucursal</option>

    <option value="2" <?php if ($viatico[0]['origen'] == 2) echo "selected"; ?>>Visita anterior</option>            

    </select>
                        -->

                    </td>

                    <td style="margin-left: -2%"><p>Destino</p>

                        <input class="text" type="text" id="form2_edit_destino_viatico"
                               value="<?php echo $viatico[0]['destino']; ?>" style="width: 80px;" />

                    </td>

                    <td style="margin-left: -2%"><p>KM</p>

                        <input class="text" type="text" id="form2_edit_km_viatico" 
                               value="<?php echo $viatico[0]['km']; ?>"
                               style="width: 80px;" />

                    </td>

                    <td style="margin-left: 0%"><p>Importe</p>

                        <input class="text" type="text" id="form2_edit_importe_viatico"
                               value="<?php echo $viatico[0]['importe']; ?>"
                               style="width: 80px;" />

                    </td>

                    <td style="margin-left: -3%"><p>n solicitud</p>

                        <input class="text" type="text" id="form2_edit_solicitud_viatico"
                               value="<?php echo $viatico[0]['nro_solicitud']; ?>"
                               style="width: 80px;" />

                    </td>

                    <td style="margin-top:48px;margin-left:29.3%">

                        <br />
                      <br />
                    <input class="botones_nueva" type="button" value="Cancelar"
                               onclick=" $('#myModal').trigger('reveal:close');"
                               style="height: 26px!important;line-height: 4px;" />

                        <input id="boton_guardado_form_2_edit" class="botones_guardar" type="button"
                               value="Guardar"
                               style="margin-left: 10px;height: 26px!important;line-height: 4px;" />

                    </td>


                </tr>
            </table>
            
            <!-- FORMULARIO 3 -->
            
            <table>
                
                <tr id="form_3_edit" style="margin-top: -1.8%;
                display:<?php if($nro_form_mostrar == FORM_VIATICO_3)
				echo 'inline'; else echo 'none'; ?>;padding-bottom:30px;" >

                    <td style="margin-left: -2%"><p>Importe
                        <br />
                      <input class="text" type="text" id="form3_edit_importe_viatico"
                               style="width: 80px;" value="<?= $viatico[0]['importe']; ?>"/>

                    </p></td>

                    <td style="margin-top:48px;margin-left:0%">

                        <br />
                        <input type="file" id="form3_edit_attach" />
                        
                        <?php
						
						if(elViaticoTieneAttach($partes_viatico[1]))
						{
						?>
	<span
	onclick="window.open('admin/attachs/<?= nombreAttachViatico($partes_viatico[1]); ?>');" style="cursor: pointer; color: blue;">
    	&nbsp;&nbsp;VER ACTUAL ATTACH
    </span>
                        <?php
						}
						?>

                    </td>

                    <td style="margin-top:48px;margin-left:45.3%"><br />
              <input class="botones_nueva cancelar" type="button" value="Cancelar"
                               onclick=" $('#myModal').trigger('reveal:close');"
                               style="height: 26px!important;line-height: 4px;" />

                        <input id="boton_guardado_form_3_edit" class="botones_guardar" type="button"
                               style="margin-left: 10px;height: 26px!important;line-height: 4px;"
                               value="Guardar" />

                    </td>

                </tr>

            </table>



        </div>

        <!--<a class="close-reveal-modal">&#215;</a>-->
    </div>

    <input type="hidden" id="formulario_editado" />
    <input type="hidden" id="id_viatico" value="<?= $partes_viatico[1]; ?>" />

<script type="text/javascript" src="../js/funciones_motivos_conceptos.js"></script>
	
	<script type="text/javascript">

    primer_carga_pantalla_edicion = true; //var global !!!!
		
	$("#edit_motivo_viatico_select").change(function() {

            if ($("#edit_motivo_viatico_select").select().val() == '')
            {
                visibilidadFormsViaticos('ninguno', true);
				resetearConceptos('edit_concepto_viatico_select');
                return false;
            }
            else
            {

	var motivo_seleccionado = $("#edit_motivo_viatico_select").select().val();
  
  	if(!primer_carga_pantalla_edicion)
	{
	
	resetearConceptos('edit_concepto_viatico_select');
	agregarItemsConceptos(motivo_seleccionado,'edit_concepto_viatico_select');
	
	}
	else
	{
		primer_carga_pantalla_edicion = false; //la doy vuelta..	
	}
	
  
  var concepto_seleccionado = $("#edit_concepto_viatico_select").select().val();

                if(concepto_seleccionado == '')
                {
                    visibilidadFormsViaticos('ninguno',true);
                    return false;
                }

                var array_conceptos_transportes = new Array(CONCEPTO_AUTO,
				CONCEPTO_MOTO,CONCEPTO_TAXI_REMIS, CONCEPTO_TRANS_PUB);

                var MOTIVO_EVALUACION_TERRENO = 1;
                var MOTIVO_VISITA_COBRANZA = 2;

                if (MOTIVO_EVALUACION_TERRENO == motivo_seleccionado)
                {

                    if (estaEnArray(concepto_seleccionado, array_conceptos_transportes))
                    {
                        visibilidadFormsViaticos(1, true);
                        $('#formulario_editado').val(1);
                    }
                    else
                    {
                        visibilidadFormsViaticos(3, true);
                        $('#formulario_editado').val(3);
                    }

                    return true;

                }

                if (MOTIVO_VISITA_COBRANZA == motivo_seleccionado)
                {

                    if (estaEnArray(concepto_seleccionado, array_conceptos_transportes))
                    {
                        visibilidadFormsViaticos(2, true);
                        $('#formulario_editado').val(2);
                    }
                    else
                    {
                        visibilidadFormsViaticos(3, true);
                        $('#formulario_editado').val(3);
                    }

                    return true;

                }

                // Todos los otros motivos:
                if (MOTIVO_EVALUACION_TERRENO != motivo_seleccionado &&
                        MOTIVO_VISITA_COBRANZA != motivo_seleccionado)
                {
                    if (estaEnArray(concepto_seleccionado, array_conceptos_transportes))
                    {
                        visibilidadFormsViaticos(2, true);
                        $('#formulario_editado').val(2);
                        return true;
                    }
                    else
                    {
                        visibilidadFormsViaticos(3, true);
                        $('#formulario_editado').val(3);
                        return true;
                    }

                }

            }

        });
		


        $('#edit_concepto_viatico_select').change(function() {

            var motivo_seleccionado = $("#edit_motivo_viatico_select").select().val();
            var concepto_seleccionado = $("#edit_concepto_viatico_select").select().val();

            if (motivo_seleccionado == '' || concepto_seleccionado == '')
            {
                visibilidadFormsViaticos('ninguno', true);
                return false;
            }

            var json_conceptos_viativo = $.parseJSON(<?php echo(json_conceptos_viaticos()); ?>);

            var CONCEPTO_AUTO = json_conceptos_viativo.Auto;
            var CONCEPTO_MOTO = json_conceptos_viativo.Moto;
            var CONCEPTO_TAXI_REMIS = json_conceptos_viativo.taxi_remis;
            var CONCEPTO_TRANS_PUB = json_conceptos_viativo.transporte_publico;

            var array_conceptos_transportes = new Array(CONCEPTO_AUTO,
                    CONCEPTO_MOTO, CONCEPTO_TAXI_REMIS, CONCEPTO_TRANS_PUB);

            var MOTIVO_EVALUACION_TERRENO = 1;
            var MOTIVO_VISITA_COBRANZA = 2;

            if (MOTIVO_EVALUACION_TERRENO == motivo_seleccionado)
            {

                if (estaEnArray(concepto_seleccionado, array_conceptos_transportes))
                {
                    visibilidadFormsViaticos(1, true);
                    $('#formulario_editado').val(1);
                }
                else
                {
                    visibilidadFormsViaticos(3, true);
                    $('#formulario_editado').val(3);
                }

                return true;

            }

            if (MOTIVO_VISITA_COBRANZA == motivo_seleccionado)
            {

                if (estaEnArray(concepto_seleccionado, array_conceptos_transportes))
                {
                    visibilidadFormsViaticos(2, true);
                    $('#formulario_editado').val(2);
                }
                else
                {
                    visibilidadFormsViaticos(3, true);
                    $('#formulario_editado').val(3);
                }

                return true;

            }

            // Todos los otros motivos:
            if (MOTIVO_EVALUACION_TERRENO != motivo_seleccionado &&
                    MOTIVO_VISITA_COBRANZA != motivo_seleccionado)
            {
                if (estaEnArray(concepto_seleccionado, array_conceptos_transportes))
                {
                    visibilidadFormsViaticos(2, true);
                    $('#formulario_editado').val(2);

                    return true;
                }
                else
                {
                    visibilidadFormsViaticos(3, true);
                    $('#formulario_editado').val(3);

                    return true;
                }

            }


        });

        //Esto es para que venga ya desplegado el form: 1, 2 o 3.
        	 $('#edit_motivo_viatico_select').trigger('change');
        	 $('#edit_concepto_viatico_select').trigger('change');
        // fin despliegue


        $("#boton_guardado_form_1_edit,#boton_guardado_form_2_edit,#boton_guardado_form_3_edit").click(function() {

    var form_editado = $('#formulario_editado').val();

    switch (form_editado)
    {
          case '1':

          var parametros =
          {
    
	"origen_viatico": $('#form1_edit_origen_viatico_select').select().val(),
    "destino_viatico": $('#form1_edit_destino_viatico').val(),
    "km_viatico": $('#form1_edit_km_viatico').val(),
    "importe_viatico": $('#form1_edit_importe_viatico').val(),
    "monto_viatico": $('#form1_edit_monto_viatico').val(),
    "plazo_viatico": $('#form1_edit_plazo_viatico').val(),
    "segmento_viatico": $('#form1_edit_segmento_viatico_select').select().val(),
    "solicitud_viatico": $('#form1_edit_solicitud_viatico').val()

           }

           break;

           
		   case '2':

           var parametros =
           {
        
	"origen_viatico": $('#form2_edit_origen_viatico_select').select().val(),
    "destino_viatico": $('#form2_edit_destino_viatico').val(),
    "km_viatico": $('#form2_edit_km_viatico').val(),
    "importe_viatico": $('#form2_edit_importe_viatico').val(),
    "solicitud_viatico": $('#form2_edit_solicitud_viatico').val()

           }

           break;

                        
			case '3':
						
			
			var formData3 = new FormData();
        				
			formData3.append('importe',$('#form3_edit_importe_viatico').val());
	
			if($('#form3_edit_attach')[0].files[0])
    		{
    	        var file_attach = $('#form3_edit_attach')[0].files[0];
    	        formData3.append('form3_file_attach', file_attach);
    		}

            break;

    }
					
	if(form_editado == '1' || form_editado == '2')	
	{			

parametros.fecha_viatico = $('#edit_fecha_viatico').val();
parametros.obs_viatico = $('#edit_observaciones_viatico').val();
parametros.motivo_viatico = $('#edit_motivo_viatico_select').select().val();
parametros.concepto_viatico = $('#edit_concepto_viatico_select').select().val();
parametros.form_editado = form_editado;
parametros.accion = 'editar_viatico';
parametros.id_viatico = $('#id_viatico').val();

$.ajax({
          
		  data: parametros,
          url: 'admin/ajax_peticiones.php',
          type: 'post',
          success: function(response)
		  {

              $('#myModal').trigger('reveal:close');

              setTimeout(function() {	location.reload();	}, 750);
                            
			  return false;

          }

       });

	
	}

	if(form_editado == '3')	
	{
	
formData3.append('fecha_viatico',$('#edit_fecha_viatico').val());
formData3.append('obs_viatico',$('#edit_observaciones_viatico').val());
formData3.append('motivo_viatico',$('#edit_motivo_viatico_select').val());
formData3.append('concepto_viatico',$('#edit_concepto_viatico_select').val());
formData3.append('form_editado',form_editado);
formData3.append('accion','editar_viatico');
formData3.append('id_viatico',$('#id_viatico').val());

	jQuery.ajax({
                
			url: 'admin/ajax_peticiones.php',
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            data: formData3,
            success: function(response)
            {
			 	$('#myModal').trigger('reveal:close');
              	setTimeout(function() {	location.reload();	}, 750);
                            
			  	return false;

            }

            	});
	
	}
	
	});

    </script>
    <?php

}

if($_POST['accion'] == 'chequear_numero_solicitud')
{
	$rs = existe_numero_solicitud($_POST['nro_solicitud']);
    
	if($rs)
	{
      echo 'succes';
    }
	else
	{
      echo 'error';
    }
}

?>