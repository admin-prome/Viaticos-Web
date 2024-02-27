<?php
$solicitudes = usuario_solicitud_pendiente($id_usuario);

if ($solicitudes)
    $solicitudes_pendientes = 1;
else
    $solicitudes_pendientes = 0;

if ($solicitudes)
    $solicitud_activa = $solicitudes[0]['id'];


$solicitudes_presentadas = usuario_solicitudes_presentadas($id_usuario);
$solicitudes_autorizada = usuario_solicitudes_autorizada($id_usuario);
$solicitudes_revisadas = usuario_solicitudes_revisadas($id_usuario);
$solicitudes_aprobada = usuario_solicitudes_aprobadas($id_usuario);
$solicitudes_rechazadas = usuario_solicitudes_rechazada($id_usuario);
$solicitudes_exportadas = usuario_solicitudes_exportadas($id_usuario);

if ($_SESSION['id_estados_gestionar'] == SOLICITUD_AUTORIZADA || $_SESSION['id_estados_gestionar'] == SOLICITUD_REVISADA) {
    $solicitudes_a_gestionar = obtener_mis_solicitudes_a_gestionar_revisar_aprobar_BASE_BANCO($_SESSION['id_estados_gestionar']);
} else {
    $solicitudes_a_gestionar = obtener_mis_solicitudes_a_gestionar_BASE_BANCO($id_usuario, $_SESSION['id_estados_gestionar']);
}
?>

<div class="titulo_rendicion">
    <h4>RENDICIONES PROPIAS</h4>
</div>
<div id="menu">
    <ul>
        <li class="botones_nueva"
        style="width:32px;margin-left: -30px;border:none;margin-top:23.5px;">
            
        <a href="home.php">
          <img style="margin-left:4px;margin-top:3px;" src="img/home_ico.png"
          border="0" />
        </a>
        
        </li>
                      
   		<?php
        if ($_SESSION['id_usuario'] == ADMINISTRADOR_1 ||
		$_SESSION['id_usuario']== ADMINISTRADOR_2)
		{
        ?>
        
        <li class="botones_nueva"
        style="width:50px;margin-left:5px;border:none;margin-top:23.5px;">
        
        <input style="width: 50px;" class="botones_nueva" type="button"
        value="Admin" onclick="window.location = 'admin/exportar.php';" />
        
        </li>
        
		<?php 
		}
		
		if($solicitudes_pendientes)
			$claseBoton = 'botones_disabled';
		else
			$claseBoton = 'botones_nueva';
		?>
   
        <li>
        
        <input style="width: 105px;" class="<?= $claseBoton; ?>"
        type="button" value="Nueva Rendicion"
        onclick="<?php if (!$solicitudes_pendientes) { ?> window.location =
        'nuevo_viatico.php?solicitud=nueva' <?php } ?>" />

        </li>
        
        <li>
            
            <?php
			
			if($solicitudes_pendientes)
				$claseBoton = 'botones_nueva';
			else
				$claseBoton = 'botones_disabled';
			
			?>
            
            <input class="<?= $claseBoton; ?>" type="button"
            value="Nuevo viatico"
            onclick="<?php if($solicitudes_pendientes) { ?> window.location =
        	'nuevo_viatico.php' <?php } ?>" />
            
            <input type="text" class="text_menu" readonly="readonly"
            placeholder="<?php if($solicitudes_pendientes) echo count($solicitudes_pendientes); else echo 0; ?>" />

        </li>
        
        <li>
        
        	<?php
			
			if($solicitudes_presentadas)
				$claseBoton = 'botones_nueva';
			else
				$claseBoton = 'botones_disabled';
			
			?>
            
            <input class="<?= $claseBoton; ?>" type="button"
            value="Presentadas"
            onclick="<?php if($solicitudes_presentadas) { ?> window.location =
			'solicitudes_presentadas.php' <?php } ?>"/>

			<input type="text" class="text_menu" readonly="readonly"
            placeholder="<?php if($solicitudes_presentadas) echo count($solicitudes_presentadas); else echo 0; ?>" />
		
        </li>
        
        <li><a href="<?php
                     if ($solicitudes_autorizada) {
                         echo("solicitudes_autorizadas.php");
                     } else {
                         echo("#");
                     }
                     ?>"><input class="<?php
               if ($solicitudes_autorizada) {
                   echo("botones_nueva");
               } else {
                   echo("botones_disabled");
               }
               ?>" type="button" value="Autorizada"/></a><input type="text" class="text_menu" readonly="readonly" placeholder="<?php
                     if ($solicitudes_autorizada) {
                         echo(count($solicitudes_autorizada));
                     } else {
                         echo("0");
                     }
                     ?>" /></li>
        <li><a href="<?php
                     if ($solicitudes_revisadas) {
                         echo("solicitudes_revisadas.php");
                     } else {
                         echo("#");
                     }
                     ?>"><input class="<?php
               if ($solicitudes_revisadas) {
                   echo("botones_nueva");
               } else {
                   echo("botones_disabled");
               }
               ?>" type="button" value="Revisadas"/></a><input type="text"  class="text_menu" readonly="readonly" placeholder="<?php
                     if ($solicitudes_revisadas) {
                         echo(count($solicitudes_revisadas));
                     } else {
                         echo("0");
                     }
                     ?>"  /></li>
        <li><a href="<?php
                     if ($solicitudes_aprobada) {
                         echo("solicitudes_aprobadas.php");
                     } else {
                         echo("#");
                     }
                     ?>"><input class="<?php
               if ($solicitudes_aprobada) {
                   echo("botones_nueva");
               } else {
                   echo("botones_disabled");
               }
               ?>" type="button" value="Aprobadas"/><a/>
                <input  type="text" class="text_menu" readonly="readonly" placeholder="<?php
                   if ($solicitudes_aprobada) {
                       echo(count($solicitudes_aprobada));
                   } else {
                       echo("0");
                   }
               ?>"  />
        </li>

        <li>
            <a href="<?php
                     if ($solicitudes_exportadas) {
                         echo("solicitudes_exportadas.php");
                     } else {
                         echo("#");
                     }
                     ?>"><input class="<?php
               if ($solicitudes_exportadas) {
                   echo("botones_nueva");
               } else {
                   echo("botones_disabled");
               }
               ?>" type="button" value="Exportadas"/></a><input type="text" class="text_menu" readonly="readonly"  placeholder="<?php
                     if ($solicitudes_exportadas) {
                         echo(count($solicitudes_exportadas));
                     } else {
                         echo("0");
                     }
                     ?>"  />
        </li>
        <li>
            <a href="<?php
                if ($solicitudes_rechazadas) {
                    echo("solicitudes_rechazadas.php");
                } else {
                    echo("#");
                }
               ?>"><input class="<?php
               if ($solicitudes_rechazadas) {
                   echo("botones_nueva");
               } else {
                   echo("botones_disabled");
               }
               ?>" type="button" value="Rechazadas"/></a><input type="text" class="text_menu" readonly="readonly"  placeholder="<?php
                     if ($solicitudes_rechazadas) {
                         echo(count($solicitudes_rechazadas));
                     } else {
                         echo("0");
                     }
                     ?>"  />
        </li>
        <li>
            <a href="<?php
                     if ($solicitudes_a_gestionar) {
                         echo("home.php");
                     } else {
                         echo("#");
                     }
                     ?>"><input class="<?php
               if ($solicitudes_a_gestionar) {
                   echo("botones_nueva");
               } else {
                   echo("botones_disabled");
               }
               ?>" type="button" value="A gestionar"/></a><input type="text" class="text_menu" readonly="readonly"  placeholder="<?php
                     if ($solicitudes_a_gestionar) {
                         echo(count($solicitudes_a_gestionar));
                     } else {
                         echo("0");
                     }
                     ?>"  />
        </li>

        <li>
    </ul>
             
</div>
