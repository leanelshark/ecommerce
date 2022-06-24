<?php
require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : ''; //id del producto
$token = isset($_GET['token']) ? $_GET['token'] : ''; //token de la url

if ($id == '' || $token == '') {
    echo'ERROR: missing ID or token.';
    exit;
}else{

        $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

        if ($token == $token_tmp) {

            $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1 ");
            $sql->execute([$id]);

            if($sql->fetchColumn() > 0){
                $sql = $con->prepare("SELECT nombre,descripcion,precio, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
                $sql->execute([$id]);
                $row = $sql->fetch(PDO::FETCH_ASSOC);
                $nombre = $row['nombre'];
                $descripcion = $row['descripcion'];
                $precio = $row['precio'];
                $descuento = $row['descuento'];
                $precio_desc = $precio - (($precio * $descuento) / 100);
                $dir_images = 'images/productos/'.$id.'/';

                $rutaImg = $dir_images . 'principal.webp';

                    if(!file_exists($rutaImg)){
                        $rutaImg = 'images/no-photo.png';
                    }

                $imagenes = array();
                if(file_exists($dir_images)){
                    $dir = dir($dir_images);

                
                //  $dir = dir($dir_images); 


                    while(($archivo = $dir->read()) != false){
                        if($archivo != 'principal.webp' && (strpos($archivo,'webp') || strpos($archivo, 'png'))){
                            $imagenes[]= $dir_images . $archivo;
                        }
                    }

                    $dir->close();
                }
            }
        } else{
            echo 'ERROR: invalid token.';
            exit;
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
                            <a class="nav-link" href="index.php">Contacto</a>
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

    <div class="container mt-5">
        <div class="row ">

            <div class="col-md-6 order-md-1 ">
                
                <div id="carouselImage" class="carousel slide" data-bs-ride="carousel">

                        <div class="carousel-inner">

                            <div class="carousel-item active">
                                <img  src="<?php echo $rutaImg; ?>" class="d-block w-100" alt="First slide">
                            </div>

                            <?php foreach($imagenes as $img){ ?>

                            <div class="carousel-item">
                                <img src="<?php echo $img; ?>" class="d-block w-100" alt="Second slide">
                            </div>

                            <?php } ?>

                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselImage"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselImage"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                </div>

            </div>

            <div class="col-md-6 order-md-2">

                <h2 class="mb-3 text-primary">
                  <?php echo $nombre; ?>
                </h2>

                <?php if($descuento > 0){ ?>

                    
                        <h5 class="text-danger">
                            <del><?php echo MONEDA . number_format($precio, 2, '.',','); ?></del>
                        </h5>
                
                        <h2 class="text-success">
                            <?php echo MONEDA . number_format($precio_desc, 2, '.',','); ?>
                            <span class="text-warning">
                                 <?php echo $descuento; ?> % descuento
                            </span>
                        </h2>

                <?php } else { ?>

                <h2 class="text-success">
                    <?php echo MONEDA . number_format($precio, 2, '.',','); ?>
                </h2>

                <?php } ?>

                <p class="lead">
                    <?php echo $descripcion; ?>
                </p>

                <div class="d-grid gap-3 col-10 mx-auto">
                    <a href="checkout.php" 
                            type="button" 
                            class="btn btn-primary">
                            Comprar Ahora
                    </a>
                    <button href="carrito.php" 
                            type="button" 
                            class="btn btn-outline-primary" 
                            onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp;  ?>')">
                            Agregar al carrito
                    </button>
                </div>

            </div>

        </div>
        
    </div>



    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
    </script>

    <script>
        function addProducto(id,token){
            let url = 'clases/carrito.php'
            let formData = new FormData()
            formData.append('id', id)
            formData.append('token', token)

            fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(response => response.json()) 
            .then(data =>{
                if(data.ok){
                    let elemento = document.getElementById('num_cart')
                    elemento.innerHTML = data.numero
                }
            })
        }

    </script>

</body>

</html>