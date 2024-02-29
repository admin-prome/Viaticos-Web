<!DOCTYPE html>
<html>

<head>

    <title>Provincia Microcreditos</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta name="language" content="ES" />
    <meta name="keywords" content="Provincia" />
    <meta name="description" content="Provincia" />
    <!-- <link rel="stylesheet" type="text/css" href="css/style-menu.css" /> -->
    <link rel="stylesheet" type="text/css" href="css/lightbox.css" />
    <link rel="stylesheet" type="text/css" href="css/tinycarousel.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="css/reveal.css" />
    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css" />
    <link rel="stylesheet" media="screen" type="text/css" href="css/datepicker.css" />
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">


    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="js/jquery-ui.css" />
    <script type="text/javascript" src="js/jquery.tinycarousel.js"></script>
    <script type="text/javascript" src="js/lightbox.min.js"></script>
    <script type="text/javascript" src="js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="js/jquery.reveal.js"></script>
    <script type="text/javascript" src="js/datepicker.js"></script>
    <script type="text/javascript" src="js/functions_genericas.js"></script>
    <script type="text/javascript" src="js/functions_solicitudes.js"></script>

    <meta name="viewport" content="initial-scale=1.0, width=device-width" />
    <script src="https://js.api.here.com/v3/3.0/mapsjs-core.js" type="text/javascript" charset="utf-8"></script>
    <script src="https://js.api.here.com/v3/3.0/mapsjs-service.js" type="text/javascript" charset="utf-8"></script>
    <meta name="viewport" content="initial-scale=1.0, 
              width=device-width" />
    <script src="https://js.api.here.com/v3/3.0/mapsjs-core.js" type="text/javascript" charset="utf-8"></script>
    <script src="https://js.api.here.com/v3/3.0/mapsjs-service.js" type="text/javascript" charset="utf-8"></script>
    <script src="https://js.api.here.com/v3/3.0/mapsjs-ui.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="UTF-8" src="https://js.api.here.com/v3/3.0/mapsjs-mapevents.js"></script>
    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.0/mapsjs-ui.css" />


    <script type="text/javascript">
        json_conceptos_viativo = $.parseJSON(<?php json_conceptos_viaticos(); ?>);

        CONCEPTO_AUTO = json_conceptos_viativo.Auto;
        CONCEPTO_MOTO = json_conceptos_viativo.Moto;
        CONCEPTO_TAXI_REMIS = json_conceptos_viativo.taxi_remis;
        CONCEPTO_TRANS_PUB = json_conceptos_viativo.transporte_publico;

        ID_ALMUERZO = <?= CONCEPTO_ALMUERZO; ?>;
        ID_CENA = <?= CONCEPTO_CENA; ?>;
        ID_GUARDERIA = <?= CONCEPTO_GUARDERIA; ?>;
        ID_GUARDERIA_LEY = <?= CONCEPTO_GUARDERIA_LEY; ?>;
        SOY_DE_CASA_MATRIZ = <?php if (soyDeCasaMatriz($_SESSION['id_sucursal'])) echo 'true';
                                else echo 'false'; ?>;

        ORIGEN_SUCURSAL = 1;
        ORIGEN_VISITA_ANTERIOR = 2;

        $(document).ready(function() {


            $.datepicker.regional['es'] = {
                closeText: 'Cerrar',
                prevText: '<Ant',
                nextText: 'Sig>',
                currentText: 'Hoy',
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                dayNames: ['Domingo', 'Lunes', 'Martes', 'MiÃ©rcoles', 'Jueves', 'Viernes', 's&aacute;bado '],
                dayNamesShort: ['Dom', 'Lun', 'Mar', 'MiÃ©', 'Juv', 'Vie', 's&aacute;b'],
                dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S&aacute;'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 0,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['es']);

            $('#example').DataTable();

            <?php
            if (estoyEnPantallaNuevoViatico()) {
            ?>
                var nro_columna_order = 1;
            <?php
            } else {
            ?>
                var nro_columna_order = 0;
            <?php
            }
            ?>

            $('#table_datatable').dataTable({
                "lengthMenu": [10, 25, 50, 100],
                "iDisplayLength": 200,
                "paging": true,
                "ordering": true,
                "scrollX": true,
                "order": [
                    [nro_columna_order, "desc"]
                ],
                "info": false,
                "search": false

            });

            $('#table_datatable_nuevo_viatico').dataTable({
                "lengthMenu": [10, 25, 50, 100],
                "iDisplayLength": 200,
                "paging": true,
                "ordering": true,
                "scrollX": true,
                "order": [
                    [nro_columna_order, "desc"]
                ],
                "info": false,
                "search": false

            });


            $('#boton_logout').click(function() {

                $("#dialog-confirm").dialog({
                    resizable: false,
                    height: 150,
                    width: 450,
                    modal: true,
                    buttons: {
                        "Cerrar Sesion": function() {
                            $(this).dialog("close");
                            window.location = 'logout_process.php';
                        },
                        "Cancelar": function() {
                            $(this).dialog("close");
                        }
                    }

                });

            });

            $("body").css("display", "none");
            $("body").fadeIn(850);

            setInterval(opacidadLogo, 2000);

        });
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBuY3ViUvLILdCPRfoZDAz8qdLBZOuOCZI"></script>

    <style type="text/css">
        .ui-dialog>.ui-widget-header {
            background: #9aca3c;
            background-color: #9aca3c;
            background-image: none;
            color: black;
        }

        .ui-widget-header .ui-icon {
            background-image: url(img/close_window.png);
        }
    </style>

</head>

<body>

    <div id="dialog-confirm" title="Procede a cerrar su sesi&oacute;n ?" style="display:none;">Fin de Sesi&oacute;n</div>

    <div id="contenedor">

        <div id="header">

            <div id="head_contenedor">

                <a href="home.php">
                    <img src="img/logo.png" alt="logo" id="logo_banco_provincia" border="0" />
                </a>

                <div style="margin-left: 77%;margin-top: -80px;">

                    <p class="marco_rojo" style="width: 200px;height:16px;font-size: 13px;">
                        SISTEMA RENDICION VIATICOS
                    </p>

                    <input type="button" value="Logout" id="boton_logout" class="botones" style="width: 150px;" />

                </div>

            </div>

        </div>