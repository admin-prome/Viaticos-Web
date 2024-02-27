$("#presentar_planilla").click(function() {
    
    $('#DIVMOSTRARMES').reveal('open');
    $('#DIVMOSTRARMES').css('display','block');     
        
});



$("#DIVMOSTRARMES button").click(function() {

        var fecha = new Date();
        var ano = fecha.getFullYear();

        
                    if($('#DIVMOSTRARMES [name=presentar_mes]').val() == ''){
                      alert("Elija un mes por favor");
                      return false;
                    
                    }
                    if($('#DIVMOSTRARMES [name=diciembre_ano]').val() == ''){
                      alert("Elija un a&Ntilde;o por favor");
                      return false;
                    
                    }
    
                    var parametros = {
                        "solicitud_activa": $('#solicitud_activa').val(),
                        "mes": $("[name=presentar_mes]").val(),
                        "accion": "cambiar_id_solicitud",
                        "ano_diciembre": $('[name=diciembre_ano]').val()
                    
                    
                     };

                $.ajax({
                    
                  
                    url: 'admin/ajax_peticiones.php',
                    data: parametros,
                    type: 'post',
                    success: function(response)
                    {
                        
                        var id_solicitud = $('#solicitud_activa').val();
                        var comentario = "Solicitud presentada para revision";
                        var usuario = id_usuario_js;

                        insertar_comentario_historial(id_solicitud, comentario, usuario);
                        setInterval(function(){
                           window.location = "home.php";
                        }, 3500);
                       

                    }
                });
            
});

$('#DIVMOSTRARMES [name=presentar_mes]').change(function() {        
   
    valor_select = $('[name=presentar_mes]').val();       
    
    if(valor_select == "12"){               
        
          $('[name=diciembre_ano]').css('display','block');
        
    }
    
});