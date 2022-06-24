<?php

define("CLIENT_ID", "ARxdzpJlncaFidGz6AmwV3mfif3n-yZ2vbDgT7R0Fkdgoe0_TWqhtxMmERG_0vMjWBPtxlGNQjhtl8zi");
define("CURRENCY","AR");
define("KEY_TOKEN", "APR.wqc-354*");
define("MONEDA", "$");

session_start();

$num_cart = 0;
if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}

?>