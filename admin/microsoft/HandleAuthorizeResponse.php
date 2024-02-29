<?php
    session_start();
    //Require other files.
    require_once 'Settings.php';
    require_once 'AuthorizationHelperForGraph.php';  
    require_once 'DisplayME.php';
    if (!isset($_GET['code'])) 
	{
		header( 'Location:Authorize.php' ) ;
    }
    else 
	{
        AuthorizationHelperForAADGraphService::GetAuthenticationHeaderFor3LeggedFlow($_GET['code']);        
        header( 'Location:DisplayME.php' ) ;
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
