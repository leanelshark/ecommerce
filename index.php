<?php

require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, precio, descripcion FROM productos WHERE activo=1");
$sql->execute();
$resultado =$sql->fetchAll(PDO::FETCH_ASSOC);

//session_destroy();
print_r($_SESSION);

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

    <div class="container mt-5">

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">

            <?php foreach($resultado as $row) { ?>
        
            <div class="col">

                <div class="card shadow-sm">
                    
                    <?php

                    $id= $row['id'];
                    $imagen = "images/productos/" . $id . "/principal.webp";

                    ?>

                    <img src="<?php echo $imagen;?>">

                    <div class="card-body">

                        <h5 class="card-title"><?php echo $row['nombre']; ?></h5>

                        <p class="card-text">$<?php echo number_format($row['precio'],2,'.',',');?></p>
                        
                        <div class="d-flex justify-content-between aling-items-center">

                            <div class="btn-group">

                                <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'],KEY_TOKEN); ?>" class="btn btn-primary">
                                    Detalles
                                </a>

                             </div>

                             <button class="btn btn-outline-success" type="button" onclick="addProducto(<?php echo $row['id']; ?>,'<?php echo hash_hmac('sha1', $row['id'],KEY_TOKEN); ?>')">
                                    Agregar al carrito
                            </button>



                                
                        </div>

                    </div>

                </div>
            
            </div>

            <?php } ?>

        </div>

    </div>

    <!-- JavaScript Bundle with Popper -->
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