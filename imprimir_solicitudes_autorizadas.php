<?php

require_once('inc/tcpdf/tcpdf.php');
require_once 'inc/checkLogin.php';
require_once 'admin/inc/const.php';
require_once 'admin/inc/funciones/all_functions.php';
$id_usuario = $_SESSION['id_usuario'];

$id_solicitud = $_GET['id_solicitud'];



$viaticos = obtener_viaticos_solicitud($_GET['id_solicitud']);
$cantidad_viaticos = obtener_cantidad_viaticos_solicitud($_GET['id_solicitud']);



// agrego rafa.. 
 $legajo = obtener_legajo_usuario_BASE_BANCO($id_usuario);


$solicitud = obtener_solicitud_datos($_GET['id_solicitud']);



$rs = obtener_usuario_que_autoriza_solicitud($id_solicitud);
    
$autorizado = false;

if(!empty($rs)){
    
    $autorizado = true;

    $id_usuario_que_autoriza = $rs[0]['usuario'];

    $fecha_autorizacion = $rs[0]['fecha'];
    
	$fecha_autorizacion = substr($fecha_autorizacion,0,10); 

    $nombre_usuario_que_autoriza = obtener_nombre_usuario_BASE_BANCO($id_usuario_que_autoriza);
    
}

// ..hasta aca

$total_paginas = 0;


class MYPDF extends TCPDF {
    


  protected $last_page_flag = false;

  public function Close() {
    $this->last_page_flag = true;
    parent::Close();
  }

  public function Footer() {
      
       parent::Footer();
      
    if ($this->last_page_flag) {
       $html =  '<p style="font-family:Arial, Helvetica, sans-serif; font-size: 13px;">FIRMA Y ACLARACION <p/>';

        $html .= '<p></p><p style="font-size:10px;">Este formulario deberá ser presentado a la Gerencia de Administración y Finanzas, con los comprobantes correspondientes </p>';          
        $footer_text = $html; $this->writeHTMLCell(200, 50, 25, 260, $footer_text, 0, 0, 0, true, 'L', true);

        
    } else {
      $html = '';
       $footer_text = $html; $this->writeHTMLCell(200, 50, 25, 260, $footer_text, 0, 0, 0, true, 'L', true);

    }
  
}
    
    /*
    public function Footer() {
        
       

        $html =  '<p style="font-family:Arial, Helvetica, sans-serif; font-size: 13px;">FIRMA Y ACLARACION <p/>';

        $html .= '<p></p><p style="font-size:10px;">Este formulario deberá ser presentado a la Gerencia de Administración y Finanzas, con los comprobantes correspondientes </p>';          

        $pagina_actual =  $this->getPage();




        $total_paginas = $this->getAliasNbPages();
        
      //  var_dump($total_paginas);

*/


        // parent::Footer();


      
         /*

         if($pagina_actual == $total_paginas){
             
             echo "pagina actual" . $pagina_actual;
             echo "paginas totales" . $total_paginas;

             $html .= "esta es la ultima pagina";
             
         }
         
         
         $footer_text = $html; $this->writeHTMLCell(200, 50, 25, 260, $footer_text, 0, 0, 0, true, 'L', true);


     }
     
      */
     
}




    
estado_imprimir_solicitud($_GET['id_solicitud'], '1');
// create new PDF document


    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  
   


//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Provincia Microempresas');
$pdf->SetTitle('Solicitud presentada por');
$pdf->SetSubject('Solicitud presentada por ');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');




// set default header data

//comento rafa:
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Solicitud presentada por ' . obtener_nombre_usuario_BASE_BANCO($id_usuario) . '. Nro de legajo: ' . $legajo, ' Correspondiente al mes de ' . obtener_nombre_mes($solicitud[0]['mes']) . ' del año ' . $solicitud[0]['ano'] . '', array(0, 0, 0), array(0, 0, 0));

// agrego rafael:


$html = '<img src="img/logo.png" style="text-align:center;" alt="logo" width="200" /> <span style="font-size:16px; font-family:Arial, Helvetica, sans-serif; text-align:center;" > Rendición de gastos</span><br/><p  style="font-size:12px;width=600px;height=600px;">Solicitud presentada por <b>' . obtener_nombre_usuario_BASE_BANCO($id_usuario) . '. </b><br/> Nro de legajo: <b>' . $legajo . ' </b> correspondiente al mes de <b>' . obtener_nombre_mes($solicitud[0]['mes']) . ' del año ' . $solicitud[0]['ano'] . '</b></p>';
$pdf->setCellHeightRatio(1.5);



///$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
//
//
// set header and footer fonts

// comento Rafael:
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT,PDF_MARGIN_TOP,PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);



// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();



/*
// set text shadow effect
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
*/

$solicitud = $_GET['id_solicitud'];

/* comienzo a armar el html de la tabla */
$html .= '
    
    <span style="text-align:center;">
	</span><br />
	<table border="0" id="detalle_solicitud" class="display" cellspacing="1" style="width:100%; margin:0 auto;font-size:9px!important;border:1px solid black;">
       
<tr style="text-align: center!important; font-weight:bold;" valign="middle">
     <th>Carga por:</th>
     <th>Fecha<br />Presentaci&oacute;n</th>
     <th>Motivo</th>
     <th>Concepto</th>
     <th>Origen</th>
     <th>Km</th>
     <th>Importe</th>
     <th>Nro.<br /> solicitud</th>
     <th>Aprobado/ Rechazado/ N.A.</th>

</tr>
       
<tbody><tr><td colspan="9"></td></tr>';

 
if ($viaticos) {
    $total = 0;
    foreach ($viaticos as $row) {

		$km = $row["km"];
        settype($km,'double');

	
		
        
        $html.='<tr align="center" style="text-align:center;" id="fila_' . $row["id"] . '" valign="middle">';
        $html.="<td>" . ponerIconoPCCelular($row["cargado_en_celular"]) . "</td>";
        $html.= "<td>". presentarFechaDateTime($row["fecha"])."</td>";
        $html.= "<td>". guion(obtener_motivo($row["id_motivo"], false)) . "</td>";
        $html.= "<td>". guion(obtener_concepto($row["id_concepto"])) . "</td>";

        if ($row["origen"] == "1")
            $html.="<td>Sucursal</td>";
        else
            $html.="<td>Visita anterior</td>";
	

        $html.="<td style='text-align:right!important;'>" . number_format($km,2) . "</td>";
        $html.="<td>" . "$ " . formatearMoneda($row["importe"]) . "</td>";
        $html.="<td>" . guion($row["nro_solicitud"]) . "</td>";
        $html.="<td></td></tr>";
        $total+=$row["importe"];
    }
     
}

$html.='</tbody></table>'
        . '<p style="font-family:Arial, Helvetica, sans-serif; font-size: 13px;">Importe total de la rendición: <b>$'.formatearMoneda($total).'</b><br />'.
'Fecha y hora de impresión: <b>'.date("d-m-Y H:i:s").'</b><br/>';

if($autorizado){


	if($autorizado == null || $autorizado == ''){
			
			$autorizado = '';
	}	
    


    $html .= '<span style="font-family:Arial, Helvetica, sans-serif; font-size: 13px;">Autorizado por <b>' . $nombre_usuario_que_autoriza . ' </b>Fecha <b>' . $fecha_autorizacion . '</b></span> </p>' ;
}
else{
    
    $html .= '</p>';
}

            



$pdf->writeHTML($html, true, false, false, false, '');


$pdf->Output('example_001.pdf', 'I');