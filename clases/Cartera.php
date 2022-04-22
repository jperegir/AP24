<?php

require_once('autoload.php');

class Cartera extends Conexion
{
    static $rootPath;
    private $clientes = [];

    public function __construct()
    {
        self::$rootPath = dirname(__DIR__, 1);
        // echo self::$rootPath;
        // echo "<br>";
        parent::__construct();
    }

    
    /**
     * Devuelve la ruta absoluta de la raíz del proyecto
     * @return String Ruta raíz absoluta del proyecto
     */
    public function getRootPath()
    {
        return self::$rootPath;
    }

    /**
     * Lee datos desde fichero CSV y los importa a BBDD
     * La ubicación de los ficheros CSV es ProjectRoot/Data
     * @param String $csvFileName Nombre del fichero sin extensión
     */
    public function import($csvFileName)
    {
        $csvDataPath = $this->getRootPath() . DIRECTORY_SEPARATOR . 'Data';
        $csvFilePath = $csvDataPath . DIRECTORY_SEPARATOR . $csvFileName . '.csv';
        try {
            $this->conexion->beginTransaction();
            $sqlDelete = "DELETE FROM empresa";
            // TRUNCATE TABLE empresa;
            $rowsDeleted = $this->conexion->exec($sqlDelete);
            echo "Filas borradas " . $rowsDeleted . "<br>";
            $stmtInsert = $this->conexion->prepare("INSERT INTO empresa VALUES(?,?,?,?,?,?)");
            $stmtInsert->bindParam(1, $id, PDO::PARAM_STR);
            $stmtInsert->bindParam(2, $company, PDO::PARAM_STR);
            $stmtInsert->bindParam(3, $investment, PDO::PARAM_STR);
            $stmtInsert->bindParam(4, $date, PDO::PARAM_STR);
            $stmtInsert->bindParam(5, $active, PDO::PARAM_BOOL);
            $stmtInsert->bindParam(6, $info, PDO::PARAM_STR);

            if (($gestor = fopen("Data/data.csv", "r")) !== false) {
                $fila = 0;
                while (($datos = fgetcsv($gestor, 1000, ",")) !== false) {
                    $numero = count($datos);
                    $num_inserts = $numero;
                    echo "<p> $numero campos en la línea $fila: <br /></p>\n";
                    $id = $datos[0];
                    $company = $datos[1];
                    $investment = $datos[2];
                    $date = $datos[3];
                    $active = ($datos[4] == 'True') ? true : false;
                    $info = $datos[5];
                    echo $id . "<br/>\n";
                    echo $company . "<br/>\n";
                    echo $investment . "<br/>\n";
                    echo $date . "<br/>\n";
                    echo $active . "<br/>\n";
                    $stmtInsert->execute();
                    $fila++;
                }
                fclose($gestor);
                echo "Filas importadas con éxito " . $fila . "<br>\n";
                $this->conexion->commit();
            }
        } catch (Exception | PDOException $e) {
            echo "Error de importación! de datsos desde el fichero CSV " . $csvFileName;
            $stmtInsert->debugDumpParams();
        }
    }

    /**
     * Consulta las empresas definidas en la BBDD y carga sus datos en diferentes posiciones de una array
     */
    public function getAll()
    {
        try {
            $sqlAll = "SELECT * FROM empresa order by 4 desc";
            $rowsAll = $this->conexion->query($sqlAll);
            /* while ($empresa = $rowsAll->fetch(PDO::FETCH_ASSOC)) {
            echo $empresa . "<br>";
            array_push($this->clientes, new Empresa(
            $empresa["id"],
            $empresa["company"],
            floatval($empresa["investment"]),
            $empresa["date"],
            boolval($empresa["active"]),
            $empresa["info"]
            ));
            } */
            $result = $rowsAll->fetchAll(PDO::FETCH_ASSOC);
            /* echo "<pre>";
            print_r($result);
            echo "</pre>"; */
            foreach ($result as $empresa) {
                /* echo "<pre>";
                print_r($empresa);
                echo "</pre>";
                echo "<br>"; */
                array_push($this->clientes, new Empresa(
                    $empresa['id'],
                    $empresa['company'],
                    floatval($empresa["investment"]),
                    $empresa["date"],
                    boolval($empresa["active"]),
                    $empresa["info"]
                ));
            }
        } catch (PDOException $e) {
            echo 'Falló la consulta: ' . $e->getMessage();
        }
        /* echo "<pre>";
        print_r($this->clientes);
        echo "<pre>";
        echo "<br>"; */
    }


    /**
     * Genera una tabla a modo de listado a partir de los datos contenidos en un array
     * @return String Tabla HTML en formato cadena
     */
    public function drawList()
    {
        $output = "";
        foreach ($this->clientes as $client) {
            $output .= "<tr>";
            $output .= "    <td>" . $client->getId() . "</td>";
            $output .= "    <td><a class='btn btn-primary btn-sm' data-id='" . $client->getId() . "' href='detalle.php?id=" . $client->getId() . "'>" . $client->getCompany() . "</a></td>";
            $output .= "    <td>" . number_format(intval($client->getInvestment()), 2, "'", ".") . " €</td>";
            $output .= "    <td>" . date("F d, Y", strtotime($client->getDate())) . "</td>";
            $output .= "    <td>";
            $output .= ($client->getActive()) ?
                "<img src='img/img05.gif'>" :
                "<img src='img/img06.gif'>";
            $output .= "    </td>";
            $output .=     "<td><a href='delete.php?id=" . $client->getId() . "'><img src='img/del_icon.png' width='25'></a></td>";
            $output .=     "<td><a href='update.php?id=" . $client->getId() . "'><img src='img/edit_icon.png' width='25'></a></td>";        
            $output .= "</tr>";
        }
        return $output;
    }


    /**
     * Consulta en la BBDD los datos de una empresa en base a su ID
     * @param String $id Identificador de empresa en BBDD
     * @return Object Objeto Empresa
     */
    function getClient($id)
    {
        try {
            $stmtClient = $this->conexion->prepare("SELECT * FROM empresa WHERE id = :id");
            $stmtClient->bindParam(':id', $id, PDO::PARAM_STR);
            if ($stmtClient->execute() && $stmtClient->rowCount() > 0) {
                $empresa = $stmtClient->fetch(PDO::FETCH_ASSOC);
                return new Empresa(
                    $empresa["id"],
                    $empresa["company"],
                    floatval($empresa["investment"]),
                    $empresa["date"],
                    boolval($empresa["active"]),
                    $empresa["info"]
                );
            }
        } catch (Exception | PDOException $e) {
            echo 'Falló la consulta: ' . $e->getMessage();
        }
        return new Empresa(null, null, null, null, null, null);
    }

    /**
     * Genera una tabla HTML a partir de los datos contenidos en un objeto de tipo Empresa
     * @param String $id Identificador de empresa en BBDD
     * @return String Tabla HTML en formato cadena
     */
    public function drawDetail($id)
    {
        $client = $this->getClient($id);
/*         $output = "";
        $output .= "<tr><th>Id</th><td>" . $client->getId() . "</td></tr>";
        $output .= "<tr><th>Company</th><td>" . $client->getCompany() . "</td></tr>";
        $output .= "<tr><th>Investment</th><td>" . number_format(intval($client->getInvestment()), 2, "'", ".") . " €</td></tr>";
        $output .= "<tr><th>Date</th><td>" . date("F d, Y", strtotime($client->getDate())) . "</td></tr>";
        $output .= "<tr><th>Active</th><td>";
        $output .= ($client->getActive()) ?
            "<img src='img/img05.gif'>" :
            "<img src='img/img06.gif'>";
        $output .= "</td></tr>";
        $output .= "<tr><th>Info</th><td>" . $client->getInfo() . "</td></tr>"; */

        $id = $client->getId();
        $company = $client->getCompany();
        $number = number_format(intval($client->getInvestment()), 2, "'", ".");
        $fecha = date("F d, Y", strtotime($client->getDate()));
        $imagen = $client->getActive() ? "<img src='img/img05.gif'>" : "<img src='img/img06.gif'>";
        $info = $client->getInfo();

        // SINTAXIS HEREDOC
        $output = <<<HTML
    <tr><th>Id</th><td>{$id}</td></tr>
    <tr><th>Company</th><td>{$company}</td></tr>
    <tr><th>Investment</th><td>{$number}€</td></tr>
    <tr><th>Date</th><td>{$fecha}</td></tr>
    <tr><th>Active</th><td>{$imagen}</td></tr>
    <tr><th>Info</th><td>{$info}</td></tr>
HTML;
        return $output;
    }

}
