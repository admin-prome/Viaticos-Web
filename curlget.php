<?php

$curl = curl_init();

$headers[] = 'Accept: application/json';
$headers[] = "Authorization: bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1bmlxdWVfbmFtZSI6Im1hcmt0cGx1cyIsImlzcyI6Imh0dHBzOi8vc3Zjcy5wcm92aW5jaWFtaWNyb2VtcHJlc2FzLmNvbSIsImF1ZCI6IjQxNGUxOTI3YTM4ODRmNjhhYmM3OWY3MjgzODM3ZmQxIiwiZXhwIjoxNTAxNjE3Nzk3LCJuYmYiOjE1MDE1MzEzOTd9.stogaBoYUlugXR0_At87VpsyYili79jUEyUY0tSs3CI";

curl_setopt($curl,CURLOPT_URL,'https://svcs.provinciamicroempresas.com/crmservices/apicrm/SiteVisitsController/getsitevisitsbyuser/DD5EC3D');
curl_setopt($curl,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
curl_setopt($curl,CURLOPT_HEADER,1);
curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);

if(!$response = curl_exec($curl))
{
    $curl_errno = curl_errno($curl);
    $curl_error = curl_error($curl);
    
	if($curl_errno > 0)
	{
		echo "cURL Error ".$curl_errno.": ".$curl_error;
    }

	//trigger_error(curl_error($curl)); 
}
else
{
	echo 'exito !';
	
	echo "<pre>";
		var_dump($response);
	echo "</pre>";
}

curl_close($curl);