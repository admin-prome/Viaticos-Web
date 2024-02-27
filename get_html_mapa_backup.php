<div id="mapa" align="center" style="margin-left: -8.5%;width:800px; height:400px;" class="reveal-modal">
    <a href="#" id="cerra_mapa"   style="position:fixed;right:20%;top: 20%;width:70px;height:15px;" onclick=" $('#mapa').css('display','none');" ><img src="img/close.png"></a>
   
	<script type="text/javascript">
		calcular_recorrido('<?= $_GET['coord1']; ?>','<?= $_GET['coord2']; ?>','mapa');
		$('#mapa').reveal('open');
	</script>
       
</div>
<!--<a href="../../CESAR/Rendicion-Provincia/get_html_mapa.php"></a>-->