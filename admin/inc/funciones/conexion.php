<?php

function ejecutarConsulta($query)
{
    $values = array();

    $conn = @ mysqli_connect(SERVER_LOCAL, USER_DB_LOCAL, PASS_DB_LOCAL, BASE_LOCAL);

    if (!$conn) {
        echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
        echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
        echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    $rs = @ mysqli_query($conn, $query);

    if (!$rs) {
        mysqli_close($conn);
        echo "Error al ejecutar la consulta." . PHP_EOL;
        printf("Errormessage: %s\n", mysqli_error($conn));
        exit;
    }

    mysqli_close($conn);

    $partes_string = explode(' ', $query);

    if (strtoupper($partes_string[0]) == 'SELECT') {
        if (mysqli_num_rows($rs) > 0) {

            // Devuelve los resultados en un array asociativo
            for ($i = 0; $fila = mysqli_fetch_assoc($rs); $i++) {
                while ($property = mysqli_fetch_field($rs)) {
                    $campo = $property->name;
                    $values[$i][$campo] = $fila[$campo];
                }
            }

            return $values;
        }

        return array();
    } else
        return true;
}
