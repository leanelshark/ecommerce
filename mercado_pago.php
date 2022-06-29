<?php

require 'vendor/autoload.php';

MercadoPago\SDK::setAccessToken('TEST-3205006224902107-062720-4f5bb15866bdb859788a7973455851ad-265346986');

$preference = new MercadoPago\Preference();

$item = new MercadoPago\Item();
$item->id = '0001';
$item->title = 'Producto CDP';
$item->quantity = 1;
$item->unit_price = 100;
$item->currency_id = "AR";
$preference->items = array($item);

$preference->back_urls = array(
  "success" => "http://localhost/misproyectos/pasarela/captura.php",
  "failure" => "http://localhost/misproyectos/pasarela/fallo.php"
);

$preference->auto_return = "approved";
$preference->binary_mode = true;

$preference->save();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>
<body>
    <h3>MERCADO PAGO</h3>

    <div class="checkout-btn"></div>

    
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