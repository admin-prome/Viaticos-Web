<?php
//require_once 'inc/checkLogin.php';
require_once 'inc/const.php';

require 'inc/header.php';
require 'inc/menu_administrador.php';

require_once 'inc/funciones/all_functions_sin_admin.php';


/*
require 'inc/funciones/sucursales.php';
require 'inc/funciones/usuarios.php';
require 'inc/funciones/cargos.php';
require 'inc/funciones/perfiles_viaticos.php';
*/

$usuarios = obtener_usuarios();
$sucursales = obtener_sucursales();
$cargos = obtener_cargos();
$perfiles = obtener_perfiles_viaticos();

?>

<div class="gestionar">
    <div id="menu">
        <input class="botones" id="nuevo_usuario" style="height: 26px!important;line-height: 4px;width: 200px;font-weight: bold;" type="button" value="+ NUEVO USUARIO"/>
    </div>
    <!-- /*Detallados*/ -->
</div>

<div class="primer_carga" id="form_usuarios" style="display:none;">
    <!--CARGA DE USUARIOS -->
    <div id="nueva">
        <ul>

            <li style="margin-left: -2%" ><p >Nombre y apellido</p><input  id="nombre" class="text" type="text" placeholder="" style="width: 150px;"/></li>
            <li>
                <p>Sucursal</p>
                <select class="text" id="sucursales" style="width:150px;height: 26px!important;">
                    <?php
                    foreach ($sucursales as $row) {
                        echo("<option value='" . $row['id'] . "'>" . $row['Nombre'] . "</option>");
                    }
                    ?>                                         
                </select>
            </li>
            <li>
                <p>Cargo</p>
                <select class="text" id="cargo" style="width:150px;height: 26px!important;">
                    <?php
                    foreach ($cargos as $row) {
                        echo("<option value='" . $row['id'] . "'>" . $row['descripcion'] . "</option>");
                    }
                    ?>                       
                </select>
            </li>
            <li style="margin-left: -2%" ><p >E-mail</p><input  id="email" class="text" type="text" placeholder="" style="width: 150px;"/></li>
            <li style="margin-left: -2%" ><p >Depende </p>
                <select class="text" id="depende_de" style="width:150px;height: 26px!important;">
                    <?php
                    foreach ($usuarios as $row) {
                        echo("<option value='" . $row['id'] . "'>" . $row['Nombre'] . "</option>");
                    }
                    ?>                                         
                </select>
            </li>
            <li style="margin-left: -2%" ><p >Perfil viaticos </p>
                <select class="text" id="perfil_viatico" style="width:150px;height: 26px!important;">
                    <?php
                    foreach ($perfiles as $row) {
                        echo("<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>");
                    }
                    ?>                                         
                </select>
            </li>
        </ul>
        <input id="cancelar_usuario" class="botones" style="margin-bottom: 3%;height: 26px!important;line-height: 4px;margin-left:40.4%;" type="button" value="Cancelar"/>
        <input id="guardar_usuario" class="botones" style="margin-bottom: 3%;margin-left:1.9%;height: 26px!important;line-height: 4px;" type="button" value="Guardar"/>
        <div id="loader_usuario" style="margin-left: 44%;margin-top:-2%;display:none;"> <img src="inc/img/load_gris.gif" /> Cargando usuario</div>
        <div id="guardado_ok" style="margin-left: 40%;margin-top:-2%;display:none;"> Usuario guardado correctamente</div>
    </div>
</div>
<br/>
<br/>
<br/>
<div id="tabla_usuarios_head" style="background-color: #eeeced;">
    <table id="admin_usuarios" class="display" cellspacing="0" width=" width: 800px;
           margin: 0 auto; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
        <thead style="text-align: center!important;">
            <tr style="text-align: center!important;">

                <th style="text-align: left!important;">Perfil de viaticos</th>
                <th style="text-align: left!important;">Nombre y apellido</th>
                <th style="text-align: left!important;">Sucursal</th>
                <th style="text-align: left!important;">Cargo</th>
                <th style="text-align: left!important;"> Email</th>
                <th style="text-align: left!important;"> Depende de</th>
                <th style="text-align: left!important;">&nbsp;</th>
                <th style="text-align: left!important;">&nbsp;</th>
            </tr>
        </thead>
        <tfoot style="text-align: center!important;">
            <tr style="text-align: center!important;">

                <th style="text-align: left!important;">&nbsp;</th>
                <th style="text-align: left!important;">&nbsp;</th>
                <th style="text-align: left!important;">&nbsp;</th>
                <th style="text-align: left!important;">&nbsp;</th>
                <th style="text-align: left!important;">&nbsp; </th>
                <th style="text-align: left!important;">&nbsp; </th>
                <th style="text-align: left!important;">&nbsp;</th>
                <th style="text-align: left!important;">&nbsp;</th>
            </tr>
        </tfoot>

        <tbody>
            <?php
            foreach ($usuarios as $row) {
                echo("<tr id='" . $row["id"] . "'>");
                echo("<td class='nombre'>" . obtener_campo_perfil_viaticos($row["id_perfil_viatico"], 'nombre') . "</td>");
                echo("<td>" . $row["Nombre"] . "</td>");
                echo("<td>" . nombre_sucursal($row["id_sucursales"]) . "</td>");
                echo("<td>" . obtener_cargo($row["Cargos_idCargos"]) . "</td>");
                echo("<td>" . $row["mail"] . "</td>");
                echo("<td>" . obtener_usuario_dato($row["depende_de_id_usuario"], "Nombre") . "</td>");
                ?>
        <td><a href="#" class="big-link editar" id="<?php echo ($row["id"]) ?>" data-reveal-id="myModal" data-animation="fade"><img width="25" src="img/editar.png"/> </a></td>
            <td><a class="eliminar" id="<?php echo ($row["id"]) ?>" href="#"><img  src="img/guardar.png" width="25"/></a></td>
            </tr>
        <?php }
        ?>  
        </tbody>
    </table>

</div>
<style type="text/css">
    #footer{position: relative;margin-top: 50%;}
</style>
<br/>
<!--/******MODAL MODIFICAR USUARIOS*******************/-->
<div id="myModal" class="reveal-modal">
    <h1>Modificar usuario</h1>
    <div class="primer_carga" id="form_usuarios" style="display:block;">
        <!--CARGA DE USUARIOS -->
        <div id="nueva">
            <ul>

                <li style="margin-left: -2%" ><p >Nombre y apellido</p><input  id="nombre2" class="text" type="text" placeholder="" style="width: 150px;"/></li>
                <li>
                    <p>Sucursal</p>
                    <select class="text" id="sucursales2" style="width:150px;height: 26px!important;">
                        <?php
                        foreach ($sucursales as $row) {
                            echo("<option value='" . $row['id'] . "'>" . $row['Nombre'] . "</option>");
                        }
                        ?>                                         
                    </select>
                </li>
                <li>
                    <p>Cargo</p>
                    <select class="text" id="cargo2" style="width:150px;height: 26px!important;">
                        <?php
                        foreach ($cargos as $row) {
                            echo("<option value='" . $row['id'] . "'>" . $row['descripcion'] . "</option>");
                        }
                        ?>                       
                    </select>
                </li>
                <li style="margin-left: -2%" ><p >E-mail</p><input  id="email2" class="text" type="text" placeholder="" style="width: 150px;"/></li>
                <li style="margin-left: -2%" ><p >Depende </p>
                    <select class="text" id="depende_de2" style="width:150px;height: 26px!important;">
                        <?php
                        foreach ($usuarios as $row) {
                            echo("<option value='" . $row['id'] . "'>" . $row['Nombre'] . "</option>");
                        }
                        ?>                                         
                    </select>
                </li>
                <li style="margin-left: -2%" ><p >Perfil viaticos </p>
                    <select class="text" id="perfil_viatico2" style="width:150px;height: 26px!important;">
                        <?php
                        foreach ($perfiles as $row) {
                            echo("<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>");
                        }
                        ?>                                         
                    </select>
                </li>
            </ul>
            <input id="cancelar_usuario" class="botones" style="margin-bottom: 3%;height: 26px!important;line-height: 4px;margin-left:40.9%;" type="button" value="Cancelar"/>
            <input id="guardar_usuario" class="botones" style="margin-bottom: 3%;margin-left:3.8%;height: 26px!important;line-height: 4px;" type="button" value="Guardar"/>
        </div>
    </div>
</div>
<?php
include_once 'inc/footer.php';
?>
<script type="text/javascript">
    $(document).ready(function()
    {
        $("#nuevo_usuario").click(function() {
            $('#form_usuarios').css("display", "block");
        });
        /*$("#guardar_usuario").click(function() {
         $('#form_usuarios').css("display", "none");
         });*/
        $("#cancelar_usuario").click(function() {
            $('#form_usuarios').css("display", "none");
        });
        $(".editar").click(function() {
            $('#myModal').reveal($(this).data());
        });

        /*GUARDAR USUARIO*/
        $("#guardar_usuario").click(function() {
            var sucursal = $("#sucursales option:selected").text()
            var perfil = $("#perfil_viatico option:selected").text();
            var cargo = $("#cargo option:selected").text();
            var depende_de = $("#depende_de option:selected").text();


            //obtemos la variable
            var parametros = {
                "id_sucursal": $("#sucursales option:selected").val(),
                "nombre": $("#nombre").val(),
                "mail": $("#email").val(),
                "id_perfil": $("#perfil_viatico option:selected").val(),
                "cargo": $("#cargo option:selected").val(),
                "depende_de": $("#depende_de option:selected").val(),
                "accion": "agregar_usuario"
            };

            // Creamos una peticion get post ajax
            $.ajax({
                data: parametros,
                url: 'ajax_peticiones.php',
                type: 'post',
                beforeSend: function() {

                },
                success: function(response) {
                    if (response == "success") {
                        $("#loader_usuario").fadeIn(200).delay(1200).fadeOut(500);
                        $("#guardado_ok").delay(2000).fadeIn(200).fadeOut(1000);
                        $('#admin_usuarios').append('<tr><td>' + perfil + '</td><td>' + parametros.nombre + '</td><td>' + sucursal + '</td><td>' + cargo + '</td><td>' + parametros.mail + '</td><td>' + depende_de + '</td><td><a href="#" class="big-link editar" data-reveal-id="myModal" data-animation="fade"><img width="25" src="img/editar.png"/></a></td>  <td><img  src="img/guardar.png" width="25"/></td></tr>');
                    } else {
                        alert("El guardado ha fallado.");
                    }
                }
            });

        });
        /*ELIMINAR USUARIO*/
        $(".eliminar").click(function() {
            var id_usuario = this.id;

            //obtemos la variable
            var parametros = {
                "id_usuario": id_usuario,
                "accion": "eliminar_usuario"
            };

            // Creamos una peticion get post ajax
            $.ajax({
                data: parametros,
                url: 'ajax_peticiones.php',
                type: 'post',
                beforeSend: function() {

                },
                success: function(response) {
                    if (response == "success"){
                    alert("Usuario eliminado correctamente.");
                            var tr = $("#" + id_usuario + "");
                            tr.css("background-color", "#FF3700");
                            tr.fadeOut(500, function() {
                            tr.remove();
                            });
                        } else{
                            alert("El usuario tiene personas a cargo , no se puede eliminar.");
                        }}
                    });

        });
        
        /*Modificar usuario*/
       
      /*  $(".editar").click(function() {
            var id_usuario = this.id;

            //obtemos la variable
            var parametros = {
                "id_usuario": id_usuario,
                "accion": "modificar_usuario"
            };

            // Creamos una peticion get post ajax
            $.ajax({
                data: parametros,
                url: 'ajax_peticiones.php',
                type: 'post',
                beforeSend: function() {

                },
                success: function(response) {
                    if (response == "success"){
                    alert("Usuario eliminado correctamente.");
                            var tr = $("#" + id_usuario + "");
                            tr.css("background-color", "#FF3700");
                            tr.fadeOut(500, function() {
                            tr.remove();
                            });
                        } else{
                            alert("El usuario tiene personas a cargo , no se puede eliminar.");
                        }}
                    });

        });*/
    });
</script>