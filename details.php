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

                $rutaImg = $dir_images . 'principal.jpeg';

                    if(!file_exists($rutaImg)){
                        $rutaImg = 'images/no-photo.png';
                    }

                $imagenes = array();
                if(file_exists($dir_images)){
                    $dir = dir($dir_images);

                
                //  $dir = dir($dir_images); 


                    while(($archivo = $dir->read()) != false){
                        if($archivo != 'principal.jpg' && (strpos($archivo,'jpg') || strpos($archivo, 'jpeg'))){
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

    

    <nav class="navbar navbar-expand-lg navbar-light bg-light position-fixed top-0 start-0 w-100">
        <div class="container">
                <a href="index.php" class="navbar-brand d-lg-none ">
                   E-commerce
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse p-2 flex-column" id="navbarHeader">
                    <div class="d-flex justify-content-center justify-content-lg-between flex-column flex-lg-row w-100">
                        <form class="d-flex">
                            <input type="search" class="form-control me-2" placeholder="Search"/>
                            <button class="btn btn-outline-dark" type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search-heart" viewBox="0 0 16 16"><path d="M6.5 4.482c1.664-1.673 5.825 1.254 0 5.018-5.825-3.764-1.664-6.69 0-5.018Z"/><path d="M13 6.5a6.471 6.471 0 0 1-1.258 3.844c.04.03.078.062.115.098l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1.007 1.007 0 0 1-.1-.115h.002A6.5 6.5 0 1 1 13 6.5ZM6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z"/></svg>
                            </button>
                        </form>
                        <a class="navbar-brand d-none d-lg-block" href="index.php">E-commerce</a>

                        <ul class="navbar-nav">
                            <li class="nav-item d-flex align-items-center">
                                <a class="nav-link mx-2" aria-current="page" href="index.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-heart" viewBox="0 0 16 16"><path d="M9 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm-9 8c0 1 1 1 1 1h10s1 0 1-1-1-4-6-4-6 3-6 4Zm13.5-8.09c1.387-1.425 4.855 1.07 0 4.277-4.854-3.207-1.387-5.702 0-4.276Z"/></svg>
                                    My Account</a>
                            </li>
                            <li class="nav-item d-flex align-items-center">
                                <a class="nav-link mx-2" href="checkout.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag-heart" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M10.5 3.5a2.5 2.5 0 0 0-5 0V4h5v-.5Zm1 0V4H15v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4h3.5v-.5a3.5 3.5 0 1 1 7 0ZM14 14V5H2v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1ZM8 7.993c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132Z"/></svg>
                                    Bag
                                </a>
                                <span id="num_cart" class="badge rounded-pill bg-secondary"><?php echo $num_cart; ?></span>
                            </li>
                        </ul>
                    </div>
                    <div class="d-block w-100">
                        <ul class="navbar-nav d-flex justify-content-center align-items-center pt-3">
                            <li class="nav-item mx-2">
                                <a class="nav-link" href="muebles.php">Muebles</a>
                            </li>
                            <li class="nav-item mx-2">
                                <a class="nav-link" href="#">Mesas</a>
                            </li>
                            <li class="nav-item mx-2">
                                <a class="nav-link" href="#">Sillas</a>
                            </li>
                            <li class="nav-item mx-2">
                                <a class="nav-link" href="#">Sillones</a>
                            </li>
                            <li class="nav-item mx-2">
                                <a class="nav-link" href="#">About</a>
                            </li>
                            <li class="nav-item mx-2">
                                <a class="nav-link" href="#">Contact</a>
                            </li>
                        </ul>
                    </div>
                </div>
        </div>
    </nav>
    

    <!--MAIN-->
    <main>
        <div class="container mt-5">
            <div class="row ">

                <div class="col-md-6 order-md-1 sliderImg">
                    
                    <div id="carouselImage" class="carousel slide" data-bs-ride="carousel">

                            

                            <div class="carousel-inner">

                                <div class="carousel-item active">
                                    <img  src="<?php echo $rutaImg; ?>" class="d-block w-100" style="max-height: 1000px;" alt="First slide">
                                </div>

                                <?php foreach($imagenes as $img){ ?>

                                <div class="carousel-item">
                                    <img src="<?php echo $img; ?>" class="d-block w-100 " style="max-height: 1000px;" alt="Second slide">
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

                <div class="col-md-6 order-md-2 detallesProd">

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

                    <p class="lead text-white">
                        <?php echo $descripcion; ?>
                    </p>

                    <div class="col-3 my-3 text-white">Cantidad:
                        <input class="form-control" id="cantidad" name="cantidad" type="number" min="1" max="10" value="1">
                    </div>

                    <div class="d-grid gap-3 col-10 mx-auto">
                        <a href="checkout.php" 
                                type="button" 
                                class="btn btn-primary">
                                Comprar Ahora
                        </a>
                        <button href="carrito.php" 
                                type="button" 
                                class="btn btn-outline-primary" 
                                onclick="addProducto(<?php echo $id; ?>, cantidad.value, '<?php echo $token_tmp;  ?>')">
                                Agregar al carrito
                        </button>
                    </div>

                </div>

            </div>
            
        </div>
    </main>

    <section class="my-5 mx-auto py-5" style="max-width:25em;">                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              
        <h2>Subscribe To Our Newsletter</h2>
        <form class="d-flex my-4">
            <input type="search" class="form-control me-2" placeholder="Your e-mail"/>
            <button class="btn btn-outline-dark" type="submit">Subscribe</button>
        </form>
    </section>                                                                                                                                                                                                                                                                                                                                        

    <footer class="d-flex justify-content-between my-5 text-start flex-wrap">
        <ul class="nav flex-column">
            <li class="fw-bold nav-item">
                <a href="#" class="nav-link text-dark">Product</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-muted">Muebles</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-muted">Sillas</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-muted">Sillones</a>
            </li>
        </ul>
        <ul class="nav flex-column">
            <li class="fw-bold nav-item">
                <a href="#" class="nav-link text-dark">Help</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-muted">Shopping guide</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-muted">Product Care</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-muted">Contact US</a>
            </li>
        </ul>
        <ul class="nav flex-column">
            <li class="fw-bold nav-item">
                <a href="#" class="nav-link text-dark">Content</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-muted">About</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-muted">Blog</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-muted">Presroom</a>
            </li>
        </ul>
        <ul class="nav flex-column">
            <li class="fw-bold nav-item">
                <a href="#" class="nav-link text-dark">Terms & Conditions</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-muted">Terms Of Use</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-muted">Privacy Policy</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-muted">Cookies Policy</a>
            </li>
        </ul>
    </footer>


    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
    </script>

    <script>
        function addProducto(id, cantidad, token){
            let url = 'clases/carrito.php'
            let formData = new FormData()
            formData.append('id', id)
            formData.append('cantidad', cantidad)
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