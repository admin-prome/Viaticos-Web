<?php

session_start();
if(!isset($_SESSION['autorizado']))
	header('Location: index.php');