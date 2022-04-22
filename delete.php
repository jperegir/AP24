<?php 

require_once "autoload.php";

// Comprobamos que los datos del formulario llegan por el método GET
if ($_SERVER['REQUEST_METHOD'] == "GET") {

    if($_GET['id']){
        $id = $_GET['id'];
        // Abrimos conexión con la BBDD
        $conexion = new Conexion;
        $conexion = $conexion->getConexion();
        try {
            $query = "DELETE FROM empresa WHERE id = :id";
            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            header("location: listado.php");
        } catch (Exception | PDOException $e) {
            print "<p class=\"aviso\">No se ha podido eliminar el cliente!</p>\n";
        }
    }else{
        header("location: listado.php");
    }
}