<?php

function ejecutarConsulta($query)
{
    $values = array();

    $conn = @ mysqli_connect(SERVER_LOCAL, USER_DB_LOCAL, PASS_DB_LOCAL, BASE_LOCAL);
    mysqli_set_charset($conn, 'utf8');

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
                $values[] = $fila;
            }

            return $values;
        }

        return array();
    } else
        return true;
}

function ejecutarConsultaPostgre($query)
{
    $values = array();

    $conn = mysqli_connect(SERVER_BANCO_PERSONAL, USER_DB_BANCO_PERSONAL, PASS_DB_BANCO_PERSONAL, BASE_BANCO_PERSONAL);
    mysqli_set_charset($conn, 'utf8');

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
                $values[] = $fila;
            }

            return $values;
        }

        return array();
    } else
        return true;
}

function obtener_ultimo_id($tabla_nombre)
{
    $sql = "SELECT MAX(id) as max FROM " . $tabla_nombre;
    $rs = ejecutarConsulta($sql);

    return $rs[0]['max'];
}

function obtener_campo_por_tabla_e_id($campo,$id,$tabla)
{
	$sql = "SELECT ".$campo." FROM " . $tabla." WHERE id = ".$id;
    $rs = ejecutarConsulta($sql);

    return $rs[0][$campo];
}