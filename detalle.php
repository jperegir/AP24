<?php 
    require_once('autoload.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Cliente</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="./public/styles/bootstrap.min.css"></script>
    <link rel="stylesheet" href="./public/styles/detalle.css"></script>
</head>
<body>
    <div class="container my-5">
        <table class="table">
        <!-- <table class="redTable"> -->
            <!-- <thead>
                <tr>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="2"></td>
                </tr>
            </tfoot> -->
            <tbody>
                <tr>
                    <?php 
                          if($_SERVER['REQUEST_METHOD'] == 'GET'){
                              if(isset($_GET['id'])){
                                  $id = $_GET['id'];
                                  $cartera = new Cartera();
                                  //echo html_entity_decode($cartera->drawDetail($id));
                                  echo $cartera->drawDetail($id);
                              }
                          }
                      ?>
                <tr>
            </tbody>
        </table>
    </div>
    <div class="container">
        <a class="btn btn-success my-4" href="listado.php">Volver</a>
    </div>
    <script src="./public/scripts/detalle.js"></script>
    
</body>
</html>