<!DOCTYPE html>
<html>
    <head>

        <title>Provincia Microempresas</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="language" content="ES" />
        <meta name="keywords" content="Provincia" />
        <meta name="description" content="Provincia" />
        <!-- <link rel="stylesheet" type="text/css" href="css/style-menu.css" /> -->
        <link rel="stylesheet" type="text/css" href="css/lightbox.css"  />	 
        <link rel="stylesheet" type="text/css" href="css/tinycarousel.css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/reveal.css" />	
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css" />   
        <link rel="stylesheet" media="screen" type="text/css" href="css/datepicker.css" />


        <script type="text/javascript" language="javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui.js"></script>
        <link rel="stylesheet" type="text/css" href="js/jquery-ui.css" />
        <script type="text/javascript" src="js/jquery.tinycarousel.js"></script>
        <script type="text/javascript" src="js/lightbox.min.js"></script>
        <script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="js/jquery.reveal.js"></script>  
        <script type="text/javascript" src="js/datepicker.js"></script>
        <script type="text/javascript" src="../js/functions_genericas.js"></script>	
    

        <meta name="viewport" content="initial-scale=1.0, width=device-width" />
        <script src="http://js.api.here.com/v3/3.0/mapsjs-core.js"
        type="text/javascript" charset="utf-8"></script>
        <script src="http://js.api.here.com/v3/3.0/mapsjs-service.js"
        type="text/javascript" charset="utf-8"></script>
        <meta name="viewport" content="initial-scale=1.0, 
              width=device-width" />
        <script src="http://js.api.here.com/v3/3.0/mapsjs-core.js" 
        type="text/javascript" charset="utf-8"></script>
        <script src="http://js.api.here.com/v3/3.0/mapsjs-service.js" 
        type="text/javascript" charset="utf-8"></script>
        <script src="http://js.api.here.com/v3/3.0/mapsjs-ui.js" 
        type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" charset="UTF-8"
        src="http://js.api.here.com/v3/3.0/mapsjs-mapevents.js"></script>
        <link rel="stylesheet" type="text/css" href="http://js.api.here.com/v3/3.0/mapsjs-ui.css" />

        <style type="text/css">

            .ui-dialog > .ui-widget-header
            {
                background: #9b1629;
                background-color: #9b1629;
                background-image: none;
                color: black;
            }

            .ui-widget-header .ui-icon
            {
                background-image: url(img/close_window.png);
            }

        </style>
        <script type="text/javascript">
            ORIGEN_SUCURSAL = 1;
            ORIGEN_VISITA_ANTERIOR = 2;
        </script>
    </head>

    <body>

        <div id="dialog-confirm" title="Procede a cerrar su sesi&oacute;n ?" style="display:none;">Fin de Sesi&oacute;n</div>

        <div id="contenedor">

            <div id="header">

                <div id="head_contenedor">

                    <a href="../../home.php">
                        <img src="img/logo.png" alt="logo" id="logo_banco_provincia" border="0" />
                    </a>

                    <div style="margin-left: 77%;margin-top: -80px;">

                        <p class="marco_rojo" style="width: 200px;height:16px;font-size: 13px;">
                            SISTEMA RENDICION VIATICOS
                        </p>

                        <input type="button" value="Logout" id="boton_logout" class="botones" style="width: 150px;"/>

                    </div>

                </div>
                <div id="menu_administrador">

                    <ul style="margin-left:-3.3%!important;font-weight: bold!important;">

<!--		<li><input class="botones" type="button" id="admin" value="ADMINISTRADOR"/></li>
<li><input  class="botones" type="button" id="usu" style="display:none;" value="USUARIOS"/></li>
<li><input class="botones" type="button" id="con" value="CONCEPTOS"/></li>
<li><input class="botones" type="button" id="suc" value="SUCURSALES"/></li>--> 
                        <li>
                            <input class="botones" type="button" id="exp" value="HOME" onclick="window.location = '../../home.php';" />         
                        </li>
                        <li>
                            <input class="botones" type="button" id="exp" value="EXPORTAR" onclick="window.location = 'exportar.php';" />         
                        </li>


                        <li>          
                            <input class="botones" type="button"  id="ckm" value="TOPE ALMUERZOS" onclick="window.location = 'topes_almuerzo.php';" />          
                        </li>

                        <li>
                            <input class="botones" type="button"  id="ckm" value="COSTO KM" onclick="window.location = 'costo_por_km.php';" />
                        </li>

                        <li>
                            <input class="botones" type="button"  id="ckm" value="CONSULTAS" onclick="window.location = 'consultas.php';" />
                        </li>
                        <li>
                            <input class="botones" type="button"  id="ckm" value="REPORTES" onclick="window.location = 'reportes.php';" />
                        </li>



                    </ul>

                </div>
            </div>