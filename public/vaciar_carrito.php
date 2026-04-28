<?php
require_once "../helpers/auth.php";

if (isset($_SESSION['carrito'])) {
    unset($_SESSION['carrito']);
}

$_SESSION['success'] = "El carrito fue vaciado correctamente.";

header("Location: carrito.php");
exit;