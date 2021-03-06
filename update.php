<?php 

require_once "autoload.php";


if($_SERVER['REQUEST_METHOD'] == "POST"){

    $id = $_POST['id'];
    $company = $_POST['company'];
    $investment = $_POST['investment'];
    $date = $_POST['date'];
    // Nos aseguramos de que si el usuario no marca el check de activo no falle el script
    $active = (array_key_exists('active', $_POST)) ? $_POST['active'] : 0;
    $info = $_POST['info'];

    // Abrimos conexión con la BBDD
    $conexion = new Conexion;
    $conexion = $conexion->getConexion();
    try {
        $query = "UPDATE empresa SET company = ?, investment = ?, `date` = ?, active = ?, info = ? WHERE id = ?";
        //print_r($query); die;
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(1, $company, PDO::PARAM_STR);
        $stmt->bindParam(2, $investment, PDO::PARAM_STR);
        $stmt->bindParam(3, $date, PDO::PARAM_STR);
        $stmt->bindParam(4, $active, PDO::PARAM_BOOL);
        $stmt->bindParam(5, $info, PDO::PARAM_STR);
        $stmt->bindParam(6, $id, PDO::PARAM_STR);
        $stmt->execute();
        header("location: listado.php");
    } catch (Exception | PDOException $e) {
        echo $e->getMessage();
        print "<p class=\"aviso\">No se ha podido actualizar la información del cliente!</p>\n";
    }

// Comprobamos que los datos del formulario llegan por el método GET
}if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if($_GET['id']){
        $id = $_GET['id'];
        try {
            $cartera = new Cartera;
            $empresa = $cartera->getClient($id);

            /* echo "<pre></pre>";
            print_r($empresa);
            echo "</pre>"; */

            $company = $empresa->getCompany();
            $investment = $empresa->getInvestment();
            $date = $empresa->getDate();
            $active = ($empresa->getActive()) ? 'checked' : "";
            $info = $empresa->getInfo();


            $htmlForm = <<<HTML
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Client Form</title>
	<link rel="stylesheet" type="text/css" href="form/view.css" media="all">
	<script type="text/javascript" src="form/view.js"></script>
</head>

<body id="main_body">

	<img id="top" src="form/top.png" alt="">
	<div id="form_container">

		<h1><a>Client Form</a></h1>
		<form id="form_36409" class="appnitro" method="POST" action="update.php">
			<div class="form_description">
				<h2>Client Form</h2>
				<p>DAW Advertising</p>
			</div>
			<ul>

				<li id="li_1">
					<label class="description" for="id">ID </label>
					<div>
						<input id="id" name="id" class="element text small" type="text" maxlength="10" value="$id" readonly/>
					</div>
				</li>
				<li id="li_2">
					<label class="description" for="company">Company </label>
					<div>
						<input id="company" name="company" class="element text medium" type="text" maxlength="255" value="$company" />
					</div>
				</li>
				<li id="li_3">
					<label class="description" for="investment">Investment </label>
					<div>
						<input id="investment" name="investment" type="number" value="$investment" />
					</div>
				</li>
				<li id="li_4">
					<label class="description" for="date">Date </label>
					<input id="date" name="date" type="date" value="$date"/>
				</li>
				<li id="li_5">
					<span>
						<input id="active" name="active" class="element checkbox" type="checkbox" value="1" {$active}/>
						<label class="choice" for="active">Active?</label>
					</span>
				</li>
                <li id="li_3">
                    <label class="description" for="info">Info </label>
                    <div>
                        <textarea id="info" name="info" cols="50" rows="5">$info</textarea>
                    </div>
                </li>
				<li class="buttons">
					<input id="saveForm" class="button_text" type="submit" name="submit" value="Enviar" />
				</li>
			</ul>
		</form>
        <a href="listado.php">Volver</a>
		<div id="footer">
			Generated by <a href="http://www.dawadvertising.org">DAW Advertising</a>
		</div>
	</div>
	<img id="bottom" src="form/bottom.png" alt="">
</body>

</html>
HTML;

        print($htmlForm);

        } catch (Exception $e) {
            print "<p class=\"aviso\">Datos del cliente no encontrados!</p>\n";
        }

    }else{
        header("location: listado.php");
    }
}




?>