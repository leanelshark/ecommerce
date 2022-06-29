<?php

require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$id_transaccion = isset($_GET['key']) ? $_GET['key'] : '0';

$error = '';
if ($id_transaccion == ''){
    $error = 'Error al procesar la petición';
}else{
    $sql = $con->prepare("SELECT count(id) FROM compra WHERE id_transaccion=? AND status=? ");
    $sql->execute([$id_transaccion, 'COMPLETED']);

    if($sql->fetchColumn() > 0){
        $sql = $con->prepare("SELECT id, fecha, email, total FROM compra WHERE id_transaccion=? AND status=? LIMIT 1");
        $sql->execute([$id_transaccion, 'COMPLETED']);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        $idCompra = $row['id'];
        $total = $row['total'];
        $fecha = $row['fecha'];

        $sqlDet = $con->prepare("SELECT nombre, precio, cantidad FROM detalle_compra WHERE id_compra =?");
        $sqlDet->execute([$idCompra]);
    } else{
        $error = 'Error al comprobar la compra';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <!--HEADER-->

    <header>

        <div class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a href="index.php" class="navbar-brand ">
                    <strong>E-commerce</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader"
                    aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarHeader">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contacto.php">Contacto</a>
                        </li>
                    </ul>

                    <a href="checkout.php" class="btn btn-primary">
                        Cart <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
                    </a>
                </div>
            </div>
        </div>

    </header>

    <!--MAIN-->

    <main>
        <div class="container mt-5">

            <?php if(strlen($error) > 0) { ?> 
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <div class="col">
                        <h3><?php echo $error; ?></h3>
                    </div>
                </div>
            <?php } else { ?>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <div class="col text-white">
                        <b>Folio de compra: </b> <?php echo $id_transaccion; ?><br>
                        <b>Fecha de compra: </b> <?php echo $fecha; ?><br>
                        <b>Total: </b> <?php echo MONEDA . number_format($total, 2,'.',','); ?> <br>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <div class="col">
                        <table class="table">
                            <thead class="text-white">
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Producto</th>
                                    <th>Importe</th>
                                </tr>
                            </thead>

                            <tbody class="text-white">
                                <?php while($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)) {
                                    $importe = $row_det['precio']*$row_det['cantidad'] ?>
                                    <tr>
                                        <td><?php echo $row_det['cantidad']; ?></td>
                                        <td><?php echo $row_det['nombre']; ?></td>
                                        <td><?php echo $importe; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>
    
</body>
</html>