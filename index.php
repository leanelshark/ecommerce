<?php

require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, precio, descripcion FROM productos WHERE activo=1");
$sql->execute();
$resultado =$sql->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="./css/style.css">

</head>

<body>
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
                                <a class="nav-link" href="#">Muebles</a>
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

    <div class="container position-relative text-center">
        <header class="position-relative text-center text-white my-5">
            <img src="./img/banner.jpg" class="w-100" alt="banner" />
            <div class="position-absolute top-50 start-50 translate-middle-x w-100 px-3">
                <h1 class="display-4"> Spring Collection 2022 </h1>
                <a href="#new" class="btn btn-light">Explore New Arrivals</a>
            </div>
        </header>
        <h2 class="display-6 py-5">Most Popular</h2>
        <div class="d-flex justify-content-between align-items-center flex-column flex-lg-row my-5" id="new">
            <div class="card m-2">
                <a href="./product.html">
                    <img src="./img/sillones/img1.jpeg" alt="Product" class="card-img-top" height="300"/>
                </a>
                <div class="card-body">
                    <p class="card-text fw-bold">
                        Tara Soft
                    </p>
                    <small class="text-secondary">$20.000,00</small>
                </div>
                <button class="btn btn-outline-dark my-2" type="button"
                        <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'],KEY_TOKEN); ?>" >
                            Detalles
                        </a>
                    </button>
                    <button class="btn btn-dark my-2" type="button" onclick="addProducto(<?php echo $row['id']; ?>,'<?php echo hash_hmac('sha1', $row['id'],KEY_TOKEN); ?>')">
                        Agregar al carrito
                    </button>
            </div>
            <div class="card m-2">
                <a href="./product.html">
                    <img src="./img/mesas/img1.jpeg" alt="Product" class="card-img-top" height="300"/>
                </a>
                <div class="card-body">
                    <p class="card-text fw-bold">
                        Mesa premium
                    </p>
                    <small class="text-secondary">$50.000,00</small>   
                </div>
                <button class="btn btn-outline-dark my-2" type="button"
                        <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'],KEY_TOKEN); ?>" >
                            Detalles
                        </a>
                    </button>
                    <button class="btn btn-dark my-2" type="button" onclick="addProducto(<?php echo $row['id']; ?>,'<?php echo hash_hmac('sha1', $row['id'],KEY_TOKEN); ?>')">
                        Agregar al carrito
                    </button>
            </div>
            <div class="card m-2">
                <a href="./product.html">
                    <img src="./img/muebles/img1.jpg" alt="Product" class="card-img-top" height="300"/>
                </a>
                <div class="card-body">
                    <p class="card-text fw-bold">
                        Mesa tv led
                    </p>
                    <small class="text-secondary">$8.000,00</small>
                </div>
                <button class="btn btn-outline-dark my-2" type="button"
                        <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'],KEY_TOKEN); ?>" >
                            Detalles
                        </a>
                    </button>
                    <button class="btn btn-dark my-2" type="button" onclick="addProducto(<?php echo $row['id']; ?>,'<?php echo hash_hmac('sha1', $row['id'],KEY_TOKEN); ?>')">
                        Agregar al carrito
                    </button>
            </div>
            <div class="card m-2">
                <a href="./product.html">
                    <img src="./img/sillas/img1.jpg" alt="Product" class="card-img-top" height="300"/>
                </a>
                <div class="card-body">
                    <p class="card-text fw-bold">
                        Sillas Soft
                    </p>
                    <small class="text-secondary">$4.000,00</small>
                </div>
                <button class="btn btn-outline-dark my-2" type="button"
                        <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'],KEY_TOKEN); ?>" >
                            Detalles
                        </a>
                    </button>
                    <button class="btn btn-dark my-2" type="button" onclick="addProducto(<?php echo $row['id']; ?>,'<?php echo hash_hmac('sha1', $row['id'],KEY_TOKEN); ?>')">
                        Agregar al carrito
                    </button>
            </div>

        </div>

        <a href="./productlist.html" class="btn btn-outline-dark my-5">View All Product</a>

        <div class="d-flex justify-content-between align-items-center flex-column flex-lg-row my-5">
            <div class="position-relative m-2">
                <img src="./img/menu/img1.jpg" height="300" alt="mesas"/>
                <a class="btn btn-light position-absolute start-0 bottom-0 ms-2 mb-2 d-block" href="#">Mesas</a>
            </div>
            <div class="position-relative m-2">
                <img src="./img/menu/img2.jpg" height="300" alt="sillones"/>
                <a class="btn btn-light position-absolute start-0 bottom-0 ms-2 mb-2 d-block" href="#">Sillones</a>
            </div>
            <div class="position-relative m-2">
                <img src="./img/menu/img3.jpg" height="300" alt="sillas"/>
                <a class="btn btn-light position-absolute start-0 bottom-0 ms-2 mb-2 d-block" href="#">Muebles</a>
            </div>
        </div>

    <div class="row text-start align-items-center gy-5 my-5">
        <div class="col-12 col-md-6">
            <img src="./img/about/img1.jpg" class="w-100 h-100"/>
        </div>
        <div class="col-12 col-md-6">
            <div>
                <h2 class="display-4">Brand</h2>
                <p>lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi sit harum reprehenderit, maxime obcaecati minus nemo rem asperiores numquam qui?
                </p>
            </div>
        </div>
    </div>
    <div class="row text-start align-items-center gy-5 my-5">
        <div class="col-12 col-md-6">
            <div>
                <h2 class="display-4">Awards</h2>
                <p>lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi sit harum reprehenderit, maxime obcaecati minus nemo rem asperiores numquam qui?
                </p>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <img src="./img/about/img2.jpg" class="w-100 h-100"/>
        </div>
    </div>
    
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
</div>
    <!--MAIN-->

    <!--<div class="container mt-5">

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">

            <?php foreach($resultado as $row) { ?>
        
            <div class="col">

                <div class="card shadow-sm">
                    
                    <?php

                    $id= $row['id'];
                    $imagen = "images/productos/" . $id . "/principal.jpeg";

                    if(!file_exists($imagen)){
                        $imagen = "images/no-photo.png";
                    }

                    ?>

                    <img src="<?php echo $imagen;?>">

                    <div class="card-body">

                        <h5 class="card-title"><?php echo $row['nombre']; ?></h5>

                        <p class="card-text">$<?php echo number_format($row['precio'],2,'.',',');?></p>
                        
                        

                    </div>

                </div>
            
            </div>

            <?php } ?>

        </div>

    </div>-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
    </script>

    <script>
            function addProducto(id,token){
                let url = 'clases/carrito.php';
                let formData = new FormData();
                formData.append('id', id);
                formData.append('token', token);

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json()) 
                .then(data =>{
                    if(data.ok){
                        let elemento = document.getElementById('num_cart');
                        elemento.innerHTML = data.numero;
                    }
                }); 
            }

    </script>

</body>

</html>