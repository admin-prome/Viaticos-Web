<?php

//DB rendicion:
define("SERVER_LOCAL", "");
define("USER_DB_LOCAL", "");
define("PASS_DB_LOCAL", "");
define("BASE_LOCAL", "rendicion");

// DB rendicion_usuarios ex DB DEL BANCO (PG):
define("SERVER_BANCO_PERSONAL", "");
define("USER_DB_BANCO_PERSONAL", "");
define("PASS_DB_BANCO_PERSONAL", "");
define("BASE_BANCO_PERSONAL", "rendicion_usuarios");

//Formularios de carga de viaticos:

define('FORM_VIATICO_1', 1);
define('FORM_VIATICO_2', 2);
define('FORM_VIATICO_3', 3);

//Forma de carga del viatico:

define('VIATICO_CARGADO_POR_CELU', 1);
define('VIATICO_CARGADO_POR_PC', 0);

//Origenes de viaticos:

define('VIATICO_ORIGEN_SUCURSAL', 1);
define('VIATICO_ORIGEN_VISITA_ANTERIOR', 2);

//Estados de solicitudes:

define('SOLICITUD_PENDIENTE', 1);
define('SOLICITUD_PRESENTADA', 3);
define('SOLICITUD_AUTORIZADA', 5);
define('SOLICITUD_REVISADA', 6);
define('SOLICITUD_APROBADA', 7);
define('SOLICITUD_RECHAZADA', 8);
define('SOLICITUD_EXPORTAR', 11);
define('SOLICITUD_A_PAGAR', 12);

//Motivos y conceptos:

define('MOTIVO_EVAL_TERRENO',1);
define('MOTIVO_REUNION_MENSUAL',2);
define('MOTIVO_GENERACION_DEMANDA',3);
define('MOTIVO_CAPACITACION',4);
define('MOTIVO_TRASPASO_CARTERA',5);
define('MOTIVO_REUNION_MODULO',6);
define('MOTIVO_OTRAS_REUNIONES',7);
define('MOTIVO_VISITA_SUCURSAL',8);
define('MOTIVO_VISITA_COBRANZA',9);
define('MOTIVO_BENEFICIO',10);
	
define('CONCEPTO_CENA',1);
define('CONCEPTO_ESTACIONAMIENTO',2);
define('CONCEPTO_ALMUERZO',3);
define('CONCEPTO_GUARDERIA',4);
define('CONCEPTO_AUTO',5);
define('CONCEPTO_MOTO',6);
define('CONCEPTO_TAXI_REMIS',7);
define('CONCEPTO_TRANS_PUBLICO',8);
define('CONCEPTO_REFRIGERIO',9);
define('CONCEPTO_PEAJE',10);
define('CONCEPTO_ALOJAMIENTO',11);

//Sucursales:
define('CM','casa_matriz');
define('ID_CASA_MATRIZ',7186);

//id usuarios admninistradores
define('ADMINISTRADOR_1',392);
define('ADMINISTRADOR_2',387);//ID de proveedores 4 para prueba
define('ADMINISTRADOR_3',447);//bianchi pablo
define('ADMINISTRADOR_4',658);//Maria pia
define('ADMINISTRADOR_5',393);//tgoldber
define('ADMINISTRADOR_6',519);//yasmin abalsamo
define('ADMINISTRADOR_7',320);//German Simour|

// GERENTES MATRIZ:

define('GERENTE_MATRIZ','gm');//ID de proveedores 4 para prueba
define('ADMINISTRACION','adm');//ID de proveedores 4 para prueba