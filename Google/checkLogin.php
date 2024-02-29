<?php
session_start();
require_once 'vendor/autoload.php';
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
require_once 'vendor/autoload.php';


// init configuration
$clientID = '100272080555-bdpc06f3n6tffv5k1bb7c6rq3e6lpv3h.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://tst-rendicion.provinciamicroempresas.com/Google/checkLogin.php';
  
// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
 
// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);
  
  // get profile info
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $email =  $google_account_info->email;
  $name =  $google_account_info->name;

  redireccionar_login($email);
 
  // now you can use this profile info to create account in your website and make user logged in.
} else {
  echo "<a id='google' href='".$client->createAuthUrl()."'>Google Login</a>";
} 

function  redireccionar_login($email){

  $datos_consulta = obtener_usuarios_login_BASE_BANCO($email);
  $id_personal = $datos_consulta['personalid'];
  
  if (!$datos_consulta){
      ?>
      <script type="text/javascript">
        alert('El usuario ingresado no pertenece a la base de datos del sistema.');
        window.location.href = '/index.php?fin=1#';
      </script>
      <?php
      } 
    else{
      var_dump ($email);
          iniciarSesion($datos_consulta);
      header('Location: /home.php?email='.$email.'&idpersonal='.$id_personal.'&idejecutivo='.obtenerIDEjecutivoSegunIDPersonal($id_personal).'&nombre='.obtenerNombreUsuarioSegunIDPersonal($id_personal));
  }


}
?>
<html>
    <head>
        <title>Rendicion</title>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" ></script>
        <script type="text/javascript" src="../js/jquery.noty.js"></script>
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    </head>
    <body>
        <style type="text/css">
            
			.botones
			{
                cursor: pointer;
                margin: 2%;
                background: #6a6869;
                background-image: -webkit-linear-gradient(top, #6a6869, #0b090a);
                background-image: -moz-linear-gradient(top, #6a6869, #0b090a);
                background-image: -ms-linear-gradient(top, #6a6869, #0b090a);
                background-image: -o-linear-gradient(top, #6a6869, #0b090a);
                background-image: linear-gradient(to bottom, #6a6869, #0b090a);
                -webkit-border-radius: 10;
                -moz-border-radius: 10;
                border-radius: 10px;
                color: #ffffff;
                font-size: 16px;
                padding: 7% 7% 7% 7%;
                text-decoration: none;
                height:10%;
                font-weight: bold;
                line-height:150px;
                width:150px;
                border: solid #c2c2c2 0px;
                font-family: 'Lato', sans-serif;
            }

            .botones:hover
			{
                cursor: pointer;
                background: #22282b;
                background-image: -webkit-linear-gradient(top, #22282b, #6a6f73);
                background-image: -moz-linear-gradient(top, #22282b, #6a6f73);
                background-image: -ms-linear-gradient(top, #22282b, #6a6f73);
                background-image: -o-linear-gradient(top, #22282b, #6a6f73);
                background-image: linear-gradient(to bottom, #22282b, #6a6f73);
                text-decoration: none;

            }
            
			.login-form-wrap img{margin-left: 0%;}
            .login-form-wrap p{color:black!important;}
            
			.login-form-wrap
			{
                background: white;
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#5170ad', endColorstr='#355493',GradientType=1 );
                border:1px solid #2d416d;
                box-shadow: 0 1px #5670A4 inset,
                    0 0 10px 5px rgba(0, 0, 0, 0.1);
                border-radius: 14px;
                position: relative;
                width: 250px;
                height: 300px;
                margin: 60px auto;
                padding: 50px 30px 0 30px;
                text-align: center;

            }
            
			#error_user_login{margin-left: 34%;color:white;font-size:bold;}
        
		</style>
		        
		<div class="login-form-wrap" id="contenedor">
            <!-- Container with the Sign-In button. -->
            <img src="../img/logo.png" alt="Logo Provincia Microempresas" id="logo_banco_provincia" /><br/>
            <p style="color:black!important;font-weight: bold;">Sistema de rendici&oacute;n de vi&aacute;ticos</p>
            <a href='#' id="loginText" class="botones">Iniciar sesion</a><br/>

        </div>
		
		
        <span id="error_user_login"></span>

		<!--
        <a href="#" style="display:none" id="logoutText" target='myIFrame' onClick="myIFrame.location = 'https://www.google.com/accounts/Logout';
                return false;">  </a>
        <iframe name='myIFrame' id="myIFrame" style='display:none'></iframe>
		
		-->

    </body>
    <script type="text/javascript">



        function loginMicrosoft()  
	    {
		    //var _url = "/microsoft/Authorize.php";
		    var _url = "./google/checkLogin.php";
    		window.location.href = _url;       
		   		   
            $('#error_user_login').html('');
            $('#error_user_login').css('display', 'block');
        }
		
		$(document).ready(function() {

            $("#contenedor").hide(0).delay(200).fadeIn(2500);

            var google = document.getElementById('google');
            google.style.display = 'none';
            document.getElementById('loginText').href = google.href;


        });

        $("body").css("background-color", "#414042");
        $("#body_container").css("padding-top", "200px");
        $("p").css("color", "white");
        $("p").css("text-align", "center");
        $("p").css("font-family", "arial");

		
        //credits: http://www.netlobo.com/url_query_string_javascript.html
        function gup(url, name) {
            name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
            var regexS = "[\\#&]" + name + "=([^&#]*)";
            var regex = new RegExp(regexS);
            var results = regex.exec(url);
            if (results == null)
                return "";
            else
                return results[1];
        }

		setInterval(opacidadLogo, 2000);

        function generate(layout) {
            var n = noty({
                text: layout,
                type: 'alert',
                dismissQueue: true,
                layout: layout,
                theme: 'defaultTheme'
            });
            console.log('html: ' + n.options.id);
        }

        function opacidadLogo()
        {
            $("#logo_banco_provincia").fadeTo(2500, 0.3);
            $("#logo_banco_provincia").fadeTo(2500, 1);
        }

<?php
	if(isset($_GET['fin']) && $_GET['fin'] == 1) 
	{
    ?>
        // $('#logoutText').trigger('click');
		//  window.location = 'index.php';
		$('#error_user_login').html('Sesion Finalizada').delay('3000').fadeOut('6000');
		$('#error_user_login').css('display', 'block');
    <?php
	}
?>

    </script>
    <div style=" position: absolute; bottom: 2px;width: 90%;margin-left: 3.3%;">
        <p style="color:lightgrey;font-size: 8px; font-family: 'Lato', sans-serif;text-align: center;">&copy; <?php echo date('Y') ?> PROVINCIA MICROCREDITOS</p>
        <div style="border-bottom: 1px solid lightgray;"></div>
    </div>
</html>
