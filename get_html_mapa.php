<div id="mapa" align="center" style="margin-left: -8.5%;width:800px; height:400px;" class="reveal-modal">
    <a href="#mapa" class="mapa_prueba" id="cerra_mapa"   style="width:70px;height:15px;" onclick=" $('#mapa').css('display','none'); $('#mapa').css('visibility','visible'); $('.reveal-modal-bg').css('display','none');" ><img style="position:absolute;top: 0.1%; right:0px; z-index:1" src="img/close.png"></a>
   
	<script type="text/javascript">
		calcular_recorrido('<?= $_GET['coord1']; ?>','<?= $_GET['coord2']; ?>','mapa');
               
		$('#mapa').reveal('open');
                  
	</script>
      
	  
</div>
<!--<a href="../../CESAR/Rendicion-Provincia/get_html_mapa.php"></a>-->