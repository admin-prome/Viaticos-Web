$(".ver_comentario").click(
                    function()
                    {

                        var id_td_comentario = (this.id).split("_");

                        $("#comentario_" + id_td_comentario[2]).dialog({
                            show: {effect: 'blind', duration: 400},
                            hide: {effect: 'explode', duration: 1000},
                            resizable: false

                        });


});