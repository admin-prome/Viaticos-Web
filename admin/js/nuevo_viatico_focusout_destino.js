$('#form1_destino_viatico, #form2_destino_viatico').focusout(
function(){
                        
var value_concepto_viatico= $("#concepto_viatico_select option:selected").val();


if($("#form_1").css('display') == 'block')
	var form_activo = 1;
else
	var form_activo = 2;


// si borro la URL, entonces borro lo previamente calculado
// en auto o moto:

if (CONCEPTO_AUTO == value_concepto_viatico ||
CONCEPTO_MOTO == value_concepto_viatico)
{

     if($('form' + form_activo + '_destino_viatico').val() == '')
     {
     	$('form' + form_activo + '_km_viatico').val('');
        $('form' + form_activo + '_importe_viatico').val('');
        
		return false;
     }

}

});