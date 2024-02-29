<?php

require_once 'inc/checkLogin.php';
require_once 'admin/inc/const.php';

$id_usuario = $_SESSION['id_usuario'];

require_once 'admin/inc/funciones/all_functions.php';
require_once 'inc/header.php';

$solicitudes_a_gestionar = obtener_mis_solicitudes_a_gestionar_BASE_BANCO($_SESSION['id_usuario'], $_SESSION['id_estados_gestionar']);

if ($solicitudes_a_gestionar)
{
    ?>
    <h1 style="color:gray;">RENDICIONES LISTAS PARA EXPORTAR</h1>
    <style>
  
        .gestionar{display:none;}
    </style>
    <br />

    <div style="background-color: #eeeced;margin-top:-2%;">

        <table id="table_datatable" class="display" cellspacing="0" width=" width: 800px; margin: 0 auto;">
            
			<thead style="text-align: center!important;">
                
				<tr style="text-align: center!important;">

                    <th style="text-align: left!important;">Seleccionar</th>
                    <th style="text-align: left!important;">Usuario</th>
                    <th style="text-align: left!important;">Fecha Presentacion</th>
                    <th style="text-align: left!important;">Mes correspondiente</th>
                    <th style="text-align: left!important;">A&ntilde;o correspondiente</th>
                    <th style="text-align: left!important;">Estado</th>
                    <th style="text-align: left!important;">Importe</th>
                    <th style="text-align: left!important;">comentario</th>
                    <th style="text-align: left!important;">&nbsp;</th>

                </tr>
            
			</thead>

            <tbody>
                <?php
                $divsComentarios = '';

                if($solicitudes_a_gestionar)
				{
                    foreach ($solicitudes_a_gestionar as $row)
					{
                        $usuario = obtener_nombre_usuario_BASE_BANCO($row["id_usuarios"]);
                        $importe = obtener_importe_solicitud($row["id"]);

                        //echo( obtener_importe_solicitud($row["id"]));
                        echo("<tr id='" . $row["id"] . "'>");
                        echo("<td><input class='seleccion_importar' type='checkbox' value='" . $row["id"] . "'/></td>");
                        echo("<td>" . $usuario[0]['apellido'] . " " . $usuario[0]['nombre'] . "</td>");
                        echo("<td>" . guion($row["fecha_presentacion"]) . "</td>");
                        echo("<td>" . guion(obtener_nombre_mes($row["mes"])) . "</td>");
                        echo("<td>" . guion($row["ano"]) . "</td>");
                        echo("<td>" . guion(obtener_estado_por_id($row["estado_id"])) . "</td>");
                        echo("<td>" . $importe[0]["importe"] . "</td>");
                        
						if(!empty($row["observacion"]))
						{

                            echo('<td class="ver_comentario" id="' . $row["id"] . '">');
                            echo('<span style="cursor: pointer;">Click aqui</span></td>');
                            $divsComentarios .= '<div title="Observaciones:" id="comentario_' .
                                    $row["id"] . '" style="display:none;background-color:white;">' .
                                    $row["observacion"] . "</div>\n";
                        }
						else
                            echo('<td> - </td>');
                        
                        echo("<td> <input id='" . $row['id'] . "' class='botones ver_solicitud' type='button' value='Ver'/></td>");
                        echo("</tr>");
                        ?>

                        <?php
                    }
                }
                ?>  

            </tbody>
        </table>

        <?= $divsComentarios; ?>

    </div>
    <br/>    <br/>
    <input id='exportar_seleccionados' class='botones exportar' type='button' style='width: 150px;' value='Exportar seleccionados'/>
    <div id="tabla_solicitudes_detalles" class="reveal-modal"> </div>
    <div id="exportar_seleccion" > </div>

    <script type="text/javascript">

        $(document).ready(function()
        {
            $(function() {
                $.datepicker.regional['es'] = {
                  
                $("#fecha_viatico").datepicker();
            });

            $(".ver_comentario").click(
                    function()
                    {
                        var id_td_comentario = (this.id).split("_");

                        $("#comentario_" + id_td_comentario).dialog({
                         resizable: false,
                        height: 150,
                        width:450,
                        modal: true,
                        }
                            show: {effect: 'blind', duration: 400},
                            hide: {effect: 'explode', duration: 1000},
                            resizable: false
                        });
                    }
            );

            $(".ver_solicitud").click(
                    function()
                    {
                        var id_solicitud = (this.id);

                        $("#tabla_solicitudes_detalles").load("tabla_solicitudes_detalles_exportar.php?id_solicitud=" + id_solicitud + "");
                        $('#tabla_solicitudes_detalles').reveal('open');
                    }
            );
            $("#exportar_seleccionados").click(
                    function()
                    {
                        var val = [];
                        //selecciono los que quiero impoirtar
                        $(':checkbox:checked').each(function(i) {
                            val[i] = $(this).val();
                        });
                         $("#exportar_seleccion").load("exportar_solicitudes_aprobadas.php?id_solicitudes=" + val + "");
                    }
            );
        });

    </script>
<?php }; ?>