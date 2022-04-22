<?php

require_once('autoload.php');

// Comprobamos que los datos del formulario llegan por el método POST
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Comprobamos que llegan los datos esperados
    if ($_POST['id'] && $_POST['company'] && $_POST['inversion'] && $_POST['fecha']) {
        // Guardamos los datos de conexión a la BBDD
        $id = $_POST['id'];
        $company = $_POST['company'];
        $inversion = $_POST['inversion'];
        $fecha = $_POST['fecha'];
        // Nos aseguramos de que si el usuario no marca el check de activo no falle el script
        $activo = (array_key_exists('active', $_POST)) ? $_POST['active'] : 0;
        // Abrimos conexión con la BBDD
        $conexion = new Conexion;
        $conn = $conexion->getConexion();
        // Generamos una consulta preparada de inserción de datos y la ejecutamos
        try {
            $query = "INSERT INTO empresa(id, company, investment, date, active) VALUES (:id, :company, :investment, :fecha, :active)";
            $result = $conn->prepare($query);
            if (!$result) {
                print "<p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } elseif (!$result->execute([":id" => $id, ":company" => $company, ":investment" => $inversion, ":fecha" => $fecha, ":active" => $activo])) {
                print "<p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } else {
                /* print "    <p>Registro creado correctamente.</p>\n";
                print "\n"; */
                header("location: listado.php");
            }
        } catch (Exception | PDOException $e) {
            echo 'Falló la conexión: ' . $e->getMessage();
        }
    }else {
        print "<p class=\"aviso\">\"No es posible crear el nuevo cliente. Por favor, informe todos los campos!\"</p>\n";
    }
}
