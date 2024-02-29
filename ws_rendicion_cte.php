<?php
require_once "nusoap/lib/nusoap.php";
$client = new nusoap_client("http://127.0.0.1/ws_rendicion.php");

$NroSolicitud = $_GET['solicitud'];
$error = $client->getError();

if ($error) 
{
    echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
}

$result = $client->call("getSolicitud", array("solicitud" => $NroSolicitud));

if ($client->fault) 
{
    echo "<h2>Fault</h2><pre>";
    print_r($result);
    echo "</pre>";
}
else 
{
    $error = $client->getError();
    if ($error) 
	{
        echo "<h2>Error</h2><pre>" . $error . "</pre>";
    }
    else 
	{
//        echo $result;
		echo '<?xml version="1.0" encoding="ISO-8859-1"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/"><SOAP-ENV:Body><ns1:getSolicitudResponse xmlns:ns1="http://tempuri.org"><return xsi:type="xsd:string">'.$result.'</return></ns1:getSolicitudResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>';
    }
}

//echo "<h2>Request</h2>";
//echo "<pre>" . htmlspecialchars($client->request, ENT_QUOTES) . "</pre>";
//echo "<h2>Response</h2>";
//echo "<pre>".htmlspecialchars($client->response, ENT_QUOTES) . "</pre>";
//echo "<pre>".htmlspecialchars($client->response, ENT_QUOTES) . "</pre>";

?>