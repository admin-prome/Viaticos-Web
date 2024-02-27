<?php

if($_POST['user'] == 'admin' && $_POST['pass'] == 'wphoneapp2015')
{
	session_start();
	$_SESSION['autorizado'] = true;
	header('Location: version_mobile.php');
}
else
	header('Location: index.php?errorlogin=1');