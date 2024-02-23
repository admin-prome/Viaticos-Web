<?php
require_once 'inc/checkLogin.php';
require_once 'inc/const.php';

require_once 'inc/header.php';

// require_once 'inc/menu_administrador.php';

require_once 'inc/funciones/all_functions_sin_admin.php';

?>

<h1 class="ayudas" style="font-size: 16px;">
    Topes del sistema:
</h1>

<div style="background-color: #eeeced;">
    <table id="tabla_tope" class="display" cellspacing="0" style="width: 0px auto;">

        <thead>

            <tr style="text-align:left;">

                <th>Concepto</th>
                <th>Tope $</th>
                <th>Guardar</th>

            </tr>

        </thead>

        <tbody>

            <tr>
                <td> Tope cena</td>
                <td>
                    <input type="text" size="8" maxlength="5"
                           id="tope_cena_<?= CM; ?>"  value="<?=
                           obtener_tope_almuerzo_cena_segun_sucursal(CM, 'tope_cena');
                           ;
                           ?>" />
                </td>
                <td><input type="submit" class="botones guardar" value="Guardar" id="tope_cena-<?= CM; ?>" /></td>
            </tr>
            <tr>
                <td>Tope Almuerzo</td>
                <td>
                    <input type="text" size="8" maxlength="5"
                           id="tope_almuerzo_<?= CM; ?>"  value="<?= obtener_tope_almuerzo_cena_segun_sucursal(CM, 'tope_almuerzo'); ?>" />
                </td>
                <td><input type="submit" class="botones guardar" value="Guardar" id="tope_almuerzo-<?= CM; ?>" /></td>
            </tr>
            <tr>
                <td>Tope Guarderia</td>
                <td>
                    <input type="text" size="8" maxlength="5"
                           id="tope_guarderia_<?= CM; ?>"  value="<?= obtener_tope_almuerzo_cena_segun_sucursal(CM, 'tope_guarderia'); ?>" />
                </td>
                <td><input type="submit" class="botones guardar" value="Guardar" id="tope_guarderia-<?= CM; ?>" /></td>
            </tr>
        </tbody>

    </table>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        $('#tabla_tope').dataTable({
            "paging": false,
            "ordering": true,
            "order": [[1, "desc"]],
            "info": false,
            "search": false,
            "bFilter": false
        });

        $(".guardar").click(function () {

            campo = this.id;
            valores = this.id.split('-');
            tipo = valores[0];
            sucursal = valores[1];
            tope = $("#" + tipo + "_" + sucursal).val();

            if (validar_numero(tope))
            {
                var parametros = {
                    "accion": "update_tope_almuerzo_cena",
                    "tope": tope,
                    "sucursal": sucursal,
                    "tipo": tipo
                };

                $.ajax({
                    url: 'ajax_peticiones.php',
                    data: parametros,
                    type: 'post',
                    success: function (response)
                    {
                        alert('Tope actualizado.');
                    }

                });

            }
            else
            {
                alert('El valor ingresado NO es num\u00e9rico o quiz\u00e1s est\u00e9 en blanco. Verifique');

                $("#tope_almuerzo_<?= CM; ?>").focus();
            }

        });
    });

</script>
<?php include 'inc/footer.php'; ?>