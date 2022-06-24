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
}

//session_destroy();
//print_r($_SESSION);

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
    <div class="container mt-5 text-white">
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                
                <thead>
                    <tr>
                        <th class="text-white">Producto</th>
                        <th class="text-white">Precio</th>
                        <th class="text-white">Cantidad</th>
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
                            <?php echo MONEDA . number_format($precio_desc, 2,'.',','); ?>
                        </td>
                        <td class="text-white">
                            <input type="number" min="1" max="10" step="1" value="<?php echo $cantidad ?>" size="5" id="cantidad_<?php echo $_id; ?>" onchange="actualizaCantidad(this.value, <?php echo $_id; ?> )">
                        </td>
                        <td class="text-white">
                            <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal,2,'.',','); ?></div>
                        </td>
                        <td class="text-white">
                            <a href="#" id="eliminar" class="btn btn-warning btn-sm" data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal">Eliminar</a>
                            </a>
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
        
        <?php if ($lista_carrito != null) { ?>
        <div class="row">
            <div class="col-md-5 offset-md-7 d-grid gap-2">
                <a href="pago.php" class="btn btn-primary btn-lg">Realizar Pago</a>
            </div>
        </div>
        <?php } ?>

    </div> 

    <!-- Modal -->
    <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eliminaModalLabel">Alerta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Realmente desea eliminar el producto de la lista?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button id="btn-elimina" type="button" class="btn btn-danger" onclick="eliminar()">Eliminar</button>
            </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
    </script>

    <script>
            let eliminaModal = document.getElementById('eliminaModal')  
            eliminaModal.addEventListener('show.bs.modal', function(event){
                let button = event.relatedTarget
                let id = button.getAttribute('data-bs-id')
                let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')
                buttonElimina.value = id
            })          

            function actualizaCantidad(cantidad, id){
                let url = 'clases/actualizar_carrito.php';
                let formData = new FormData();
                formData.append('action','agregar');
                formData.append('id', id);
                formData.append('cantidad', cantidad);

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json()) 
                .then(data =>{
                    if(data.ok){

                        let divsubtotal = document.getElementById('subtotal_' + id);
                        divsubtotal.innerHTML = data.sub;

                        let total = 0.00
                        let list = document.getElementsByName('subtotal[]')

                        for(let i = 0; i<list.length;i++){
                            total += parseFloat(list[i].innerHTML.replace(/[$,]/g, ''))
                        }

                        total = new Intl.NumberFormat('es-US', {
                            minimumractionDigits: 2
                        }).format(total)
                        document.getElementById('total').innerHTML = '<?php echo MONEDA; ?>' + total 
                    
                        
                    }
                }); 
            }

            function eliminar(){

                let botonElimina = document.getElementById('btn-elimina')
                let id = botonElimina.value

                let url = 'clases/actualizar_carrito.php';
                let formData = new FormData();
                formData.append('action','eliminar');
                formData.append('id', id);
               

                fetch(url, {
                    method: 'POST', 
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json()) 
                .then(data =>{
                    if(data.ok){
                        location.reload()
                    }
                }); 
            }


    </script>

</body>

</html>