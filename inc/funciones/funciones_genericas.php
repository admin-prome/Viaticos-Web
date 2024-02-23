<?php



function guion($param)
{
    if (empty($param) || $param== 'undefined')
		return " - ";
    else
		return $param;
}

function formatearMoneda($valor)
{
	return @number_format($valor,2,",",".");
}


function formatearMonedaCalipso($valor)
{


	return @number_format($valor,2,",",".");


}

function estoyEnPantallaNuevoViatico()
{
	return 	(substr_count($_SERVER['PHP_SELF'],"nuevo_viatico.php") == 1);
}





function sendEmail($para, $asunto, $mensaje, $file_name='', $path = '') {
    
    
    if(substr_count($_SERVER['PHP_SELF'], '/test/') == 1 || substr_count($_SERVER['PHP_SELF'], '/devlalo/') == 1 || substr_count($_SERVER['PHP_SELF'], '/devcesar/') == 1 || substr_count($_SERVER['PHP_SELF'], '/devrafa/') == 1){
        
         $para = 'info@marketingplus.com.ar';
        
    }
    
    
        $msg = $mensaje;
	$from = "rendicion@provinciamicroempresas.com";
	$to = $para;

	$mail = new PHPMailer(true); 

	try {


	$mail->IsSMTP();
	$mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
	$mail->Host = "smtp.office365.com";
	$mail->Port = 25;
	$mail->Username = "rendicion@provinciamicroempresas.com";
	$mail->Password = "Prome2015";
	//$mail->SetFrom("rendicion@provinciamicroempresas.com");
	$mail->AddReplyTo("rendicion@provinciamicroempresas.com");
	$mail->SMTPDebug = 2;
	

     

/*   
        $mail->Host = "marketingplus.com.ar";
	$mail->Port = 25;
	$mail->Username = "outlook";
	$mail->Password = "Promx260";

*/

	$mail->From = $from;
        
        $encodeado = "ADMINISTRACION - Rendicion de viaticos";
        $encodeado = utf8_decode($encodeado);
        
	$mail->FromName = $encodeado;
	$mail->Subject = $asunto;
	$mail->AltBody = "Para ver el mensaje, por favor utilize un navegador compatible con HTML";
	$mail->MsgHTML($msg);
	//$mail->AddAttachment($path.$file_name);
	$mail->AddAddress($to, '');
	$mail->IsHTML(true);

	 $a = $mail->Send();
   
	} catch (phpmailerException $e) {      
	  echo $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {       
	  echo $e->getMessage(); //Boring error messages from anything else!
	}
	return 1;	

}

function new_var_dump($valor)
{


	echo('<pre>');
	var_dump($valor);
    echo('</pre>');


}

	

