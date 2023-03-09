<?php
include_once "../../config.php";

$control = new CarritoControl();
$resp = $control->eliminarProductoCarrito(data_submitted());

header("Status: 301 Moved Permanently");
header('Location: ../carritoCompra.php');

?>
