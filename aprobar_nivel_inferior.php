<?php require_once 'inc/checkLogin.php'; ?>
<?php require_once 'admin/inc/const.php'; ?>
<?php $id_usuario = $_SESSION['id_usuario']; ?>
<?php require_once 'admin/inc/funciones/all_functions.php'; ?>
<?php require_once 'inc/header.php'; ?>
<?php require 'inc/menu.php'; ?>
<?php

if ($_SESSION['id_estados_gestionar'] == SOLICITUD_APROBADA) {
     $solicitudes_a_gestionar = obtener_mis_solicitudes_a_gestionar_revisar_aprobar_BASE_BANCO(SOLICITUD_AUTORIZADA);
     echo('soy el que aprueba obtengo lo que esta para autorizado esperando a ser revisado');
//Soy la persona que pasa de aprobado a exportar , quiero saltar un nivel quiero ver todo lo que esta autorizado 
//esperando a ser revisado , tengo que traer todo lo que este en estado 5(ya autorizado esperando a pasar a ya revisado 6)
      
} else if ($_SESSION['id_estados_gestionar'] == SOLICITUD_REVISADA) {
     echo('soy el que revisa obtengo lo que esta  presentado esperando a ser autorizado');
     //OBTENGO TODOS LOS USUARIOS QUE TIENEN COMO ESTADOS_GESTIONAR EL 3
     $usuarios_saltando_nivel=obtener_usuarios_segun_estado_gestionar(SOLICITUD_PRESENTADA);
    // var_dump($usuarios_saltando_nivel);
//Soy la persona que pasa de revisado  a aprobado , quiero saltar un nivel quiero ver todo lo que esta presentado (salteo la autorizacion)
//para eso tengo que elegir las cosas pendientes de que usuario quiero ver , necesito traer todas las personas que autorizan y alli elegir
//el trabajo pendiente de quien quiero ver
  
}
?>


    <?php
    include_once 'inc/footer.php';
    ?>