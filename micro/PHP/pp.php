<?php
	$_SESSION['access_token'];
	require_once 'GraphServiceAccessHelper.php';
	require_once 'Settings.php';
    require_once 'AuthorizationHelperForGraph.php'; 
//    require_once '../../protected/controllers/SiteController.php'; 
	
	AuthorizationHelperForAADGraphService::GetAuthenticationHeaderFor3LeggedFlow($_GET['code']);
	
	$user = GraphServiceAccessHelper::getMeEntry();  
	echo $user->{'mail'};
	
	//$Site = new SiteController;
	
//	$Site->loginControl('dblum@provinciamicroepmresas.com');
	
 	/*
	echo('<tr><td>Display Name:</td>');
	echo('<td>'. $user->{'displayName'}.'</td>');
	echo('</tr><tr><td>User Principal Name:</td>');
	echo('<td>'. $user->{'userPrincipalName'}.'</td>');
	echo('</tr><tr><td>Object ID:</td>');
	echo('<td>'. $user->{'objectId'}.'</td>');
	echo('</tr><tr><td>Immutable ID:</td>');
	echo('<td>'. $user->{'immutableId'}.'</td>');
	echo('</tr><tr><td>Street:</td>');
	echo('<td>'. $user->{'streetAddress'}.'</td>');
	echo('</tr><tr><td>Delivery Location:</td>');
	echo('<td>'. $user->{'physicalDeliveryOfficeName'}.'</td>');
	echo('</tr><tr><td>Usage Location:</td>');
	echo('<td>'. $user->{'usageLocation'}.'</td>');
	echo('</tr><tr><td>City:</td>');
	echo('<td>'. $user->{'city'}.'</td>');
	echo('</tr><tr><td>Country:</td>');
	echo('<td>'. $user->{'country'}.'</td>');
	echo('</tr><tr><td>Department:</td>');
	echo('<td>'. $user->{'department'}.'</td>');
	echo('</tr><tr><td>Job Title:</td>');
	echo('<td>'. $user->{'jobTitle'}.'</td>');
	echo('</tr><tr><td>Mail:</td>');
	echo('<td>'. $user->{'mail'}.'</td>');
	*/
?>