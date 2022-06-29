<?php

require 'config/config.php';
require 'config/database.php';
require 'vendor/autoload.php';

MercadoPago\SDK::setAccessToken(TOKEN_MP);

$preference = new MercadoPago\Preference();
$productos_mp = array();


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
    <script src="https://sdk.mercadopago.com/js/v2"></script>
   

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
                <div class="row">
                    <div class="col-12">
                        <div id="paypal-button-container"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="checkout-btn"></div>
                    </div>
                </div>
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

                                    $item = new MercadoPago\Item();
                                    $item->id = $_id;
                                    $item->title = $nombre;
                                    $item->quantity = $cantidad;
                                    $item->unit_price = $precio_desc;
                                    $item->currency_id = "AR";

                                    array_push($productos_mp, $item);
                                    unset($item);
                                
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
                                    <td colspan="2">
                                        <p class="h3 text-end text-white" id="total"><?php echo MONEDA . number_format($total, 2,'.',','); ?></p>
                                    </td>
                            </tr>      
                        </tbody>

                        <?php } ?>
                        
                    </table>
                </div>
            </div>
        
        </div>

    </div> 

    <?php 

    $preference->items = $productos_mp;
    $preference->back_urls = array(
        "success" => "http://localhost/misproyectos/pasarela/captura.php",
        "failure" => "http://localhost/misproyectos/pasarela/fallo.php"
    );
    $preference->auto_return = "approved";
    $preference->binary_mode = true;

    $preference->save();
      

    ?>


    <!-- JavaScript Bundle with Popper -->
  
  

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
            // Set up the transaction
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: <?php echo $total; ?>
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

            
           
            actions.order.capture().then(function(detalles) {

                console.log(detalles)

                let url = 'clases/captura.php'
            
                return fetch(url, {
                    method: 'post',
                    headers: {
                        'content-type': 'application/json'
                    },
                    body: JSON.stringify({
                        detalles:detalles
                    })
                }).then(function(response){
                    window.location.href = "completado.php?key=" + detalles['id'];
                })
            });
        },
        onCancel: function (data) {
            // Show a cancel page, or return to cart
            alert("Pago cancelado");
            console.log(data);
        },
        onError: function (err) {
            // For example, redirect to a specific error page
            alert("Error al procesar el pago");
            console.log(data);
        }
        }).render('#paypal-button-container');

    </script>

    <script>
    // Agrega credenciales de SDK
    const mp = new MercadoPago("TEST-31df5324-1bc7-45f3-8a5b-2c4e45b6bcb7", {
        locale: "es-AR",
    });

    // Inicializa el checkout
    mp.checkout({
        preference: {
        id: "<?php echo $preference->id; ?>",
        },
        render: {
        container: ".checkout-btn", // Indica el nombre de la clase donde se mostrará el botón de pago
        label: "Pagar con MercadoPago", // Cambia el texto del botón de pago (opcional)
        },
    });
    </script>

  
</body>

</html>