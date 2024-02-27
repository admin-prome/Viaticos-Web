<?php
$json = file_get_contents('php://input');
$data=json_decode($json);  /* ahora en $data tenemos el objeto que nos enviaron x json, */

$id=$data->id; /*  suponiendo que sus propiedades eran id, name, y email, los recibimos así: */
$name=$data->name;
$email=$data->email;
?>