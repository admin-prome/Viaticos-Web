<?php

$url_token = 'https://svcs.provinciamicroempresas.com/crmservices/apicrm/token';
	
$varpost = array('grant_type'=>'password','username'=>'marktplus',
'password'=>'49830BBC8CAD3118701D67B364034427');
                                                            
$curl = curl_init();
 
// definimos la URL a la que hacemos la petición
curl_setopt($curl, CURLOPT_URL,"https://svcs.provinciamicroempresas.com/crmservices/apicrm/token");
// indicamos el tipo de petición: POST
curl_setopt($curl, CURLOPT_POST, TRUE);
// definimos cada uno de los parámetros
curl_setopt($curl, CURLOPT_POSTFIELDS, "grant_type=password&username=marktplus&password=49830BBC8CAD3118701D67B364034427");
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_VERBOSE, 1);
 
// recibimos la respuesta y la guardamos en una variable
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$remote_server_output = curl_exec ($curl);

 $info = curl_getinfo($curl);

if(!$response = curl_exec($curl))
{
    $curl_errno = curl_errno($curl);
    $curl_error = curl_error($curl);
   
    
	if($curl_errno > 0)
	{
		echo "cURL Error ".$curl_errno.": ".$curl_error;
        echo('<pre>');
		var_dump($info);
        echo('</pre>');
    }

	//trigger_error(curl_error($curl)); 
}
else
{
        echo('<pre>');
		var_dump($info);
        echo('</pre>');
    
    
	echo "<pre>";
		var_dump($response);
	echo "</pre>";
}

curl_close($curl);