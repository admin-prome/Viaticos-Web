<?php
    
session_start();
	$_SESSION['access_token'] = "";
	$_SESSION['MailMicrosoft'] = "";

	require_once 'GraphServiceAccessHelper.php';
	require_once 'Settings.php';
        require_once 'AuthorizationHelperForGraph.php'; 
	
	require_once '../admin/inc/const.php';
	
	require_once '../admin/inc/funciones/cargos.php';
	require_once '../admin/inc/funciones/conceptos.php';
	require_once '../admin/inc/funciones/estados.php';
	require_once '../admin/inc/funciones/fechas.php';
	require_once '../admin/inc/funciones/funciones_genericas.php';
	require_once '../admin/inc/funciones/kilometros.php';
	require_once '../admin/inc/funciones/login.php';
	require_once '../admin/inc/funciones/motivos.php';
	require_once '../admin/inc/funciones/perfiles_viaticos.php';
	require_once '../admin/inc/funciones/querys_mysql.php';
	require_once '../admin/inc/funciones/segmentos.php';
	require_once '../admin/inc/funciones/seguridad.php';
	require_once '../admin/inc/funciones/solicitudes.php';
	require_once '../admin/inc/funciones/sucursales.php';
	require_once '../admin/inc/funciones/usuarios.php';
	require_once '../admin/inc/funciones/viaticos.php';
	
	AuthorizationHelperForAADGraphService::GetAuthenticationHeaderFor3LeggedFlow($_GET['code']);
	
	$user = GraphServiceAccessHelper::getMeEntry();  
	 
	 $datos_consulta = obtener_usuarios_login_BASE_BANCO($user->{'mail'});

    if (!$datos_consulta) 
	{
        ?>
		<script type="text/javascript">
			alert('El usuario ingresado no pertenece a la base de datos del sistema.');
			window.location.href = 'http://rendicion.provinciamicroempresas.com/index.php?fin=1#';
		</script>
		<?php
    } 
	else 
	{
        iniciarSesion($datos_consulta);
		header('Location: http://rendicion.provinciamicroempresas.com/home.php');
    }
?>