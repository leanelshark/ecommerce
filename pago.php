<?php

require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

$lista_carrito = array(); 

if($productos !=null){
    foreach($productos as $clave => $cantidad){

        $sql = $con->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id=? AND activo=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
} else{
    header("Location: index.php");
    exit;
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

    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&components=buttons"></script>
   
   

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
    <div class="container mt-5 text-white">

        <div class="row">
            
            <div class="col-6">
                <h4>Detalles de pago</h4>
                <div id="paypal-button-container"></div>

            </div>
            
            <div class="col-6">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        
                        <thead>
                            <tr>
                                <th class="text-white">Producto</th>
                                <th class="text-white">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody class="text-white">
                            <?php if($lista_carrito == null){
                                echo '<tr>
                                        <td colspan="5" class="text-center">
                                            <b class="text-white">Lista vacia</b>
                                        </td>
                                    </tr>';
                            } else {
                                $total = 0;
                                foreach($lista_carrito as $producto){
                                    $_id = $producto['id'];
                                    $nombre = $producto['nombre'];
                                    $precio = $producto['precio'];
                                    $descuento = $producto['descuento'];                            
                                    $cantidad = $producto['cantidad'];
                                    $precio_desc = $precio - (($precio * $descuento) / 100);
                                    $subtotal = $cantidad * $precio_desc;
                                    $total += $subtotal;
                                
                                ?>
                            <tr">
                                <td class="text-white">
                                    <?php echo $nombre; ?>
                                </td>
                                
        
                                <td class="text-white">
                                    <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal,2,'.',','); ?></div>
                                </td>
                                
                            </tr>

                                <?php } ?>

                            <tr class="text-white">
                                    <td colspan="3"></td>
                                    <td colspan="2">
                                        <p class="h3 text-right text-white" id="total"><?php echo MONEDA . number_format($total, 2,'.',','); ?></p>
                                    </td>
                            </tr>      
                        </tbody>

                        <?php } ?>
                        
                    </table>
                </div>
            </div>
        
        </div>

    </div> 



    <!-- JavaScript Bundle with Popper -->
  
  

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
            // Set up the transaction
            return actions.order.create({
                purchase_units: [{
                amount: {
                    value: '100.00'
                }
                }]
            });
            },
            style: {
            layout: 'vertical',
            color:  'blue',
            shape:  'rect',
            label:  'paypal'
        },
        onApprove: function(data, actions) {
            // This function captures the funds from the transaction.
            return actions.order.capture().then(function(details) {
            // This function shows a transaction success message to your buyer.
            window.location.href = "completdo.html";
            
            });
        },
        onCancel: function (data) {
            // Show a cancel page, or return to cart
            window.location.href = "completdo.html";
        },
        onError: function (err) {
            // For example, redirect to a specific error page
            window.location.href = "completdo.html";
        }
        }).render('#paypal-button-container');

    </script>

  
</body>

</html>