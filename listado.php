<?php 
    require_once('autoload.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="./css/bootstrap.min.css"></script>
    <title>Listado Clientes</title>
</head>
<body>
    <div class="container mt-5">
    <a href="form.html" class="btn btn-success col-md-12 text-center" role="button">Nuevo Cliente</a>
    </div>
    <div class="container my-5">
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Company</th>
                    <th scope="col">Investment</th>
                    <th scope="col">Date</th>
                    <th scope="col">Active</th>
                    <th scope="col-2">Del</th>
                    <th scope="col-2">Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $cartera = new Cartera();
                    $cartera->getAll();
                    echo $cartera->drawList();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>