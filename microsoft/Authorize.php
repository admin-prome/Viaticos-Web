<?php
	
    //Require other files.
    require_once 'Settings.php';
    require_once 'AuthorizationHelperForGraph.php';   

	AuthorizationHelperForAADGraphService::getAuthorizatonURL();

	//echo "<br />URL:".AuthorizationHelperForAADGraphService::getAuthorizatonURL();
   
	if(!isset($_GET['code'])) 
	{
		header( 'Location:'.AuthorizationHelperForAADGraphService::getAuthorizatonURL() ) ;
    }
    else 
	{
		AuthorizationHelperForAADGraphService::GetAuthenticationHeaderFor3LeggedFlow($_GET['code']);       
    }
?>
 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
    </head>
    <body>
         
    </body>
</html>
