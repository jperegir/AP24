<?php

class Conexion
{
    // Propiedad para almacenar la conexión a la BBDD
    protected $conexion;

    public function __construct()
    {
        // Cargamos los datosde conexión desde fichero JSON
        $dataJson = file_get_contents(dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . "conf/conf.json");
        // Convertimos la info de conexión a un array
        $dbconfig = json_decode($dataJson, true);
        // Definimos la cadena de conexión
        $conection_string = "mysql:host={$dbconfig['host']};port={$dbconfig['port']};dbname={$dbconfig['database']};charset=utf8";
        // Establecemos la conexión a la BBDD
        try {
            $this->conexion = new PDO($conection_string, $dbconfig['user'], $dbconfig['password']);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $conexion = null;
            echo 'Falló la conexión: ' . $e->getMessage();
        }
    }

    /**
     * Cierra la conexión a la BBDD
     */
    public function __destruct()
    {
        $this->conexion = null;
    }

    /**
     * Obtiene la conexión a la BBDD
     * @return Conexion resultado de la conexión a la BBDD
     */
    public function getConexion()
    {
        return $this->conexion;
    }
}
