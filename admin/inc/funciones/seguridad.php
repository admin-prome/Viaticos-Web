<?php

function generarAlfaNumericoAleatorio($long = 32)
{
	$random = str_shuffle(sha1(rand().microtime()));
	
	return substr($random,0,$long);
}

function inputUserPasswordSospechoso($dato)
{
	
	if(substr_count($dato,' ') > 0)
	{
		return true;	
	}
	
	$injections = array('select','insert','update','delete','create');
	
	if(in_array(strtolower(substr($dato,0,6)),$injections))
	{
		return true;	
	}
	
	return false;

}

function antiSQL($dirtyData)
{
	return strip_tags(stripslashes($dirtyData));
}

function controlesParamsPage($page)
{
	switch($page)
	{
		//Pagina de Login:
		case 'index.php':
		
		$paramsIndex = array('fin');
		
		foreach($_GET as $key => $value)
		{
			if(!in_array($key,$paramsIndex))
			{
				header('Location: '.$page);
			}
		}
		
		if(sizeof($_POST) > 0)
		{
			header('Location: '.$page);
		}
		
		break;
		
		
		//Login:
		case 'login_process.php':
		
		$paramsIndex = array('usuario','password');
		
		foreach($_POST as $key => $value)
		{
			if(!in_array($key,$paramsIndex))
			{
				header('Location: index.php');
			}
		}
		
		if(sizeof($_GET) > 0)
		{
			header('Location: index.php');
		}
		
		break;
	}
}